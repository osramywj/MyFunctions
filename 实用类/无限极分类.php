<?php
/**
 * 无限极分类
 * @param int $pid  分类的父id
 * @param int $spaceNum  上下级分类之间的缩进
 * @param array $cateList   最终返回的分类列表
 * @return array
 */
function getAllCates($pid=0,$spaceNum=0,&$cateList=array()){
    $spaceNum+=4;

    $cate=$this->select();
    foreach ($cate as $k=> $v){
        if($v['pid']==$pid){
            $v['catename']=str_repeat('&nbsp;',$spaceNum).$v['catename'];
            $cateList[]=$v;
            $this->getAllCates($v['id'],$spaceNum,$cateList);
        }
    }
    return $cateList;
}
?>