<?php
/**
*
* quickreply [Turkish]
*
* @package quickreply
* @copyright (c) 2015 Edip Dincer
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
	'ACP_QUICKREPLY'				=> 'Quick Reply',
	'ACP_QUICKREPLY_EXPLAIN'		=> 'Quick Reply Ayarları',

	'ACL_A_QUICKREPLY'			=> 'Quick Reply (Hızlı Cevap) için ayarları değiştirebilirsiniz',
	'ACL_F_QR_CHANGE_SUBJECT'	=> 'Gönderi konu başlığını değiştirebilirsiniz',
));
