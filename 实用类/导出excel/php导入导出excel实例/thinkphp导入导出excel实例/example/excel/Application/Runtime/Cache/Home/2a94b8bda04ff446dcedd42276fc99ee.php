<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>excel导出导入</title>
</head>
<body>
	<a href="/thinkphp/all/excel/index.php/Home/Index/export" target="_blank">导出</a>
	<br>
	<br>
	<br>
	<br>
	<form action="/thinkphp/all/excel/index.php/Home/Index/eximport" enctype="multipart/form-data" method="post" >
		<input type="file" name="excel" />
		<input type="submit" value="提交" >
	</form>
</body>
</html>