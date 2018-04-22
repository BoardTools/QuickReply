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
	// Translate according to plural rules.
	'NOTIFICATION_QUICKNICK' => array(
		1 => '<strong>Dein Name wurde erwähnt</strong> von %1$s Benutzer in der Nachricht:',
		2 => '<strong>Dein Name wurde erwähnt</strong> von %1$s Benutzern in Nachricht:',
		3 => '<strong>Dein Name wurde erwähnt</strong> von %1$s Benutzern in der Nachricht:',
	),

	'NOTIFICATION_TYPE_QUICKNICK' => 'Du wurdest erwähnt in der Nachricht ',

	'QR_BBPOST' => 'Ursprung des Beitrags',
));
