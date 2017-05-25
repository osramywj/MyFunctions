<?php
namespace Home\Controller;
use Think\Controller;
class WechatController extends Controller{
    private $token='wechat';
    private $appId='wx797c8526c8b78fde';
    private $appSecret='13dbf5955d6fabee70a8c6280e57cbc0';



    //签名认证
    public function checkSignature(){
        //1）将token、timestamp、nonce三个参数进行字典序排序
            $token=$this->token;
            $timestamp=I('get.timestamp');
            $nonce=I('get.nonce');
            $arr=array($token,$timestamp,$nonce);
            sort($arr);
        //2）将三个参数字符串拼接成一个字符串进行sha1加密
            $str=sha1(join('',$arr));
        //3）开发者获得加密后的字符串可与signature对比，标识该请求来源于微信
            if(I('get.signature')==$str){
                die(I('get.echostr'));
            }else{
                $this->responseMsg();
            }
    }

    public function responseMsg(){
        //获取微信服务器推送过来的POST数据(XML格式)，可以接受任何格式的内容
        $postXML=$GLOBALS['HTTP_RAW_POST_DATA'];
        //将XML转成对象
        $postObj=simplexml_load_string($postXML);
        //判断消息类型
        if(strtolower($postObj->MsgType)=='event'){
            //判断事件类型
            if(strtolower($postObj->Event)=='subscribe'){
                switch(strtolower($postObj->EventKey)){
                    case 'qrscene_1001':
                            echo $this->replyText($postObj,'您是通过1001号二维码关注我的');
                        break;
                    default:
                        echo $this->replyText($postObj,'[嘿哈]你怎么敢关注我');
                }

            }elseif(strtolower($postObj->Event)=='click'){
                if(strtolower($postObj->EventKey)=='praise_me'){
                    $content='谢谢你赞我';
                    echo $this->replyText($postObj,$content);
                }
            }
        }elseif(strtolower($postObj->MsgType)=='text'){
            switch(strtolower($postObj->Content)){
                case '新闻':
                        $content=array(
                            array(
                                'title'=>'京东睡眠节',
                                'description'=>'满券满500减150',
                                'picurl'=>'https://m.360buyimg.com/mobilecms/jfs/t3088/267/9118000589/45383/49855839/58cf796aNe6c2315b.jpg!q70.jpg',
                                'url'=>'https://pro.m.jd.com/mall/active/4MJAAdf2ZNDPMERFfGcANaVAGDPk/index.html'
                            ),
                            array(
                                'title'=>'冰洗狂欢节',
                                'description'=>'购好货 购优惠',
                                'picurl'=>'https://m.360buyimg.com/mobilecms/jfs/t4261/65/2136102958/49295/941d767b/58cbdc5bN77b08df1.jpg!q70.jpg',
                                'url'=>'https://sale.jd.com/m/act/mOQFqlETKVo17.html'
                            )
                        );
                        echo $this->replyImgText($postObj,$content);
                    break;
                default:
                    $content= '没有该项服务';
                    echo $this->replyText($postObj,$content);
            }
        }elseif(strtolower($postObj->MsgType)=='voice'){
            $content=$postObj->Recognition;
            if(preg_match('/天气/',$content)){
                if(preg_match('/郑州/',$content)){
                    $content=$this->getWeather('郑州');
                }elseif(preg_match('/天津/',$content)){
                    $content=$this->getWeather('天津');
                }else{
                    $content='网络暂时连接不上，请稍后重试';
                }
            }
            echo $this->replyText($postObj,$content);
        }elseif(strtolower($postObj->MsgType)=='image'){
            $mediaId=$postObj->MediaId;
            echo $this->replyImg($postObj,$mediaId);
        }
    }



    //万能curl
    public function curl($url,$type='get',$post=''){
        //初始化curl
        $curl=curl_init();

        curl_setopt($curl,CURLOPT_URL,$url);
        //设置获取到的内容不在浏览中显示，而是以文档流的方式返回
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        //POST方式
        if($type=='post'){
            curl_setopt($curl,CURLOPT_POST,1);
            curl_setopt($curl,CURLOPT_POSTFIELDS,$post);
        }
        //执行curl,返回json字符串
        $res=curl_exec($curl);
        //关闭curl
        curl_close($curl);
        return $res;
    }





    //回复文本信息
    public function replyText($postObj,$content){
        $template="<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                    </xml>";
        $toUserName=$postObj->FromUserName;
        $fromUserName=$postObj->ToUserName;
        $createTime=time();
        $msgType='text';

        $msg=sprintf($template,$toUserName,$fromUserName,$createTime,$msgType,$content);
        return $msg;
    }

    //回复图文信息
    public function replyImgText($postObj,$content){
        $template="<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <ArticleCount>%s</ArticleCount>
                        <Articles>";
        foreach($content as $v) {
            $template .= "<item>
                        <Title><![CDATA[".$v['title']."]]></Title>
                        <Description><![CDATA[".$v['description']."]]></Description>
                        <PicUrl><![CDATA[".$v['picurl']."]]></PicUrl>
                        <Url><![CDATA[".$v['url']."]]></Url>
                        </item>";
        }
        $template.="    </Articles>
                    </xml>";
        $toUserName=$postObj->FromUserName;
        $fromUserName=$postObj->ToUserName;
        $createTime=time();
        $msgType='news';
        $articleCount=count($content);
        $msg=sprintf($template,$toUserName,$fromUserName,$createTime,$msgType,$articleCount,$content);
        return $msg;
    }

    public function getWeather($city){

        //设置参数
        $url="http://v.juhe.cn/weather/index?format=2&cityname=".urlencode($city)."&key=6246672c3f95d3c42444f9a07848d800";
        $res=$this->curl($url);

        //将结果转化为数组
        $arr=json_decode($res,true);
        if($arr['reason']=='successed!'){
            $str=$arr['result']['today']['city']."今日天气情况:\n";
            $str.=$arr['result']['sk']['wind_direction'].$arr['result']['sk']['wind_strength'].'气温:'.$arr['result']['today']['temperature']."度\n";
            $str.=$arr['result']['today']['weather'].',天气'.$arr['result']['today']['dressing_index'].$arr['result']['today']['dressing_advice'];
        }else{
            $str='服务暂时未响应';
        }
        return $str;

    }
    public function replyImg($postObj,$mediaId){
        $template="<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Image>
                        <MediaId><![CDATA[%s]]></MediaId>
                        </Image>
                    </xml>";
        $toUserName=$postObj->FromUserName;
        $fromUserName=$postObj->ToUserName;
        $creteTime=time();
        $msgType='image';
        $msg=sprintf($template,$toUserName,$fromUserName,$creteTime,$msgType,$mediaId);
        return $msg;
    }

    //获取access_token
    public function getAccessToken(){
        $token=M('Token');
        $tokenInfo=$token->where(array('token_name'=>'access_token'))->find();
        //当表中token_value没有值或者过期的情况下才去请求api获取access_token,其他情况直接从数据库读取;
        if(!$tokenInfo['token_value'] || $tokenInfo['token_expire']<time()){
            $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appId."&secret=".$this->appSecret;
            $res=$this->curl($url);
            $arr=json_decode($res,true);
            $data['token_value']=$arr['access_token'];
            $data['token_expire']=$arr['expires_in']+time();
            if($token->where(array('token_name'=>'access_token'))->save($data)){
                return $arr['access_token'];
            }
        }else{
            return  $tokenInfo['token_value'];
        }
    }

    public function setMenu(){
        $url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->getAccessToken();
        $post=' {
                 "button":[
                 {
                      "type":"click",
                      "name":"赞我",
                      "key":"praise_me"
                  },
                  {
                       "name":"菜单",
                       "sub_button":[
                       {
                           "type":"view",
                           "name":"搜索",
                           "url":"http://www.baidu.com/"
                        },
                        {
                           "type":"view",
                           "name":"视频",
                           "url":"http://v.qq.com/"
                        },
                        {
                           "type":"view",
                           "name":"京东",
                           "url":"http://m.jd.com"
                        }]
                   }]
                }';
        $res=$this->curl($url,'post',$post);
        echo $res;
    }

    public function deleteMenu(){
        $url="https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$this->getAccessToken();
        $res=$this->curl($url);
        echo $res;
    }

    public function getFansList(){
        $url="https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$this->getAccessToken();
        $res=$this->curl($url);
        $arr=json_decode($res,true);
        return $arr['data']['openid'];
    }

    //根据openId来群发消息
    public function sendMsgToAll(){
        $url="https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=".$this->getAccessToken();
        $post=array(
            'touser'=>$this->getFansList(),
            'msgtype'=>'text',
            'text'=>array(
                'content'=>urlencode('大傻子')
            )
        );
        $post=urldecode(json_encode($post));
        $res=$this->curl($url,'post',$post);
        echo $res;
    }

    //获取粉丝的基本信息
    public function getUserDetail(){
        $openId='oyEQ60lzxDR2CBsKvEIPwXjj8q_8';
        $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$this->getAccessToken()."&openid=".$openId."&lang=zh_CN";
        $res=$this->curl($url);
        $arr=json_decode($res,true);
        $this->assign('userInfo',$arr);
        $this->display('Index/userInfo');
    }

    //生成可推广的临时二维码
    public function getTempQr(){
        $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$this->getAccessToken();
        $post='{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 1001}}}';
        $res=$this->curl($url,'post',$post);
        $arr=json_decode($res,true);
        $ticket=$arr['ticket'];

        $imgUrl="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket);
        header("location:".$imgUrl);
    }

    //1、引导用户进入授权页面同意授权，获取code
    public function getUserAuthCode(){
        $callback=urlencode("http://osram.applinzi.com/Home/Wechat/getAuthAccessTokenByCode");
        //scope要填，state也要填
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appId."&redirect_uri=".$callback."&response_type=code&scope=snsapi_userinfo&state=111#wechat_redirect";
        header("location:".$url);
    }
    //2、通过code换取网页授权access_token（与基础支持中的access_token不同）
    public function getAuthAccessTokenByCode(){
        $code=$_GET['code'];

        $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appId."&secret=".$this->appSecret."&code=".$code."&grant_type=authorization_code";
        $res=$this->curl($url);
        $arr=json_decode($res,true);
        $data['access_token']=$arr['access_token'];
        $data['expires_in']=$arr['expires_in']+time();
        $data['refresh_token']=$arr['refresh_token'];
        $data['openid']=$arr['openid'];
        $model=M('User');
        if($model->where(array('openid'=>$arr['openid']))->find()){
            $model->save($data);
        }else{
            $model->add($data);
        }
        $this->getUserInfo($arr['access_token'],$arr['openid']);
    }
    //3、如果需要，开发者可以刷新网页授权access_token，避免过期
    public function refreshToken($refreshToken){
        $url="https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=".$this->appId."&grant_type=refresh_token&refresh_token=".$refreshToken;
        $res=$this->curl($url);
        $arr=json_decode($res,true);
        $data['access_token']=$arr['access_token'];
        $data['expires_in']=$arr['expires_in']+time();
        $data['refresh_token']=$arr['refresh_token'];
        $data['openid']=$arr['openid'];
        M('User')->save($data);
        return $arr['access_token'];
    }
    //4、通过网页授权access_token和openid获取用户基本信息（支持UnionID机制）
    public function getUserInfo($access_token,$openid){
        //判断access_token是否过期
        if(!$this->checkAccessToken($access_token,$openid)){
            $refreshToken=M('User')->getFieldByOpenid($openid,'refresh_token');
            $access_token=$this->refreshToken($refreshToken);
        }

        $url="https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
        $res=$this->curl($url);
        $arr=json_decode($res,true);
        $data['nickname']=$arr['nickname'];
        $data['headurl']=$arr['headimgurl'];
        $data['username']=$arr['nickname'];
        M('User')->where(array('openid'=>$openid))->save($data);
        $this->assign('userInfo',$arr);
        $this->display('Index/userInfo');
    }

    //检查access_token 是否过期
    public function checkAccessToken($access_token,$openid){
        $url="https://api.weixin.qq.com/sns/auth?access_token=".$access_token."&openid=".$openid;
        $res=$this->curl($url);
        $arr=json_decode($res,true);
        if($arr['errcode']==0){
            return true;
        }else{
            return false;
        }
    }

}