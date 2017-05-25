<?php
include_once "init.inc.php";

$smarty->assign('title','我是title父亲');
$smarty->assign("content","I'm the longest content.");



$smarty->display('parent.html');


