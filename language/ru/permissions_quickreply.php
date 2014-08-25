<?php
/**
*
* quickreply [Russian]
*
* @package quickreply
* @copyright (c) 2014 Татьяна5
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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
	'ACP_QUICKREPLY'				=> 'Быстрый ответ',
	'ACP_QUICKREPLY_EXPLAIN'		=> 'Настройки быстрого ответа',

	'ACL_A_QUICKREPLY'			=> 'Может изменять настройки быстрого ответа',
	'ACL_F_QR_CHANGE_SUBJECT'	=> 'Может изменять заголовок сообщения',
));
