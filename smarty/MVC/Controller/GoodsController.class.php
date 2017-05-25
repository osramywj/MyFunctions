<?php
/**
 * Created by Wenju Yang.
 * 商品列表控制器
 */

class GoodsController extends Controller{
    public function goodsList(){
        $goods=new GoodsModel();
        $goodsList=$goods->getGoodsList();
        $this->assign('goodsList',$goodsList);
        $this->display('goodsList.html');
    }
    public function goodsDetail(){
        $goods=new GoodsModel();
        $goodsDetail=$goods->getGoodsDetail();
        $this->assign('goodsDetail',$goodsDetail);
        $this->display("goodsDetail.html");
    }
}