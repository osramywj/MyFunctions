<?php
/**
 * Created by Wenju Yang.
 *初始化Smarty,相当于Smarty里的init.inc.php
 */

class Core extends Smarty{
    public function __construct(){
        parent::__construct();//继承父类的构造方法
        //初始化Smarty对象中的属性
        $this->template_dir=ROOT.'/View';//模板目录
        $this->compile_dir=ROOT.'/View_c';//编译文件目录
        $this->config_dir=ROOT.'/configs';//配置文件目录
    }
}


