<?php
/**
*
* quickreply [English]
*
* @package quickreply
* @copyright (c) 2014 William Jacoby (bonelifer)
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
	'ACP_QUICKREPLY_EXPLAIN'		=> 'Quick Reply Settings',

	'ACL_A_QUICKREPLY'			=> 'Can change the settings of the Quick Reply',
	'ACL_F_QR_CHANGE_SUBJECT'	=> 'Can modify the Post subject',
));
