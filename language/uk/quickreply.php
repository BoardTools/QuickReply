<?php
/**
*
* quickreply [Ukrainian]
*
* @package language quickreply
* @copyright (c) 2014 Alex Fryschyn (Sherlock)
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
	'QR_INSERT_TEXT'			=> 'Вставити цитату у вікно відповіді',
	'QR_PROFILE'				=> 'Перейти в профіль',
	'QR_QUICKNICK'				=> 'Звернутися за ніком',
	'QR_QUICKNICK_TITLE'		=> 'Вставити ім\'я користувача у вікно швидкої відповіді',
));
