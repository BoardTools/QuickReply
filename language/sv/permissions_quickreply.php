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
	'ACP_QUICKREPLY'          => 'Snabbsvar',
	'ACP_QUICKREPLY_EXPLAIN'  => 'Snabbsvar - Inställningar',
	//
	'ACL_A_QUICKREPLY'        => 'Inställningarna för “Snabbsvar” kan ändras',
	'ACL_F_QR_CHANGE_SUBJECT' => '“Ämnesrubriken” kan ändras',
	'ACL_F_QR_FULL_QUOTE'     => 'Can use full quote in topics<br /><em>It will be suggested to use quick quote if the user does not have this permission and quick quote feature is enabled.</em>',
]);
