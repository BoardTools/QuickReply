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
	'QR_ATTACH_NOTICE'                 => 'Diese Antwort enthält mindestens einen Dateianhang.',
	'QR_BBCODE'                        => 'BBCode',
	'QR_CANCEL_SUBMISSION'             => 'Übermittlung abbrechen',
	'QR_CTRL_ENTER'                    => 'Du kannst deine Antwort auch senden, indem du gleichzeitig STRG und die Eingabetaste auf deiner Tastatur drückst.',
	'QR_FORM_HIDE'                     => 'Schnellantwortformular minimieren',
	'QR_FULLSCREEN'                    => 'Vollbildeditor',
	'QR_FULLSCREEN_EXIT'               => 'Verlasse Vollbildmodus',
	'QR_INSERT_TEXT'                   => 'Füge als Zitat in das Schnellantwort Formular ein',
	'QR_QUICKQUOTE'                    => 'Schnellzitat',
	'QR_QUICKQUOTE_TITLE'              => 'Antwort mit Schnellzitat',
	'QR_LOADING'                       => 'Lade',
	'QR_LOADING_ATTACHMENTS'           => 'Warte auf Fertigstellung des Hochladevorgangs für den Dateianhang…',
	'QR_LOADING_NEW_FORM_TOKEN'        => 'Das Formular-Token war veraltet und wurde aktualisiert. <br> Sende das Formular erneut…',
	'QR_LOADING_NEW_POSTS'             => 'Mindestens ein neuer Beitrag wurde dem Thema hinzugefügt.<br>Deine Antwort wurde nicht übermittelt, weil du sie vielleicht aktualisieren möchtest.<br>Rufe neue Beiträge ab…',
	'QR_LOADING_PREVIEW'               => 'Rufe Vorschau ab…',
	'QR_LOADING_SUBMITTED'             => 'Dein Beitrag wurde erfolgreich übermittelt.<br>Rufe Ergebnis ab…',
	'QR_LOADING_SUBMITTING'            => 'Übermittle deine Antwort…',
	'QR_LOADING_WAIT'                  => 'Warte auf Antwort des Servers…',
	'QR_MORE'                          => 'Weitere Aktionen',
	'QR_NO_FULL_QUOTE'                 => 'Bitte wähle einen Teil der Nachricht aus, um ihn zitieren zu können.',
	'QR_PREVIEW_CLOSE'                 => 'Schließe den Vorschau-Block',
	'QR_PROFILE'                       => 'Gehe zum Benutzerprofil',
	'QR_QUICKNICK'                     => 'Verweise auf Benutzernamen',
	'QR_QUICKNICK_TITLE'               => 'Füge Benutzernamen in das Schnellantwort-Formular ein',
	'QR_REPLY_IN_PM'                   => 'Antworte in privater Nachricht',
	'QR_TYPE_REPLY'                    => 'Gib deine Antwort hier ein…',
	'QR_WARN_BEFORE_UNLOAD'            => 'Deine eingegebene Antwort wurde nicht übermittelt und könnte verloren gegangen sein!',
	// begin mod Translit
	'QR_TRANSLIT_TEXT'                 => 'Translit',
	'QR_TRANSLIT_TEXT_TO_RU'           => 'ins russische', // can be changed to your language here and below
	'QR_TRANSLIT_TEXT_TOOLTIP'         => 'Für Schnellansicht in Russisch klicke den Knopf',
	'QR_TRANSLIT_FROM'                 => 'je,jo,ayu,ay,aj,oju,oje,oja,oj,uj,yi,ya,ja,ju,yu,ja,y,zh,i\',shch,sch,ch,sh,ea,a,b,v,w,g,d,e,z,i,k,l,m,n,o,p,r,s,t,u,f,x,c,\'e,\',`,j,h', // language specific adaptation required (do not use spaces or line breaks), use commas as separators here and below
	'QR_TRANSLIT_TO'                   => 'э,ё,aю,ай,ай,ою,ое,оя,ой,уй,ый,я,я,ю,ю,я,ы,ж,й,щ,щ,ч,ш,э,а,б,в,в,г,д,е,з,и,к,л,м,н,о,п,р,с,т,у,ф,х,ц,э,ь,ъ,й,х',
	'QR_TRANSLIT_FROM_CAPS'            => 'Yo,Jo,Ey,Je,Ay,Oy,Oj,Uy,Uj,Ya,Ja,Ju,Yu,Ja,Y,Zh,I\',Sch,Ch,Sh,Ea,Tz,A,B,V,W,G,D,E,Z,I,K,L,M,N,O,P,R,S,T,U,F,X,C,EA,J,H',
	'QR_TRANSLIT_TO_CAPS'              => 'Ё,Ё,Ей,Э,Ай,Ой,Ой,Уй,Уй,Я,Я,Ю,Ю,Я,Ы,Ж,Й,Щ,Ч,Ш,Э,Ц,А,Б,В,В,Г,Д,Е,З,И,К,Л,М,Н,О,П,Р,С,Т,У,Ф,Х,Ц,Э,Й,Х',
	// end mod Translit
	// begin mod CapsLock Transform
	'QR_TRANSFORM_TEXT'                => 'Change Text Case',
	'QR_TRANSFORM_TEXT_TOOLTIP'        => 'Press a button to change the case of the selected text',
	'QR_TRANSFORM_TEXT_LOWER'          => '&#9660; abc',
	'QR_TRANSFORM_TEXT_UPPER'          => '&#9650; ABC',
	'QR_TRANSFORM_TEXT_INVERS'         => '&#9660;&#9650; aBC',
	'QR_TRANSFORM_TEXT_LOWER_TOOLTIP'  => 'kleinbuchstaben',
	'QR_TRANSFORM_TEXT_UPPER_TOOLTIP'  => 'GROßBUCHSTABEN',
	'QR_TRANSFORM_TEXT_INVERS_TOOLTIP' => 'iNVERTIERE gROß/kLEINSCHREIBUNG',
	// end mod CapsLock Transform
));
