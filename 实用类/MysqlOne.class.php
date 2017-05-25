<?php

class Mysql{
    private static $instance=null;
    private function  __clone(){}

    private function __construct(){
    }
//第一方法是在调用getInstance()时自动调用connect()连接数据库函数；
//第二种方法是在调用getInstance()，new Mysql时将参数传进来，通过内部自动触发__construct(),将参数传给此构造方法，，运行内部的连接数据库函数体；

    public static function getInstance($dbInfo){
        if(self::$instance==null || !(self::$instance instanceof Mysql)){
            self::$instance=new Mysql;
        }
        self::connect($dbInfo);
        return self::$instance;
    }

    public static function connect($dbInfo){
        $connect = mysql_connect($dbInfo['db_host'] . ':' . $dbInfo['db_port'], $dbInfo['db_user'], $dbInfo['db_pwd']) or die("数据库连接失败,错误编码:" . mysql_errno() . ',错误信息：' . mysql_error());
        mysql_select_db($dbInfo['db_name']);
        mysql_set_charset($dbInfo['db_charset']);
    }

    //查询并返回一条记录
    public function fetchOne($sql,$result_type=MYSQL_ASSOC){
        if($res=mysql_query($sql)){
            if(mysql_num_rows($res)>0){
                $arr=mysql_fetch_array($res,$result_type);
                return $arr;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    //查询并返回多条记录
    public function fetchAll($sql,$result_type=MYSQL_ASSOC){
        if($res=mysql_query($sql)){
            if(mysql_num_rows($res)>0){
                while($row=mysql_fetch_array($res,$result_type)){
                    $arr[]=$row;
                };
                return $arr;

            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    //更新数据并返回受影响的记录行数
    //update 表名 set 字段名='值',字段名='值',.... where ....
    public function update($table,$arr,$where=null){
        // print_r($arr);
        $str='';
        foreach($arr as $key=>$val){
            $str.=$key.'="'.$val.'",';
        }
        $str=substr($str,0,-1);
        $w=$where?"where {$where} ":'';

        $sql="update {$table} set {$str} $w";
        if(mysql_query($sql)){
            if(mysql_affected_rows()>0){
                return mysql_affected_rows();
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    //插入数据并返回最后一次插入记录的主键ID
    //insert into 表名(字段名,字段名,....) values(值,值,....)
    public function insert($table,$arr){
        // print_r($arr);
        $keys=join(',',array_keys($arr));
        $values='"'.join('","',array_values($arr)).'"';
        $sql="insert into {$table}({$keys}) values({$values})";
        if(mysql_query($sql)){
            if(mysql_affected_rows()>0){
                return mysql_insert_id();
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    //删除数据并返回受影响的记录行数
    //delete from 表名 where....
    public function delete($table,$where){
        $w=$where?" where {$where}":'';
        $sql="delete from {$table} $w";
        if(mysql_query($sql)){
            if(mysql_affected_rows()>0){
                return mysql_affected_rows();
            }else{
                return false;
            }
        }else{
            return false;
        };
    }

    //获取结果集的总记录数
    public function getTotalRows($sql){
        if($res=mysql_query($sql)){
            return mysql_num_rows($res);
        }else{
            return false;
        }
    }
}
/*$dbInfo=array(
    'db_host'    => '127.0.0.1',
    'db_port'    => '3306',
    'db_user'    => 'root',
    'db_pwd'     => 'root',
    'db_name'    => 'junemall',
    'db_charset' => 'utf8'
);*/
//$obj=Mysql::getInstance($dbInfo);
//$arr=$obj->fetchOne("select * from junemall_goods where id=1");
//$arr1=$obj->insert('junemall_admin',array('username'=>'zs','password'=>123,'last_login_time'=>123,'last_login_ip'=>123));
//echo "<pre>";
//
//var_dump($obj);
//print_r($arr1);