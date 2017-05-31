<?php
class EncryptCalss
{
  var $key=12;
  function encode($txt){
    for($i=0;$i<strlen($txt);$i++){
      $txt[$i]=chr(ord($txt[$i])+$this->key);
    }
    return $txt=urlencode(base64_encode(urlencode($txt)));
  }
  function decode($txt){
    $txt=urldecode(base64_decode(urldecode($txt)));
    for($i=0;$i<strlen($txt);$i++){
      $txt[$i]=chr(ord($txt[$i])-$this->key);
    }
    return $txt;
  }
}
//test:
//$str = 'php是世界上最好的语言';
//$str = 'JTdDdCU3QyVGMiVBNCVCQiVGMCVDNCVBMiVGMyVBMSU5OCVGMCVDNCU5NiVGMiVBOCU4QyVGMSVCMSVDOSVGMyVBNiU5MCVGNCVCQiVCOSVGNCVCNCU4Qw%3D%3D';
//$obj = new EncryptCalss();
//echo $obj->decode($str);
?>