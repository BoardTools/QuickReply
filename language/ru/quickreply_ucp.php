<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2019 Tatiana5 and LavIgor
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
	'QR_CHANGE_QUICKNICK_STRING' => 'Переключить всплывающее меню при клике по нику на строку «Обратиться по нику» под аватаром',
	'QR_ENABLE_AJAX_PAGINATION'  => 'Не очищать форму быстрого ответа при просмотре темы',
	'QR_ENABLE_SCROLL'           => 'Включить автоматическую прокрутку страницы при просмотре темы',
	'QR_ENABLE_SOFT_SCROLL'      => 'Включить плавную прокрутку страницы и анимацию при просмотре темы и после быстрого ответа',
	'QR_ENABLE_WARNING'          => 'Предупреждать, если введённый быстрый ответ может быть потерян',
	'QR_FIX_EMPTY_FORM'          => 'Разрешить фиксировать форму быстрого ответа, когда она пустая',
));
