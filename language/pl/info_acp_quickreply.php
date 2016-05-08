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
	'ACP_QUICKREPLY'                  => 'Szybka odpowiedź',
	'ACP_QUICKREPLY_EXPLAIN'          => 'Ustawienia szybkiej odpowiedzi',
	//
	'ACP_QR_AJAX_PAGINATION'          => 'Pozwól na przeglądanie tematu bez przeładowania strony',
	'ACP_QR_AJAX_PAGINATION_EXPLAIN'  => 'Pozwól użytkownikom na ustawienie “Pozwól na przeglądanie tematu bez przeładowania strony”.',
	'ACP_QR_AJAX_SUBMIT'              => 'Włącz Ajax przy pisaniu posta',
	'ACP_QR_AJAX_SUBMIT_EXPLAIN'      => 'Pozwól na wysyłanie wiadomości bez przeładowania strony.',
	'ACP_QR_ALLOW_FOR_GUESTS'         => 'Pozwól gosciom na uzywanie szybkiej odpowiedz (jesli włączone)',
	'ACP_QR_ATTACH'                   => 'Włącz załączniki',
	'ACP_QR_BBCODE'                   => 'Włącz BBcode',
	'ACP_QR_BBCODE_EXPLAIN'           => 'Włącz przyciski BBCode w formularzu szybkiej odpowiedzi.',
	'ACP_QR_CAPSLOCK'                 => 'Włącz zmiany znaków tekstu wielkie/małe znaki',
	'ACP_QR_COLOUR_NICKNAME'          => 'Koloruj odnośnik do nazwy użytkownika',
	'ACP_QR_COMMA'                    => 'Dodawaj kprzecinek po nazwie użytkownika',
	'ACP_QR_COMMA_EXPLAIN'            => 'Automatycznie dodawaj przecinek po nazwie użytkownika gdy korzysta z  “Patrz po nazwie użytkownika”.',
	'ACP_QR_CTRLENTER'                => 'Włacz wysyłanie przez “Ctrl+Enter”',
	'ACP_QR_CTRLENTER_EXPLAIN'        => 'Pozwala na wysyłanie za pomoca kombinacji “Ctrl+Enter”.',
	'ACP_QR_ENABLE_RE'                => 'Włącz “Re:”',
	'ACP_QR_ENABLE_RE_EXPLAIN'        => 'Automatycznie dodaje prefix “Re:” e “Temacie wiadomości” w formularzu szybkiej odpowiedzi.',
	'ACP_QR_FULL_QUOTE'               => 'Wprowadzaj pełne cyaty w formularzu szybkiej odpowiedzi',
	'ACP_QR_FULL_QUOTE_EXPLAIN'       => 'Zastępuje standardowa funkcję “Odpowiedz z cytatem”.',
	'ACP_QR_HIDE_SUBJECT_BOX'         => 'Ukryj formularz tematu posta jeśli modyfykacja tematu jest wyłaczona',
	'ACP_QR_HIDE_SUBJECT_BOX_EXPLAIN' => 'Jeśli użytkownik nie ma uprawnień do modyfikowania Tematu postu, pole formularza będą ukryte zamiast być wyłączone',
	'ACP_QR_QUICKNICK'                => 'Wpisz nazwe użytkownika',
	'ACP_QR_QUICKNICK_EXPLAIN'        => 'Pozwól na wstawienie nazwy użytkownika w formularzu szybkiej odpowiedzi po kliknięciu na link "odnies się poprzez podanie nazwy użytkownika".',
	'ACP_QR_QUICKNICK_PM'             => 'Wprowadź przycisk «Odpowiedz przez PW» w funkcji “odnieś się poprzez podanie nazwy użytkownika”',
	'ACP_QR_QUICKNICK_REF'            => 'Włącz specjalny znacznik odniesienia sie do użytkownika',
	'ACP_QR_QUICKNICK_REF_EXPLAIN'    => 'BBCode [ref] bedzie używany razem z  [b] w funkcji “odnieś się poprzez podanie nazwy użytkownika”.',
	'ACP_QR_QUICKQUOTE'               => 'Włącz szybkie cytowanie',
	'ACP_QR_QUICKQUOTE_EXPLAIN'       => 'Pozwól na cytowanie przez “popup” gdy zaznaczysz tekwst w wiadomości.',
	'ACP_QR_QUICKQUOTE_LINK'          => 'Podaj link do profilu uzytkownika, gdy uzywa szybkiego cytowania.',
	'ACP_QR_SCROLL_TIME'              => 'Czas pojedynczego przewinięcia i animacji',
	'ACP_QR_SCROLL_TIME_EXPLAIN'      => 'Czas w milisekundach dla miekkiego przewijania. Ustawienie na 0 przywraca normalne przewijanie.',
	'ACP_QR_SHOW_BUTTON_TRANSLIT'     => 'Pokaz przycisk “Konwertuj”',
	'ACP_QR_SHOW_SUBJECTS'            => 'Pokaż temat posta',
	'ACP_QR_SHOW_SUBJECTS_IN_SEARCH'  => 'Pokazuj temat posta w wynikach wyszukiwania',
	'ACP_QR_SMILIES'                  => 'Włącz emotikony',
	'ACP_QR_SMILIES_EXPLAIN'          => 'Pozwól na wyśiwetlanie emotikonów w oknie szybkiej odpowiedzi.',
	'ACP_QR_SOURCE_POST'              => 'Dodaj link do cytowanego posta podczas cytowania',
));
