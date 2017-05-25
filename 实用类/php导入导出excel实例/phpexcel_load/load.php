<?php
/**
 * Created by ju.
 *读取excel文件
 */

require 'phpExcel/PHPExcel/IOFactory.php';//引入IOFactory.php类文件
$file = './test.xls';

/***********若要直接在加载文件之前就选择需要加载的sheet，采用下面的方法（不必要一开始就把整个表所有sheet都加载了） start**************/
/*$fileType = PHPExcel_IOFactory::identify($file);//自动获取文件类型
$objReader = PHPExcel_IOFactory::createReader($fileType);//获取文件读取操作对象
$sheetName =array('用户信息表');//需要读取的sheet，当读取多个sheet时，采用数组
$objReader->setLoadSheetsOnly($sheetName);//只加载制定的sheet
$objPHPExcel = $objReader->load($file);*/

/***************************************只加载指定sheet  end********************************************************************/




$objPHPExcel = PHPExcel_IOFactory::load($file);//加载文件
//1.
/*$sheetCount = $objPHPExcel->getSheetCount();//获取excel里有多少sheet；
for($i=0;$i<$sheetCount;$i++){
    $data[] = $objPHPExcel->getSheet($i)->toArray();//读取每个sheet里的数据，全部放入到数组中;一次性将所有信息读出来，数据量大时会崩溃
}*/

foreach($objPHPExcel->getWorksheetIterator() as $sheet){//循环活动表
    foreach($sheet ->getRowIterator() as $row){//循环行
        if($row->getRowIndex()<3){//跳过前三行，不输出显示
            continue;
        }
        foreach ($row->getCellIterator() as $cell){//循环单元格
            $data=$cell->getValue()." ";//获取每个单元格的值
            echo $data;
        }
        echo "<br>";
    }
    echo "<br>";
}










