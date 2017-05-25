<?php
/* Smarty version 3.1.30, created on 2017-02-08 20:14:01
  from "D:\www\document\smarty\day3\smarty\templates\child.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_589b0b8994b7f9_43721515',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'db619fa94ef7f62d52e61760797d8e332ef21c4e' => 
    array (
      0 => 'D:\\www\\document\\smarty\\day3\\smarty\\templates\\child.html',
      1 => 1486556040,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:parent.html' => 1,
  ),
),false)) {
function content_589b0b8994b7f9_43721515 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>





<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_26727589b0b89947976_75845447', 'sss');
$_smarty_tpl->inheritance->endChild();
$_smarty_tpl->_subTemplateRender("file:parent.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 2, false);
}
/* {block 'sss'} */
class Block_26727589b0b89947976_75845447 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<span>大大<?php 
$_smarty_tpl->inheritance->callParent($_smarty_tpl, $this);
?>
大</span>
<?php
}
}
/* {/block 'sss'} */
}
