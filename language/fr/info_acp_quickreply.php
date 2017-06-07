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
	'ACP_QUICKREPLY'                 => 'Réponse rapide',
	'ACP_QUICKREPLY_EXPLAIN'         => 'Paramètres de la réponse rapide',
	//
	'ACP_QR_BBCODE'                  => 'Activer les BBcodes',
	'ACP_QR_BBCODE_EXPLAIN'          => 'Permet d’activer les boutons des BBCodes dans le formulaire de la réponse rapide.',
	'ACP_QR_COMMA'                   => 'Ajouter une virgule après le nom d’utilisateur',
	'ACP_QR_COMMA_EXPLAIN'           => 'Permet d’ajouter automatiquement une virgule après le nom d’utilisateur lors de l’utilisation de la fonctionnalité « Se référer au nom d’utilisateur ».',
	'ACP_QR_ENABLE_RE'               => 'Activer « Re : »',
	'ACP_QR_ENABLE_RE_EXPLAIN'       => 'Permet d’ajouter automatiquement le préfixe « Re : » dans le « sujet du message » dans le formulaire de la réponse rapide.',
	'ACP_QR_QUICKNICK'               => 'Insérer le nom d’utilisateur dans le formulaire de la réponse rapide',
	'ACP_QR_QUICKNICK_EXPLAIN'       => 'Modifie le comportement par défaut lorsque l’on clique sur le nom d’utilisateur dans le mini-profil. Un menu s’affiche permettant d’effectuer plusieurs actions, telles que « Voir le profil » de l’utilisateur, « Se référer au nom d’utilisateur » dans le formulaire de la réponse rapide et « Répondre dans un MP » si l’option est activée.',
	'ACP_QR_QUICKNICK_REF'           => 'Activer un tag spécial pour se référer au nom d’utilisateur',
	'ACP_QR_QUICKNICK_REF_EXPLAIN'   => 'Permet d’utiliser le BBCode [ref] en lieu et place du BBCode [b] pour la fonctionnalité « Se référer au nom d’utilisateur ».',
	'ACP_QR_QUICKNICK_PM'            => 'Activer l’action « Répondre dans un MP » dans le menu s’affichant lorsque l’on clique sur le nom d’utilisateur dans le mini-profil',
	'ACP_QR_QUICKQUOTE'              => 'Activer la citation rapide',
	'ACP_QR_QUICKQUOTE_EXPLAIN'      => 'Permet de citer rapidement dans le formulaire de la réponse rapide, au travers d’une fenêtre en incrustation « popup », lorsque du texte est sélectionné dans un message.',
	'ACP_QR_FULL_QUOTE'              => 'Insérer la citation dans le formulaire de la réponse rapide',
	'ACP_QR_FULL_QUOTE_EXPLAIN'      => 'Modifie le comportement par défaut du bouton « Répondre en citant le message ».',
	'ACP_QR_SMILIES'                 => 'Activer les smileys',
	'ACP_QR_SMILIES_EXPLAIN'         => 'Permet d’afficher les smileys dans le formulaire de la réponse rapide.',
	'ACP_QR_CAPSLOCK'                => 'Activer le texte en majuscules / minuscules',
	'ACP_QR_SHOW_BUTTON_TRANSLIT'    => 'Afficher le bouton « Translittérer » (en russe)',
	'ACP_QR_AJAX_SUBMIT'             => 'Activer la réponse Ajax',
	'ACP_QR_AJAX_SUBMIT_EXPLAIN'     => 'Permet d’envoyer les messages sans recharger la page.',
	'ACP_QR_AJAX_PAGINATION'         => 'Autoriser la navigation dans les sujets sans recharger la page',
	'ACP_QR_AJAX_PAGINATION_EXPLAIN' => 'Permet aux utilisateurs d’activer le paramètre « Ne pas recharger le formulaire de la réponse rapide lors de la navigation sur le sujet ».',
	'ACP_QR_SCROLL_TIME'             => 'Durée du défilement et de l’animation',
	'ACP_QR_SCROLL_TIME_EXPLAIN'     => 'Temps en millisecondes pour la fonctionnalité du défilement léger. Saisir 0 pour le défilement par défaut.',
	'ACP_QR_SHOW_SUBJECTS'           => 'Afficher les sujets des messages dans les sujets',
	'ACP_QR_SHOW_SUBJECTS_IN_SEARCH' => 'Afficher les sujets des messages dans les résultats de la recherche',
	'ACP_QR_ATTACH'                  => 'Activer les fichiers joints',
	'ACP_QR_ALLOW_FOR_GUESTS'        => 'Autoriser la réponse rapide pour les invités si elle est activée',
	'ACP_QR_COLOUR_NICKNAME'         => 'Ajouter de la couleur lorsque l’on se réfère à un nom d’utilisateur',
));
