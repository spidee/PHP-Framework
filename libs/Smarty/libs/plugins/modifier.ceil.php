<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty cat modifier plugin
 *
 * Type:     ceil<br>
 * Name:     ceil<br>
 * @author   Jan Krizak
 * @version 1.0
 * @param float
 * @return int
 */
function smarty_modifier_ceil($value)
{
    return (int)ceil($value);
}

/* vim: set expandtab: */

?>
