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
	'QR_ENABLE_AJAX_PAGINATION' => 'Ne pas recharger le formulaire de la réponse rapide lors de la navigation sur le sujet',
	'QR_ENABLE_SCROLL'          => 'Activer le défilement automatique lors de la navigation sur le sujet',
	'QR_ENABLE_SOFT_SCROLL'     => 'Activer le défilement léger et les animations lors de la navigation sur le sujet, après avoir utilisé la réponse rapide',
));
