<?php
$v1 = $_POST['val1'];
if ($v1=='abc') {
    $res  = [
        'status'=>'success',
        'msg'=>'成功',
        'data'=>[
            'title'=>'new line',
            'content'=>'This is a new paragraph'
        ]
    ];
}else{
    $res  = [
        'status'=>'error',
        'msg'=>'失败'
    ];
}

echo json_encode($res);