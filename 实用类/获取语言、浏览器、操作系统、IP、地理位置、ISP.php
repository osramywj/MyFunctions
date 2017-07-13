<?php
/**
 * 获取访客信息的类：语言、浏览器、操作系统、IP、地理位置、ISP。
 * 使用：
 *      $obj = new class_guest_info;
 *      $obj->GetLang();        //获取访客语言：简体中文、繁體中文、English。
 *      $obj->GetBrowser();     //获取访客浏览器：MSIE、Firefox、Chrome、Safari、Opera、Other。
 *      $obj->GetOS();          //获取访客操作系统：Windows、MAC、Linux、Unix、BSD、Other。
 *      $obj->GetIP();          //获取访客IP地址。
 *      $obj->GetAdd();         //获取访客地理位置，使用 Baidu 隐藏接口。
 *      $obj->GetIsp();         //获取访客ISP，使用 Baidu 隐藏接口。
 */
class class_guest_info{
    function GetLang() {
        $Lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 4);
        //使用substr()截取字符串，从 0 位开始，截取4个字符
        if (preg_match('/zh-c/i',$Lang)) {
            //preg_match()正则表达式匹配函数
            $Lang = '简体中文';
        }
        elseif (preg_match('/zh/i',$Lang)) {
            $Lang = '繁體中文';
        }
        else {
            $Lang = 'English';
        }
        return $Lang;
    }
    function GetBrowser() {
        $Browser = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/MSIE/i',$Browser)) {
            $Browser = 'MSIE';
        }
        elseif (preg_match('/Firefox/i',$Browser)) {
            $Browser = 'Firefox';
        }
        elseif (preg_match('/Chrome/i',$Browser)) {
            $Browser = 'Chrome';
        }
        elseif (preg_match('/Safari/i',$Browser)) {
            $Browser = 'Safari';
        }
        elseif (preg_match('/Opera/i',$Browser)) {
            $Browser = 'Opera';
        }
        else {
            $Browser = 'Other';
        }
        return $Browser;
    }
    function GetOS() {
        $OS = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/win/i',$OS)) {
            $OS = 'Windows';
        }
        elseif (preg_match('/mac/i',$OS)) {
            $OS = 'MAC';
        }
        elseif (preg_match('/linux/i',$OS)) {
            $OS = 'Linux';
        }
        elseif (preg_match('/unix/i',$OS)) {
            $OS = 'Unix';
        }
        elseif (preg_match('/bsd/i',$OS)) {
            $OS = 'BSD';
        }
        else {
            $OS = 'Other';
        }
        return $OS;
    }
    function GetIP() {
        if (!emptyempty($_SERVER['HTTP_CLIENT_IP'])) {
            //如果变量是非空或非零的值，则 empty()返回 FALSE。
            $IP = explode(',',$_SERVER['HTTP_CLIENT_IP']);
        }
        elseif (!emptyempty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $IP = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
        }
        elseif (!emptyempty($_SERVER['REMOTE_ADDR'])) {
            $IP = explode(',',$_SERVER['REMOTE_ADDR']);
        }
        else {
            $IP[0] = 'None';
        }
        return $IP[0];
    }
    private function GetAddIsp() {
        $IP = $this->GetIP();
        $AddIsp = mb_convert_encoding(file_get_contents('http://open.baidu.com/ipsearch/s?tn=ipjson&wd='.$IP),'UTF-8','GBK');
        //mb_convert_encoding() 转换字符编码。
        if (preg_match('/noresult/i',$AddIsp)) {
            $AddIsp = 'None';
        }
        else {
            $Sta = stripos($AddIsp,$IP) + strlen($IP) + strlen('来自');
            $Len = stripos($AddIsp,'"}')-$Sta;
            $AddIsp = substr($AddIsp,$Sta,$Len);
        }
        $AddIsp = explode(' ',$AddIsp);
        return $AddIsp;
    }
    function GetAdd() {
        $Add = $this->GetAddIsp();
        return $Add[0];
    }
    function GetIsp() {
        $Isp = $this->GetAddIsp();
        if ($Isp[0] != 'None' && isset($Isp[1])) {
            $Isp = $Isp[1];
        }
        else {
            $Isp = 'None';
        }
        return $Isp;
    }
}
?>