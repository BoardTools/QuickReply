<?php
/**
*
* quickreply [Turkish]
*
* @package language quickreply
* @copyright (c) 2015 Edip Dincer
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
	'QR_BBPOST'					=> 'Gönderinin Kaynağı',
	'QR_INSERT_TEXT'			=> 'Hızlı Cevap formuna Alıntı ekle',
	'QR_PROFILE'				=> 'Profile git',
	'QR_QUICKNICK'				=> 'Kullanıcı adından bahset',
	'QR_QUICKNICK_TITLE'		=> 'Kullanıcı adını Hızlı Cevap formuna aktar',
	'QR_REPLY_IN_PM'			=> 'ÖM ile cevapla',
//begin mod Translit
	'QR_TRANSLIT_TEXT'			=> 'Translit:',
	'QR_TRANSLIT_TEXT_TO_RU'	=> 'to russian',
	'QR_TRANSLIT_TEXT_TOOLTIP'	=> 'For instant view in Russian click the button',
//end mod Translit
//begin mod CapsLock Transfer
	'QR_TRANSFORM_TEXT'			=> 'Büyük/Küçük harf değiştir:',
	'QR_TRANSFORM_TEXT_TOOLTIP'	=> 'Seçili metnin büyük/küçük harf olarak değiştirmek için bir butona basın',
	'QR_TRANSFORM_TEXT_LOWER'	=> '▼ abc',
	'QR_TRANSFORM_TEXT_UPPER'	=> '▲ ABC',
	'QR_TRANSFORM_TEXT_INVERS'	=> '▼▲ aBC',
	'QR_TRANSFORM_TEXT_LOWER_TOOLTIP'	=> 'küçük harf',
	'QR_TRANSFORM_TEXT_UPPER_TOOLTIP'	=> 'BÜYÜK HARF',
	'QR_TRANSFORM_TEXT_INVERS_TOOLTIP'	=> 'kÜÇÜK/bÜYÜK hARF dEĞİŞTİR',
//end mod CapsLock Transfer
));
