<?php

/************************************PHP导出CSV文件**********************************************/
/**
 * 开始生成
 * 1. 首先将数组拆分成以逗号（注意需要英文）分割的字符串
 * 2. 然后加上每行的换行符号，这里建议直接使用PHP的预定义常量PHP_EOL
 * 3. 最后写入文件
 */


$csv_header = ['名称','性别','年龄'];
// 内容
$csv_body = [
    ['张三','男','13'],
    ['李四','女','13'],
    ['王五','男','13'],
    ['赵六','未知','13']
];


// 处理头部标题
$header = implode(',', $csv_header) . PHP_EOL;
// 处理内容
$content = '';
foreach ($csv_body as $k => $v) {
    $content .= implode(',', $v) . PHP_EOL;
}
// 拼接
$csv = $header.$content;

// 打开文件资源，不存在则创建
$fp = fopen('test.csv','a');

//要解决PHP生成CSV文件的乱码问题，只需要在文件的开始输出BOM头，告诉windows CSV文件的编码方式，从而让Excel打开CSV时采用正确的编码。
fwrite($fp, chr(0xEF).chr(0xBB).chr(0xBF)); // 添加 BOM
// 写入并关闭资源
fwrite($fp, $csv);
fclose($fp);
