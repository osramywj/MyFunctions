<?php
class Image{
    /**
     *验证码
     */

    function verifyCode($type=1,$length=4,$width=100,$height=40){
        //第一步：创建画布，获取图像资源
        $img=imagecreatetruecolor($width,$height);

        //第二步：操作图像资源
        //1)分配颜色
        $backColor=imagecolorallocate($img,mt_rand(220,255),mt_rand(220,255),mt_rand(220,255));
        $fontColor=imagecolorallocate($img,mt_rand(0,100),mt_rand(0,100),mt_rand(0,100));
        //2)填充背景色
        imagefilledrectangle($img,0,0,$width,$height,$backColor);
        //3)写入文字
        //准备字体
        $fonts=array('msyh.ttf','msyhbd.ttf','simhei.ttf');
        $font='fonts/'.$fonts[array_rand($fonts)];
        //随机指定长度字符串
        include_once "Common.class.php";
        $common=new Common();
        $str=$common->getRandString($type,$length);
        $_SESSION['june_verify']=$str;
        //写入字符串
        imagettftext($img,mt_rand(20,25),mt_rand(-10,10),mt_rand(10,18),mt_rand(25,30),$fontColor,$font,$str);
        //4)写入干扰项
        //A.点干扰项
        for($i=0;$i<500;$i++){
            imagesetpixel($img,mt_rand(3,$width),mt_rand(3,$height),imagecolorallocate($img,mt_rand(0,100),mt_rand(0,100),mt_rand(0,100)));
        }
        //B.线干扰项
        for($i=0;$i<20;$i++){
            $x=mt_rand(0,$width);
            $y=mt_rand(0,$length);
            imageline($img,$x,$y,$x+mt_rand(-50,50),$y+mt_rand(-50,+50),imagecolorallocate($img,mt_rand(0,100),mt_rand(0,100),mt_rand(0,100)));
        }
        //第三步：输出图像
        header('Content-Type:image/png');
        imagepng($img);
        //第四步：释放图像资源
        imagedestroy($img);
    }
    /**
     *文字水印
     */
    function waterMarkText($srcImg,$text,$dir,$x=5,$y=35,$color=array(255,0,0),$alpha=60,$fontSize=32,$angle=-50,$font='msyhbd.ttf'){
    //创建图像资源
        $imgInfo=getimagesize($srcImg);
        //print_r($imgInfo);
        $ext=image_type_to_extension($imgInfo[2],false);
        //利用变量函数来根据图像来选择产生图像资源的函数
        $fun="imagecreatefrom".$ext;
        $img=$fun($srcImg);

    //写入文字水印
        //1)设置水印颜色
        $color=imagecolorallocatealpha($img,$color[0],$color[1],$color[2],$alpha);
        //2)写入文字
        $font='fonts/'.$font;
        imagettftext($img,$fontSize,$angle,$x,$y,$color,$font,$text);

    //输出或者保存图像
        if(!file_exists($dir)){
            mkdir($dir);
            chmod($dir,0777);
        }
        $outFun="image".$ext;
        $outFun($img,$dir.'/watertext_'.basename($srcImg));

    //释放图像资源
        imagedestroy($img);
    }

    /**
     *图片水印
     */
    function waterMarkImg($desImg,$waterImg,$dir,$x=10,$y=30,$alpha=50){
    //创建图像资源
        //1)创建目标图像资源
        $desImgInfo=getimagesize($desImg);
        //print_r($imgInfo);
        $desExt=image_type_to_extension($desImgInfo[2],false);
        //利用变量函数来根据图像来选择产生图像资源的函数
        $desFun="imagecreatefrom".$desExt;
        $img=$desFun($desImg);
        //2)创建水印图像资源
        $waterImgInfo=getimagesize($waterImg);
        $waterExt=image_type_to_extension($waterImgInfo[2],false);
        $waterFun="imagecreatefrom".$waterExt;
        $water=$waterFun($waterImg);

    //写入图片水印
        imagecopymerge ( $img , $water, $x, $y , 0 , 0 , imagesx($water) , imagesy($water),$alpha );

    //输出或者保存图像
        if(!file_exists($dir)){
            mkdir($dir);
            chmod($dir,0777);
        }
        $outFun="image".$desExt;
        $outFun($img,$dir.'/waterimg_'.basename($desImg));

    //释放图像资源
        imagedestroy($img);
        imagedestroy($water);
    }

    /**
     * 等比缩放
     */
    function thumbImg($src,$w,$dir){
    //第一步：创建图像资源
    //1)源图片资源（大图)
        $srcInfo=getimagesize($src);
        $srcExt=image_type_to_extension($srcInfo[2],false);
        $srcFun="imagecreatefrom".$srcExt;
        $srcImg=$srcFun($src);
    //2)缩略图画布资源(缩略图)
        $scale=imagesx($srcImg)/imagesy($srcImg);
        $h=$w/$scale;
        $thumbImg=imagecreatetruecolor($w,$h);
    //第二步：操作图像资源（将大图更新抽样之后拷贝到缩略图中)
        imagecopyresampled ( $thumbImg , $srcImg, 0 , 0, 0 , 0 , imagesx($thumbImg) , imagesy($thumbImg) , imagesx($srcImg), imagesy($srcImg) );
    //第三步：保存图像
        $outFun='image'.$srcExt;
        if(!file_exists($dir)){
            mkdir($dir);
            chmod($dir,0777);
        }
        $outFun($thumbImg,$dir.'/thumb_'.$w.'_'.basename($src));
    //第四步:释放图像资源
        imagedestroy($srcImg);
        imagedestroy($thumbImg);
    }
}