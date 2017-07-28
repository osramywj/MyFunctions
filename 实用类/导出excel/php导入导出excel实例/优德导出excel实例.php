<?php

define('IN_ECS', true);
require('../includes/init.php');
require('../includes/PHPExcel_1.8.0_doc/Classes/PHPExcel.php');

//准备数据


//获得关注商品列表
function getfocusList(){
    $sql = "select Goodsid , Addtime ,Email from ecs_focusReport";
    return $focusList = $GLOBALS['db']->getAll($sql);
}

$goodsArr = getfocusList();//是个二维数组，应该是对应不同的邮箱的；


$goodsIds = explode(',',$goodsArr[0]['Goodsid']);


//测试
//$goodsIds = explode(',',$goodsArr[1]['Goodsid']);
//关注商品的个数
$count =count($goodsIds);

$email = str_replace(',', ' ', $goodsArr[0]['Email']);

echo $email;


$sql = "select goods_alias from ecs_goods where goods_id in (".$goodsArr[0]['Goodsid'].")";



//商品通用名称的数组；
$goodsNames = $GLOBALS['db']->getAll($sql);


//print_r($goodsNames);die;
//根据商品id获取它的销售信息
function getGoodsSaleInfo($goodsId){
    $startTime = strtotime('today')-24*60*60-28800;
    $endTime = strtotime('today')-28800;
//    $startTime = strtotime('2017-7-4')-24*60*60-28800;
//    $endTime = strtotime('2017-7-16')-28800;

    $sql = "select  DISTINCT oi.order_sn,og.order_id,g.goods_alias,og.goods_sn,og.goods_number,og.goods_price,og.act_price,og.rule_id,og.rule_name,og.rule_con,oi.code_id,oi.coupon_val,oi.add_time,oi.shipping_name,oi.invoice_no,oi.shipping_fee,
            oi.postscript ,oi.to_buyer ,oi.pay_name ,oi.payway,oi.inv_payee from ecs_order_goods as og inner join ecs_order_info as oi 
            on og.order_id = oi.order_id inner join ecs_goods as g on g.goods_sn=og.goods_sn where og.goods_id ={$goodsId} and oi.is_delete = 0 and adminuser_del = 0 and
            if(pay_id = 3,pay_status = 2,order_status <> 0 and order_status <>2) and oi.add_time BETWEEN $startTime and $endTime";

    return $GLOBALS['db']->getAll($sql);
}

//$goodsList = getGoodsSaleInfo($goodsIds[2]);
//print_r($goodsList);die;
//echo $goodsNames[0]['goods_alias'];die;
$objPHPExcel = new PHPExcel();//实例化PHPExcel类，等同于在桌面上新建一个excel，里面已经含有一个sheet了
for($i=0;$i<$count;$i++){
    if($i>0){
        $objPHPExcel->createSheet();//创建新的内置表
    }
    $objPHPExcel->setActiveSheetIndex($i);//把新创建的sheet设置为当前活动sheet
    $objSheet = $objPHPExcel->getActiveSheet();//获得当前的内置表


    $goodsList = getGoodsSaleInfo($goodsIds[$i]);  //获取每个关注商品的详细销售记录

    $objSheet->setTitle($goodsNames[$i]['goods_alias']); //给当前活动sheet设置名称

    $objSheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//设置默认垂直水平居中

    $objSheet->setCellValue('A2','订单编号')->setCellValue('B2','商品名称')->setCellValue('C2','商品编码')->setCellValue('D2','数量')->setCellValue('E2','单价')
             ->setCellValue('F2','订单日期')->setCellValue('G2','物流公司')->setCellValue('H2','物流单号')->setCellValue('I2','买家运费')->setCellValue('J2','货到手续费')
             ->setCellValue('K2','买家留言')->setCellValue('L2','卖家备注')->setCellValue('M2','发票抬头')->setCellValue('N2','支付方式')->setCellValue('O2','活动')->setCellValue('P2','优惠券')->setCellValue('Q2','小计');//设置表头

    $j=3;

    //单个订单商品小计
    $val['subTotalPrice'] = 0;

    //该商品总销售额
    $totalPrice = 0;
    foreach($goodsList as $key=>$val){
        switch ($val['payway']){
            case 1:
                $payway = '支付宝';
                break;
            case 2:
                $payway = '连连';
                break;
            case 3:
                $payway = '微信';
                break;
        }

        //每种活动都减去优惠券coupon_val,有就减，没有就减0;
        if ($val['rule_name']=='秒杀') {
            $val['goods_price']=$val['act_price'];
            $val['subTotalPrice'] = $val['act_price']*$val['goods_number']-$val['coupon_val'];
        }
        elseif($val['rule_name']=='满减'){
            preg_match_all('/满(\d+)元减(\d+)元/',$val['rule_con'],$matches);
            $meet = $matches[1][0];
            $minus = $matches[2][0];

            $val['subTotalPrice'] = ($val['goods_price']*$val['goods_number']-$meet)>=0 ? ($val['goods_price']*$val['goods_number']-$minus)-$val['coupon_val'] : $val['goods_price']*$val['goods_number']-$val['coupon_val'];
        }
        else{
            $val['subTotalPrice'] = $val['goods_price']*$val['goods_number']-$val['coupon_val'];
        }


        $objSheet->setCellValueExplicit('A'.$j,$val['order_sn'],PHPExcel_Cell_DataType::TYPE_STRING)->setCellValue('B'.$j,$val['goods_alias'])->setCellValue('C'.$j,$val['goods_sn'])
            ->setCellValue('D'.$j,$val['goods_number'])->setCellValue('E'.$j,$val['goods_price'])->setCellValueExplicit('F'.$j,local_date("Y-m-d H:i:s",$val['add_time']),PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('G'.$j,$val['shipping_name'])->setCellValueExplicit('H'.$j,$val['invoice_no'],PHPExcel_Cell_DataType::TYPE_STRING)->setCellValue('I'.$j,$val['shipping_fee'])
            ->setCellValue('J'.$j,$val[''])->setCellValue('K'.$j,$val['postscript'])->setCellValue('L'.$j,$val['to_buyer'])
            ->setCellValue('M'.$j,$val['inv_payee'])->setCellValue('N'.$j,$val['pay_name']=='货到付款' ? '货到付款' :$val['pay_name'].'('.$payway.')')
            ->setCellValue('O'.$j,$val['rule_con'])->setCellValue('P'.$j,$val['coupon_val'])->setCellValue('Q'.$j,$val['subTotalPrice']);
            $totalPrice += $val['subTotalPrice'];



        $j++;
//        $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$j,$result[1],PHPExcel_Cell_DataType::TYPE_STRING);//设置单元格数字以文本形式显示
    }

    $objSheet->setCellValue('A1','商品订单表');
    $objSheet->mergeCells("A1:Q1");//合并第一行单元格
    $objSheet->getStyle('A1:Q1')->getFont()->setSize('16')->setBold(true);//设置特定单元格的字体格式
    $objPHPExcel->getActiveSheet()->getStyle('A2:N'.$j)->getAlignment()->setWrapText(true);//设置自动换行
    $objSheet->getColumnDimension('A')->setWidth(15);//设置单元格宽度
    $objSheet->getColumnDimension('B')->setWidth(30);
    $objSheet->getColumnDimension('C')->setWidth(15);
    $objSheet->getColumnDimension('F')->setWidth(15);
    $objSheet->getColumnDimension('H')->setWidth(15);
    $objSheet->getColumnDimension('N')->setWidth(15);
    $objSheet->getColumnDimension('O')->setWidth(20);
    $objSheet->getRowDimension($j)->setRowHeight(30);//设置行高
    $objSheet->setCellValue('A'.$j,'销售总览')->setCellValue('D'.$j,'销售额 ： '.$totalPrice);
    $objSheet->mergeCells("A".$j.":C".$j)->mergeCells('D'.$j.':Q'.$j);
    $objSheet->getStyle('A'.$j.':Q'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objSheet->getStyle('A'.($j+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objSheet->mergeCells("A".($j+1).":Q".($j+1))->setCellValue('A'.($j+1),'备注：订单中优惠券抵扣已算入关注商品中，特此说明');

}



/******************直接输出到浏览器*******************************/
/*export_brower('Excel2007','goods_sale_info_'.date("Y-m-d",strtotime('last day')).'.xlsx');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
function export_brower($type,$filename){
    if($type == 'Excel5'){
        header('Content-Type: application/vnd.ms-excel');//输出excel03文件
    }else{
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//输出excel07文件
    }
    header("Content-Disposition: attachment;filename=".$filename); //文件以附件形式输出，设置文件名
    header('Cache-Control: max-age=0');//禁止缓存
}*/

/*************************将excel文件导出保存到本地文件*********************************/
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');//生成excel文件

$objWriter->save(dirname(__FILE__).'/excel/orderInfo_'.date("Y-m-d",strtotime('today')-1).'.xlsx');//输出excel文件