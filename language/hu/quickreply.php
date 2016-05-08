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
	'QR_BBPOST'                        => 'Hozzászólás forrása',
	'QR_INSERT_TEXT'                   => 'Idézet beszúrása a Gyors válasz űrlapra',
	'QR_PROFILE'                       => 'Profil megtekintése',
	'QR_QUICKNICK'                     => 'Hivatkozás felhasználónévvel',
	'QR_QUICKNICK_TITLE'               => 'Felhasználónév beszúrása a Gyors válasz űrlapra',
	'QR_REPLY_IN_PM'                   => 'Válasz PÜ-ben',
	//begin mod Translit
	'QR_TRANSLIT_TEXT'                 => 'Átír:',
	'QR_TRANSLIT_TEXT_TO_RU'           => 'orosz nyelvre',
	'QR_TRANSLIT_TEXT_TOOLTIP'         => 'Kattints a gombra orosz nyelvű előnézethez',
	//end mod Translit
	//begin mod CapsLock Transform
	'QR_TRANSFORM_TEXT'                => 'Kisbetű-nagybetű megváltoztatása:',
	'QR_TRANSFORM_TEXT_TOOLTIP'        => 'Nyomd meg az egyik gombot a kis/nagybetűk megváltoztatására a kijelölt szövegben',
	'QR_TRANSFORM_TEXT_LOWER'          => '&#9660; abc',
	'QR_TRANSFORM_TEXT_UPPER'          => '&#9650; ABC',
	'QR_TRANSFORM_TEXT_INVERS'         => '&#9660;&#9650; aBC',
	'QR_TRANSFORM_TEXT_LOWER_TOOLTIP'  => 'kisbetű',
	'QR_TRANSFORM_TEXT_UPPER_TOOLTIP'  => 'NAGYBETŰ',
	'QR_TRANSFORM_TEXT_INVERS_TOOLTIP' => 'kIS/nAGYBETŰ cSERE',
	//end mod CapsLock Transform
));
