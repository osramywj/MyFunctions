<?php
function smarty_function_multiline($params,$smarty){
    $str='';
    for($i=1;$i<=$params['line'];$i++){
        $str.="<p style='color: {$params['color']};font-size: {$params['size']}'>{$params['content']}</p>";
    }
    return $str;
}