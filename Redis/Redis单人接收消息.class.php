<?php
#单接收者接收消息
class SinglePullMessage
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
     * @desc 发送消息（一个人）
     *
     * @param $toUser string | 接收人
     * @param $messageArr array | 发送的消息数组，包含sender、message、time
     *
     * @return bool
     */
    public function sendSingle($toUser,$messageArr)
    {
        $json_message=json_encode($messageArr); #编码成json数据
        return $this->redis->lpush($toUser,$json_message); #将数据推入链表
    }
    /**
     * @desc 用户获取新消息
     *
     * @param $user string | 用户名
     *
     * @return array 返回数组，包含多少个用户发来新消息，以及具体消息
     */
    public function getNewMessage($user)
    {
        #接收新信息数据，并且将数据推入旧信息数据链表中，并且在原链表中删除
        $messageArr=array();
        while($json_message=$this->redis->rpoplpush($user, 'preMessage_'.$user))
        {
            $temp=json_decode($json_message); #将json数据变成对象
            $messageArr[$temp->sender][]=$temp; #转换成数组信息
        }
        if($messageArr)
        {
            $arr['count']=count($messageArr); #统计有多少个用户发来信息
            $arr['messageArr']=$messageArr;
            return $arr;
        }
        return false;
    }

    //旧消息是从左边插入的，最左边是最新加入的消息，最右边的是最早的消息。
    public function getPreMessage($user)
    {
        ##取出旧消息
        $messageArr=array();
        $json_pre=$this->redis->lrange('preMessage_'.$user, 0, -1); #一次性将全部旧消息取出来
        foreach ($json_pre as $k => $v)
        {
            $temp=json_decode($v);  #json反编码
            $timeout=$temp->time+60*60*24*7; #数据过期时间 七天过期
            if($timeout<time())  #判断数据是否过期
            {
                if($k==0)   #若是最迟插入的数据都过期了，则将所有数据删除
                {
                    $this->redis->del('preMessage_'.$user);
                    break;
                }
                $this->redis->ltrim('preMessage_'.$user, 0, $k); #若检测出有过期的，则将比它之前插入的所有数据删除，0~$k是没有过期的消息;
                break;
            }
            $messageArr[$temp->sender][]=$temp;
        }
        return $messageArr;
    }
    /**
     * @desc 消息处理，没什么特别的作用。在这里这是用来处理数组信息，然后将其输出。
     *
     * @param $arr array | 需要处理的信息数组
     *
     * @return 返回打印输出
     */
    public function dealArr($arr)
    {
        foreach ($arr as $k => $v)
        {
            foreach ($v as $k1 => $v2)
            {
                echo '发送人：'.$v2->sender.' 发送时间：'.date('Y-m-d h:i:s',$v2->time).'<br/>';
                echo '消息内容：'.$v2->message.'<br/>';
            }
            echo "<hr/>";
        }
    }
}

//test
$obj = new SinglePullMessage('127.0.0.1','123456',6379);

//发消息
//消息是一个含有sender、message、time的数组
$obj->sendSingle('jane',array('sender'=>'boss','message'=>'How are you ','time'=>time()));

//收消息
$message = $obj->getNewMessage('jane');


if($message){
    echo $message['count'].'个联系人发来消息';
    $obj->dealArr($message['messageArr']);
}