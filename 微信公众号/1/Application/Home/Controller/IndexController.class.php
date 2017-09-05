<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){

        $this->display();
    }

    public function uploadImg()
    {
        $this->display('Index/uploadImg');
    }

    public function uploadMedia()
    {
        $this->display('Index/uploadMedia');
    }
}