<?php

/*****************************php 30分钟未付款自动取消订单***************************************/
//1.使用PHP函数ignore_user_abort
function submitOrder()
{
       $orderinfo=[];
    //写入订单表，商品表减库存
    //执行watchOrder()脚本
    watchOrder();
}


function watchOrder()
{
    ignore_user_abort(true);//设置客户端断开连接时不中断脚本的执行
    set_time_limit(0);//脚本最大执行时间；0为一直执行
    //设置倒计时时间；
    $interval=20;
    while ($interval>0) {
        $connect = mysql_connect('127.0.0.1:3307','root','');
        mysql_select_db('web1');
        mysql_set_charset('utf8');
        $res = mysql_query("select statu_s from dada_order where id=1");
        $row = mysql_fetch_assoc($res);
        //如果已支付；则跳出循环；若未支付则休眠10秒后继续检查支付状态；
        if ($row['statu_s'] == 1) {
            break;
        }
        sleep(10);
        $interval-=10;
    }
    //倒计时走完，检查库存；
    $res = mysql_query("select statu_s from dada_order where id=1");
    $row = mysql_fetch_assoc($res);
    //若订单还未支付，则释放库存
    if ($row['statu_s'] == 0) {
        mysql_query('update dada_goods set num=num+1 where id=1');
    }
}

//2使用swoole扩展里的定时器功能
//3使用linux的定时任务，定期执行PHP文件