<?php
Vendor("PHPExcel.PHPExcel");//引入phpexcel类(注意你自己的路径)  
Vendor("PHPExcel.PHPExcel.IOFactory");  
// Vendor("PHPExcel.PHPExcel.Reader.Excel5");  
// Vendor("PHPExcel.PHPExcel.Reader.Excel2007");  
class ExcelReader {
    /**
     * 读取excel
     * @param unknown_type $excelPath：excel路径
     * @param unknown_type $allColumn：读取的列数
     * @param unknown_type $sheet：读取的工作表
     */
    public static function reader_excel($excelPath, $allColumn = 0, $sheet = 0) {
        $excel_arr = array();
 
        //默认用excel2007读取excel,若格式 不对，则用之前的版本进行读取
        // $PHPReader = new \PHPExcel_Reader_Excel2007();
        // if(!$PHPReader->canRead($excelPath)) {
        //     $PHPReader = new \PHPExcel_Reader_Excel5();
        //     if(!$PHPReader->canRead($excelPath)) {
        //         //返回空的数组
        //         return $excel_arr; 
        //     }
        // }
        $PHPReader = PHPExcel_IOFactory::createReader('Excel5'); 
         
        //载入excel文件
        $PHPExcel  = new \PHPExcel();
        $PHPExcel  = $PHPReader->load($excelPath);
         
        //获取工作表总数
        $sheetCount = $PHPExcel->getSheetCount();
         
        //判断是否超过工作表总数，取最小值
        $sheet = $sheet < $sheetCount ? $sheet : $sheetCount;
         
        //默认读取excel文件中的第一个工作表
        $currentSheet = $PHPExcel->getSheet($sheet);
         
        if(empty($allColumn)) {
            //取得最大列号，这里输出的是大写的英文字母，ord()函数将字符转为十进制，65代表A
            $allColumn = ord($currentSheet->getHighestColumn()) - 65 + 1;
        }
         
        //取得一共多少行
        $allRow = $currentSheet->getHighestRow();
         
        //从第二行开始输出，因为excel表中第一行为列名
        for($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
            for($currentColumn = 0; $currentColumn <= $allColumn - 1; $currentColumn++) {
                $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                $excel_arr[$currentRow - 2][$currentColumn] = $val;
            }
        }
         
        //返回二维数组
        return $excel_arr;
    }
}
 
?>