<?php
/**
 * Created by Wenju Yang.
 * 
 */
class GoodsController extends Controller{
    public function goodsList(){
        $goods=new GoodsModel();
        $goodsList=$goods->getGoodsList();
        $this->assign('goodsList',$goodsList);
        $this->display('goodsList.html');
    }
}