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
	'QR_CHANGE_QUICKNICK_STRING' => 'Afficher sur un menu déroulant lorsque l’on clique sur le nom d’utilisateur situé sous l’avatar pour insérer le lien  « Se référer au nom d’utilisateur »',
	'QR_ENABLE_AJAX_PAGINATION'  => 'Ne pas recharger le formulaire de la réponse rapide lors de la navigation sur le sujet',
	'QR_ENABLE_SCROLL'           => 'Activer le défilement automatique lors de la navigation sur le sujet',
	'QR_ENABLE_SOFT_SCROLL'      => 'Activer le défilement léger et les animations lors de la navigation sur le sujet, après avoir utilisé la réponse rapide',
	'QR_ENABLE_WARNING'          => 'Avertir lorsque le contenu saisi dans la réponse rapide peut être perdu.',
	'QR_FIX_EMPTY_FORM'          => 'Permettre de corriger le formulaire de la réponse rapide lorsque celui-ci est vide',
));
