<?php

function getTree($data, $pId)
{
$tree = '';
foreach($data as $k => $v)
{
  if($v['cate_ParentId'] == $pId)
  {        //父亲找到儿子
   $v['cate_ParentId'] = getTree($data, $v['cate_Id']);
   $tree[] = $v;
   //unset($data[$k]);
  }
}
return $tree;
}
$tree = getTree($data, 0);







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