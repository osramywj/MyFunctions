<?php
#分布式锁
class Lock
{
    public  $redis=''; #存储redis对象
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
     * @desc 加锁方法
     *
     * @param $lockName string | 锁的名字
     * @param $timeout int | 锁的过期时间
     *
     * @return 成功返回identifier/失败返回false
     */
    public function getLock($lockName, $timeout=2)
    {
        $identifier=uniqid();  #获取唯一标识符
        $timeout=ceil($timeout); #确保是整数
        $end=time()+$timeout;
        while(time()<$end)   #循环获取锁
        {
            if($this->redis->setnx($lockName, $identifier)) #若此锁已存在，返回false，若没有，设置成功，返回true;
            {
                $this->redis->expire($lockName, $timeout);  #为$lockName设置过期时间，防止死锁
                return $identifier;        #返回一维标识符
            }
            elseif ($this->redis->ttl($lockName)===-1)//查看一个key的剩余生存时间,-1为没设置过期时间;
            {
                $this->redis->expire($lockName, $timeout);  #检测是否有设置过期时间，没有则加上（假设，客户端A上一步没能设置时间就进程奔溃了，客户端B就可检测出来，并设置时间）
            }
            usleep(0.001);   #停止0.001ms
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
    /**
     * @desc 测试
     *
     * @param $lockName string | 锁名
     */
    public function test($lockName)
    {
        $start=time();
        for ($i=0; $i < 10000; $i++)
        {
            $identifier=$this->getLock($lockName);
            if($identifier)
            {
                $count=$this->redis->get('count');
                $count=$count+1;
                $this->redis->set('count',$count);
                $this->releaseLock($lockName,$identifier);
            }else{
                $i--;
            }
        }
        $end=time();
        echo "this OK<br/>";
        echo "执行时间为：".($end-$start);
    }

}
//分别在两个浏览器中访问，最后执行结束的浏览器会是20000;
//为何第一个执行结束的结果是十万多呢，是因为第一个浏览器的10000已经加上去了，第二个浏览器那时等待解锁的时间比较长，
//还没有执行10000次；但当第二个浏览器也执行结束后，20000次就正好了；
header("content-type: text/html;charset=utf8;");
$obj=new Lock('127.0.0.1','123456');
$obj->test('lock_count');
//$obj->redis->del('count');
echo "<pre>";
echo $obj->redis->get('count');