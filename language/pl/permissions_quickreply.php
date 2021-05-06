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
	'ACP_QUICKREPLY'          => 'Quick Reply',
	'ACP_QUICKREPLY_EXPLAIN'  => 'Quick Reply Ustawienia',
	//
	'ACL_A_QUICKREPLY'        => 'Może zmienić ustawienia szybkiej odpowiedzi',
	'ACL_F_QR_CHANGE_SUBJECT' => 'Może modyfikować temat posta',
	'ACL_F_QR_FULL_QUOTE'     => 'Can use full quote in topics<br /><em>It will be suggested to use quick quote if the user does not have this permission and quick quote feature is enabled.</em>',
]);
