<?php
/**
 * Created by Wenju Yang.
 * 
 */
class Controller extends Core{
    public function init(){
        $act=isset($_GET['a'])?$_GET['a']:'index';
        if(method_exists($this,$act)){
            $this->$act();
        }else{
            die('方法不存在');
        }
    }
}