<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2017 Tatiana5 and LavIgor
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
	'QR_CHANGE_QUICKNICK_STRING' => 'Dropdown-Menü wechseln, wenn du auf den Nicknamen klickst, um auf „Auf Benutzernamen verweisen“ unter Avatar zu linken.',
	'QR_ENABLE_AJAX_PAGINATION'  => 'Lade das Schnellantwort Formular nicht neu wenn im Thema navigiert wird',
	'QR_ENABLE_SCROLL'           => 'Aktiviere das Auto Scroll-Feature wenn in einem Thema navigiert wird',
	'QR_ENABLE_SOFT_SCROLL'      => 'Aktiviere sanftes Scrollen und Animationen wenn im Thema navigiert wird und nach einer Schnellantwort',
	'QR_ENABLE_WARNING'          => 'Warne, wenn eine eingegebene Schnellantwort verloren gehen könnte',
	'QR_FIX_EMPTY_FORM'          => 'Erlaube, das Schnellantwortformular zu fixieren, wenn es leer ist',
));
