<?php
/**
 *对表格做一些设置
 */
require './db.php';//引入数据库操作类文件
require 'phpExcel/PHPExcel.php';//引入PHPExcel类文件
$db = new MysqlCon($dbconfig);//连接数据库
$objPHPExcel = new PHPExcel();//实例化PHPExcel类，等同于在桌面上新建一个excel，里面已经含有一个sheet了
$objSheet = $objPHPExcel->getActiveSheet();
$objSheet->setTitle('用户信息表');
$objSheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//设置默认垂直水平居中
$objSheet->getDefaultStyle()->getFont()->setName('微软雅黑')->setSize('12');//设置默认的字体
$objSheet->getStyle('A2:H2')->getFont()->setSize('14')->setBold(true);//设置特定单元格的字体格式
$objSheet->getStyle('A2:H2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('7FFFD4');//填充背景颜色
$objSheet->getStyle('A1:H1')->getFont()->setSize('20')->setBold(true);
$objSheet->getStyle('A1:H1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FF0000');
$styleArray = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THICK,
            'color' => array('rgb' => '000000'),
        ),
    ),
);
$objSheet->getStyle('A1:H13')->applyFromArray($styleArray);//设置单元格边框样式；也可将以上的样式全部写在$styleArray中；

$objSheet->getColumnDimension('A')->setWidth(15);//设置某一列的宽度

$objPHPExcel->getActiveSheet()->getStyle('A1:H13')->getAlignment()->setWrapText(true);//设置自动换行

$degree = $db->getDegree();//获得所有等级数

/*也可以用fromArray()来一行一行导入数据*/
/*$arr = array(
    array('','','',''),
    array('','','',''),
    array('','','','')
);
$objSheet->fromArray($arr);//直接加载数组，填充到单元格内*/


$i=0;
foreach($degree as $k_d=>$v_d){
    $degreeIndex = getCells($i*2);//每个等级所对应的列
    $objSheet->setCellValue($degreeIndex.'2','等级'.$v_d['degree']);

    $userInfo = $db->userInfo($v_d['degree']);//根据等级获取用户详细信息
    $idIndex = getCells($i*2);//获得id所在的列
    $usernameIndex = getCells($i*2+1);//获得username所在的列
    $objSheet->setCellValue($idIndex.'3','id')->setCellValue($usernameIndex.'3','姓名');//设置id,姓名的表头
    $j=4;
    foreach($userInfo as $key=>$val){
        $objSheet->setCellValue($idIndex.$j,$val['id'])->setCellValue($usernameIndex.$j,$val['username']);//插入数据
        //        $objSheet->setCellValueExplicit('B'.$j,$val['code'],PHPExcel_Cell_DataType::TYPE_STRING);//设置单元格数字以文本形式显示,防止科学计数法
        $j++;
    }
    $i++;
    $endIndex = getCells($i*2-1);//获得等级单元格的终止列
    $objSheet->mergeCells($idIndex.'2:'.$endIndex.'2');//合并等级单元格
}
$lastIndex = getCells($i*2-1);//获得最后一列

$objSheet->setCellValue('A1','用户信息');
$objSheet->mergeCells("A1:$lastIndex".'1');//合并第一行单元格

//根据下标获取所对应的列
function getCells($index){
    $arr = range('A','Z');
    return $arr[$index];
}










/*************************将excel文件导出到本地文件*********************************/
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
        header("Content-Disposition: attachment;filename=".$filename); //文件以附件形式输出，设置文件名
        header('Cache-Control: max-age=0');//禁止缓存
    }