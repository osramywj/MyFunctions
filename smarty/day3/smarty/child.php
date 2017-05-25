<?php
include_once "init.inc.php";

$smarty->assign('title','我是title儿子');
$smarty->assign("content","I'm the 小 content.");



$smarty->display('child.html');


