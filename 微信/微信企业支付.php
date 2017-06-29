<?php


class CompanyPay
{


    //证书存放路径
    protected $SSLCERT_PATH = '/alidata/www/youde/appClientApi/appwxcert/apiclient_cert.pem';
    protected $SSLKEY_PATH  = '/alidata/www/youde/appClientApi/appwxcert/apiclient_key.pem';

    protected $APPID 		 = 'wx636c84e256924513';//公众账号appid
    protected $MCHID 		 = '1336211201';//商户号
    protected $KEY 		 = '4kd8zd46jas9815klm43h2j5d90zj2m7';
    protected $APPSECRET    = 'cfff3e3c83c4313be9cb8784b1ae581d';
    protected $APPURL 		 = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
    /**
     * 微信企业付款
     * @param  用户的openid
     * @param  收款用户真实姓名。
     * @param  说明信息。必填。
     * @param  金额 单位分
     * @author zhanghao
     */
    public function pay($openid='', $desc='', $money=0){
        $apiUrl = $this->APPURL;//企业付款接口url
        $Parameters=array();
        $Parameters['amount']           = $money;//企业付款金额，单位为分
        $Parameters['check_name']       = 'NO_CHECK';//NO_CHECK：不校验真实姓名 FORCE_CHECK：强校验真实姓名（未实名认证的用户会校验失败，无法转账） OPTION_CHECK：针对已实名认证的用户才校验真实姓名（未实名认证用户不校验，可以转账成功）
        $Parameters['desc']             = $desc;//企业付款操作说明信息。必填。
        $Parameters['mch_appid']        = $this->APPID;//微信分配的公众账号ID
        $Parameters['mchid']            = $this->MCHID;//微信支付分配的商户号
        $Parameters['nonce_str']        = $this->createNoncestr();//随机字符串，不长于32位
        $Parameters['openid']           = $openid;//商户appid下，某用户的openid
        $Parameters['partner_trade_no'] = 'YD'.time().rand(10000, 99999);//商户订单号，需保持唯一性
        //$Parameters['re_user_name']     = $username;//收款用户真实姓名。 如果check_name设置为FORCE_CHECK或OPTION_CHECK，则必填用户真实姓名
        $Parameters['spbill_create_ip'] = $_SERVER['SERVER_ADDR'];//调用接口的机器Ip地址

        $Parameters['sign']             = $this->getSign($Parameters);//签名

        $xml    = $this->arrayToXml($Parameters);

        $res    = $this->postXmlSSLCurl($xml,$apiUrl);

        $result = $this->xmlToArray($res);

        file_put_contents("/alidata/www/youde/temp/logs_paid/HB_".date('Ymd').'.txt', "===================================================================================\r\n".date('Y-m-d H:i:s')."\r\n".$res."\r\n\r\n",FILE_APPEND);

        if($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS'){


            return array('flag'=>true,'msg'=>'发送成功');

        }else{

            return array('flag'=>false,'msg'=>$result);

        }

    }





    //格式化参数 签名要用
    public function formatBizQueryParaMap($paraMap, $urlencode){

        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v){

            if($urlencode){

                $v = urlencode($v);
            }
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar;
        if (strlen($buff) > 0)
        {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }

    //生成签名
    public function getSign($Obj){

        foreach ($Obj as $k => $v){

            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        //echo '【string1】'.$String.'</br>';
        //签名步骤二：在string后加入KEY
        $String = $String."&key=4kd8zd46jas9815klm43h2j5d90zj2m7";
        //echo "【string2】".$String."</br>";
        //签名步骤三：MD5加密
        $String = md5($String);
        //echo "【string3】 ".$String."</br>";
        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        //echo "【result】 ".$result_."</br>";
        return $result_;
    }


    //随机字符串 32位
    public function createNoncestr( $length = 32 ) {

        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }



    // array to xml
    public function arrayToXml($arr){

        $xml = "<xml>";
        foreach ($arr as $key=>$val){

            if (is_numeric($val)){

                $xml.="<".$key.">".$val."</".$key.">";

            }
            else
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
        $xml.="</xml>";
        return $xml;
    }


    //xml to array
    public function xmlToArray($xml){

        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }


    //使用证书 post提交xml到url
    function postXmlSSLCurl($xml,$url,$second=30){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch,CURLOPT_HEADER,FALSE);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT, $this->SSLCERT_PATH);
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY, $this->SSLKEY_PATH);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
        $data = curl_exec($ch);
        if($data){
            curl_close($ch);
            return $data;
        }
        else {
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error"."<br>";
            echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
            curl_close($ch);
            return false;
        }
    }
}