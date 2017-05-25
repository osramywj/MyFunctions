<?php
/**
 *输出excel的基本功能
 */
require './db.php';//引入数据库操作类文件
require 'phpExcel/PHPExcel.php';//引入PHPExcel类文件
$db = new MysqlCon($dbconfig);//连接数据库
$objPHPExcel = new PHPExcel();//实例化PHPExcel类，等同于在桌面上新建一个excel，里面已经含有一个sheet了
for($i=0;$i<4;$i++){
    if($i>0){
        $objPHPExcel->createSheet();//创建新的内置表
    }
    $objPHPExcel->setActiveSheetIndex($i);//把新创建的sheet设置为当前活动sheet
    $objSheet = $objPHPExcel->getActiveSheet();//获得当前的内置表
    $objSheet->setTitle(($i+1).'等级'); //给当前活动sheet设置名称
    $userlist = $db->getUserList($i+1);//获取每个级别的用户列表
    $objSheet->setCellValue('A1','姓名')->setCellValue('B1','密码')->setCellValue('C1','等级');//设置表头
    $j=2;
    foreach($userlist as $key=>$val){
        $objSheet->setCellValue('A'.$j,$val['username'])->setCellValue('B'.$j,$val['password'])->setCellValue('C'.$j,$val['degree']);
        $j++;
    }
}
/*************************讲excel文件到处到本地文件*********************************/
/*$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');//生成excel文件
$objWriter->save('./export1.xlsx');//输出excel文件*/

/******************直接输出到浏览器*******************************/
export_brower('Excel5','test.xls');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
    function export_brower($type,$filename){
        if($type == 'Excel5'){
            header('Content-Type: application/vnd.ms-excel');//输出excel03文件
        }else{
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//输出excel07文件
        }
        header("Content-Disposition: attachment;filename='".$filename."'"); //文件以附件形式输出，设置文件名
        header('Cache-Control: max-age=0');//禁止缓存
    }