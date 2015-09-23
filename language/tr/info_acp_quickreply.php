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
	'ACP_QUICKREPLY'				=> 'Quick Reply',
	'ACP_QUICKREPLY_EXPLAIN'		=> 'Quick Reply Ayarları',
	'ACP_QR_BBCODE'					=> 'BBcode\'a izin ver',
	'ACP_QR_BBCODE_EXPLAIN'			=> 'BBCode butonlarına Hızlı Cevap kutusunda izin ver.',
	'ACP_QR_COMMA'					=> 'Kullanıcı adından sonra virgül koy',
	'ACP_QR_COMMA_EXPLAIN'			=> '"Kullanıcı adından bahset" kullanıldığında, kullanıcı adından sonra virgül koy.',
	'ACP_QR_CTRLENTER'				=> '“Ctrl+Enter” ile göndermeye izin ver',
	'ACP_QR_CTRLENTER_EXPLAIN'		=> '“Ctrl+Enter”a basarak cevap yollamayı etkinleştir.',
	'ACP_QR_ENABLE_RE'				=> '“Re:” yazısını etkinleştir',
	'ACP_QR_ENABLE_RE_EXPLAIN'		=> 'Hızlı cevap kutusunda "Mesaj başlığında" “Re:” ön ekini otomatik olarak ekle.',
	'ACP_QR_QUICKNICK'				=> 'Kullanıcı adı ekle',
	'ACP_QR_QUICKNICK_EXPLAIN'		=> '"Kullanıcı adından bahset" kullanıldığında hızlı cevap kutusunda kullanıcı adının çıkmasını etkinleştir.',
	'ACP_QR_QUICKNICK_REF'			=> '"Kullanıcı adından bahset" için özel kod kullan',
	'ACP_QR_QUICKNICK_REF_EXPLAIN'	=> '"Kullanıcı adından bahset" kullanıldığında [b] yerine [ref] kodu kullanılacak.',
	'ACP_QR_QUICKNICK_PM'			=> 'Kullanıcı adına tıklanıldığında açılan menüde "Özel Mesaj ile Cevapla" butonunu göster',
	'ACP_QR_QUICKQUOTE'				=> 'Hızlı alıntıyı etkinleştir',
	'ACP_QR_QUICKQUOTE_EXPLAIN'		=> 'Mesajda bir yazı seçtiğinizde "Alıntı Yap" menüsü çıkmasını sağlar.',
	'ACP_QR_QUICKQUOTE_LINK'		=> 'Hızlı alıntı kullanıldığında gönderinin yazarına ait profil bağlantısı ekle',
	'ACP_QR_FULL_QUOTE'				=> 'Hızlı alıntı formuna tam alıntıları ekle',
	'ACP_QR_FULL_QUOTE_EXPLAIN'		=> '"Alıntı ile Cevapla" butonunun davranışını değiştirir.',
	'ACP_QR_SMILIES'				=> 'Smiley\'leri etkinleştir',
	'ACP_QR_SMILIES_EXPLAIN'		=> 'Hızlı Cevap kutusunda smiley\'lere izin ver.',
	'ACP_QR_CAPSLOCK'				=> 'Yazıyı küçült / büyüt seçeneğini etkinleştir',
	'ACP_QR_SHOW_BUTTON_TRANSLIT'	=> '"Dönüştür" düğmesini göster',
	'ACP_QR_AJAX_SUBMIT'			=> 'Ajax yollamayı etkinleştir',
	'ACP_QR_AJAX_SUBMIT_EXPLAIN'	=> 'Mesajların, sayfa yenilenmeden yollamanmasını sağlar.',
	'ACP_QR_AJAX_PAGINATION'		=> 'Başlıklar arasındaki gezintiyi sayfayı yenilemeden yap',
	'ACP_QR_AJAX_PAGINATION_EXPLAIN'=> 'Kullanıcıların "Başlıkta gezinirken hızlı cevap formunu yeniden yükleme" seçeneğini değiştirebilmelerini sağlar.',
	'ACP_QR_SCROLL_TIME'			=> 'Tek kaydırma ve animasyon süreleri',
	'ACP_QR_SCROLL_TIME_EXPLAIN'	=> 'Yumuşa kaydırma (soft scroll) özelliğinin milisaniye olarak süresi. Standart kaydırma için 0 kullanınız.',
	'ACP_QR_SHOW_SUBJECTS'			=> 'Gönderi konu başlıklarını, başlıklarda göster',
	'ACP_QR_SHOW_SUBJECTS_IN_SEARCH'=> 'Gönderi konu başlıklarını arama sonuçlarında göster',
	'ACP_QR_SOURCE_POST'			=> 'Alıntı yaparken, alıntı yapılan mesajın bağlantısını ekle',
	'ACP_QR_ATTACH'					=> 'Eklentilere izin ver',
	'ACP_QR_ALLOW_FOR_GUESTS'		=> 'Misafirler için hlı cevap kutusunu etkinleştir',
	'ACP_QR_COLOUR_NICKNAME'		=> 'Kullanıcı adından bahsederken bir renk kullan',
));
