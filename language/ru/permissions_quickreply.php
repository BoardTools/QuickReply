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
	'ACP_QUICKREPLY'          => 'Быстрый ответ',
	'ACP_QUICKREPLY_EXPLAIN'  => 'Настройки быстрого ответа',
	//
	'ACL_A_QUICKREPLY'        => 'Может изменять настройки быстрого ответа',
	'ACL_F_QR_CHANGE_SUBJECT' => 'Может изменять заголовок сообщения',
	'ACL_F_QR_FULL_QUOTE'     => 'Может использовать полную цитату в темах<br /><em>При отсутствии данного права пользователю будет показано сообщение с предложением использовать быструю цитату, если эта возможность включена.</em>',
]);
