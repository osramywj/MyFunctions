<?php
$card = $_POST['card'];
if(strlen($card)>3){
    echo 'true';
}else{
    echo 'false';
}
