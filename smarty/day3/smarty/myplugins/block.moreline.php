<?php

function smarty_block_moreline($params,$content,$smarty,&$repeat){
    if(!$repeat){  //只在结束标签调用函数是输出
        if(isset($content)){
            $str='';
            for($i=1;$i<=$params['line'];$i++){
                $str.= "<p style='color: {$params['color']};font-size: {$params['size']};font-weight: {$params['weight']};'>{$content}</p>";
            }
            return $str;
        }
    }
}