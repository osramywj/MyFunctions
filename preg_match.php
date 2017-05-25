<?php

/**
 * preg_match($reg,$str,$match);
 * $match[0]  是完整匹配，$match[1]是第一个子模式的匹配
 */

//>>1、获取test.php
    $str1 = "http://www.baidu.com/index/home/test.php?act=add";

    $reg1 = '/\/([^\/]+\.[a-z]+)[^\/]*$/';
    preg_match($reg1,$str1,$match);
    print_r($match);
    //输出结果：
    /*    Array(
            [0] => /test.php?act=add
            [1] => test.php
          )
    */

//>>2、


