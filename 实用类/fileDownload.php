<?php
/**
 * 文件下载一个简单封装；
 */
function file_download($fileName){
    //对中文文件名进行转码
    $filename = iconv('UTF-8','GB2312',$fileName);
    //文件绝对路径
    $fullName = __DIR__.'/'.$fileName;
    if(!file_exists($fullName)){
        echo '文件不存在';
        return false;
    }

    $fp = fopen($fullName,'r');//fopen只能打开绝对路径
    $fileSize = filesize($fullName);//字节数
    if($fileSize>10000000){
        echo "<script>alert('文件大小：".$fileSize."Byte 无法下载')</script>";
        return false;
    }
    //Http头部信息
    header("Content-type: applicaction/octet-stream");
    header("Accept-Ranges: bytes");
    header("Accept-Lengh: ".$fileSize);
    header("Content-Disposition:attachment; filename=".$filename);//决定echo的输出形式，以附件形式输出

//    echo fread($fp,$fileSize);
    $buffer = 1024;
    //为了下载安全，做了一个文件字节读取计数器
    $fileCount = 0;
    while(!feof($fp) && $fileSize-$fileCount>0){
        $data = fread($fp,$buffer);
        $fileCount += $buffer;
        echo $data;
    }
    fclose($fp);
}

file_download('phpinfo.php');//只用填写文件名