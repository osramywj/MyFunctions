<?php
/**
 * 检测是来自哪种终端的访问
 * @return bool
 */
function getAccessClient(){
    $useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
//    $useragent='';
    $useragent_info=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';
    function CheckSubstrs($substrs,$text){
        foreach($substrs as $substr)
            if(false!==strpos($text,$substr)){
                return $substr;
            }
        return "PC";
    }

    $client=['Android','iPhone','iPad'];
    return CheckSubstrs($client,$useragent_info);

}

var_dump(getAccessClient());