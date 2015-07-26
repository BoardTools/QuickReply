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
	'ACP_QUICKREPLY'				=> 'Réponse rapide',
	'ACP_QUICKREPLY_EXPLAIN'		=> 'Paramètres de la réponse rapide',

	'ACP_QR_BBCODE'					=> 'Activer les BBcodes',
	'ACP_QR_BBCODE_EXPLAIN'			=> 'Permet d’activer les boutons des BBCodes dans le formulaire de la réponse rapide.',
	'ACP_QR_COMMA'					=> 'Ajouter une virgule après le nom d’utilisateur',
	'ACP_QR_COMMA_EXPLAIN'			=> 'Permet d’ajouter automatiquement une virgule après le nom d’utilisateur lors de l’utilisation de la fonctionnalité « Se référer au nom d’utilisateur ».',
	'ACP_QR_CTRLENTER'				=> 'Activer l’envoi par « Ctrl + Entrée »',
	'ACP_QR_CTRLENTER_EXPLAIN'		=> 'Permet d’envoyer un message en utilisant les touches « Ctrl + Entrée ».',
	'ACP_QR_ENABLE_RE'				=> 'Activer « Re : »',
	'ACP_QR_ENABLE_RE_EXPLAIN'		=> 'Permet d’ajouter automatiquement le préfixe « Re : » dans le « sujet du message » dans le formulaire de la réponse rapide.',
	'ACP_QR_QUICKNICK'				=> 'Insérer le nom d’utilisateur dans le formulaire de la réponse rapide',
	'ACP_QR_QUICKNICK_EXPLAIN'		=> 'Modifie le comportement par défaut lorsque l’on clique sur le nom d’utilisateur dans le mini-profil. Un menu s’affiche permettant d’effectuer plusieurs actions, telles que « Voir le profil » de l’utilisateur, « Se référer au nom d’utilisateur » dans le formulaire de la réponse rapide et « Répondre dans un MP » si l’option est activée.',
	'ACP_QR_QUICKNICK_REF'			=> 'Activer un tag spécial pour se référer au nom d’utilisateur',
	'ACP_QR_QUICKNICK_REF_EXPLAIN'	=> 'Permet d’utiliser le BBCode [ref] en lieu et place du BBCode [b] pour la fonctionnalité « Se référer au nom d’utilisateur ».',
	'ACP_QR_QUICKNICK_PM'			=> 'Activer l’action « Répondre dans un MP » dans le menu s’affichant lorsque l’on clique sur le nom d’utilisateur dans le mini-profil',
	'ACP_QR_QUICKQUOTE'				=> 'Activer la citation rapide',
	'ACP_QR_QUICKQUOTE_EXPLAIN'		=> 'Permet de citer rapidement dans le formulaire de la réponse rapide, au travers d’une fenêtre en incrustation « popup », lorsque du texte est sélectionné dans un message.',
	'ACP_QR_QUICKQUOTE_LINK'		=> 'Ajouter un lien vers le profile de l’auteur du message dans la citation rapide',
	'ACP_QR_FULL_QUOTE'				=> 'Insérer la citation dans le formulaire de la réponse rapide',
	'ACP_QR_FULL_QUOTE_EXPLAIN'		=> 'Modifie le comportement par défaut du bouton « Répondre en citant le message ».',
	'ACP_QR_SMILIES'				=> 'Activer les smileys',
	'ACP_QR_SMILIES_EXPLAIN'		=> 'Permet d’afficher les smileys dans le formulaire de la réponse rapide.',
	'ACP_QR_CAPSLOCK'				=> 'Activer le texte en majuscules / minuscules',
	'ACP_QR_SHOW_BUTTON_TRANSLIT'	=> 'Afficher le bouton « Translittérer » (en russe)',
	'ACP_QR_AJAX_SUBMIT'			=> 'Activer la réponse Ajax',
	'ACP_QR_AJAX_SUBMIT_EXPLAIN'	=> 'Permet d’envoyer les messages sans recharger la page.',
	'ACP_QR_AJAX_PAGINATION'		=> 'Autoriser la navigation dans les sujets sans recharger la page',
	'ACP_QR_AJAX_PAGINATION_EXPLAIN'=> 'Permet aux utilisateurs d’activer le paramètre « Ne pas recharger le formulaire de la réponse rapide lors de la navigation sur le sujet ».',
	'ACP_QR_SCROLL_TIME'			=> 'Durée du défilement et de l’animation',
	'ACP_QR_SCROLL_TIME_EXPLAIN'	=> 'Temps en millisecondes pour la fonctionnalité du défilement léger. Saisir 0 pour le défilement par défaut.',
	'ACP_QR_SHOW_SUBJECTS'			=> 'Afficher les sujets des messages dans les sujets',
	'ACP_QR_SHOW_SUBJECTS_IN_SEARCH'=> 'Afficher les sujets des messages dans les résultats de la recherche',
	'ACP_QR_SOURCE_POST'			=> 'Ajouter un lien vers le message cité dans la citation rapide',
	'ACP_QR_ATTACH'					=> 'Activer les fichiers joints',
	'ACP_QR_ALLOW_FOR_GUESTS'		=> 'Autoriser la réponse rapide pour les invités si elle est activée',
	'ACP_QR_COLOUR_NICKNAME'		=> 'Ajouter de la couleur lorsque l’on se réfère à un nom d’utilisateur',
));
