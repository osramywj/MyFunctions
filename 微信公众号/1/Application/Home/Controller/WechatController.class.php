<?php
namespace Home\Controller;
use Think\Controller;
class WechatController extends Controller{
//    private $token='wechat';
//    private $appId='wxc1f8fc528b60e9d7';
//    private $appSecret='5bb3e28420f0fdbabbdfd9aecb1415fa';
    //公共平台测试账号
    private $token='wechat';
    private $appId='wx797c8526c8b78fde';
    private $appSecret='13dbf5955d6fabee70a8c6280e57cbc0';

    //签名认证
    //仅仅在提交服务器配置的时候才会传递signature，首次验证成功后，之后就不再传递参数了，故直接走responseMsg()
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

    //需要消息排重：普通消息依据msgid判断，事件消息通过 FromUserName + CreateTime判断；可以将这些信息放在一个集合里，设置过期时间；
    public function responseMsg(){
        //获取微信服务器推送过来的POST数据(XML格式)，可以接受任何格式的内容
        $postXML=$GLOBALS['HTTP_RAW_POST_DATA'];
        //将XML转成对象
        $postObj=simplexml_load_string($postXML);

        //若有msgid则使用msgid，没有则使用用户openid+time方式
        /*1.第一种方法：若过了五秒，服务器没返回数据，还是会重新发起三次请求的，只是每次检测到缓存之后不会再处理一遍了
          2.另一种方法是手册上的，若服务器无法保证5秒内处理并回复，必须直接作出'success'或''回复，
            这样微信服务器才不会发起重试，（此时可以使用客服接口进行异步恢复）
        */

        /*if ($postObj->MsgId) {
            if($redis->setnx($postObj->MsgId,1)){
                $redis->setTimeout($postObj->MsgId,15);
            }else{
                echo 'success';
            }
        }else{
            $flag = ($postObj->FromUserName).($postObj->CreateTime);
            if ($redis->setnx($flag,1)) {
                $redis->setTimeout($flag,15);
            }else{
                echo 'success';
            }
        }*/



        //判断消息类型
        if(strtolower($postObj->MsgType)=='event'){
            //判断事件类型
            if(strtolower($postObj->Event)=='subscribe'){
                switch(strtolower($postObj->EventKey)){
                    //未关注时扫描二维码
                    case 'qrscene_1001':
                            echo $this->replyText($postObj,'您是通过1001号二维码关注我的');
                        break;
                    default:
                        echo $this->replyText($postObj,'[嘿哈]你怎么敢关注我');
                }

            }
            //关注之后扫描二维码
            elseif (strtolower($postObj->Event)=='scan') {
                if (strtolower($postObj->EventKey) == '1001') {
                    echo $this->replyText($postObj,'谢谢您扫描1001号二维码');
                }
            }
            elseif(strtolower($postObj->Event)=='click'){
                if(strtolower($postObj->EventKey)=='praise_me'){
                    $content='谢谢你赞我';
                    echo $this->replyText($postObj,$content);
                } elseif (strtolower($postObj->EventKey)=='get_msg') {
                    $content = '拉取信息返回的数据';
                    echo $this->replyText($postObj,$content);
                }
            }
            //自定义菜单的扫描事件推送
            elseif (strtolower($postObj->Event)=='scancode_waitmsg') {
                if (strtolower($postObj->EventKey) == 'rselfmenu_0_0') {
                    echo $this->replyQrInfo($postObj);
                }
            }elseif (strtolower($postObj->Event)=='scancode_push') {
                if (strtolower($postObj->EventKey) == 'rselfmenu_0_1') {
                    echo $this->replyQrInfo($postObj);
                }
            }elseif (strtolower($postObj->Event)=='location_select') {
                //有问题

                if (strtolower($postObj->EventKey) == 'rselfmenu_2_0') {
                    $location= $postObj->Event;
                    echo $this->replyText($postObj,$location);
                }
            } elseif (strtolower($postObj->Event)=='scan') {
                if (strtolower($postObj->EventKey) == '1001') {
                    $ticket = $postObj->Ticket;
                    echo $this->replyText($postObj,$ticket);
                }
            }elseif (strtolower($postObj->Event)=='location') {
                $lati = $postObj->Latitude;
                $longi = $postObj->Longitude;
                echo $this->replyText($postObj,'您当前的纬度：'.$lati.'，经度：'.$longi);
            }
            //消息群发推送结果
            elseif (strtolower($postObj->Event)=='masssendjobfinish') {
                echo $this->replyText($postObj,$postObj->Status);
            }


            else {
                echo '';
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
        } else {
            //告诉微信服务器开发者收到了粉丝的消息，不用重复请求三次了
            echo '';
        }
    }



    //万能curl
    public function curl($url,$type='get',$post=''){
        //初始化curl
        $curl=curl_init();

        curl_setopt($curl,CURLOPT_URL,$url);
        //设置获取到的内容不在浏览中显示，而是以文档流的方式返回
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);

        if(stripos($url,"https://")!==FALSE){
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        //POST方式
        if($type=='post'){
            curl_setopt ($curl,CURLOPT_SAFE_UPLOAD, false);
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
    //调用接口时，请登录“微信公众平台-开发-基本配置”提前将服务器IP地址添加到IP白名单中，点击查看设置方法，否则将无法调用成功。
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
//        $post=' {
//                 "button":[
//                  {
//                      "type":"click",
//                      "name":"赞我",
//                      "key":"praise_me"
//                  },
//                  {
//                       "name":"菜单",
//                       "sub_button":[
//                       {
//                           "type":"view",
//                           "name":"搜索",
//                           "url":"http://www.baidu.com/"
//                        },
//                        {
//                           "type":"view",
//                           "name":"视频",
//                           "url":"http://v.qq.com/"
//                        },
//                        {
//                           "type":"view",
//                           "name":"京东",
//                           "url":"http://m.jd.com"
//                        }]
//                  }]
//                }';
            $post = '{
                        "button": [
                            {
                                "name": "扫码",
                                "sub_button": [
                                    {
                                        "type": "scancode_waitmsg",
                                        "name": "扫码带提示",
                                        "key": "rselfmenu_0_0",
                                        "sub_button": [ ]
                                    },
                                    {
                                        "type": "scancode_push",
                                        "name": "扫码推事件",
                                        "key": "rselfmenu_0_1",
                                        "sub_button": [ ]
                                    }
                                ]
                            },
                            {
                                "name": "发图",
                                "sub_button": [
                                    {
                                        "type": "pic_sysphoto",
                                        "name": "系统拍照发图",
                                        "key": "rselfmenu_1_0",
                                       "sub_button": [ ]
                                     },
                                    {
                                        "type": "pic_photo_or_album",
                                        "name": "拍照或者相册发图",
                                        "key": "rselfmenu_1_1",
                                        "sub_button": [ ]
                                    },
                                    {
                                        "type": "pic_weixin",
                                        "name": "微信相册发图",
                                        "key": "rselfmenu_1_2",
                                        "sub_button": [ ]
                                    }
                                ]
                            },
                             {
                                "name": "发送位置", 
                                "type": "location_select", 
                                "key": "rselfmenu_2_0"
                            },

                        ]
                    }';
        $res=$this->curl($url,'post',$post);
        echo $res;
    }

    public function deleteMenu(){
        $url="https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$this->getAccessToken();
        $res=$this->curl($url);
        echo $res;
    }

    //自定义菜单查询接口
    public function getMenuInfo()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$this->getAccessToken();
        $res = $this->curl($url);
        $arr = json_decode($res,true);
        //返回自定义菜单id
//        return $arr;
        return $arr['conditionalmenu'][1]['menuid'];
    }


/***************************************用户管理开始*********************************************/
    //获取粉丝列表
    public function getFansList(){
        $url="https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$this->getAccessToken();
        $res=$this->curl($url);
        $arr=json_decode($res,true);
        return $arr['data']['openid'];
//        $this->assign('userList',$arr['data']['openid']);
//        $this->display('Wechat/userList');
    }

    //获取粉丝的基本信息
    public function getUserDetail($openId=''){
        $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$this->getAccessToken()."&openid=".$openId."&lang=zh_CN";
        $res=$this->curl($url);
        $arr=json_decode($res,true);
        $this->assign('userInfo',$arr);
        $this->display('Index/userInfo');
    }

    //批量获取用户基本信息
    public function batchGetUserDetail($userList)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token='.$this->getAccessToken();
        $post = '{
                   "user_list": [';

        foreach ($userList as $user) {
            $post.='{
                       "openid": "'.$user['openid'].'", 
                       "lang": "zh_CN"
                    }, ';
        };

        $post.=']
              }';

        $res=$this->curl($url,'post',$post);
        return $res;
    }

//    public function test()
//    {
//        $userList=[
//            ['openid'=>'oyEQ60ok7cx48XwxwBiBoEu-750c'],
//            ['openid'=>'oyEQ60lzxDR2CBsKvEIPwXjj8q_8'],
//            ['openid'=>'oyEQ60sI4nQDWxcTQhLA-9Jl9Kng'],
//        ];
//        echo $this->batchGetUserDetail($userList);
//    }
    //创建标签
    public function createUserTags($tag)
    {
        $url="https://api.weixin.qq.com/cgi-bin/tags/create?access_token=".$this->getAccessToken();
        $post = '{
                  "tag" : {
                    "name" : "'.$tag.'"
                  }
                 }';
        $res=$this->curl($url,'post',$post);
        echo $res;
    }

    //获取已创建的用户标签
    public function getUserTags()
    {
        $url="https://api.weixin.qq.com/cgi-bin/tags/get?access_token=".$this->getAccessToken();
        $res=$this->curl($url);
        echo $res;
    }

    //获取标签下的粉丝列表
    public function getFansByTags($tag_id)
    {
        $url="https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token=".$this->getAccessToken();
        $post = '{
                    "tagid" : '.$tag_id.',
                    "next_openid":""
                  }
                 }';
        $res=$this->curl($url,'post',$post);
        echo $res;
    }

/***************************************用户管理结束*********************************************/

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






/********************************************网页授权开始**************************************************************/

    //1、引导用户进入授权页面同意授权，获取code，授权后重定向到$callback回调地址上
    //页面里可以放个链接；www.yangwenju.cn/weixin/Home/Wechat/getUserAuthCode 只要用户点击，就会进去$url链接
    public function getUserAuthCode(){
        $callback=urlencode("http://www.yangwenju.cn/weixin/index.php/Home/Wechat/getAuthAccessTokenByCode");
        //scope要填，state也要填
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appId."&redirect_uri=".$callback."
              &response_type=code&scope=snsapi_userinfo&state=111#wechat_redirect";
        header("location:".$url);
    }
    //2、通过code换取网页授权access_token（与基础支持中的access_token不同）
    public function getAuthAccessTokenByCode(){
        //若用户授权，会跳转到$callback/?code=CODE&state=STATE。
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
/********************************************网页授权结束**************************************************************/


    //生成可推广的临时二维码（里面已经含有该公众号信息）
    //需要跟消息管理-接收事件推送的扫描带参数二维码事件结合
    public function getTempQr(){
        $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$this->getAccessToken();
        $post='{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id":1001}}}';
        $res=$this->curl($url,'post',$post);
        $arr=json_decode($res,true);
        $ticket=$arr['ticket'];

        $imgUrl="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket);
        echo $imgUrl;die;
        //1.直接输出到浏览器
        //header("location:".$imgUrl);
        //2.下载到某个目录下
        header("Content-Disposition:attachment;filename=test.png");
        $res = $this->curl($imgUrl);
        echo $res;
        //3.直接保存到某个目录下
        //$res = $this->curl($imgUrl);
        //file_put_contents(APP_PATH.'upload/test.jpg',$res);
    }


    //返回二维码信息
    public function replyQrInfo($postObj)
    {
        $result = $postObj->ScanCodeInfo->ScanResult;
        return $this->replyText($postObj,$result);
    }

    public function shortUrl($longUrl)
    {
        $url="https://api.weixin.qq.com/cgi-bin/shorturl?access_token=".$this->getAccessToken();
        $post = '{
                    "action":"long2short",
                    "long_url":"'.$longUrl.'"
                 }';
        $res = $this->curl($url,'post',$post);
        $result = json_decode($res);
        if ($result->errcode === 0) {
            return $result->short_url;
        }
    }

    public function test()
    {
        $url= 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQGo8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyT09mWHNEODRjV2kxSWZudjFwMWwAAgSPnJZZAwSAOgkA';
        echo $this->shortUrl($url);
    }




    //创建个性化菜单
    public function createSpecMenu()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token='.$this->getAccessToken();
        $post = '{
                    "button":[
                    {	
                        "type":"click",
                        "name":"今日歌曲",
                        "key":"V1001_TODAY_MUSIC" 
                    },
                    {
                        "name":"菜单",
                        "sub_button":[
                         {	
                            "type":"view",
                            "name":"搜索",
                            "url":"http://www.soso.com/"
                         },
                         
                         {
                            "type":"click",
                            "name":"赞一下我们",
                            "key":"V1001_GOOD"
                         }]
                  }],
                "matchrule":{
                  
                  "sex":"1",
                  "country":"中国",
                  "province":"河南",
                  "city":"郑州",
                  "client_platform_type":"2",
                  "language":"zh_CN"
                  }
                }';

        $res = $this->curl($url,'post',$post);
        echo $res;
    }

    //删除个性化菜单
    public function deleteSpecMenu()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/delconditional?access_token='.$this->getAccessToken();
        $post = '{
                    "menuid":'.$this->getMenuInfo().'
                 }';
        $res = $this->curl($url,'post',$post);
        echo $res;
    }

    //添加客服账号(有问题）
    public function addKf()
    {
        $url = 'https://api.weixin.qq.com/customservice/kfaccount/add?access_token='.$this->getAccessToken();
        $post = '{
                     "kf_account" : "test1@test",
                     "nickname" : "客服1",
                     "password" : "pswmd5",
                 }';
        $res = $this->curl($url,'post',$post);
        echo $res;
    }

    /***************************图文群发开始********************************/
    //群发接口里的上传图片和上传图文都是临时的，几天后media_id就失效了，url里都是以cgi-bin/media开头的；而上传永久素材里media不会过期，url是以material开头



    /**
     * 临时图片
     * 1:获取上传图文消息内的图片并获取图片url ；此url可以放到图文消息中；
     * 2:坑：PHP5.6以上必须加上这一行  curl_setopt ( $ch, CURLOPT_SAFE_UPLOAD, false);
     * 3:上传的图片类型只支持png格式，大小1M以下
     */
    public function getImgUrl()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.$this->getAccessToken();
        $tmp_file = $_FILES['img']['tmp_name'];
        $name = $_FILES['img']['name'];
        $des = __DIR__.'/../../../upload/'.$name;
        move_uploaded_file($tmp_file,$des);

        //旧版本的
        $data['media']='@'.$des;
        //php5.6以上的使用CURLFile
//        $data = array('media' => new \CURLFile($des));

//        $ch = curl_init($url);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        //据说PHP5.6以上必须加上这一行，否则获取不到文件
//        curl_setopt ( $ch, CURLOPT_SAFE_UPLOAD, false);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
//        echo curl_exec($ch);
        echo $this->curl($url,'post',$data);
    }

    //上传图文消息素材
    public function uploadNews($articles)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token='.$this->getAccessToken();
        $post = '{
                  "articles": [';

        foreach($articles as $article){
            //thumb_media_id必须是永久的media_id;
            $post.='{
                      "title": '.$article['title'].',
                       "thumb_media_id": '.$article['thumb_media_id'].',
                       "author": '.$article['author'].',
                       "digest": DIGEST,
                       "show_cover_pic": SHOW_COVER_PIC(0 / 1),
                       "content": '.$article['content'].',
                       "content_source_url": '.$article['content_source_url'].'
                    },';
        }

        $post.=']
                }';
        $res = $this->curl($url,'post',$post);
        $arr = json_decode($res,true);
        return $arr['media_id'];
    }

    //调用上传临时图文素材
    public function newsUpload()
    {
        $content = '<a href="www.baidu.com"><img src="http:\/\/mmbiz.qpic.cn\/mmbiz_jpg\/ic4IjYMvrhM2uUDeyoJwRialj6kE8Df56Cd2j12aRqTKUiaHtDKDqMavTjtjknPvo55zb0EiaCXRpWFVtIjicR8vrOw\/0"></a>';
        //必须是永久media_id;
        $thumb_media_id = 'test';
        $articles = [
            [
                'title'=>'first news',
                'thumb_media_id'=>$thumb_media_id,
                'author'=>'Mr Yang',
                'content'=>$content,
                'content_source_url'=>'www.baidu.com',
            ]
        ];
        $this->uploadNews($articles);
    }

    /**
     * 按标签进行群发
     * @param array $articles
     */
    public function sendAllByTags($articles=[])
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$this->getAccessToken();
        $media_id = $this->uploadNews($articles);
        //群发图文
        $post = '{
                   "filter":{
                      "is_to_all":false,
                      "tag_id":2
                   },
                   "mpnews":{
                      "media_id":"'.$media_id.'"
                   },
                    "msgtype":"mpnews",
                    "send_ignore_reprint":0
                  }';
        //其他类型的消息分别有不同的$post模板，此处省略；
        $text = '{
                   "filter":{
                      "is_to_all":true
                   },
                   "text":{
                      "content":"早上好"
                   },
                    "msgtype":"text"
                 }';
        $media_id='utPoAAe_sLaI4wS5bF5tVFY6H5AnW_8Lf0gds392rmPmElpyA8vMkoovlnqMof0H';
        $image = '{
                   "filter":{
                      "is_to_all":false,
                      "tag_id":2
                   },
                   "image":{
                      "media_id":"'.$media_id.'"
                   },
                    "msgtype":"image"
                    "clientmsgid":"send_tag_2"//避免重复推送
                  }';
        $res = $this->curl($url,'post',$text);
        echo $res;
    }

    //通过openid群发
    public function sendAllByOpenId()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$this->getAccessToken();
        //图文消息
        $post = '{
                   "touser":[
                    '.$this->getFansList().'
                   ],
                   "mpnews":{
                      "media_id":"123dsdajkasd231jhksad"
                   },
                    "msgtype":"mpnews"，
                    "send_ignore_reprint":0
                 }';
        $res = $this->curl($url,'post',$post);
        echo $res;
    }


    public function deleteSendAll()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/delete?access_token='.$this->getAccessToken();
        $post= '{
                   "msg_id":30124,
                   "article_idx":2
                }';
        $res = $this->curl($url,'post',$post);
        echo $res;
    }

    /**
     * 消息预览接口，可以根据openid发，也可以根据微信号发
     */
    public function preview()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token='.$this->getAccessToken();
        //图片
        $post = '{
                   "towxname":"ju910628",
                   "image":{              
                            "media_id":"utPoAAe_sLaI4wS5bF5tVFY6H5AnW_8Lf0gds392rmPmElpyA8vMkoovlnqMof0H"               
                             },
                   "msgtype":"image" 
                 }';
        $text = '{     
                    "towxname":"ju910628",
                    "text":{           
                           "content":"test test test"            
                           },     
                    "msgtype":"text"
                  }';
        $res = $this->curl($url,'post',$post);
        echo $res;
    }

    public function getMsgStatus($msg_id='')
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/get?access_token='.$this->getAccessToken();
        $post = '{
                    "msg_id": "'.$msg_id.'"
                 }';
        $res = $this->curl($url,'post',$post);
        echo $res;
    }





    /***************************图文群发结束********************************/

    //上传临时素材(只保存3天）
    public function uploadTempMedia()
    {
        $tmp_file = $_FILES['media']['tmp_name'];
        $name = $_FILES['media']['name'];
        $typeInfo = $_FILES['media']['type'];
        $arr = explode('/',$typeInfo);
        $type = $arr[0];
        $des = __DIR__.'/../../../upload/'.$name;
        move_uploaded_file($tmp_file,$des);
        $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$this->getAccessToken().'&type='.$type;
        $data = ['media'=>new \CURLFile($des)];
        $res = $this->curl($url,'post',$data);
        echo $res;
    }

    //下载临时素材
    public function downTempMeida()
    {
        $media_id='oT3V6wolUtZE6Nx9YyNY8TEjvwlbNzzsPRlGitoN6GmSRJQxBJlOn8o9uJ4ZbVeB';
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->getAccessToken().'&media_id='.$media_id;
        //初始化
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        //执行并获取图片信息（string类型的）
        $package = curl_exec($ch);
        //获得curl请求的的一些信息；
        $httpinfo = curl_getinfo($ch);
        //释放curl句柄
        curl_close($ch);

        $media = array_merge(array('mediaBody' => $package), $httpinfo);
        //求出文件格式
        preg_match('/\w\/(\w+)/i', $media["content_type"], $extmatches);
        $fileExt = $extmatches[1];
        $filename = time().rand(100,999).".".$fileExt;
        $dirname = "./upload/";
        if(!file_exists($dirname)){
            mkdir($dirname,0777,true);
        }
        file_put_contents($dirname.$filename,$media['mediaBody']);
        return $dirname.$filename;
    }

    //上传永久图文素材
    public function addNews($articles)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/add_news?access_token='.$this->getAccessToken();
        $post = '{
                  "articles": [';

              foreach($articles as $article){
                  //thumb_media_id必须是永久的media_id;
                  $post.='{
                      "title": '.$article['title'].',
                       "thumb_media_id": '.$article['thumb_media_id'].',
                       "author": '.$article['author'].',
                       "digest": DIGEST,
                       "show_cover_pic": SHOW_COVER_PIC(0 / 1),
                       "content": '.$article['content'].',
                       "content_source_url": '.$article['content_source_url'].'
                    },';
              }

         $post.=']
                }';
        $res = $this->curl($url,'post',$post);
        echo $res;
    }



    //上传其他类型的永久素材

    //未测试
    public function addMaterial()
    {
        $tmp_file = $_FILES['material']['tmp_name'];
        $typeInfo = $_FILES['material']['type'];
        $name = $_FILES['material']['name'];
        $size = $_FILES['material']['size'];
        $arr = explode('/',$typeInfo);
        $type = $arr[0];
        $url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=".$this->getAccessToken()."&type=".$type;
        $des_file = __DIR__.'/../../../upload/'.time().'_'.$name;
        move_uploaded_file($tmp_file,$des_file);
        $file_info = ['filename'=>$des_file,'filelength'=>$size,'content-type'=>$typeInfo];
        $data= array("media"=>"@{$des_file}",'form-data'=>$file_info);

        if ($type == 'video') {
            $data['description']=[
                "title"=>"first video",
                "introduction"=>"我的第一个视频"
            ];

            $res = $this->curl($url,'post',$data);
        }else{
//            print_r($data);die;
//            $data=json_encode($data);
            $res = $this->curl($url,'post',$data);
        }

        echo $res;
    }


    /**
     * 获取永久素材
     * 素材可能是图文、视频、其他素材
     * 图文和视频有不同的返回模板，其他素材可以自行保存为文件
     * @param $media_id
     */
    public function getMaterial($media_id)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/get_material?access_token='.$this->getAccessToken();
        $post = '{
                    "media_id":'.$media_id.'
                 }';
        $res = $this->curl($url,'post',$post);

    }

    /**
     * 删除 永久素材，临时素材不能通过该接口删除
     * @param $media_id
     */
    public function deleteMaterial($media_id)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/del_material?access_token='.$this->getAccessToken();
        $post = '{
                    "media_id":'.$media_id.'
                 }';
        $res = $this->curl($url,'post',$post);
        echo $res;
    }

    /**
     * 修改永久 图文 素材
     */
    public function updateNews($media_id)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/update_news?access_token='.$this->getAccessToken();
        //首先获取图文的信息；
        $material = $this->getMaterial($media_id);
        $material = json_decode($material,true);

        //然后修改图文信息,把$new_material通过表单提交过来
//        $new_material = ;
//        $res = $this->curl($url,'post',$new_material);
//        echo $res;
    }

    /**
     * 获取永久素材的总数
     */
    public function getMaterialCount()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token='.$this->getAccessToken();
        $res = $this->curl($url);
        echo $res;
    }

    /**
     * 获取素材列表
     * @param $type :素材种类
     * @param $offset :起始位置
     * @param $count :返回数量
     */
    public function getMaterialList($type,$offset,$count)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.$this->getAccessToken();
        $post = '{
                   "type":'.$type.',
                   "offset":'.$offset.',
                   "count":'.$count.'
                 }';
        $res = $this->curl($url,'post',$post);

        $url = 'https://mp.weixin.qq.com/mp/subscribemsg?action=get_confirm&
        appid='.$this->appId.'&scene=1000&template_id=1uDxHNXwYQfBmXOfPJcjAS3FynHArD8aWMEFN 
        RGSbCc& redirect_url=http%3a%2f%2fsupport.qq.com&reserved=test#wechat_redirect';
    }


}