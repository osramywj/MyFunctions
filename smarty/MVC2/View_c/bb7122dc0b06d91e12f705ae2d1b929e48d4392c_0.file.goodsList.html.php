<?php
/* Smarty version 3.1.30, created on 2017-02-08 19:51:03
  from "D:\www\document\smarty\MVC2\View\goodsList.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_589b0627062b28_88152027',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bb7122dc0b06d91e12f705ae2d1b929e48d4392c' => 
    array (
      0 => 'D:\\www\\document\\smarty\\MVC2\\View\\goodsList.html',
      1 => 1486554662,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_589b0627062b28_88152027 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['goodsList']->value, 'val');
$_smarty_tpl->tpl_vars['val']->iteration = 0;
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->iteration++;
$__foreach_val_0_saved = $_smarty_tpl->tpl_vars['val'];
?>
<span><?php echo $_smarty_tpl->tpl_vars['val']->iteration;?>
</span>
<p><?php echo $_smarty_tpl->tpl_vars['val']->value['goodsname'];?>
</p>
<?php
$_smarty_tpl->tpl_vars['val'] = $__foreach_val_0_saved;
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

</body>
</html><?php }
}
