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
	// Translate according to plural rules.
	'NOTIFICATION_QUICKNICK' => [
		1 => '<strong>Mentioned</strong> by %1$s in the message:',
	],

	'NOTIFICATION_TYPE_QUICKNICK' => 'Usted ha sido mencionado en el mensaje',

	'QR_BBPOST' => 'Fuente del mensaje',
]);
