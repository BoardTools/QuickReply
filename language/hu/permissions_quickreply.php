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
	'ACP_QUICKREPLY'          => 'Gyors válasz',
	'ACP_QUICKREPLY_EXPLAIN'  => 'Gyors válasz beállításai',
	//
	'ACL_A_QUICKREPLY'        => 'Megváltoztathatja Gyors válasz beállításait',
	'ACL_F_QR_CHANGE_SUBJECT' => 'Módosíthatja a hozzászólás tárgyát',
	'ACL_F_QR_FULL_QUOTE'     => 'Can use full quote in topics<br /><em>It will be suggested to use quick quote if the user does not have this permission and quick quote feature is enabled.</em>',
));
