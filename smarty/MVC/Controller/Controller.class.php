<?php
/**
 * Created by Wenju Yang.
 * 控制器基础类
 */

class Controller extends Core{
    public function init(){
        $act=isset($_GET['a'])?$_GET['a']:'index';
        if(method_exists($this,$act)){//判断方法是否存在于类的对象中；
            $this->$act();
        }else{
            die('没有此方法');
        }
    }
}


