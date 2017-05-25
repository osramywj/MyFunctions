<?php
include_once "init.inc.php";

$smarty->assign(array('title'=>'我是title2','content'=>'我是内容2'));
$smarty->assign('title','我是title');
$smarty->assign("content","I'm the longest content.");
$smarty->assign('arr',array('username'=>'zs','age'=>30));


/*function test($var,$color,$weight,$size){
    return "<p style='color: {$color};font-weight: {$weight};font-size: {$size}'>$var</p>";
}

$smarty->registerPlugin('modifier','highLight','test');*/

//$smarty->registerPlugin('modifier','strlen','strlen');


//preg_match()函数第一个参数不是要进行处理的变量，要重新定义并注册一下
/*function test2($obj,$reg){
    return preg_match($reg,$obj);
}
$smarty->registerPlugin('modifier','preg_match','test2');*/

//自定义函数($smarty不能省)
/*function test3($params,$smarty){
    $str='';
    for($i=1;$i<=$params['line'];$i++){
        $str.="<p style='color: {$params['color']};font-size: {$params['size']}'>{$params['content']}</p>";
    }
    return $str;
}
$smarty->registerPlugin('function','multiline','test3');*/


//属性都放在$params里，在开始标签和结束标签函数将被smarty调用两次，开始标签第一次调用时$content=null,$repeat=true,
//此后的调用$repeat都为false;在结束标签时，$content里是被smarty处理过的模板的全部内容;
/*function test4($params,$content,$smarty,&$repeat){
    if(!$repeat){  //只在结束标签调用函数时输出
        if(isset($content)){
            $str='';
            for($i=1;$i<=$params['line'];$i++){
                $str.= "<p style='color: {$params['color']};font-size: {$params['size']};font-weight: {$params['weight']};'>{$content}</p>";
            }
                return $str;
        }
    }
}
$smarty->registerPlugin('block','moreline','test4');*/

try{
    $pdo=new PDO("mysql:host=127.0.0.1;port=3306;dbname=junemall",'root','root',array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    $pdo->exec('set names utf8');
    $statements=$pdo->prepare("select * from junemall_admin");
    $statements->execute();
    $admin=$statements->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e){
    echo $e->getMessage();
}
$smarty->assign('admin',$admin);



$user2=array(
    array('username'=>'jack','age'=>34,'sex'=>'male','hobby'=>'football'),
    array('username'=>'rose','age'=>24,'sex'=>'female','hobby'=>'football'),
    array('username'=>'jim','age'=>54,'sex'=>'male','hobby'=>'sing'),
    array('username'=>'tom','age'=>34,'sex'=>'male','hobby'=>'football'),
    array('username'=>'kate','age'=>14,'sex'=>'female','hobby'=>'dance')
);
$smarty->assign('user2',$user2);

//display()内的参数是模板文件夹（templates）下的模板文件名，也可以使用文件的绝对路径
//$smarty->display("test.html");
$smarty->display('test.html');


