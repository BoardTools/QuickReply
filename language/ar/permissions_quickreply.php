<?php
/**
*
* quickreply [Arabic]
*
* @package quickreply
* @copyright (c) 2014 William Jacoby (bonelifer)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
* Translated By : Bassel Taha Alhitary - www.alhitary.net
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
	'ACP_QUICKREPLY'				=> 'الرد السريع',
	'ACP_QUICKREPLY_EXPLAIN'		=> 'الإعدادات',

	'ACL_A_QUICKREPLY'			=> 'يستطيع تعديل إعدادات الرد السريع',
	'ACL_F_QR_CHANGE_SUBJECT'	=> 'يستطيع تعديل عنوان المُشاركة',
));
