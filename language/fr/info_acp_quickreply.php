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
	'ACP_QUICKREPLY'				=> 'Réponse rapide',
	'ACP_QUICKREPLY_EXPLAIN'		=> 'Paramètres de la réponse rapide',

	'ACP_QR_BBCODE'					=> 'Activer les BBcodes',
	'ACP_QR_BBCODE_EXPLAIN'			=> 'Permet d’activer les boutons des BBCodes dans le formulaire de la réponse rapide.',
	'ACP_QR_COMMA'					=> 'Ajouter une virgule après le nom d’utilisateur',
	'ACP_QR_COMMA_EXPLAIN'			=> 'Permet d’ajouter automatiquement une virgule après le nom d’utilisateur lors de l’utilisation de la fonctionnalité « Se référer au nom d’utilisateur ».',
	'ACP_QR_CTRLENTER'				=> 'Activer l’envoi par « Ctrl + Entrée »',
	'ACP_QR_CTRLENTER_EXPLAIN'		=> 'Permet d’envoyer un message en utilisant les touches « Ctrl + Entrée ».',
	'ACP_QR_ENABLE_RE'				=> 'Activer « Re : »',
	'ACP_QR_ENABLE_RE_EXPLAIN'		=> 'Permet d’ajouter automatiquement le préfixe « Re : » dans le « sujet du message » dans le formulaire de la réponse rapide.',
	'ACP_QR_QUICKNICK'				=> 'Insérer le nom d’utilisateur',
	'ACP_QR_QUICKNICK_EXPLAIN'		=> 'Permet d’insérer le nom d’utilisateur dans le formulaire de la réponse rapide lorsque vous cliquez sur le lien « Se référer au nom d’utilisateur ».',
	'ACP_QR_QUICKQUOTE'				=> 'Activer la citation rapide',
	'ACP_QR_QUICKQUOTE_EXPLAIN'		=> 'Permet d’autoriser la citation au travers d’une fenêtre en incrustation  « popup » qui apparaît lorsque vous sélectionnez du texte dans un message.',
	'ACP_QR_SMILIES'				=> 'Activer les smileys',
	'ACP_QR_SMILIES_EXPLAIN'		=> 'Permet d’afficher les smileys dans le formulaire de la réponse rapide.',
	'ACP_QR_CAPSLOCK'				=> 'Activer le texte en majuscules / minuscules',
	'ACP_QR_SHOW_BUTTON_TRANSLIT'			=> 'Afficher le bouton « Translittérer »',
	'ACP_QR_AJAX_SUBMIT'			=> 'Activer la réponse Ajax',
	'ACP_QR_AJAX_SUBMIT_EXPLAIN'	=> 'Permet d’envoyer les messages sans recharger la page.',
	'ACP_QR_SHOW_SUBJECTS'			=> 'Afficher les sujets des messages',
	'ACP_QR_SOURCE_POST'			=> 'Ajouter un lien vers le message cité dans la citation',
	'ACP_QR_ATTACH'					=> 'Activer les fichiers joints',
	'ACP_QR_COLOUR_NICKNAME'		=> 'Ajouter de la couleur lorsque l’on se réfère à un nom d’utilisateur',
));
