<?php
/**
*
* quickreply [English]
*
* @package language quickreply
* @copyright (c) 2014 William Jacoby (bonelifer)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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
	'QR_ENABLE_AJAX_PAGINATION'			=> 'Do not refresh quick reply form when navigating the topic',
	'QR_ENABLE_SCROLL'					=> 'Enable auto scroll when navigating the topic',
	'QR_ENABLE_SOFT_SCROLL'				=> 'Enable soft scroll and animations when navigating the topic and after quick reply',
));
