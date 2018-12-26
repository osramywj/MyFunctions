<?php


if($_FILES &&$_POST){
//if($_POST['username']=='yang'){
    $data['status'] = 'success';
    $data['info'] = '成功';
//    echo json_encode($data);
        echo json_encode($data);
//    echo '提交成功';
}else{
    $data['status'] = 'fail';
    $data['info'] = '失败';
//    echo json_encode('提交失败');
    echo json_encode($data);
}