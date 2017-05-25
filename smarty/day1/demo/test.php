<?php
include_once 'smarty.php';
$smarty=new Smarty();
$smarty->assign('title','PHP');
$smarty->assign('content','周三就放假了');
$smarty->display('test.html');