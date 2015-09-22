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
	'QR_ENABLE_AJAX_PAGINATION'			=> 'Başlıkta gezinirken hızlı cevap formunu yeniden yükleme',
	'QR_ENABLE_SCROLL'					=> 'Başlıkta gezinirken otomatik kaydırmayı etkinleştir',
	'QR_ENABLE_SOFT_SCROLL'				=> 'Başlıkta gezinirken ve hızlı cevaptan sonra yumuşak kaydırma ve animasyonları etkinleştir',
));
