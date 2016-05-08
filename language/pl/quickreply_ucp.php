<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
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
	'QR_ENABLE_AJAX_PAGINATION' => 'Nie odświeżaj szybkiej odpowiedzi kiedy poruszasz się po tematach',
	'QR_ENABLE_SCROLL'          => 'Włącz automatyczne przewijanie gdy przechodzisz między stronami tematu.',
	'QR_ENABLE_SOFT_SCROLL'     => 'Włącz delikatne przewijanie i animację kiedy przechodzisz między stronami tematu i gdy wyślesz szybką odpowiedź.',
));
