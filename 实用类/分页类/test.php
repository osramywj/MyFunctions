<?php
include_once "Page.class.php";
mysql_connect('127.0.0.1:3306','root','root') or die("连接数据库失败，错误代码是：".mysql_errno().",错误信息是：".mysql_error());
mysql_select_db('junemall');
mysql_set_charset('utf8');

$res=mysql_query("select * from junemall_admin");

mysql_fetch_assoc($res);
$total=mysql_num_rows($res);

$obj=new Page($total);
echo "<pre>";
$page=$obj->showPage();
$res2=mysql_query("select * from junemall_admin limit $obj->firstNumPerPage , $obj->numPerPage");
$arr=mysql_fetch_assoc($res2);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<?php
if($res2){
    if(mysql_num_rows($res2)>0){
        while($rows=mysql_fetch_assoc($res2)){

            ?>
            <li><?=$rows['username']?></li>
        <?php
        }
    }
}

?>
<li><?=$page?></li>
</body>
</html>