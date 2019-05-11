<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2019 Tatiana5 and LavIgor
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
	'ACP_QUICKREPLY'                       => 'Gyors válasz',
	'ACP_QUICKREPLY_EXPLAIN'               => 'Quick reply settings',
	'ACP_QUICKREPLY_TITLE'                 => 'Quick Reply',
	'ACP_QUICKREPLY_TITLE_EXPLAIN'         => 'Here you can set general and forum based settings for quick reply form itself.<br />NOTE: “Allow quick reply” and “Enable quick reply” are built-in phpBB settings listed here for convenience and completeness purposes. Other settings listed here depend on them.',
	//
	'ACP_QUICKREPLY_QN'                    => 'QuickQuote and QuickNick settings',
	'ACP_QUICKREPLY_QN_EXPLAIN'            => 'QuickQuote and QuickNick settings',
	'ACP_QUICKREPLY_QN_TITLE'              => 'Quick Reply',
	'ACP_QUICKREPLY_QN_TITLE_EXPLAIN'      => 'Here you can set QuickQuote and QuickNick settings.<br />NOTE: these settings have no effect in forums where quick reply is disabled or if quick reply is disallowed.',
	//
	'ACP_QUICKREPLY_PLUGINS'               => 'Additional settings',
	'ACP_QUICKREPLY_PLUGINS_EXPLAIN'       => 'Additional settings',
	'ACP_QUICKREPLY_PLUGINS_TITLE'         => 'Quick Reply',
	'ACP_QUICKREPLY_PLUGINS_TITLE_EXPLAIN' => 'Here you can set the settings for special features included in QuickReply extension.<br />NOTE: these settings work regardless of whether quick reply is enabled in a certain forum.',
	//
	'ACP_QR_AJAX_PAGINATION'               => 'Engedélyezze a témákban való navigálást az oldal újratöltése nélkül',
	'ACP_QR_AJAX_PAGINATION_EXPLAIN'       => 'If this setting is enabled, Ajax pagination will be used instead of standard form submissions when users enable the option “Do not refresh quick reply form when navigating the topic”.',
	'ACP_QR_AJAX_SUBMIT'                   => 'Allow Ajax posting',
	'ACP_QR_AJAX_SUBMIT_EXPLAIN'           => 'Allow sending messages without reloading the page.<br />When enabled, forum specific settings will be used to determine whether the Ajax posting is used in individual forums.',
	'ACP_QR_ALLOW_FOR_GUESTS'              => 'Gyors válasz engedélyezése vendégeknek, ha be van állítva',
	'ACP_QR_ATTACH'                        => 'Csatolmányok engedélyezése',
	'ACP_QR_ATTACH_EXPLAIN'                => 'Allow attachments in quick reply form.',
	'ACP_QR_BBCODE'                        => 'Display BBcode buttons',
	'ACP_QR_BBCODE_EXPLAIN'                => 'Enable BBCode buttons in quick reply form.',
	'ACP_QR_CAPSLOCK'                      => 'Enable text case transformations',
	'ACP_QR_COLOUR_NICKNAME'               => 'Szín hozzáadása felhasználónévre hivatkozáskor',
	'ACP_QR_COMMA'                         => 'Vessző hozzáadása felhasználónév után',
	'ACP_QR_COMMA_EXPLAIN'                 => 'Vessző beszúrása a felhasználónév után a "Hivatkozás felhasználónévvel" használatakor',
	'ACP_QR_CTRLENTER'                     => 'Küldés engedélyezése "Ctrl+Enter"-rel',
	'ACP_QR_CTRLENTER_EXPLAIN'             => 'Allow sending a message by clicking “Ctrl+Enter” in quick reply and full reply forms. The tooltip about this functionality will be shown after hovering the cursor over the “Submit” button in quick reply form.',
	'ACP_QR_CTRLENTER_NOTICE'              => 'Enable “Ctrl+Enter” tooltip in quick reply form',
	'ACP_QR_CTRLENTER_NOTICE_EXPLAIN'      => 'The tooltip will be shown after hovering the cursor over the “Submit” button in quick reply form. Disabling this setting does not disable “Ctrl+Enter” functionality.',
	'ACP_QR_ENABLE_AJAX_SUBMIT'            => 'Enable Ajax posting in all forums',
	'ACP_QR_ENABLE_AJAX_SUBMIT_EXPLAIN'    => 'Allow Ajax posting in all forums right away.',
	'ACP_QR_ENABLE_RE'                     => '"Re:" engedélyezése',
	'ACP_QR_ENABLE_RE_EXPLAIN'             => 'Automatically add prefix “Re:” in the “Post subject” in quick reply and full reply forms.',
	'ACP_QR_ENABLE_QUICK_REPLY'            => 'Enable quick reply in all forums',
	'ACP_QR_ENABLE_QUICK_REPLY_EXPLAIN'    => 'Allow quick reply in all forums right away.',
	'ACP_QR_FORM_TYPE'                     => 'Quick reply form type',
	'ACP_QR_FORM_TYPE_EXPLAIN'             => 'If “Fixed with post reloads” option is selected, ability to load posts to the current page using “show next/previous posts” buttons will supplement the standard pagination.', // reserved
	'ACP_QR_FORM_TYPE_FIXED'               => 'Fixed',
	'ACP_QR_FORM_TYPE_SCROLL'              => 'Fixed with post reloads', // reserved
	'ACP_QR_FORM_TYPE_STANDARD'            => 'Standard',
	'ACP_QR_FORUM_AJAX_SUBMIT'             => 'Enable Ajax posting',
	'ACP_QR_FORUM_AJAX_SUBMIT_EXPLAIN'     => 'Allow sending messages without reloading the page.',
	'ACP_QR_FULL_QUOTE'                    => 'Teljes idézetek beszúrása a Gyors válasz űrlapon',
	'ACP_QR_FULL_QUOTE_EXPLAIN'            => 'Cserélje le a "Hozzászólás az előzmény idézésével" gomb alapértelmezett viselkedését',
	'ACP_QR_HIDE_SUBJECT_BOX'              => 'A tárgy mező elrejtése, ha a tárgy módosítása nem engedélyezett',
	'ACP_QR_HIDE_SUBJECT_BOX_EXPLAIN'      => 'Ha egy felhasználónak nincs jogosultsága megváltoztatni a hozzászólás tárgyát, a tárgy mező el lesz rejtve, nem letiltva',
	'ACP_QR_LAST_QUOTE'                    => 'Enable full quotes for last posts in topics',
	'ACP_QR_LAST_QUOTE_EXPLAIN'            => 'Allow full quotes through a standard quote button.<br /><em>Note that quote button will be hidden if this setting is disabled together with the setting for quick quote. This setting overrides user permission for full quotes.</em>',
	'ACP_QR_LEAVE_AS_IS'                   => 'Leave as is',
	'ACP_QR_LEAVE_AS_IS_EXPLAIN'           => '<em>If you select “Leave as is”, forum settings will not be changed.</em>',
	'ACP_QR_LEGEND_AJAX'                   => 'Ajax settings',
	'ACP_QR_LEGEND_DISPLAY'                => 'Display settings',
	'ACP_QR_LEGEND_GENERAL'                => 'General settings',
	'ACP_QR_LEGEND_QUICKNICK'              => 'Quick nick settings',
	'ACP_QR_LEGEND_QUICKQUOTE'             => 'Quick quote settings',
	'ACP_QR_LEGEND_SPECIAL'                => 'Special features',
	'ACP_QR_QUICKNICK'                     => 'Enable quick nick (in the dropdown menu)',
	'ACP_QR_QUICKNICK_EXPLAIN'             => 'Creates a dropdown with a link “Refer by username” that inserts the post author’s username in the quick reply form. That dropdown is triggered by a click on post author’s username and also contains links to user’s profile and “Reply in PM” (when available).<br />If this setting is enabled and the setting “Enable quick nick (under avatar)” is disabled, the user can switch to the version of the link “Refer by username” under avatar in the User Control Panel.',
	'ACP_QR_QUICKNICK_STRING'              => 'Enable quick nick (under avatar)',
	'ACP_QR_QUICKNICK_STRING_EXPLAIN'      => 'Shows a link “Refer by username” in users’ postprofiles that inserts the username in the quick reply form.',
	'ACP_QR_QUICKNICK_PM'                  => 'Include button “Reply in PM” into the dropdown of the function “Refer by username”',
	'ACP_QR_QUICKNICK_REF'                 => 'Speciális címke használata felhasználókra hivatkozáskor',
	'ACP_QR_QUICKNICK_REF_EXPLAIN'         => 'BBCode [ref] will be used instead of [b] in the function “Refer by username”.<br /><em>Note that if this option is disabled, users will only receive notifications about being mentioned only when [ref] tag is inserted in a message manually.</em>',
	'ACP_QR_QUICKQUOTE'                    => 'Enable quick quote popup',
	'ACP_QR_QUICKQUOTE_BUTTON'             => 'Enable quick quote using button',
	'ACP_QR_QUICKQUOTE_BUTTON_EXPLAIN'     => 'Allow quotes through a standard quote button.<br /><em>Note that quote button will be hidden if this setting is disabled and the user does not have the permission to use it for full quote.</em>',
	'ACP_QR_QUICKQUOTE_EXPLAIN'            => 'Idézés engedélyezése felugró menü által, mely egy hozzászólás szövegének kijelölésekor jelenik meg',
	'ACP_QR_QUICKQUOTE_LINK'               => 'Link hozzáadása a hozzászólás szerzőjének profiljára Gyors válasz használatakor',
	'ACP_QR_SCROLL_TIME'                   => 'Görgetés és animálás esemény ideje',
	'ACP_QR_SCROLL_TIME_EXPLAIN'           => 'Finom görgetés ideje milliszekundumban. Az alapértelmezetthez írj be 0-t.',
	'ACP_QR_SHOW_BUTTON_TRANSLIT'          => '"Konvertálás" gomb mutatása',
	'ACP_QR_SHOW_SUBJECTS'                 => 'Hozzászólások tárgyának mutatása a témákban',
	'ACP_QR_SHOW_SUBJECTS_IN_SEARCH'       => 'Hozzászólások tárgyának mutatása a keresési eredményekben',
	'ACP_QR_SMILIES'                       => 'Display smilies',
	'ACP_QR_SMILIES_EXPLAIN'               => 'Allow display of smilies in quick reply form.',
	'ACP_QR_SOURCE_POST'                   => 'Link beszúrása az idézett hozzászólásra annak idézésekor',
));
