<?php
/**
*
* quickreply [French]
* French translation by Galixte (http://www.galixte.com)
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
	'QR_BBPOST'					=> 'Source du message',
	'QR_INSERT_TEXT'			=> 'Insérer une citation dans le formulaire de la réponse rapide',
	'QR_PROFILE'				=> 'Voir le profil',
	'QR_QUICKNICK'				=> 'Se référer au nom d’utilisateur',
	'QR_QUICKNICK_TITLE'		=> 'Insérer le nom d’utilisateur dans le formulaire de la réponse rapide',
//begin mod Translit
	'QR_TRANSLIT_TEXT'			=> 'Translittérer :',
	'QR_TRANSLIT_TEXT_TOOLTIP'	=> 'Pour une vue instantanée en russe cliquez sur le bouton',
//end mod Translit
//begin mod CapsLock Transfer
	'QR_TRANSFORM_TEXT'			=> 'Modifier la casse du texte :',
	'QR_TRANSFORM_TEXT_TOOLTIP'	=> 'Appuyez sur un bouton pour modifier la casse du texte en surbrillance',
	'QR_TRANSFORM_TEXT_LOWER'	=> '&#9660; abc',
	'QR_TRANSFORM_TEXT_UPPER'	=> '&#9650; ABC',
	'QR_TRANSFORM_TEXT_INVERS'	=> '&#9660;&#9650; aBC',
	'QR_TRANSFORM_TEXT_LOWER_TOOLTIP'	=> 'minuscules',
	'QR_TRANSFORM_TEXT_UPPER_TOOLTIP'	=> 'MAJUSCULES',
	'QR_TRANSFORM_TEXT_INVERS_TOOLTIP'	=> 'inverser la casse',
//end mod CapsLock Transfer
));
