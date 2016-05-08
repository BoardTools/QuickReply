<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

/**
 * DO NOT CHANGE
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'QR_ENABLE_AJAX_PAGINATION' => 'Ne frissítse a Gyors válasz űrlapot a témában való navigálás során',
	'QR_ENABLE_SCROLL'          => 'Automatikus görgetés a témában való navigálás során',
	'QR_ENABLE_SOFT_SCROLL'     => 'Finom görgetés és animálás a témában való navigáláskor és gyors válasz után',
));
