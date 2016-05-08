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
	'ACP_QUICKREPLY'                  => 'Gyors válasz',
	'ACP_QUICKREPLY_EXPLAIN'          => 'Gyors válasz beállításai',
	//
	'ACP_QR_AJAX_PAGINATION'          => 'Engedélyezze a témákban való navigálást az oldal újratöltése nélkül',
	'ACP_QR_AJAX_PAGINATION_EXPLAIN'  => 'A felhasználók használhatják a "Ne frissítse a Gyors válasz űrlapot a témában való navigálás során" beállítást',
	'ACP_QR_AJAX_SUBMIT'              => 'Engedélyezze az Ajax hozzászólást',
	'ACP_QR_AJAX_SUBMIT_EXPLAIN'      => 'Engedélyezze az üzenetek küldését az oldal újratöltése nélkül',
	'ACP_QR_ALLOW_FOR_GUESTS'         => 'Gyors válasz engedélyezése vendégeknek, ha be van állítva',
	'ACP_QR_ATTACH'                   => 'Csatolmányok engedélyezése',
	'ACP_QR_BBCODE'                   => 'BBcode engedélyezése',
	'ACP_QR_BBCODE_EXPLAIN'           => 'BBcode gombok engedélyezése a Gyors válasz űrlapon',
	'ACP_QR_CAPSLOCK'                 => 'Kis/nagybetű váltás engedélyezése',
	'ACP_QR_COLOUR_NICKNAME'          => 'Szín hozzáadása felhasználónévre hivatkozáskor',
	'ACP_QR_COMMA'                    => 'Vessző hozzáadása felhasználónév után',
	'ACP_QR_COMMA_EXPLAIN'            => 'Vessző beszúrása a felhasználónév után a "Hivatkozás felhasználónévvel" használatakor',
	'ACP_QR_CTRLENTER'                => 'Küldés engedélyezése "Ctrl+Enter"-rel',
	'ACP_QR_CTRLENTER_EXPLAIN'        => 'Üzenet elküldésének engedélyezése "Ctrl+Enter"-rel',
	'ACP_QR_ENABLE_RE'                => '"Re:" engedélyezése',
	'ACP_QR_ENABLE_RE_EXPLAIN'        => 'A "Re:" előtag automatikus hozzáadása a "Hozzászólás tárgya" mezőben a Gyors válasz űrlapon',
	'ACP_QR_FULL_QUOTE'               => 'Teljes idézetek beszúrása a Gyors válasz űrlapon',
	'ACP_QR_FULL_QUOTE_EXPLAIN'       => 'Cserélje le a "Hozzászólás az előzmény idézésével" gomb alapértelmezett viselkedését',
	'ACP_QR_HIDE_SUBJECT_BOX'         => 'A tárgy mező elrejtése, ha a tárgy módosítása nem engedélyezett',
	'ACP_QR_HIDE_SUBJECT_BOX_EXPLAIN' => 'Ha egy felhasználónak nincs jogosultsága megváltoztatni a hozzászólás tárgyát, a tárgy mező el lesz rejtve, nem letiltva',
	'ACP_QR_QUICKNICK'                => 'Felhasználónév beszúrása',
	'ACP_QR_QUICKNICK_EXPLAIN'        => 'Felhasználónév beszúrása a Gyors válasz űrlapon a "Hivatkozás felhasználónévvel" linkre kattintáskor',
	'ACP_QR_QUICKNICK_PM'             => 'A "Hivatkozás felhasználónévvel" legördülő menüjében a "Válasz PÜ-ben" lehetőség szerepeltetése',
	'ACP_QR_QUICKNICK_REF'            => 'Speciális címke használata felhasználókra hivatkozáskor',
	'ACP_QR_QUICKNICK_REF_EXPLAIN'    => 'A [ref] BBcode használata [b] helyett a "Hivatkozás felhasználónévvel" funkcióban',
	'ACP_QR_QUICKQUOTE'               => 'Gyors válasz engedélyezése',
	'ACP_QR_QUICKQUOTE_EXPLAIN'       => 'Idézés engedélyezése felugró menü által, mely egy hozzászólás szövegének kijelölésekor jelenik meg',
	'ACP_QR_QUICKQUOTE_LINK'          => 'Link hozzáadása a hozzászólás szerzőjének profiljára Gyors válasz használatakor',
	'ACP_QR_SCROLL_TIME'              => 'Görgetés és animálás esemény ideje',
	'ACP_QR_SCROLL_TIME_EXPLAIN'      => 'Finom görgetés ideje milliszekundumban. Az alapértelmezetthez írj be 0-t.',
	'ACP_QR_SHOW_BUTTON_TRANSLIT'     => '"Konvertálás" gomb mutatása',
	'ACP_QR_SHOW_SUBJECTS'            => 'Hozzászólások tárgyának mutatása a témákban',
	'ACP_QR_SHOW_SUBJECTS_IN_SEARCH'  => 'Hozzászólások tárgyának mutatása a keresési eredményekben',
	'ACP_QR_SMILIES'                  => 'Emotikonok engedélyezése',
	'ACP_QR_SMILIES_EXPLAIN'          => 'Emotikonok mutatása a Gyors válasz űrlapon',
	'ACP_QR_SOURCE_POST'              => 'Link beszúrása az idézett hozzászólásra annak idézésekor',
));
