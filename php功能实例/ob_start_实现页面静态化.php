<?php
/**
 * 使用php内置的缓存机制实现页面静态化
 * Buffer缓冲区认知：
 * 内容--->php 缓冲区--->tcp--->终端
 * 即运行php文件首先将处理结果输出到缓冲区，然后经过协议传到终端；
 * ob_start();//开启一个新的输出缓冲区
 * ob_get_content();//返回输出缓冲区的内容;
 *
 *
 * ob_clean();//清空缓冲区内容，但不删除输出缓冲区
 * ob_end_clean;//删除输出缓冲区；
 *
 * ob_get_clean();//获取输出缓冲区内容，并删除缓冲区
 *
 *
 */

if (is_file('./index.html') && filemtime('./index.html') > time() - 10) {//静态化页面存在并且没有到更新时间，直接输出静态页面，不请求数据库
    require './index.html';
    echo '我是静态页面';
}else {//页面过期后重新请求数据库，重新生成静态化页面

    $connect = mysql_connect('127.0.0.1:3307', 'root', '');
    mysql_select_db('web1');
    mysql_set_charset('utf8');
    $res = mysql_query("select * from dada_admin");
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_assoc($res)) {
            $admins[] = $row;
        }
    }


    ob_start();//开启一个新的输出缓冲区


    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
    <?php
    foreach ($admins as $admin) {
        ?>
        <div><?= $admin['username'] ?></div>
        <div><?= $admin['password'] ?></div>
        <?php
    }
    ?>
    </body>
    </html>
    <?php
    file_put_contents('./index.html', ob_get_contents());//获取输出缓冲区内容（即以上内容），并将其存入到./index.html文件，文件不存在会自动创建，文件到期后会直接覆盖
    ob_clean();
    echo '我是新页面';
}