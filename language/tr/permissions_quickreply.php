<?php
/**
*
* quickreply [Turkish - Türkçe]
*
* @package language quickreply
* @copyright (c) 2013 Татьяна5
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
	'ACP_QUICKREPLY'				=> 'Hızlı Cevap',
	'ACP_QUICKREPLY_EXPLAIN'		=> 'Hızlı Cevap Ayarları',

	'ACL_A_QUICKREPLY'			=> 'Hızlı Cevap ayarlarını değiştirebilir',
	'ACL_F_QR_CHANGE_SUBJECT'	=> 'Hızlı Cevap konu başlığını değiştirebilir',
));
