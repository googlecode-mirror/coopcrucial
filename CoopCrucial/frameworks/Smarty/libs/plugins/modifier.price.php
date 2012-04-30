<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier<br>
 * Name:     price<br>
 * Purpose:  getting formatted price with grouped thousands and 
 *           decimal separators
 * Example:  {$price|price:"2":".":","}
 * -------------------------------------------------------------
 */

function smarty_modifier_price($price,$decimals='0',$dec_point='.',$thousands_sep=',')
{
	return number_format(abs($price),$decimals,$dec_point,$thousands_sep);
}

/* vim: set expandtab: */
?>