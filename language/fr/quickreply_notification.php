<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2020 Татьяна5 and LavIgor
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
		1 => '<strong>Mentionné</strong> par %1$s dans le message :',
		2 => '<strong>Mentionné</strong> par %1$s dans le message :',
	],

	'NOTIFICATION_TYPE_QUICKNICK' => 'Vous avez été mentionné dans le message',

	'QR_BBPOST' => 'Source du message',
]);
