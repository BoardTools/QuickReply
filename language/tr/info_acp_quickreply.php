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
	'ACP_QUICKREPLY'				=> 'Hızlı Cevap',
	'ACP_QUICKREPLY_EXPLAIN'		=> 'Hızlı Cevap Ayarları',

	'ACP_QR_BBCODE'					=> 'BBcode\'lar Etkin',
	'ACP_QR_BBCODE_EXPLAIN'			=> 'Hızlı cevap için BBCode\'ları etkinleştirir',
	'ACP_QR_COMMA'					=> 'Kullanıcı isminden sonra virgül ekle',
	'ACP_QR_COMMA_EXPLAIN'			=> 'Bahsedilen kullanıcı isminden sonra otamatik olarak virgül ekler ',
	'ACP_QR_CTRLENTER'				=> '“Ctrl+Enter” etkin',
	'ACP_QR_CTRLENTER_EXPLAIN'		=> '“Ctrl+Enter” bastığınızda mesaj gönderimini etkinleştirir',
	'ACP_QR_ENABLE_RE'				=> '“Re:” Etkin',
	'ACP_QR_ENABLE_RE_EXPLAIN'		=> 'Hızlı Cevap için gönderilen konu başlığına otamatik olarak “Re:” ekler',
	'ACP_QR_QUICKNICK'				=> 'Kullanıcı adını ekle',
	'ACP_QR_QUICKNICK_EXPLAIN'		=> 'Kullanıcı ismine tıklandığında kullanıcıdan bahsetmeniz için açılan bir menü görünmesine izin verir',
	'ACP_QR_QUICKQUOTE'				=> 'Hızlı Alıntı Etkin',
	'ACP_QR_QUICKQUOTE_EXPLAIN'		=> 'Bir mesajda ki metni seçtiğinizde alıntı yapmanız için açılan bir menü görünmesine izin verir',
	'ACP_QR_SMILIES'				=> 'İfadeler Etkin',
	'ACP_QR_SMILIES_EXPLAIN'		=> 'Hızlı cevap için ifadeleri etkinleştirir',
	'ACP_QR_CAPSLOCK'				=> 'Metin biçimini değiştir ( büyük / küçük harf ) Etkin',
	'ACP_QR_SHOW_BUTTON_TRANSLIT'			=> '“Dönüştür” butonunu göster',
	'ACP_QR_AJAX_SUBMIT'			=> 'Ajax Etkin',
	'ACP_QR_AJAX_SUBMIT_EXPLAIN'	=> 'Sayfayı yenilemeden mesajın gönderilmesini sağlar',
	'ACP_QR_SHOW_SUBJECTS'			=> 'Konu başlığını göster',
	'ACP_QR_SOURCE_POST'			=> 'Alıntı yaparken alıntı yapılan mesajın kaynağını göster',
	'ACP_QR_ATTACH'					=> 'Dosya eklentilere izin ver',
	'ACP_QR_COLOUR_NICKNAME'		=> 'Bahsedilen kullanıcı isminin rengini ekle',
));
