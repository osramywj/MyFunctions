<?php
/* Smarty version 3.1.30, created on 2017-01-16 11:46:16
  from "D:\www\document\smarty\day1\smarty\templates\test.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_587c4208936d55_08743609',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9da55b47b38e7464ce9ff008e1f46403fa48fa0a' => 
    array (
      0 => 'D:\\www\\document\\smarty\\day1\\smarty\\templates\\test.html',
      1 => 1484528261,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_587c4208936d55_08743609 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>

<?php
$_smarty_tpl->smarty->ext->configLoad->_loadConfigFile($_smarty_tpl, "foo.conf", null, 0);
?>

<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body style="color: <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'color');?>
;">

<h1><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h1>
<h1><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'text');?>
</h1>
<div><?php echo $_smarty_tpl->tpl_vars['content']->value;?>
</div>
<div>{$content}</div>

<div>{<?php echo $_smarty_tpl->tpl_vars['content']->value;?>
}</div>

<div><?php echo $_smarty_tpl->smarty->left_delimiter;
echo $_smarty_tpl->tpl_vars['content']->value;
echo $_smarty_tpl->smarty->right_delimiter;?>
</div>
<div><?php echo $_smarty_tpl->tpl_vars['arr']->value[0];?>
,<?php echo $_smarty_tpl->tpl_vars['arr']->value[1];?>
,<?php echo $_smarty_tpl->tpl_vars['arr']->value[2];?>
,<?php echo $_smarty_tpl->tpl_vars['arr']->value[3];?>
</div>

<div><?php echo $_smarty_tpl->tpl_vars['arr']->value[0];?>
,<?php echo $_smarty_tpl->tpl_vars['arr']->value[1];?>
,<?php echo $_smarty_tpl->tpl_vars['arr']->value[2];?>
,<?php echo $_smarty_tpl->tpl_vars['arr']->value[3];?>
</div>

<div><?php echo $_smarty_tpl->tpl_vars['arr2']->value['user'];?>
</div>
<div><?php echo $_smarty_tpl->tpl_vars['arr2']->value['pwd'];?>
</div>
<div><?php echo $_smarty_tpl->tpl_vars['arr2']->value['age'];?>
</div>
<div><?php echo $_smarty_tpl->tpl_vars['arr2']->value['user'];?>
</div>
<div><?php echo $_smarty_tpl->tpl_vars['arr2']->value['pwd'];?>
</div>
<div><?php echo $_smarty_tpl->tpl_vars['arr2']->value['age'];?>
</div>
<div><?php echo $_smarty_tpl->tpl_vars['person']->value->age;?>
</div>

<div><?php echo $_smarty_tpl->tpl_vars['person']->value->walk()->eat();?>
</div>
<div><?php echo $_smarty_tpl->tpl_vars['arr']->value[2]+$_smarty_tpl->tpl_vars['arr']->value[3];?>
</div>
<div><?php $_smarty_tpl->_assignInScope('num', '3');
?></div>
<div><?php echo $_smarty_tpl->tpl_vars['arr']->value[$_smarty_tpl->tpl_vars['num']->value-1];?>
</div>
<div><?php $_smarty_tpl->_assignInScope('foo', $_smarty_tpl->tpl_vars['count']->value+1);
?></div>
<div><?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
</div>
<div><?php $_smarty_tpl->_assignInScope('say', "我今年".((string)$_smarty_tpl->tpl_vars['count']->value)."岁了");
?></div>
<div><?php echo $_smarty_tpl->tpl_vars['say']->value;?>
</div>
<div><?php $_smarty_tpl->_assignInScope('length', strlen($_smarty_tpl->tpl_vars['count']->value));
?></div>
<div><?php echo $_smarty_tpl->tpl_vars['length']->value;?>
</div>
<p><?php echo $_POST['page'];?>
</p>
<p><?php echo $_SESSION['id'];?>
</p>
<p><?php echo time();?>
</p>
<p><?php echo date('Y-m-d',time());?>
</p>
<p><?php echo @constant('ROOT');?>
</p>
<p><?php echo basename($_smarty_tpl->source->filepath);?>
</p>
<p><?php echo dirname($_smarty_tpl->source->filepath);
echo basename($_smarty_tpl->source->filepath);?>
</p>
<p><?php echo $_smarty_tpl->smarty->left_delimiter;?>
 , <?php echo $_smarty_tpl->smarty->right_delimiter;?>
</p>
<p><?php echo Smarty::SMARTY_VERSION;?>
</p>
</body>
</html><?php }
}
