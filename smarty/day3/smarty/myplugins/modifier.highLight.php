<?php
//高亮显示
function smarty_modifier_highLight($var,$color,$weight,$size){
    return "<p style='color: {$color};font-weight: {$weight};font-size: {$size}'>$var</p>";
}

