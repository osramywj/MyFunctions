<?php
/**
 * Class 一个字符串加密解密类；
 */
class ption
{

  private static $original = array('=', '+', '/');
  private static $later = array('O0O0O', 'o0O0o', 'oo00o');

  function __construct()
  {

  }

  private static function md5($skey = '')
  {
    $skey = $skey ? $skey : 'ui' ; //uicms::_config('security/authkey');
    return md5(substr($skey, 0, 16));
  }

  /**
   * @use ption::en($string, $key);
   * @param String $string 需要加密的字串
   * @param String $skey 密钥
   * @param int $expiry 密文有效期, 加密时候有效， 单位 秒，0 为永久有效
   * @return String
   */
  static public function en($string = '', $skey = '', $expiry=0)
  {
    if( is_array( $string ) )
    {
      $string = json_encode($string); // uicms::json($string, true, 'en');
    }
    $string = str_pad($expiry ? $expiry + time() : 0, 10, 0).$string;
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    $skey = static::md5($skey);
    foreach (str_split($skey) as $key => $value)
    {
      $key < $strCount && $strArr[$key].=$value;
    }
    return str_replace(self::$original, self::$later, join('', $strArr));
  }

  /**
   * @use ption::de($string, $key);
   * @param String $string 需要解密的字串
   * @param String $skey 密钥
   * @return String
   */
  static public function de($string = '', $skey = '')
  {
    $strArr = str_split(str_replace(self::$later, self::$original, $string), 2);
    $strCount = count($strArr);
    $skey = static::md5($skey);
    foreach (str_split($skey) as $key => $value)
    {
      $key <= $strCount && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
    }
    $result = base64_decode(join('', $strArr));
    if(substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0)
    {
      return substr($result, 10);
    }
    else
    {
      return false;
    }
  }
}