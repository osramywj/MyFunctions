<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<p><a href="<?php echo U('Home/Wechat/setMenu');?>">建菜单</a></p>
<p><a href="<?php echo U('Home/Wechat/deleteMenu');?>">删菜单</a></p>
<p><a href="<?php echo U('Home/Wechat/sendMsgToAll');?>">群发消息</a></p>
<p><a href="<?php echo U('Home/Wechat/getTempQr');?>">获取临时二维码</a></p>
</body>
</html>