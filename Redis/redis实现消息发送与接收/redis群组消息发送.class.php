<?php
class ManyPullMessage
{
    public $redis=''; #存储redis对象
    /**
     * @desc 构造函数
     *
     * @param $host string | redis主机
     * @param $port int | 端口
     */
    public function __construct($host,$password,$port=6379)
    {
        $this->redis=new Redis();
        $this->redis->connect($host,$port);
        $this->redis->auth($password);
    }
    /**
     * @desc 用于创建群组的方法，在创建的同时还可以拉人进群组
     *
     * @param $user string  :用户名，群主名
     * @param $addUser array  :其他用户构成的数组
     *
     * @param $lockName string  :锁的名字，用于获取群组ID的时候用
     * @return int 返回群组ID
     */
    public function createGroupChat($user, $addUser=array(), $lockName='chatIdLock')
    {
        $identifier=$this->getLock($lockName); #若加锁成功，返回唯一标识符
        if($identifier)
        {
            $id=$this->redis->incr('groupChatID'); #获取群组ID
            $this->releaseLock($lockName,$identifier); #释放锁
        }
        else
            return false;
        $messageCount=$this->redis->set('countMessage_'.$id, 0); #初始化这个群组消息计数器
        #开启非事务型流水线，一次性将所有redis命令传给redis，减少与redis的连接
        //一次性向redis服务器发送多个命令，一起返回结果，不用等待上一条命令被处理之后再发送第二条命令；
        $pipe=$this->redis->pipeline();
        $this->redis->zadd('groupChat_'.$id, 1, $user); #创建群组成员有序集合，并添加群主
        #将这个群组添加到user所参加的群组有序集合中
        $this->redis->zadd('hasGroupChat_'.$user, 0, $id);//score  对应群组消息的score，此处指第一条消息
        foreach ($addUser as $v) #创建群组的同时需要添加的用户成员
        {
            $this->redis->zadd('groupChat_'.$id, 2, $v);//在新增的群组表添加用户信息
            $this->redis->zadd('hasGroupChat_'.$v, 0, $id);//在用户所拥有的群组表添加群组id和消息score
        }
        $pipe->exec();
        return $id; #返回群组ID
    }
    /**
     * @desc 群主主动拉人进群
     *
     * @param $user string | 群主名
     * @param $groupChatID int | 群组ID
     * @param $addMembers array | 需要拉进群的用户
     *
     * @return bool
     */
    public function addMembers($user, $groupChatID, $addMembers=array())
    {
        $groupMasterScore=$this->redis->zscore('groupChat_'.$groupChatID, $user); #将groupChatName的群主取出来
        if($groupMasterScore==1) #判断user是否是群主
        {
            $pipe=$this->redis->pipeline(); #开启非事务流水线
            foreach ($addMembers as $v)
            {
                $this->redis->zadd('groupChat_'.$groupChatID, 2, $v);   #添加进群
                $this->redis->zadd('hasGroupChat_'.$v, 0, $groupChatID); #添加群名到用户的有序集合中
            }
            $pipe->exec();
            return true;
        }
        return false;
    }
    /**
     * @desc 群主删除成员
     *
     * @param $user string | 群主名
     * @param $groupChatID int | 群组ID
     * @param $delMembers array | 需要删除的成员名字
     *
     * @return bool
     */
    public function delMembers($user, $groupChatID, $delMembers=array())
    {
        $groupMasterScore=$this->redis->zscore('groupChat_'.$groupChatID, $user);
        if($groupMasterScore==1) #判断user是否是群主
        {
            $pipe=$this->redis->pipeline(); #开启非事务流水线
            foreach ($delMembers as $v)
            {
                $this->redis->zrem('groupChat_'.$groupChatID, $v);
                $this->redis->zrem('hasGroupChat_'.$v, $groupChatID);
            }
            $pipe->exec();
            return true;
        }
        return false;
    }
    /**
     * @desc 退出群组
     *
     * @param $user string | 用户名
     * @param $groupChatID int | 群组名
     */
    public function quitGroupChat($user, $groupChatID)
    {
        $this->redis->zrem('groupChat_'.$groupChatID, $user);
        $this->redis->zrem('hasGroupChat_'.$user, $groupChatID);
        return true;
    }
    /**
     * @desc 发送消息
     *
     * @param $user string | 用户名
     * @param $groupChatID int | 群组ID
     * @param $messageArr array | 包含发送消息的数组
     * @param $preLockName string | 群消息锁前缀，群消息锁全名为countLock_群ID
     *
     * @return bool
     */
    public function sendMessage($user, $groupChatID, $messageArr, $preLockName='countLock_')
    {
        $memberScore=$this->redis->zscore('groupChat_'.$groupChatID, $user); #成员score
        if($memberScore)
        {
            $identifier=$this->getLock($preLockName.$groupChatID); #获取锁
            if($identifier) #判断获取锁是否成功
            {
                $messageCount=$this->redis->incr('countMessage_'.$groupChatID);
                $this->releaseLock($preLockName.$groupChatID,$identifier); #释放锁
            }
            else
                return false;
            $json_message=json_encode($messageArr);
            $this->redis->zadd('groupChatMessage_'.$groupChatID, $messageCount, $json_message);//消息的score是从1开始的
            $count=$this->redis->zcard('groupChatMessage_'.$groupChatID); #查看信息量大小
            if($count>5000) #判断数据量有没有达到5000条
            { #数据量超5000，则需要清除旧数据
                $start=5000-$count;
                $this->redis->zremrangebyrank('groupChatMessage_'.$groupChatID, $start, $count);
            }
            return true;
        }
        return false;
    }
    /**
     * @desc 获取新信息
     *
     * @param $user string | 用户名
     *
     * @return 成功则放回json数据数组，无新信息返回false
     */
    public function getNewMessage($user)
    {
        $arrID=$this->redis->zrange('hasGroupChat_'.$user, 0, -1, 'withscores'); #获取用户拥有的群组ID
        $json_message=array(); #初始化
        //$k 是组id   $v 是message score
        foreach ($arrID as $k => $v) #遍历循环所有群组，查看是否有新消息
        {
            $messageCount=$this->redis->get('countMessage_'.$k); #群组最大信息分值数
            if($messageCount>$v) #判断用户是否存在未读新消息
            {
                $json_message[$k]['message']=$this->redis->zrangebyscore('groupChatMessage_'.$k, $v+1, $messageCount);//未读消息数组
                $json_message[$k]['count']=count($json_message[$k]['message']); #统计新消息数量
                $this->redis->zadd('hasGroupChat_'.$user, $messageCount, $k); #更新已获取消息
            }
        }
        if($json_message)
            return $json_message;
        return false;
    }
    /**
     * @desc 分页获取群组信息
     *
     * @param $user string | 用户名
     * @param $groupChatID int | 群组ID
     * @param $page int | 第几页
     * @param $size int | 每页多少条数据
     *
     * @return 成功返回json数据，失败返回false
     */
    public function getPartMessage($user, $groupChatID, $page=1, $size=10)
    {
        $start=$page*$size-$size; #开始截取数据位置
        $stop=$page*$size-1; #结束截取数据位置
        $json_message=$this->redis->zrange('groupChatMessage_'.$groupChatID, $start, $stop);
        if($json_message)
            return $json_message;
        return false;
    }
    /**
     * @desc 加锁方法
     *
     * @param $lockName string | 锁的名字
     * @param $timeout int | 锁的过期时间
     *
     * @return 成功返回identifier/失败返回false
     */
    public function getLock($lockName, $timeout=2)
    {
        $identifier=uniqid(); #获取唯一标识符
        $timeout=ceil($timeout); #确保是整数
        $end=time()+$timeout;
        while(time()<$end)  #循环获取锁
        {
            /*
            #这里的set操作可以等同于下面那个if操作，并且可以减少一次与redis通讯
            if($this->redis->set($lockName, $identifier array('nx', 'ex'=>$timeout)))
            return $identifier;
            */
            if($this->redis->setnx($lockName, $identifier)) #查看$lockName是否被上锁
            {
                $this->redis->expire($lockName, $timeout); #为$lockName设置过期时间
                return $identifier;    #返回一维标识符
            }
            elseif ($this->redis->ttl($lockName)===-1)
            {
                $this->redis->expire($lockName, $timeout); #检测是否有设置过期时间，没有则加上
            }
            usleep(0.001);  #停止0.001ms
        }
        return false;
    }
    /**
     * @desc 释放锁
     *
     * @param $lockName string | 锁名
     * @param $identifier string | 锁的唯一值
     *
     * @param bool
     */
    public function releaseLock($lockName,$identifier)
    {
        if($this->redis->get($lockName)==$identifier) #判断是锁有没有被其他客户端修改
        {
            $this->redis->multi();
            $this->redis->del($lockName); #释放锁
            $this->redis->exec();
            return true;
        }
        else
        {
            return false; #其他客户端修改了锁，不能删除别人的锁
        }
    }

}


$object=new ManyPullMessage('127.0.0.1','123456');
#创建群组
//$user='jack';
//$arr=array('jane1','jane2');
//$a=$object->createGroupChat($user,$arr);
//echo "<pre>";
//print_r($a);
//echo "</pre>";die;

#向群组添加用户
//$b=$object->addMembers('jack','1',array('jane1','jane2','jane3','jane4'));
//echo "<pre>";
//var_dump($b);
//echo "</pre>";die;

#群主删除成员
//$c=$object->delMembers('jack', '1', array('jane1','jane4'));
//echo "<pre>";
//var_dump($c);
//echo "</pre>";die;

//$res = $object->redis->zRange('groupChat_1',0,3,true);
//print_r($res);


#发送消息
//$user='jack';
//$message='Everyone is ok?';
//$groupChatID=1;
//$arr=array('sender'=>$user, 'message'=>$message, 'time'=>time());
//$d=$object->sendMessage($user,$groupChatID,$arr);
//echo "<pre>";
//var_dump($d);
//echo "</pre>";die;


#用户获取新消息
//$res = $object->getNewMessage('jack');
//print_r($res);

#获取全部信息
//$res = $object->redis->zRange('groupChatMessage_1',0,-1,true);
//print_r($res);die;

#用户获取某个群组部分消息
//$f=$object->getPartMessage('jane2', 1, 3, 10);
//echo "<pre>";
//print_r($f);
//echo "</pre>";die;

















