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
<form action="<?php echo U('Wechat/addMaterial');?>" enctype="multipart/form-data" method="post">
    <input type="file" name="material">
    <input type="submit" value="上传其他类型永久素材">
</form>
</body>
</html>