<?php
function sendMail($subject,$msghtml,$sendAddress)
{
require_once './PHPMailer-master/PHPMailerAutoload.php';//引入自动加载类，老版本是引入class.phpmailer.php,新版本会报错

    $mail = new PHPMailer();
    $mail->IsSMTP();//设置使用SMTP服务器发送
    $mail->SMTPAuth = true;//开启SMTP认证
    $mail->Port = 25;
    $mail->Host = 'smtp.163.com';//设置SMTP服务器，
    $mail->Username = 'ju910628';//发信人邮箱用户名
    $mail->Password = 'WENju115588';//发信人邮箱密码

    $mail->IsHTML(true);//指定邮件内容格式为html
    $mail->CharSet = "UTF-8";//设置邮件的字符编码，这很重要，不然中文乱码
    $mail->From = 'ju910628@163.com';//发件人完整的邮箱名称
    $mail->FromName = 'ju910628';//发件人署名
    $mail->Subject = $subject;//标题
    $mail->MsgHTML($msghtml);//信件主题内容
//    $mail->Body=$msghtml;//也是信件内容
    $mail->AddAddress($sendAddress);//收件人地址
//$mail->AddAttachment("f:/test.png"); //可以添加附件
    if (!$mail->send()) {//发送
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Message sent!";
    }
}


sendMail('php发送邮件测试','Used by many open-source projects:wordPress...','1027612662@qq.com');