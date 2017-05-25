<?php
session_start();
date_default_timezone_set("PRC");
//制定项目的根目录
define("ROOT",str_replace('\\','/',__DIR__.'/'));
//包含smarty类文件；
require_once ROOT."libs/Smarty.class.php";
//实例化smarty对象
$smarty=new Smarty();
//设置smarty相关属性
$smarty->setTemplateDir(ROOT."/templates")//设置模板目录；模板目录不对会出现致命错误；
       ->setCompileDir(ROOT."/templates_c")//设置编译文件目录，目录不存在会自动创建；
       ->setPluginsDir(ROOT."plugins")//设置扩展插件存放目录
       ->setCacheDir(ROOT."cache")//设置缓存目录
       ->setConfigDIR(ROOT.'configs');//设置配置文件存放目录；
$smarty->caching = false;//设置缓存是否开启；
$smarty->cache_lifetime=3600*24;//设置缓存过期时间
$smarty->left_delimiter = "{";//自定义左边界符
$smarty->right_delimiter = "}";//自定义右边符
$smarty->auto_literal = false;//设置边界符内是否允许出现空格   false为允许 true 为不允许  literal意为逐字的