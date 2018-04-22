<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2017 Tatiana5 and LavIgor
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
	'ACP_QUICKREPLY'                       => 'Schnellantwort',
	'ACP_QUICKREPLY_EXPLAIN'               => 'Schnellantwort Einstellungen',
	'ACP_QUICKREPLY_TITLE'                 => 'Schnellantwort',
	'ACP_QUICKREPLY_TITLE_EXPLAIN'         => 'Hier kannst du für die Schnellantwort selbst generelle und forenbasierte Einstellungen vornehmen.<br>HINWEIS: „Erlaube Schnellantwort“ und „Aktiviere Schnellantwort“ sind Einstellungen für die in phpBB eingebaute Schnellantwort , hier aus Gründen der Bequemlichkeit und Vollständigkeit aufgeführt. Andere hier aufgeführte Einstellungen hängen davon ab.',
	//
	'ACP_QUICKREPLY_QN'                    => 'Schnellzitat and QuickNick Einstellungen',
	'ACP_QUICKREPLY_QN_EXPLAIN'            => 'Einstellungen für Schnellzitat und QuickNick',
	'ACP_QUICKREPLY_QN_TITLE'              => 'Schnellantwort',
	'ACP_QUICKREPLY_QN_TITLE_EXPLAIN'      => 'Hier kannst du Einstellungen für Schnellzitat und QuickNick vornehmen.<br>HINWEIS: diese Einstellungen haben keinen Effekt in Foren, wo die Schnellantwort deaktiviert oder nicht erlaubt ist.',
	//
	'ACP_QUICKREPLY_PLUGINS'               => 'Zusätzliche Einstellungen',
	'ACP_QUICKREPLY_PLUGINS_EXPLAIN'       => 'Hier können zusätzliche Einstellungen vorgenommen werden.',
	'ACP_QUICKREPLY_PLUGINS_TITLE'         => 'Schnellantwort',
	'ACP_QUICKREPLY_PLUGINS_TITLE_EXPLAIN' => 'Hier kannst du Einstellungen vornehmen für spezielle Features der QuickReply-Erweiterung.<br>HINWEIS: Diese Einstellungen funktionieren unabhängig davon, ob die Schnellantwort in einem bestimmten Forum aktiviert ist.',
	//
	'ACP_QR_AJAX_PAGINATION'               => 'Erlaube das Navigieren in Themen, ohne die Seite neu zu laden',
	'ACP_QR_AJAX_PAGINATION_EXPLAIN'       => 'Wenn diese Einstellung aktiviert ist, wird die Ajax-Seitennavigation anstelle von Standardformularübermittlungen verwendet, wenn Benutzer die Option „Schnellantwortformular beim Navigieren zum Thema nicht aktualisieren“ aktivieren.',
	'ACP_QR_AJAX_SUBMIT'                   => 'Erlaube Ajax-Posting',
	'ACP_QR_AJAX_SUBMIT_EXPLAIN'           => 'Senden von Nachrichten zulassen, ohne die Seite erneut zu laden. <br> Wenn diese Option aktiviert ist, werden forumspezifische Einstellungen verwendet, um zu bestimmen, ob der Ajax-Beitrag in einzelnen Foren verwendet wird.',
	'ACP_QR_ALLOW_FOR_GUESTS'              => 'Erlaube Gästen die Schnellantwort zu benutzen, wenn sie aktiviert ist',
	'ACP_QR_ATTACH'                        => 'Erlaube Dateianhänge',
	'ACP_QR_ATTACH_EXPLAIN'                => 'Erlaube Dateianhänge im Schnellantwort-Formular',
	'ACP_QR_BBCODE'                        => 'Zeige BBcode Schaltflächen',
	'ACP_QR_BBCODE_EXPLAIN'                => 'Erlaube BBCode Schaltflächen im Schnellantwort-Formular',
	'ACP_QR_CAPSLOCK'                      => 'Aktiviere Texttransformation auf Großbuchstaben',
	'ACP_QR_COLOUR_NICKNAME'               => 'Füge die Farbe hinzu, wenn auf einen Benutzer verwiesen wird',
	'ACP_QR_COMMA'                         => 'Füge ein Komma nach einem Benutzernamen ein',
	'ACP_QR_COMMA_EXPLAIN'                 => 'Füge automatisch ein Komma nach einem Benutzernamen ein, wenn die Funktion „Verweis auf Benutzernamen“ benutzt wird',
	'ACP_QR_CTRLENTER_NOTICE'                     => 'Aktiviere den Tooltip „STRG+ENTER“ im Schnellantwort-Formular',
	'ACP_QR_CTRLENTER_NOTICE_EXPLAIN'             => 'Der Tooltip wird angezeigt, nachdem der Mauszeiger über den „Senden“-Button im Schnellantwortformular bewegt wurde. Durch das Deaktivieren dieser Einstellung wird die Funktion „STRG+ENTER“ nicht deaktiviert.',
	'ACP_QR_ENABLE_AJAX_SUBMIT'            => 'Aktiviere Ajax-Posting in allen Foren',
	'ACP_QR_ENABLE_AJAX_SUBMIT_EXPLAIN'    => 'Erlaubt sofort Ajax-Beiträge in allen Foren zu senden.',
	'ACP_QR_ENABLE_RE'                     => 'Aktiviere "Re:" ',
	'ACP_QR_ENABLE_RE_EXPLAIN'             => 'Füge das Prefix „Re:“ automatisch dem „Betreff“ der Schnellantwort und der vollständigen Antwortformulare hinzu.',
	'ACP_QR_ENABLE_QUICK_REPLY'            => 'Erlaube die Schnellantwort in allen Foren',
	'ACP_QR_ENABLE_QUICK_REPLY_EXPLAIN'    => 'Erlaubt sofort Schnellantwort in allen Foren.',
	'ACP_QR_FORM_TYPE'                     => 'Schnellantwort-Formulartyp',
	'ACP_QR_FORM_TYPE_EXPLAIN'             => 'Wenn die Option „Feststehend mit nachladen von Beiträgen“ ausgewählt wird, ergänzt die Möglichkeit, Beiträge unter Verwendung von „Zeige nächsten/vorherigen Beitrag“ in die aktuelle Seite zu laden, die Seitennavigation.', // reserved
	'ACP_QR_FORM_TYPE_FIXED'               => 'Fixed',
	'ACP_QR_FORM_TYPE_SCROLL'              => 'Feststehend mit nachladen von Beiträgen', // reserved
	'ACP_QR_FORM_TYPE_STANDARD'            => 'Standard',
	'ACP_QR_FORUM_AJAX_SUBMIT'             => 'Aktiviere Ajax-Posting',
	'ACP_QR_FORUM_AJAX_SUBMIT_EXPLAIN'     => 'Erlaubt das Senden von Nachrichten, ohne die Seite neu zu laden.',
	'ACP_QR_FULL_QUOTE'                    => 'Füge Komplettzitate in das Schnellantwort Formular ein',
	'ACP_QR_FULL_QUOTE_EXPLAIN'            => 'Ersetzt das Standardverhalten von des „Antwort mit Zitat“ Buttons.',
	'ACP_QR_HIDE_SUBJECT_BOX'              => 'Verstecke die Betreffeingabe, wenn Änderungen deaktiviert sind',
	'ACP_QR_HIDE_SUBJECT_BOX_EXPLAIN'      => 'Wenn ein Benutzer keine Berechtigung hat den Titel eines Beitrags zu ändern wird das Formular versteckt anstatt es nur zu deaktivieren',
	'ACP_QR_LAST_QUOTE'                    => 'Aktiviere Komplettzitate für letzte Beiträge von Themen',
	'ACP_QR_LAST_QUOTE_EXPLAIN'            => 'Erlaubt Komplettzitate über einen Standard-Zitat-Button.<br><em>Beachte, dass der Zitat-Button versteckt wird, wenn diese Einstellung zusammen mit der Einstellung für das Schnellzitat deaktiviert ist. Diese Einstellung überschreibt die Benutzerberechtigung für Komplettzitate.</em>',
	'ACP_QR_LEAVE_AS_IS'                   => 'Lass es, wie es ist',
	'ACP_QR_LEAVE_AS_IS_EXPLAIN'           => '<em>Wenn du „Lass es, wie es ist“ auswählst, werden die Forumseinstellungen nicht geändert.</em>',
	'ACP_QR_LEGEND_AJAX'                   => 'Ajax-Einstellungen',
	'ACP_QR_LEGEND_DISPLAY'                => 'Zeige Einstellungen',
	'ACP_QR_LEGEND_GENERAL'                => 'Allgemeine Einstellungen',
	'ACP_QR_LEGEND_QUICKNICK'              => 'QuickNick-Einstellungen',
	'ACP_QR_LEGEND_QUICKQUOTE'             => 'Schnellzitat-Einstellungen',
	'ACP_QR_LEGEND_SPECIAL'                => 'Spezial-Features',
	'ACP_QR_QUICKNICK'                     => 'Aktiviere QuickNick (im Dropdown-Menü)',
	'ACP_QR_QUICKNICK_EXPLAIN'             => 'Erstellt ein Drop-down mit einem Link „Auf Benutzernamen verweisen“, der den Benutzernamen des Beitragsautors in das Schnellantwortformular einfügt. Dieses Drop-down wird durch einen Klick auf den Benutzernamen des Beitragsautors ausgelöst, und enthält auch Links zum Benutzerprofil, und „Antworten in PN“ (wenn verfügbar). <br> Wenn diese Einstellung aktiviert ist, und die Einstellung „QuickNick aktivieren (unter Avatar)“ ist deaktiviert, kann der Benutzer auf die Version des Links „Auf Benutzernamen verweisen“ unter Avatar im Benutzer-Kontrollbereich wechseln.',
	'ACP_QR_QUICKNICK_STRING'              => 'Aktiviere QuickNick (unter Avatar)',
	'ACP_QR_QUICKNICK_STRING_EXPLAIN'      => 'Zeigt einen Link „Auf Benutzernamen verweisen“ in den Beitragsprofilen des Benutzers, welches den Benutzernamen in ein Schnellantwortformular einfügt.',
	'ACP_QR_QUICKNICK_PM'                  => 'Button „Antworten in PN“ in das Drop-down der Funktion „Auf Benutzernamen verweisen“ einfügen',
	'ACP_QR_QUICKNICK_REF'                 => 'Wähle ein Sonderzeichen für den Verweis auf einen Benutzer',
	'ACP_QR_QUICKNICK_REF_EXPLAIN'         => 'Der BBCode [ref] wird anstelle von [b] in der Funktion „Auf Benutzernamen verweisen“ verwendet.<br><em>Beachte, wenn diese Option deaktiviert ist, dass Benutzer nur dann Nachrichten über ihre Erwähnung in einem Beitrag erhalten, wenn [ref]  manuell in eine Nachricht eingefügt wurde.</em>',
	'ACP_QR_QUICKQUOTE'                    => 'Aktiviere Schnellzitat-Popup',
	'ACP_QR_QUICKQUOTE_BUTTON'             => 'Aktiviere Schnellzitat mit Button',
	'ACP_QR_QUICKQUOTE_BUTTON_EXPLAIN'     => 'Erlaubt Zitate über einen Standard-Zitatbutton.<br><em>Beachte, dass der Zitatbutton ausgeblendet wird, wenn diese Einstellung deaktiviert ist, und der Benutzer nicht die Berechtigung hat, sie für Komplettzitate zu benutzen.</em>',
	'ACP_QR_QUICKQUOTE_EXPLAIN'            => 'Erlaubt Zitate über ein „Popup“, welches erscheint, wenn Text in einer Nachricht markiert wird.',
	'ACP_QR_SCROLL_TIME'                   => 'Zeit für ein einziges Scrollen oder eine Animation',
	'ACP_QR_SCROLL_TIME_EXPLAIN'           => 'Zeit in Millisekunden für die Scrollen Funktion. Gebe 0 für die Standard Funktion ein.',
	'ACP_QR_SHOW_BUTTON_TRANSLIT'          => 'Zeige den Button „Konvertiere“',
	'ACP_QR_SHOW_SUBJECTS'                 => 'Zeige Beitragstitel in Themen',
	'ACP_QR_SHOW_SUBJECTS_IN_SEARCH'       => 'Zeige Beitragstitel in Suchresultaten',
	'ACP_QR_SMILIES'                       => 'Zeige Smilies an',
	'ACP_QR_SMILIES_EXPLAIN'               => 'Erlaubt die Anzeige von Smilies im Schnellantwortformular.',
));
