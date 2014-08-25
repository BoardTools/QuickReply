<?php
/**
*
* quickreply [Ukrainian]
*
* @package language quickreply
* @copyright (c) 2014 Alex Fryschyn (Sherlock)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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
));
