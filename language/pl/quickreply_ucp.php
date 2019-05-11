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
	'QR_CHANGE_QUICKNICK_STRING' => 'Switch dropdown menu when you click on the nickname to link “Refer by username” under avatar',
	'QR_ENABLE_AJAX_PAGINATION'  => 'Nie odświeżaj szybkiej odpowiedzi kiedy poruszasz się po tematach',
	'QR_ENABLE_SCROLL'           => 'Włącz automatyczne przewijanie gdy przechodzisz między stronami tematu.',
	'QR_ENABLE_SOFT_SCROLL'      => 'Włącz delikatne przewijanie i animację kiedy przechodzisz między stronami tematu i gdy wyślesz szybką odpowiedź.',
	'QR_ENABLE_WARNING'          => 'Warn if the entered quick reply can be lost',
	'QR_FIX_EMPTY_FORM'          => 'Allow to fix quick reply form when it is empty',
));
