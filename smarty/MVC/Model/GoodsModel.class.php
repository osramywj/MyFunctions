<?php
/**
 * Created by Wenju Yang.
 * 商品表模型
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
    public function getGoodsDetail(){
        $id=$_GET['id'];
        try{
            $statement=self::$db->query("select detail from junemall_goods where id={$id}");
            $goodsDetail=$statement->fetchAll(PDO::FETCH_ASSOC);
            return $goodsDetail;
        }catch (PDOException $e){
            die($e->getMessage());
        }
    }
}