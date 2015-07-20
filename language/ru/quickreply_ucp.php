<?php
/**
*
* quickreply [Russian]
*
* @package language quickreply
* @copyright (c) 2013 Татьяна5
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
	'QR_ENABLE_AJAX_PAGINATION'			=> 'Не очищать форму быстрого ответа при просмотре темы',
	'QR_ENABLE_SCROLL'					=> 'Включить автоматическую прокрутку страницы при просмотре темы',
	'QR_ENABLE_SOFT_SCROLL'				=> 'Включить плавную прокрутку страницы и анимацию при просмотре темы и после быстрого ответа',
));
