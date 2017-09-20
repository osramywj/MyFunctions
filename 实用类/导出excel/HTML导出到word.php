<?php
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
//有了这些，可以把带html标签的html源码导入到word里，并且可以保持html的样式。
/*
<STYLE>
BR.page { page-break-after: always }
</STYLE>
在<head>部分加这个是为了实现打印的时候分页
 */
$wordStr = '<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns="http://www.w3.org/TR/REC-html40">
<head>
<STYLE>
BR.page { page-break-after: always }
</STYLE>
</head><body>';

$wordStr .= "<b>hello</b><p>this is html code</p>";

$wordStr .= '</body></html>';
//防止导出乱码
$filename = pathinfo(__FILE__);
$filename = $filename['filename'];
$file = iconv("utf-8", "GBK", $filename);

header("Content-Type: application/doc");
header("Content-Disposition: attachment; filename=" . $file . ".doc");
echo $wordStr;
?>