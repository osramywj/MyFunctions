<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<h1>昵称：<?php echo ($userInfo["nickname"]); ?></h1>
<h1>性别：<?php echo ($userInfo['sex']==1?'男':'女'); ?></h1>
<h1>省份：<?php echo ($userInfo['province']); ?></h1>
<h1>城市：<?php echo ($userInfo['city']); ?></h1>
<h1>openid：<?php echo ($userInfo['openid']); ?></h1>
<h1>unionid：<?php echo ($userInfo['openid']); ?></h1>
<p>头像：<img src="<?php echo ($userInfo['headimgurl']); ?>" width="100" height="100" alt=""/></p>
<p>关注时间：<?php echo date('Y-m-d H:i:s',$userInfo['subscribe_time']);?></p>
<h1><?php echo ($userInfo["city"]); ?></h1>
</body>
</html>