<?php
/**
 * Created by Wenju Yang.
 * 单入口文件
 */
header('content-type:text/html;charset=utf-8');
define("ROOT",str_replace('\\','/',__DIR__));
date_default_timezone_set('PRC');



//自动加载
function __autoload($className){
    set_include_path(get_include_path().PATH_SEPARATOR.ROOT.'/Controller'.
        PATH_SEPARATOR.ROOT.'/Model'.PATH_SEPARATOR.ROOT.'/ORG'.
        PATH_SEPARATOR.ROOT.'/Smarty'.PATH_SEPARATOR.ROOT.'/Smarty/plugins'.
        PATH_SEPARATOR.ROOT.'/Smarty/sysplugins');
    if(file_exists(ROOT."/Smarty/plugins/{$className}.php")){
        include_once ROOT."/Smarty/plugins/{$className}.php";
    }elseif(file_exists(ROOT."/Smarty/sysplugins/{$className}.php")){
        include_once ROOT."/Smarty/sysplugins/{$className}.php";
    }else{
        include_once $className.'.class.php';
    }
}
$c=isset($_GET['c'])?$_GET['c']:'Index';
$className=$c.'Controller';


$controller =new $className();
$controller->init();