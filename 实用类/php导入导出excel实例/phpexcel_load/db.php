<?php
/**
 * Created by ju.
 *
 */
require './dbconfig.php';
class MysqlCon{
    public $connect = null;
    public function __construct($dbconfig){
        $this->connect = mysql_connect($dbconfig['host'].':'.$dbconfig['port'],$dbconfig['username'],$dbconfig['password']) or die('数据库连接错误：'.mysql_error());
        mysql_set_charset($dbconfig['charset']);
        mysql_select_db($dbconfig['dbname']);
    }
    public function getResult($sql){
        $resource = mysql_query($sql);
        if(mysql_num_rows($resource)){
            while($row = mysql_fetch_assoc($resource)){
                $res[] = $row;
            }
        }
        return $res;
    }
    public function getUserList($degree){
        $sql = "select * from dada_user where degree = $degree";
        return $res = $this->getResult($sql);
    }

    public function getDegree(){
        $sql = "select DISTINCT (degree) from dada_user order by degree asc";
        return $res = $this->getResult($sql);
    }

    public function userInfo($degree){
        $sql = "select id,username from dada_user where degree =$degree";
        return $res = $this->getResult($sql);
    }
}