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
		1 => '<strong>Обращение по никнейму</strong> от пользователя %1$s в сообщении:',
		2 => '<strong>Обращение по никнейму</strong> от пользователей %1$s в сообщении:',
		3 => '<strong>Обращение по никнейму</strong> от пользователей %1$s в сообщении:',
	],

	'NOTIFICATION_TYPE_QUICKNICK' => 'К вам обратились по никнейму в сообщении',

	'QR_BBPOST' => 'Источник цитаты',
]);
