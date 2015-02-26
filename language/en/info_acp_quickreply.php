<?php
/**
*
* quickreply [English]
*
* @package language quickreply
* @copyright (c) 2014 William Jacoby (bonelifer)
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
	'ACP_QUICKREPLY'				=> 'Quick Reply',
	'ACP_QUICKREPLY_EXPLAIN'		=> 'Quick Reply Settings',

	'ACP_QR_BBCODE'					=> 'Enable BBcode',
	'ACP_QR_BBCODE_EXPLAIN'			=> 'Enable BBCode buttons in the Quick Reply form',
	'ACP_QR_COMMA'					=> 'Add comma after username',
	'ACP_QR_COMMA_EXPLAIN'			=> 'Automatically add a comma after the username when using “Refer by username”',
	'ACP_QR_CTRLENTER'				=> 'Enable sending with “Ctrl+Enter”',
	'ACP_QR_CTRLENTER_EXPLAIN'		=> 'Allow sending a message by clicking “Ctrl+Enter”',
	'ACP_QR_ENABLE_RE'				=> 'Enable “Re:”',
	'ACP_QR_ENABLE_RE_EXPLAIN'		=> 'Automatically add prefix “Re:” in the “Post subject” in the Quick Reply form',
	'ACP_QR_QUICKNICK'				=> 'Insert Username',
	'ACP_QR_QUICKNICK_EXPLAIN'		=> 'Allow insertion of username in the form of a quick response when you click on the link “Refer by username”',
	'ACP_QR_QUICKQUOTE'				=> 'Enable quick quote',
	'ACP_QR_QUICKQUOTE_EXPLAIN'		=> 'Allow quotes through a “popup” that appears when you select text in a message',
	'ACP_QR_SMILIES'				=> 'Enable Smilies',
	'ACP_QR_SMILIES_EXPLAIN'		=> 'Allow display of smiles in the Quick Reply form',
	'ACP_QR_CAPSLOCK'				=> 'Enable text-to upper / lower case',
	'ACP_QR_SHOW_BUTTON_TRANSLIT'			=> 'Show button “Convert”',
	'ACP_QR_AJAX_SUBMIT'			=> 'Enable ajax-posting',
	'ACP_QR_AJAX_SUBMIT_EXPLAIN'	=> 'Allow sending messages without reloading the page',
	'ACP_QR_SHOW_SUBJECTS'			=> 'Show posts subjects',
	'ACP_QR_SOURCE_POST'			=> 'Add a link to the quoted message when quoting',
	'ACP_QR_ATTACH'					=> 'Allow attachments',
	'ACP_QR_COLOUR_NICKNAME'		=> 'Add colour when refer by username',
));
