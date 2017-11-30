<?php
/**
 * 计算某个文件夹下所有php  html css js文件的代码总行数
 * @param $dir  :需要检索的文件目录
 * @return int
 */
function countLine($dir)
{
    $count = 0;
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file[0] == '.') continue; //过滤掉以.开头的文件
            $file = $dir . "/" . $file;
            if (is_dir($file)) {
                $count += countLine($file);
            } else {
                if (strpos($file, ".php") || strpos($file,'.html') || strpos($file,'.css') ||strpos($file,'.js'))//只算php代码行数
                    $count += count(file($file));
            }
        }
    } else {
        $count += count(file($dir));
    }
    return $count;
}