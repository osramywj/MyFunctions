<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="<?php echo U('Wechat/uploadTempMedia');?>" enctype="multipart/form-data" method="post">
    <input type="file" name="media">
    <input type="submit" value="上传临时素材">
</form>
</body>
</html>