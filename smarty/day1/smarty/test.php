<?php
include_once "init.inc.php";

$smarty->assign(array('title'=>'我是title2','content'=>'我是内容2'));
//$smarty->assign('title','我是title');
//$smarty->assign("content",'我是内容');
//display()内的参数是模板文件夹（templates）下的模板文件名，也可以使用文件的绝对路径
//$smarty->display("test.html");
$smarty->assign('count',123456);
$smarty->assign('arr',array(0,1,2,3,4));
$smarty->assign('arr2',array('user'=>'zs','pwd'=>'aaa','age'=>123));

class Person{
    public $age=12;
    public $sex='male';
    public function walk(){
        echo "I'm walking";
        return $this;
    }
    public function eat(){
        echo "I'm eating";
    }
}
$smarty->assign('person',new Person());

$_POST['page']=213;
$_SESSION['id']='jsdaifo';
const A='1234';





$smarty->display(ROOT."templates/test.html");
