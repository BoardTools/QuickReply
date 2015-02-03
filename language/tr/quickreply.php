<?php
/**
*
* quickreply [Turkish - Türkçe]
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
	'QR_BBPOST'					=> 'Mesajın kaynağı',
	'QR_INSERT_TEXT'			=> 'Hızlı cevaba alıntı olarak ekle',
	'QR_PROFILE'				=> 'Profile git',
	'QR_QUICKNICK'				=> 'Kullanıcıdan Bahset',
	'QR_QUICKNICK_TITLE'		=> 'Hızlı cevaba kullanıcı ismini ekle',
//begin mod Translit
	'QR_TRANSLIT_TEXT'			=> 'Dönüştür:',
	'QR_TRANSLIT_TEXT_TOOLTIP'	=> 'Kiril alfabesi ile görüntüle',
//end mod Translit
//begin mod CapsLock Transfer
	'QR_TRANSFORM_TEXT'			=> 'Metin biçimini değiştirin:',
	'QR_TRANSFORM_TEXT_TOOLTIP'	=> 'Değiştirmek için metni seçin ve bir düğmeye basın',
	'QR_TRANSFORM_TEXT_LOWER'	=> '&#9660; abc',
	'QR_TRANSFORM_TEXT_UPPER'	=> '&#9650; ABC',
	'QR_TRANSFORM_TEXT_INVERS'	=> '&#9660;&#9650; aBC',
	'QR_TRANSFORM_TEXT_LOWER_TOOLTIP'	=> 'Küçük harf',
	'QR_TRANSFORM_TEXT_UPPER_TOOLTIP'	=> 'Büyük harf',
	'QR_TRANSFORM_TEXT_INVERS_TOOLTIP'	=> 'Ters çevir',
//end mod CapsLock Transfer
));
