<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
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
	'ACP_QUICKREPLY'                   => 'Quick Reply',
	'ACP_QUICKREPLY_EXPLAIN'           => 'Quick Reply Settings',
	//
	'ACP_QUICKREPLY_QN'                => 'Quick Reply',
	'ACP_QUICKREPLY_QN_EXPLAIN'        => 'QuickQuote and Refer by username Settings',
	//
	'ACP_QUICKREPLY_PLUGINS'           => 'Quick Reply',
	'ACP_QUICKREPLY_PLUGINS_EXPLAIN'   => 'Additional Settings',
	//
	'ACP_QR_ALLOW_AJAX_SUBMIT'         => 'Submit and enable Ajax posting in all forums',
	'ACP_QR_AJAX_PAGINATION'           => 'Allow navigating topics without reloading the page',
	'ACP_QR_AJAX_PAGINATION_EXPLAIN'   => 'Allow users to enable the setting “Do not refresh quick reply form when navigating the topic”.',
	'ACP_QR_AJAX_SUBMIT'               => 'Enable Ajax posting',
	'ACP_QR_AJAX_SUBMIT_EXPLAIN'       => 'Allow sending messages without reloading the page.',
	'ACP_QR_ALLOW_FOR_GUESTS'          => 'Allow quick reply for guests if it is enabled',
	'ACP_QR_ATTACH'                    => 'Allow attachments',
	'ACP_QR_BBCODE'                    => 'Enable BBcode',
	'ACP_QR_BBCODE_EXPLAIN'            => 'Enable BBCode buttons in the Quick Reply form.',
	'ACP_QR_CAPSLOCK'                  => 'Enable text-to upper / lower case',
	'ACP_QR_COLOUR_NICKNAME'           => 'Add colour when refer by username',
	'ACP_QR_COMMA'                     => 'Add comma after username',
	'ACP_QR_COMMA_EXPLAIN'             => 'Automatically add a comma after the username when using “Refer by username”.',
	'ACP_QR_CTRLENTER'                 => 'Enable sending with “Ctrl+Enter”',
	'ACP_QR_CTRLENTER_EXPLAIN'         => 'Allow sending a message by clicking “Ctrl+Enter”.',
	'ACP_QR_ENABLE_RE'                 => 'Enable “Re:”',
	'ACP_QR_ENABLE_RE_EXPLAIN'         => 'Automatically add prefix “Re:” in the “Post subject” in the Quick Reply form.',
	'ACP_QR_FORM_TYPE'                 => 'Quick reply form type',
	'ACP_QR_FORM_TYPE_EXPLAIN'         => 'If “Fixed with post reloads” option is selected, ability to load posts to the current page using “show next/previous posts” buttons will supplement the standard pagination.',
	'ACP_QR_FORM_TYPE_FIXED'           => 'Fixed',
	'ACP_QR_FORM_TYPE_SCROLL'          => 'Fixed with post reloads',
	'ACP_QR_FORM_TYPE_STANDARD'        => 'Standard',
	'ACP_QR_FULL_QUOTE'                => 'Insert full quotes into the quick reply form',
	'ACP_QR_FULL_QUOTE_EXPLAIN'        => 'Replace the standard behaviour of the “Reply with quote” button.',
	'ACP_QR_HIDE_SUBJECT_BOX'          => 'Hide subject box if subject modification is disabled',
	'ACP_QR_HIDE_SUBJECT_BOX_EXPLAIN'  => 'If a user does not have a permission to modify the post subject, subject form field will be hidden instead of being disabled.',
	'ACP_QR_QUICKNICK'                 => 'Enable quick nick (in the dropdown menu)',
	'ACP_QR_QUICKNICK_EXPLAIN'         => 'Allow insertion of the username in the form of a quick reply when you click on the link “Refer by username”.<br />If enabled, the user can switch in the User Panel to a version of the link “Refer by username” under avatar.',
	'ACP_QR_QUICKNICK_STRING'          => 'Enable quick nick (under avatar)',
	'ACP_QR_QUICKNICK_PM'              => 'Include button «Reply in PM» into the dropdown of the function “Refer by username”',
	'ACP_QR_QUICKNICK_REF'             => 'Enable special tag for user reference',
	'ACP_QR_QUICKNICK_REF_EXPLAIN'     => 'BBCode [ref] will be used instead of [b] in the function “Refer by username”.<br />Note that if this option is disabled, users will only receive notifications about being mentioned only when [ref] tag is inserted in a message manually.',
	'ACP_QR_QUICKQUOTE'                => 'Enable quick quote popup',
	'ACP_QR_QUICKQUOTE_BUTTON'         => 'Enable quick quote using button',
	'ACP_QR_QUICKQUOTE_BUTTON_EXPLAIN' => 'Allow quotes through a standard quote button. Note that quote button will be hidden if this setting is disabled and the user does not have the permission to use it for full quote.',
	'ACP_QR_QUICKQUOTE_EXPLAIN'        => 'Allow quotes through a “popup” that appears when you select text in a message.',
	'ACP_QR_QUICKQUOTE_LINK'           => 'Add a link to the profile of the post author when using quick quote',
	'ACP_QR_LAST_QUOTE'                => 'Enable full quotes for last posts in topics',
	'ACP_QR_LAST_QUOTE_EXPLAIN'        => 'Allow full quotes through a standard quote button. Note that quote button will be hidden if this setting is disabled together with the setting for quick quote. This setting overrides user permission for full quotes.',
	'ACP_QR_SCROLL_TIME'               => 'Time for a single scroll and animation event',
	'ACP_QR_SCROLL_TIME_EXPLAIN'       => 'Time in milliseconds for the soft scroll feature. Enter 0 for the standard scroll.',
	'ACP_QR_SHOW_BUTTON_TRANSLIT'      => 'Show button “Convert”',
	'ACP_QR_SHOW_SUBJECTS'             => 'Show posts subjects in topics',
	'ACP_QR_SHOW_SUBJECTS_IN_SEARCH'   => 'Show posts subjects in search results',
	'ACP_QR_SMILIES'                   => 'Enable Smilies',
	'ACP_QR_SMILIES_EXPLAIN'           => 'Allow display of smiles in the Quick Reply form.',
	'ACP_QR_SOURCE_POST'               => 'Add a link to the quoted message when quoting',
));
