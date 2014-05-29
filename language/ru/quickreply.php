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
	'QR_INSERT_TEXT'			=> 'Вставить цитату в окно ответа',
	'QR_PROFILE'				=> 'Перейти в профиль',
	'QR_TOO_FEW_CHARS'			=> 'Ваше сообщение слишком короткое.',
	'QR_QUICKNICK'				=> 'Обратиться по нику',
	'QR_QUICKNICK_TITLE'		=> 'Вставить имя пользователя в окно быстрого ответа',
//begin mod CapsLock Transfer
	'TRANSFORM_TEXT'			        => 'Перевести текст в ',
	'TRANSFORM_TEXT_LOWER'		=> 'нижний регистр',
	'TRANSFORM_TEXT_UPPER'		=> 'верхний регистр',
	'TRANSFORM_TEXT_TOOLTIP'    => 'Смена регистра выделенного текста ',
  //end mod CapsLock Transfer    
));
