<?php
/* Smarty version 3.1.30, created on 2017-01-17 17:03:28
  from "D:\www\document\smarty\day2\smarty\templates\test.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_587ddde019b5c8_68752370',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5a081c3843c6a3a035200c05c5ae68afb39d6bae' => 
    array (
      0 => 'D:\\www\\document\\smarty\\day2\\smarty\\templates\\test.html',
      1 => 1484643807,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_587ddde019b5c8_68752370 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->ext->_tplFunction->registerTplFunctions($_smarty_tpl, array (
  'table' => 
  array (
    'compiled_filepath' => 'D:\\www\\document\\smarty\\day2\\smarty\\templates_c\\5a081c3843c6a3a035200c05c5ae68afb39d6bae_0.file.test.html.php',
    'uid' => '5a081c3843c6a3a035200c05c5ae68afb39d6bae',
    'call_name' => 'smarty_template_function_table_14591587ddde003faf1_15286978',
  ),
));
if (!is_callable('smarty_modifier_highLight')) require_once 'D:\\www\\document\\smarty\\day2\\smarty\\myplugins\\modifier.highLight.php';
if (!is_callable('smarty_function_multiline')) require_once 'D:\\www\\document\\smarty\\day2\\smarty\\myplugins\\function.multiline.php';
if (!is_callable('smarty_function_eightball')) require_once 'D:\\www\\document\\smarty\\day2\\smarty\\myplugins\\function.eightball.php';
if (!is_callable('smarty_block_moreline')) require_once 'D:\\www\\document\\smarty\\day2\\smarty\\myplugins\\block.moreline.php';
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
<body>
<h1><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h1>
<p><?php echo $_smarty_tpl->tpl_vars['content']->value;?>
</p>
<?php echo smarty_modifier_highLight($_smarty_tpl->tpl_vars['content']->value,'cyan','bold','30px');?>

<?php echo substr($_smarty_tpl->tpl_vars['content']->value,0,10);?>

<p><?php echo strlen($_smarty_tpl->tpl_vars['content']->value);?>
</p>
<?php echo substr($_smarty_tpl->tpl_vars['content']->value,0,14);?>

<p><?php echo implode($_smarty_tpl->tpl_vars['arr']->value,',');?>
</p>

<?php echo smarty_function_multiline(array('color'=>'red','size'=>'30px','content'=>'This is a paragraph','line'=>5),$_smarty_tpl);?>

<p><?php echo smarty_function_eightball(array(),$_smarty_tpl);?>
</p>
<hr>
<?php $_smarty_tpl->smarty->_cache['_tag_stack'][] = array('moreline', array('line'=>4,'color'=>'cyan','size'=>'40px','weight'=>'bold'));
$_block_repeat1=true;
echo smarty_block_moreline(array('line'=>4,'color'=>'cyan','size'=>'40px','weight'=>'bold'), null, $_smarty_tpl, $_block_repeat1);
while ($_block_repeat1) {
ob_start();
?>

我是$content
<?php $_block_repeat1=false;
echo smarty_block_moreline(array('line'=>4,'color'=>'cyan','size'=>'40px','weight'=>'bold'), ob_get_clean(), $_smarty_tpl, $_block_repeat1);
}
array_pop($_smarty_tpl->smarty->_cache['_tag_stack']);?>


<?php $_smarty_tpl->_assignInScope('arr', array('age'=>32,'sex'=>'female','hobby'=>'basketball'));
$_smarty_tpl->_assignInScope('arr1', array(1,2,3));
$_smarty_tpl->_assignInScope('arr2', array('age'=>32,'sex'=>'female','hobby'=>'basketball'));
$_smarty_tpl->_assignInScope('arr3', array(12,13,14));
?>
<p><?php echo $_smarty_tpl->tpl_vars['arr']->value['hobby'];?>
</p>
<p><?php echo $_smarty_tpl->tpl_vars['arr1']->value[1];?>
</p>
<p><?php echo $_smarty_tpl->tpl_vars['arr2']->value['sex'];?>
</p>
<p><?php echo $_smarty_tpl->tpl_vars['arr3']->value[0];?>
</p>
<hr>
<?php $_smarty_tpl->_assignInScope('num', 30);
if ((1 & $_smarty_tpl->tpl_vars['num']->value)) {?>
<h1>我是奇数</h1>
<?php } elseif (!(1 & $_smarty_tpl->tpl_vars['num']->value / 3)) {?>
<h2>我被3除商是偶数</h2>
<?php } else { ?>
<h2>我被3除商是奇数</h2>
<?php }
if ($_smarty_tpl->tpl_vars['num']->value%4 == 2) {?>
<h2>XXXXX</h2>
<?php }?>

<?php
$_smarty_tpl->tpl_vars['i'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['i']->step = -2;$_smarty_tpl->tpl_vars['i']->total = (int) min(ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? 50+1 - (1) : 1-(50)+1)/abs($_smarty_tpl->tpl_vars['i']->step)),10);
if ($_smarty_tpl->tpl_vars['i']->total > 0) {
for ($_smarty_tpl->tpl_vars['i']->value = 1, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++) {
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;
echo $_smarty_tpl->tpl_vars['i']->value;?>

<?php }} else { ?>
<p>循环有错误</p>
<?php }
?>

<?php $_smarty_tpl->_assignInScope('k', 3);
while ($_smarty_tpl->tpl_vars['k']->value < 15) {?>
<p><?php echo $_smarty_tpl->tpl_vars['k']->value++;?>
</p>
<?php }?>



<?php $_smarty_tpl->ext->_tplFunction->callTemplateFunction($_smarty_tpl, 'table', array('a'=>'aaaaaaa','b'=>'bbbbbbb'), true);?>


<?php $_smarty_tpl->_assignInScope('user', array('jack',34,'male','football'));
$_smarty_tpl->_assignInScope('user1', array('username'=>'jack','age'=>34,'sex'=>'male','hobby'=>'football'));
$__section_user_1_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_user']) ? $_smarty_tpl->tpl_vars['__smarty_section_user'] : false;
$__section_user_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['user2']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_user_1_total = $__section_user_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_user'] = new Smarty_Variable(array());
if ($__section_user_1_total != 0) {
for ($__section_user_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_user']->value['index'] = 0; $__section_user_1_iteration <= $__section_user_1_total; $__section_user_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_user']->value['index']++){
$_smarty_tpl->tpl_vars['__smarty_section_user']->value['first'] = ($__section_user_1_iteration == 1);
if ((isset($_smarty_tpl->tpl_vars['__smarty_section_user']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_section_user']->value['first'] : null)) {?>
<p style="background-color: #00C4F6"><?php echo $_smarty_tpl->tpl_vars['user2']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_user']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_user']->value['index'] : null)]['username'];?>
-<?php echo $_smarty_tpl->tpl_vars['user2']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_user']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_user']->value['index'] : null)]['age'];?>
-<?php echo $_smarty_tpl->tpl_vars['user2']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_user']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_user']->value['index'] : null)]['sex'];?>
-<?php echo $_smarty_tpl->tpl_vars['user2']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_user']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_user']->value['index'] : null)]['hobby'];?>
</p>
<?php } else { ?>
<p><?php echo $_smarty_tpl->tpl_vars['user2']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_user']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_user']->value['index'] : null)]['username'];?>
-<?php echo $_smarty_tpl->tpl_vars['user2']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_user']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_user']->value['index'] : null)]['age'];?>
-<?php echo $_smarty_tpl->tpl_vars['user2']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_user']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_user']->value['index'] : null)]['sex'];?>
-<?php echo $_smarty_tpl->tpl_vars['user2']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_user']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_user']->value['index'] : null)]['hobby'];?>
</p>
<?php }
}} else {
 ?>
<p>没有查到数据</p>
<?php
}
if ($__section_user_1_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_user'] = $__section_user_1_saved;
}
?>





</body>
</html><?php }
/* smarty_template_function_table_14591587ddde003faf1_15286978 */
if (!function_exists('smarty_template_function_table_14591587ddde003faf1_15286978')) {
function smarty_template_function_table_14591587ddde003faf1_15286978($_smarty_tpl,$params) {
$params = array_merge(array('a'=>'aaa','b'=>'bbb'), $params);
foreach ($params as $key => $value) {
$_smarty_tpl->tpl_vars[$key] = new Smarty_Variable($value, $_smarty_tpl->isRenderingCache);
}?>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>编号<?php echo $_smarty_tpl->tpl_vars['a']->value;?>
</th>
        <th>用户名<?php echo $_smarty_tpl->tpl_vars['b']->value;?>
</th>
        <th>密码</th>
    </tr>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['admin']->value, 'val', true, 'key');
$_smarty_tpl->tpl_vars['val']->show = ($_smarty_tpl->tpl_vars['val']->total > 0);
$_smarty_tpl->tpl_vars['val']->iteration = 0;
$_smarty_tpl->tpl_vars['val']->index = -1;
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
$_smarty_tpl->tpl_vars['val']->iteration++;
$_smarty_tpl->tpl_vars['val']->index++;
$_smarty_tpl->tpl_vars['val']->first = !$_smarty_tpl->tpl_vars['val']->index;
$_smarty_tpl->tpl_vars['val']->last = $_smarty_tpl->tpl_vars['val']->iteration == $_smarty_tpl->tpl_vars['val']->total;
$__foreach_val_0_saved = $_smarty_tpl->tpl_vars['val'];
?>
    <?php if ($_smarty_tpl->tpl_vars['val']->first) {?>
    <tr style="background-color: #00C4F6">
    <?php } elseif ($_smarty_tpl->tpl_vars['val']->last) {?>
    <tr style="background-color: #f67e4b">
    <?php } elseif (!(1 & $_smarty_tpl->tpl_vars['val']->iteration)) {?>
    <tr style="background-color: #f6c45f">
    <?php } else { ?>
    <tr style="background-color: #4cf6b3">
    <?php }?>
        <td><?php echo $_smarty_tpl->tpl_vars['key']->value;?>
-<?php echo $_smarty_tpl->tpl_vars['val']->index;?>
-<?php echo $_smarty_tpl->tpl_vars['val']->iteration;?>
-<?php echo $_smarty_tpl->tpl_vars['val']->key;?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['val']->value['username'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['val']->value['password'];?>
</td>
    </tr>
    <?php
$_smarty_tpl->tpl_vars['val'] = $__foreach_val_0_saved;
}
} else {
?>

    <tr>
        <td colspan="3">没有查找到数据</td>
    </tr>
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

    <?php if (!$_smarty_tpl->tpl_vars['val']->show) {?>
    <tr>
        <td colspan="3">没有找到数据</td>
    </tr>
    <?php }?>
    <tr>
        <td colspan="3">共查到<?php echo $_smarty_tpl->tpl_vars['val']->total;?>
条记录</td>
    </tr>

    <?php
$__section_test_0_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_test']) ? $_smarty_tpl->tpl_vars['__smarty_section_test'] : false;
$__section_test_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['admin']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_test_0_total = $__section_test_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_test'] = new Smarty_Variable(array());
if ($__section_test_0_total != 0) {
for ($__section_test_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_test']->value['index'] = 0; $__section_test_0_iteration <= $__section_test_0_total; $__section_test_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_test']->value['index']++){
?>
    <tr>
        <td><?php echo $_smarty_tpl->tpl_vars['admin']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_test']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_test']->value['index'] : null)]['id'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['admin']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_test']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_test']->value['index'] : null)]['username'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['admin']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_test']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_test']->value['index'] : null)]['password'];?>
</td>
    </tr>
    <?php }} else {
 ?>
    <tr>
        <td colspan="3">
            没有查到数据
        </td>
    </tr>
    <?php
}
if ($__section_test_0_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_test'] = $__section_test_0_saved;
}
?>
</table>
<?php
}}
/*/ smarty_template_function_table_14591587ddde003faf1_15286978 */
}
