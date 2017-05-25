<?php
/* Smarty version 3.1.30, created on 2017-02-08 20:14:01
  from "D:\www\document\smarty\day3\smarty\templates\parent.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_589b0b8997a606_00536361',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '90fad426bd8f9e00a93d814ec66e140087bb18ff' => 
    array (
      0 => 'D:\\www\\document\\smarty\\day3\\smarty\\templates\\parent.html',
      1 => 1486556040,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_589b0b8997a606_00536361 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
</head>
<body>
<h1>I'm header</h1>
<h2><?php echo $_smarty_tpl->tpl_vars['content']->value;?>
</h2>
<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_24253589b0b8997a601_27699679', 'sss');
?>

<h1>I'm footer</h1>
</body>
</html><?php }
/* {block 'sss'} */
class Block_24253589b0b8997a601_27699679 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<h2>我是你爸爸爸</h2>
<?php
}
}
/* {/block 'sss'} */
}
