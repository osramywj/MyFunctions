<?php
/**
 * Created by Wenju Yang.
 * 
 */
class IndexController extends Controller{
    public function index(){
        $this->assign('content','我是大内容');
        $this->display('index.html');
    }
}