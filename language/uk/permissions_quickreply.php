<?php
/**
*
* quickreply [Ukrainian]
*
* @package quickreply
* @copyright (c) 2014 Татьяна5
* @translation (c) 2014 www.phpbbukraine.net
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
	'ACP_QUICKREPLY'				=> 'Швидка відповідь',
	'ACP_QUICKREPLY_EXPLAIN'		=> 'Налаштування швидкої відповіді',

	'ACL_A_QUICKREPLY'			=> 'Може змінювати налаштування швидкої відповіді',
	'ACL_F_QR_CHANGE_SUBJECT'	=> 'Може змінювати заголовок повідомлення',
	'ACL_M_QR_CHANGE_SUBJECT'	=> 'Може змінювати заголовок повідомлення',
));

?>