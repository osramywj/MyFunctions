<?php

/**
 *PHP输出缓冲控制
 * 举例：你打开文本编辑器编辑一个文件的时候，你每输入一个字符，操作系统并不会立即把这个字符直接写入到磁盘，
 *       而是先写入到buffer，当写满了一个buffer的时候，才会把buffer中的数据写入磁盘，当然当调用内核函数flush()的时候，强制要求把buffer中的脏数据写回磁盘。
 * php在执行输出的时候，比如echo print ，输出并没有立即送给web server，而是将数据写入php buffer， PHP output_buffering机制能提升性能。
 * 默认情况下，PHP buffer是开启的。
 * php文件最终在浏览器上显示，走过了3个缓冲阶段 ：php buffer >> web server buffer >> browser buffer。 最后显示到浏览器
 * ob_flush()  刷新PHP自身的缓冲区
 * flush()     刷新WebServer 服务器的缓冲,输出到浏览器缓冲
 * ob_end_flush     关闭并输出缓冲区（针对PHP buffer）
 * ob_end_clean     关闭并清除缓冲区（针对PHP buffer）
 */


/**
 * PHPexcel包需要将所有的数据拿到后才能生成excel，在面对生成超大数据量的excel文件时这显然是会造成内存溢出的，所以考虑使用让PHP边写入输出流边让浏览器下载的形式来完成需求。
 * 使用php输出流：
 * $fp = fopen('php://output', 'a');
    fwrite($fp, 'strings');
    ....
    ....
    fclose($fp)
 *
 * php://output 是一个可写的输出流，允许程序像操作文件一样将输出写入到输出流中，PHP会把输出流中的内容发送给web服务器并返回给发起请求的浏览器
 *
 * fputcsv($resource,$fields) 将行格式化为 CSV 并写入文件指针
 * $resource 是资源句柄，$fields是一行数据的数组。
 */

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');



set_time_limit(0);

$csvFileName = 'test';
header('Content-Description: File Transfer');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'. $csvFileName .'.csv"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
$fp = fopen('php://output','a');//打开output流
$header = ['real_id','coupon_id','coupon_code','add_time',  'status','use_time','user_name'];
fputcsv($fp,$header);//将数据格式化为CSV格式并写入到output流中
$sql = 'select count(*) as count from ecs_actcoupon_real';
$res = $db->getAll($sql);
$total=$res[0]['count'];//数据总条数
$perSize = 1000;//一次读取1000条
$pages = ceil($total/$perSize);
for($i=1;$i<=$pages;$i++){
    $content = getCoupon($i,$perSize);
    foreach($content as $val){
        $row = array_values($val);
        mb_convert_variables('GBK','UTF-8',$row);
        fputcsv($fp,$row);
    }
    unset($content);//释放变量的内存
    ob_flush();   //刷新输出缓冲到服务器
    flush();      //刷新输出缓冲到浏览器，两个必须一起使用
}
fclose($fp);

function getCoupon($startPage,$perSize){
    $start = ($startPage-1)*$perSize+1;
    $end = $startPage*$perSize;
    return $couponList = UTables::findAll('actcoupon_real','real_id between '.$start.' and '.$end,[],'',1000);
}