<?php
/**
*
* QuickReply Reloaded extension for the phpBB Forum Software package.
* French translation by Galixte (http://www.galixte.com)
*
* @copyright (c) 2014 - 2015 Tatiana5 and LavIgor
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

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ « » “ ” …
//

$lang = array_merge($lang, array(
	'QR_BBPOST'					=> 'Source du message',
	'QR_INSERT_TEXT'			=> 'Insérer une citation dans le formulaire de la réponse rapide',
	'QR_PROFILE'				=> 'Voir le profil',
	'QR_QUICKNICK'				=> 'Se référer au nom d’utilisateur',
	'QR_QUICKNICK_TITLE'		=> 'Insérer le nom d’utilisateur dans le formulaire de la réponse rapide',
	'QR_REPLY_IN_PM'			=> 'Répondre dans un MP',
//begin mod Translit
	'QR_TRANSLIT_TEXT'			=> 'Translittérer :',
	'QR_TRANSLIT_TEXT_TO_RU'	=> 'en russe',
	'QR_TRANSLIT_TEXT_TOOLTIP'	=> 'Pour modifier le texte en russe cliquer sur le bouton',
//end mod Translit
//begin mod CapsLock Transfer
	'QR_TRANSFORM_TEXT'			=> 'Modifier la casse du texte :',
	'QR_TRANSFORM_TEXT_TOOLTIP'	=> 'Appuyer sur un bouton pour modifier la casse du texte sélectionné ou de tout le texte',
	'QR_TRANSFORM_TEXT_LOWER'	=> '&#9660; abc',
	'QR_TRANSFORM_TEXT_UPPER'	=> '&#9650; ABC',
	'QR_TRANSFORM_TEXT_INVERS'	=> '&#9660;&#9650; aBC',
	'QR_TRANSFORM_TEXT_LOWER_TOOLTIP'	=> 'minuscules',
	'QR_TRANSFORM_TEXT_UPPER_TOOLTIP'	=> 'MAJUSCULES',
	'QR_TRANSFORM_TEXT_INVERS_TOOLTIP'	=> 'iNVERSER lA cASSE',
//end mod CapsLock Transfer
));
