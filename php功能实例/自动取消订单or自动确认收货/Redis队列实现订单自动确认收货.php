<?php
/**
 * 前言：每天在凌晨两点的时候，通过linux的定时任务把即将要确认收货的订单信息查询出来，然后存储在redis上，
 * redis上我们选择的队列，队列处理的特点就是先进先出，前面的数据在查询订单时，通过发货时间排序，
 * 所以最先出队列的肯定是距离规定的自动收货时间最近的订单。
 */

/****************************************生成队列**********************************************/
$successCount=0;
$failCount=0;
$screen_time = 3600*24*9;//设置筛选天数
$data = array();
$now_time = time();
//查询符合要求的数据（查询时按发货时间升序排列，最早发货的放最前，也最先插入到redis队列，先进先出，处理时也最先处理）
$sql="select id,send_time as deliver_time from `order` where is_send=1 and is_del=0 and is_cancel=0 and is_token=0 and send_time>0 
and send_time + {$screen_time} < $now_time order by send_time asc";
$res = $con->query($sql);
//当队列还有数据时将数据记录并清除，循环删除，写入日志文件
while($redis->LLEN('auto_recevice_order')){
    $txt = '执行时间：'.date('Y-m-d H:i:s').',信息:'.$redis->RPOP('auto_recevice_order');
    file_put_contents('./autoToken/fail_log.txt',$txt."\r\n".PHP_EOL,FILE_APPEND);
    $failCount++;
}
//重新填充数据进队列
while ($row = $res->fetch_assoc()) {
    $successCount++;
    $redis->LPUSH('auto_recevice_order',json_encode($row1));
}
$con->close();
$success=date('Y-m-d H:i:s').':[推送成功]:本次成功推送数据：'.$successCount.'条;记录上次处理失败数据:'.$failCount."条\r\n";
file_put_contents('./success_log.txt',$success."\r\n".PHP_EOL,FILE_APPEND);


/*******************************************处理队列*************************************************/
/*处理时没有通过linux的定时任务去做，用linux的screen+php cli模式执行php脚本，只需要不断的从队列中读取订单信息，然后判断订单信息中的发货
时间，如果达到自动收货的要求，就执行update语句。同时如果没有达到收货的时间，而且与收货时间间距比较大的时候，可以让php脚本休眠sleep一定的时间数，
这个时间数自己调节设计，获取出来的未达到时间要求的订单，需要重新推送到redis队列中去，而且还是队列的顶端。*/

$set_time = 3600*24*10;//设置几天后自动收货
while(true){
    if($i%30==0){//不停断执行30次，休息10微秒；
        usleep(10);//防止while 循环使CPU使用率过高
    }
    if($redis->LLEN('auto_recevice_order')){
        $data = json_decode($redis->lGet('auto_recevice_order',-1));//获取队列最后面的一个值；
        $id = (int)$data->id;//将数据转化为整形
        $deliver_time = (int)$data->deliver_time;//将数据转化为整形
        $res1 = $res2 = false ;
        $now_time = time();
        if(($deliver_time+$set_time)<$now_time){//若当前时间超过自动收货时间
            $sql1 = "update `order` set `is_token`='1',`token_time` = $now_time where id=$id and is_send=1 and is_del=0 and is_cancel=0 and is_token=0 and send_time + {$set_time} < $now_time";
            $res1 = $con->query($sql1);//更新数据
            $rows = mysqli_affected_rows($con);
            if($rows){//将这条记录从队列删除，并添加日志数据
                $redis->rpop('auto_recevice_order');

                $ip = $this->getIp();
                $sql2 = "insert into `order_log`(`order_id`,`log_msg`,`log_ip`,`log_role`,`log_user`,`log_order_state`,`log_time`) VALUES($id,'系统自动收货','$ip','系统','服务器','收货',$now_time)";//写入订单日志
                $res2 = $con->query($sql2);
            }
        }

    }
    $i++;
}