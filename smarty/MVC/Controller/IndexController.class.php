<?php
/**
 * Created by Wenju Yang.
 * 主页控制器
 */
//IndexController方法继承了Controller类的init()方法！！！当new IndexController()时，可以直接调用
//init()方法
class IndexController extends Controller{

    public function index(){
        $this->assign('content','我是内容');
        $this->display('index.html');
    }
    public function test(){
        $this->assign('content','我是测试内容');
        $this->display('test.html');
    }
}