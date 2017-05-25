<?php
//没有传参数
function smarty_function_eightball($params, $smarty) {
    $answers = array(
        '是的',
        '不行',
        '没门儿',
        '看起来不好',
        '再回答一次',
        '根据情况决定'
    );
    $result = array_rand($answers);
    return  $answers[$result];
}
