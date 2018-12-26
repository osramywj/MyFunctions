<?php
$v1 = $_POST['val1'];
if ($v1=='abc') {
    $res  = [
        'status'=>'success',
        'msg'=>'成功'
    ];
}else{
    $res  = [
        'status'=>'error',
        'msg'=>'失败'
    ];
}

echo json_encode($res);