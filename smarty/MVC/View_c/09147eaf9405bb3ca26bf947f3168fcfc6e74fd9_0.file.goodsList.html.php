<?php
/* Smarty version 3.1.30, created on 2017-02-08 17:24:57
  from "D:\www\document\smarty\MVC\View\goodsList.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_589ae3e9b8d5d1_69096308',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '09147eaf9405bb3ca26bf947f3168fcfc6e74fd9' => 
    array (
      0 => 'D:\\www\\document\\smarty\\MVC\\View\\goodsList.html',
      1 => 1486545897,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_589ae3e9b8d5d1_69096308 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['goodsList']->value, 'v');
$_smarty_tpl->tpl_vars['v']->iteration = 0;
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->iteration++;
$__foreach_v_0_saved = $_smarty_tpl->tpl_vars['v'];
?>
<p><?php echo $_smarty_tpl->tpl_vars['v']->iteration;?>
 - <?php echo $_smarty_tpl->tpl_vars['v']->value['goodsname'];?>
</p>
<?php
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_0_saved;
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

</body>
</html><?php }
}
