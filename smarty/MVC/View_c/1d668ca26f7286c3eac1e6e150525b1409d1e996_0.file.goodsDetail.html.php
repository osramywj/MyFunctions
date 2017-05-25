<?php
/* Smarty version 3.1.30, created on 2017-02-09 11:45:51
  from "D:\www\document\smarty\MVC\View\goodsDetail.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_589be5ef1825e7_98789181',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1d668ca26f7286c3eac1e6e150525b1409d1e996' => 
    array (
      0 => 'D:\\www\\document\\smarty\\MVC\\View\\goodsDetail.html',
      1 => 1486611773,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_589be5ef1825e7_98789181 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['goodsDetail']->value, 'val');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['val']->value) {
?>
<p><a href=""><?php echo $_smarty_tpl->tpl_vars['val']->value['detail'];?>
</a></p>
<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

</body>
</html><?php }
}
