<?php
/**
*
* quickreply [Arabic]
*
* @package language quickreply
* @copyright (c) 2014 William Jacoby (bonelifer)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
* Translated By : Basil Taha Alhitary - www.alhitary.net
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
	'QR_BBPOST'					=> 'مصدر المشاركة',
	'QR_INSERT_TEXT'			=> 'اقتباس هذا النص في الرد السريع',
	'QR_PROFILE'				=> 'الذهاب إلى الملف الشخصي',
	'QR_QUICKNICK'				=> 'الإشارة إلى الإسم',
	'QR_QUICKNICK_TITLE'		=> 'ادخال اسم العضو في الرد السريع',
//begin mod Translit
	'QR_TRANSLIT_TEXT'			=> 'ترجمة :',
	'QR_TRANSLIT_TEXT_TOOLTIP'	=> 'انقر على الزر للترجمة إلى اللغة الروسية',
//end mod Translit
//begin mod CapsLock Transfer
	'QR_TRANSFORM_TEXT'			=> 'تغيير حالة النص :',
	'QR_TRANSFORM_TEXT_TOOLTIP'	=> 'انقر على أحد الأزرار لتغيير حالة النص المُظلل (حرف كبير/صغير)',
	'QR_TRANSFORM_TEXT_LOWER'	=> '&#9660; abc',
	'QR_TRANSFORM_TEXT_UPPER'	=> '&#9650; ABC',
	'QR_TRANSFORM_TEXT_INVERS'	=> '&#9660;&#9650; aBC',
	'QR_TRANSFORM_TEXT_LOWER_TOOLTIP'	=> 'أصغر',
	'QR_TRANSFORM_TEXT_UPPER_TOOLTIP'	=> 'أكبر',
	'QR_TRANSFORM_TEXT_INVERS_TOOLTIP'	=> 'عكسي',
//end mod CapsLock Transfer
));
