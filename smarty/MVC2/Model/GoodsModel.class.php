<?php
/**
 * Created by Wenju Yang.
 * 
 */
class GoodsModel extends Model{
    public function getGoodsList(){
        try{
            $statement=self::$db->query("select * from junemall_goods");
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            echo $e->getMessage();
        }
    }
}