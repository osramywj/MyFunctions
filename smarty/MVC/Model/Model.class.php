<?php
/**
 * Created by Wenju Yang.
 * 模型基础类
 */

class Model extends Core{
    protected static $db;
    public function __construct(){
        try{
            $pdo=new PDO('mysql:host=127.0.0.1;port=3306;dbname=junemall','root','root',
                array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
            $pdo->exec('set names utf8');
            self::$db=$pdo;
        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }
}