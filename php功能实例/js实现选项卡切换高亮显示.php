<!--回去将那个选项卡的跟这个结合一下-->
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .menu { padding:0; margin:0; list-style-type:none;}
        .menu li { background:#FFD1A4; margin-right:1px; float:left; color:#fff; }
        .menu li a { display:block; width:80px; text-align:center; height:32px; line-height:32px; color:#fff; font-size:13px; text-decoration:none;}

        .cur{ background:#D96C00; font-weight:bold;}
    </style>
</head>
<body>

<ul class="menu" id="menu">
    <li><a href="demo.html">首页</a></li>
    <li><a href="te1.html">PHP综合</a></li>
    <li><a href="te2.html">Ecshop</a></li>
</ul>



<script>
    var urlstr = location.href;//获取当前的url地址
    //alert((urlstr + '/').indexOf($(this).attr('href')));
    var urlstatus=false;
    $("#menu a").each(function () {//循环所有的当行栏，能够匹配就高亮，不能匹配的移除高亮；
        if ((urlstr + '/').indexOf($(this).attr('href')) > -1&&$(this).attr('href')!='') {//当当前url地址里含有被点击的a链接地址时，给该栏加个class
            $(this).addClass('cur'); urlstatus = true;
        } else {
            $(this).removeClass('cur');
        }
    });
    if (!urlstatus) {$("#menu a").eq(0).addClass('cur'); }//刚进入该页面时，默认给第一个导航栏高亮；
</script>
</body>
</html>



