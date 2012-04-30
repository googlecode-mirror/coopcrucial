<?php
if(!isset($smarty)){
    require_once('../frameworks/Smarty/libs/Smarty.class.php');
    $smarty = new Smarty();
}
$smarty->compile_check = true;
$smarty->left_delimiter = '{#';
$smarty->right_delimiter = '#}';

$smarty->template_dir = '../view/';
$smarty->compile_dir = '../view_c/';
?>