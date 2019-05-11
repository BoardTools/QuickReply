<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2019 Tatiana5 and LavIgor
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
	'ACP_QUICKREPLY'                       => 'Réponse rapide',
	'ACP_QUICKREPLY_EXPLAIN'               => 'Paramètres de la réponses rapide.',
	'ACP_QUICKREPLY_TITLE'                 => 'Réponse rapide',
	'ACP_QUICKREPLY_TITLE_EXPLAIN'         => 'Sur cette page il est possible de paramétrer les différentes réponses de l’extension « QuickReply Reloaded ».<br />NOTE : les paramètres « Autoriser la réponse rapide » & « Activer la réponse rapide » sont aussi disponibles dans les options de phpBB et sont listés sur cette page pour simplifier la configuration de cette extension. Tous les autres paramètres présentés ici sont uniquement présents sur cette page.',
	//
	'ACP_QUICKREPLY_QN'                    => 'Paramètres de la citation rapide & de la mention rapide',
	'ACP_QUICKREPLY_QN_EXPLAIN'            => 'Permet de paramétrer les options de la citation rapide & de la mention rapide.',
	'ACP_QUICKREPLY_QN_TITLE'              => 'Réponse rapide',
	'ACP_QUICKREPLY_QN_TITLE_EXPLAIN'      => 'Sur cette page il est possible de paramétrer les options de la citation rapide & de la mention rapide.<br />NOTE : ces paramètres n’ont aucun effet sur les forums où la réponse rapide est désactivée ou non autorisée.',
	//
	'ACP_QUICKREPLY_PLUGINS'               => 'Paramètres supplémentaires',
	'ACP_QUICKREPLY_PLUGINS_EXPLAIN'       => 'Permet de configurer les paramètres supplémentaires.',
	'ACP_QUICKREPLY_PLUGINS_TITLE'         => 'Réponse rapide',
	'ACP_QUICKREPLY_PLUGINS_TITLE_EXPLAIN' => 'Sur cette page il est possible de paramétrer les fonctionnalités spéciales inclues dans l’extension « QuickReply Reloaded ».<br />NOTE : ces paramètres fonctionnent indépendamment de l’état d’activation de la réponse rapide dans les forums.',
	//
	'ACP_QR_AJAX_PAGINATION'               => 'Autoriser la navigation dans les sujets sans recharger la page',
	'ACP_QR_AJAX_PAGINATION_EXPLAIN'       => 'Permet d’activer la pagination Ajax, lors de la navigation dans les sujets en lieu et place de la navigation standard, ainsi que de proposer aux utilisateurs d\'activer l’option « Ne pas recharger le formulaire de la réponse rapide lors de la navigation sur le sujet » depuis leur « Panneau de l’utilisateur ».',
	'ACP_QR_AJAX_SUBMIT'                   => 'Activer le mode Ajax dans la réponse rapide',
	'ACP_QR_AJAX_SUBMIT_EXPLAIN'           => 'Permet d’envoyer les messages sans recharger la page.<br />Si activé, les paramètres spécifiques du forum seront utilisés pour déterminer quelle mode Ajax sera utilisé pour chacun des forums.',
	'ACP_QR_ALLOW_FOR_GUESTS'              => 'Autoriser la réponse rapide pour les invités si elle est activée',
	'ACP_QR_ATTACH'                        => 'Activer les fichiers joints',
	'ACP_QR_ATTACH_EXPLAIN'                => 'Permet d’activer les fichiers joints dans le formulaire de la réponse rapide.',
	'ACP_QR_BBCODE'                        => 'Afficher les BBCodes',
	'ACP_QR_BBCODE_EXPLAIN'                => 'Permet d’activer les boutons des BBCodes dans le formulaire de la réponse rapide.',
	'ACP_QR_CAPSLOCK'                      => 'Activer la transformation du texte en majuscules / minuscules',
	'ACP_QR_COLOUR_NICKNAME'               => 'Ajouter de la couleur lorsque l’on se réfère à un nom d’utilisateur',
	'ACP_QR_COMMA'                         => 'Ajouter une virgule après le nom d’utilisateur',
	'ACP_QR_COMMA_EXPLAIN'                 => 'Permet d’ajouter automatiquement une virgule après le nom d’utilisateur lors de l’utilisation de la fonctionnalité « Se référer au nom d’utilisateur ».',
	'ACP_QR_CTRLENTER'                     => 'Activer l’envoi par « Ctrl + Entrée »',
	'ACP_QR_CTRLENTER_EXPLAIN'             => 'Permet d’envoyer un message en utilisant les touches « Ctrl + Entrée » dans le formulaire de la réponse rapide et sur la page de l’éditeur de texte complet. L’astuce concernant cette fonctionnalité sera affichée lors du survol de la souris sur le bouton « Envoyer » du formulaire de la réponse rapide.',
	'ACP_QR_CTRLENTER_NOTICE'              => 'Activer l’astuce « Ctrl + Entrée » dans le formulaire de la réponse rapide',
	'ACP_QR_CTRLENTER_NOTICE_EXPLAIN'      => 'Permet d’afficher l’astuce lors du survol de la souris sur le bouton « Envoyer » du formulaire de la réponse rapide. En désactivant cette option la fonctionnalité « Ctrl + Entrée » ne sera pas désactivée.',
	'ACP_QR_ENABLE_AJAX_SUBMIT'            => 'Activer le mode Ajax dans la réponse rapide pour tous les forums',
	'ACP_QR_ENABLE_AJAX_SUBMIT_EXPLAIN'    => 'Permet d’activer immédiatement le mode Ajax dans la réponse rapide pour tous les forums.',
	'ACP_QR_ENABLE_RE'                     => 'Activer le préfixe « Re : » dans les sujets des messages',
	'ACP_QR_ENABLE_RE_EXPLAIN'             => 'Permet d’ajouter le préfixe « Re : » dans le champ de saisie du texte des sujets, leurs titres, des messages depuis le formulaire de la réponse rapide et sur la page de l’éditeur de texte complet.',
	'ACP_QR_ENABLE_QUICK_REPLY'            => 'Activer la réponse rapide dans tous les forums',
	'ACP_QR_ENABLE_QUICK_REPLY_EXPLAIN'    => 'Permet d’activer immédiatement la réponse rapide dans tous les forums.',
	'ACP_QR_FORM_TYPE'                     => 'Type de formulaire de la réponse rapide',
	'ACP_QR_FORM_TYPE_EXPLAIN'             => 'Si l\'option « Corrigé avec rechargement des messages » est sélectionnée, la possibilité de charger les messages sur la page en cours à l\'aide des boutons « Afficher le message suivant / précédent » complètera la pagination standard.', // reserved
	'ACP_QR_FORM_TYPE_FIXED'               => 'Corrigé',
	'ACP_QR_FORM_TYPE_SCROLL'              => 'Corrigé avec rechargement des messages', // reserved
	'ACP_QR_FORM_TYPE_STANDARD'            => 'Standard',
	'ACP_QR_FORUM_AJAX_SUBMIT'             => 'Activer le mode Ajax dans la réponse rapide',
	'ACP_QR_FORUM_AJAX_SUBMIT_EXPLAIN'     => 'Permet d’envoyer les messages sans recharger la page.',
	'ACP_QR_FULL_QUOTE'                    => 'Insérer la citation dans le formulaire de la réponse rapide',
	'ACP_QR_FULL_QUOTE_EXPLAIN'            => 'Modifier le comportement par défaut du bouton « Répondre en citant le message ».',
	'ACP_QR_HIDE_SUBJECT_BOX'              => 'Masquer le sujet du message si l’utilisateur n’est pas autorisé à le modifier',
	'ACP_QR_HIDE_SUBJECT_BOX_EXPLAIN'      => 'Lorsqu’un utilisateur n’est pas autorisé à modifier le sujet d’un message le champ du sujet du message sera masqué en lieu et place d’être désactivé.',
	'ACP_QR_LAST_QUOTE'                    => 'Activer les citations complètes pour les derniers messages des sujets',
	'ACP_QR_LAST_QUOTE_EXPLAIN'            => 'Permettre les citations complètes au moyen du bouton de citation standard.<br /><em>NOTE : ce bouton de citation sera masqué si ce paramètre est désactivé ainsi que le paramètre de la citation rapide. Ce paramètre substitue le paramètre utilisateur de la citation complète.</em>',
	'ACP_QR_LEAVE_AS_IS'                   => 'Laisser tel que',
	'ACP_QR_LEAVE_AS_IS_EXPLAIN'           => 'Permet de ne pas modifier les paramètres du forum <em>si l’option « Laisser tel que » est sélectionnée.</em>',
	'ACP_QR_LEGEND_AJAX'                   => 'Paramètres Ajax',
	'ACP_QR_LEGEND_DISPLAY'                => 'Paramètres d’affichage',
	'ACP_QR_LEGEND_GENERAL'                => 'Paramètres généraux',
	'ACP_QR_LEGEND_QUICKNICK'              => 'Paramètres de la mention rapide',
	'ACP_QR_LEGEND_QUICKQUOTE'             => 'Paramètres de la citation rapide',
	'ACP_QR_LEGEND_SPECIAL'                => 'Fonctionnalités spéciales',
	'ACP_QR_QUICKNICK'                     => 'Activer la mention rapide (dans le menu déroulant)',
	'ACP_QR_QUICKNICK_EXPLAIN'             => 'Permet d’afficher un menu déroulant au moyen du lien « Se référer au nom d’utilisateur » insérant automatiquement le nom d’utilisateur du message dans le formulaire de la réponse rapide. Ce menu déroulant s’affiche lorsque l’on clique sur le nom d’utilisateur dans le mini-profiel et permet d’effectuer plusieurs actions, telles que « Voir le profil » de l’utilisateur, « Se référer au nom d’utilisateur » dans le formulaire de la réponse rapide et « Répondre dans un MP » si l’option est activée.<br />Si ce paramètre est activé et que le paramètre « Activer la mention rapide (située sous l’avatar) » est désactivé l’utilisateur peut basculer vers la version du lien « Se référer au nom d’utilisateur » situé sous l’avatar dans le « Panneau de l’utilisateur ».',
	'ACP_QR_QUICKNICK_STRING'              => 'Activer la mention rapide (située sous l’avatar)',
	'ACP_QR_QUICKNICK_STRING_EXPLAIN'      => 'Permet d’afficher un lien « Se référer au nom d’utilisateur » dans le mini-profil des utilisateurs en insérant le nom d’utilisateur dans le formulaire de la réponse rapide.',
	'ACP_QR_QUICKNICK_PM'                  => 'Inclure le bouton « Réponse dans un MP » dans le menu déroulant de la fonctionnalité « Se référer au nom d’utilisateur »',
	'ACP_QR_QUICKNICK_REF'                 => 'Activer un tag spécial pour se référer au nom d’utilisateur',
	'ACP_QR_QUICKNICK_REF_EXPLAIN'         => 'Le BBCode [ref] sera utilisé en lieu et place du BBCode [b] pour la fonctionnalité « Se référer au nom d’utilisateur ».<br /><em>NOTE : si cette option est désactivée, les utilisateurs recevront uniquement des notifications concernant concernant les mentions et seulement lorsque le BBCode [ref] été saisi manuellement dans un message.</em>',
	'ACP_QR_QUICKQUOTE'                    => 'Activer la citation rapide dans une fenêtre en incrustation « pop-up »',
	'ACP_QR_QUICKQUOTE_BUTTON'             => 'Activer la citation personnelle en utilisant le bouton',
	'ACP_QR_QUICKQUOTE_BUTTON_EXPLAIN'     => 'Permet les citations au moyen du bouton de citation standard.<br /><em>NOTE : ce bouton de citation sera masqué si ce paramètre est désactivé et que l’utilisateur n’a pas les permissions nécessaires pour utiliser les citation complète.</em>',
	'ACP_QR_QUICKQUOTE_EXPLAIN'            => 'Permet de citer rapidement dans le formulaire de la réponse rapide, au travers d’une fenêtre en incrustation « popup », lorsque du texte est sélectionné dans un message.',
	'ACP_QR_QUICKQUOTE_LINK'               => 'Ajouter un lien vers le profil de l’auteur du message dans la citation rapide',
	'ACP_QR_SCROLL_TIME'                   => 'Durée du défilement et de l’animation',
	'ACP_QR_SCROLL_TIME_EXPLAIN'           => 'Temps en millisecondes pour la fonctionnalité du défilement léger. Saisir 0 pour le défilement par défaut.',
	'ACP_QR_SHOW_BUTTON_TRANSLIT'          => 'Afficher le bouton « Translittérer » (en russe)',
	'ACP_QR_SHOW_SUBJECTS'                 => 'Afficher les sujets des messages dans les sujets',
	'ACP_QR_SHOW_SUBJECTS_IN_SEARCH'       => 'Afficher les sujets des messages dans les résultats de la recherche',
	'ACP_QR_SMILIES'                       => 'Afficher les Smileys',
	'ACP_QR_SMILIES_EXPLAIN'               => 'Permet d’afficher les smileys dans le formulaire de la réponse rapide.',
	'ACP_QR_SOURCE_POST'                   => 'Ajouter un lien vers le message cité dans la citation rapide',
));
