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
	'QR_INSERT_TEXT'                   => 'Insérer une citation dans le formulaire de la réponse rapide',
	'QR_PROFILE'                       => 'Voir le profil',
	'QR_QUICKNICK'                     => 'Se référer au nom d’utilisateur',
	'QR_QUICKNICK_TITLE'               => 'Insérer le nom d’utilisateur dans le formulaire de la réponse rapide',
	'QR_REPLY_IN_PM'                   => 'Répondre dans un MP',
	//begin mod Translit
	'QR_TRANSLIT_TEXT'                 => 'Translittérer',
	'QR_TRANSLIT_TEXT_TO_RU'           => 'en russe',
	'QR_TRANSLIT_TEXT_TOOLTIP'         => 'Pour modifier le texte en russe cliquer sur le bouton',
	//end mod Translit
	//begin mod CapsLock Transform
	'QR_TRANSFORM_TEXT'                => 'Modifier la casse du texte',
	'QR_TRANSFORM_TEXT_TOOLTIP'        => 'Appuyer sur un bouton pour modifier la casse du texte sélectionné ou de tout le texte',
	'QR_TRANSFORM_TEXT_LOWER'          => '&#9660; abc',
	'QR_TRANSFORM_TEXT_UPPER'          => '&#9650; ABC',
	'QR_TRANSFORM_TEXT_INVERS'         => '&#9660;&#9650; aBC',
	'QR_TRANSFORM_TEXT_LOWER_TOOLTIP'  => 'minuscules',
	'QR_TRANSFORM_TEXT_UPPER_TOOLTIP'  => 'MAJUSCULES',
	'QR_TRANSFORM_TEXT_INVERS_TOOLTIP' => 'iNVERSER lA cASSE',
	//end mod CapsLock Transform
));
