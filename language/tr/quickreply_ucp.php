<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2021 Татьяна5 and LavIgor
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
	$lang = [];
}

$lang = array_merge($lang, [
	'QR_CHANGE_QUICKNICK_STRING' => 'Switch dropdown menu when you click on the nickname to link “Refer by username” under avatar',
	'QR_ENABLE_AJAX_PAGINATION'  => 'Başlıkta gezinirken hızlı cevap formunu yeniden yükleme',
	'QR_ENABLE_SCROLL'           => 'Başlıkta gezinirken otomatik kaydırmayı etkinleştir',
	'QR_ENABLE_SOFT_SCROLL'      => 'Başlıkta gezinirken ve hızlı cevaptan sonra yumuşak kaydırma ve animasyonları etkinleştir',
	'QR_ENABLE_WARNING'          => 'Warn if the entered quick reply can be lost',
	'QR_FIX_EMPTY_FORM'          => 'Allow to fix quick reply form when it is empty',
]);
