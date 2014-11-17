<?php
/**
*
* quickreply [Ukrainian]
*
* @package language quickreply
* @copyright (c) 2014 Oleksii Fryschyn (Sherlock)
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
	'QR_QUICKNICK_TITLE'		=> 'Вставити ім&#39;я користувача у вікно швидкої відповіді',
//begin mod Translit
	'QR_TRANSLIT_TEXT'			=> 'Транслит:',
	'QR_TRANSLIT_TEXT_TOOLTIP'	=> 'Для мнгновенного отображения на русском языке нажмите  кнопку',
//end mod Translit
//begin mod CapsLock Transfer
	'QR_TRANSFORM_TEXT'			=> 'Зміна регістра тексту:',
	'QR_TRANSFORM_TEXT_TOOLTIP'	=> 'Для зміни регістру виділіть частину тексту та натисніть потрібну кнопку',
	'QR_TRANSFORM_TEXT_LOWER'	=> '&#9660; абв',
	'QR_TRANSFORM_TEXT_UPPER'	=> '&#9650; АБВ',
	'QR_TRANSFORM_TEXT_INVERS'	=> '&#9660;&#9650; аБВ',
	'QR_TRANSFORM_TEXT_LOWER_TOOLTIP'	=> 'нижній регістр',
	'QR_TRANSFORM_TEXT_UPPER_TOOLTIP'	=> 'ВЕРХНІЙ РЕГІСТР',
	'QR_TRANSFORM_TEXT_INVERS_TOOLTIP'	=> 'іНВЕРСІЯ рЕГІСТРА',
//end mod CapsLock Transfer
));
