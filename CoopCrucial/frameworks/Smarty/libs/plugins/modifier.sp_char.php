<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty replace modifier plugin
 *
 * Type:     modifier<br>
 * Name:     replace<br>
 * Purpose:  simple search/replace
 * @link http://smarty.php.net/manual/en/language.modifier.replace.php
 *          replace (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param string
 * @param string
 * @return string
 */
function smarty_modifier_sp_char($string)
{
    return str_replace(array('á','é','í','ó','ú','Á','É','Í','Ó','Ú','ñ','Ñ'),
											 array('a','e','i','o','u','A','E','I','O','U','n','N'),
											 $string);
}

/* vim: set expandtab: */

?>
