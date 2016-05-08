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
	'QR_BBPOST'                        => 'Przejdź do cytowanego posta',
	'QR_INSERT_TEXT'                   => 'Wstaw cytat w pole szybkiej odpowiedzi',
	'QR_PROFILE'                       => 'Przejdź do profilu',
	'QR_QUICKNICK'                     => 'Cytuj nazwe użytkownika',
	'QR_QUICKNICK_TITLE'               => 'Wstaw ten nick w pole szybkiej odpowiedzi',
	'QR_REPLY_IN_PM'                   => 'Odpowiedz przez PW',
	//begin mod Translit
	'QR_TRANSLIT_TEXT'                 => 'Przetłumacz:',
	'QR_TRANSLIT_TEXT_TO_RU'           => 'na rosyjski',
	'QR_TRANSLIT_TEXT_TOOLTIP'         => 'Natychmiastowy wyidok rozysjski - kliknij tutaj',
	//end mod Translit
	//begin mod CapsLock Transform
	'QR_TRANSFORM_TEXT'                => 'Zmień rozmiar tekstu:',
	'QR_TRANSFORM_TEXT_TOOLTIP'        => 'Kliknij aby zmienić rozmiar zaznaczonego tekstu',
	'QR_TRANSFORM_TEXT_LOWER'          => '&#9660; abc',
	'QR_TRANSFORM_TEXT_UPPER'          => '&#9650; ABC',
	'QR_TRANSFORM_TEXT_INVERS'         => '&#9660;&#9650; aBC',
	'QR_TRANSFORM_TEXT_LOWER_TOOLTIP'  => 'małe litery',
	'QR_TRANSFORM_TEXT_UPPER_TOOLTIP'  => 'DRUKOWANE LITERY',
	'QR_TRANSFORM_TEXT_INVERS_TOOLTIP' => 'oDWRÓĆ Znaki',
	//end mod CapsLock Transform
));
