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
	'ACP_QUICKREPLY'          => 'Réponse rapide',
	'ACP_QUICKREPLY_EXPLAIN'  => 'Paramètres de la réponse rapide',
	//
	'ACL_A_QUICKREPLY'        => 'Peut modifier les paramètres de la réponse rapide.',
	'ACL_F_QR_CHANGE_SUBJECT' => 'Peut modifier le sujet du message.',
	'ACL_F_QR_FULL_QUOTE'     => 'Peut utiliser la citation complète dans les sujets.<br /><em>Si la citation rapide est activée, celle-ci sera suggérée si l’utilisateur n’a pas les permissions sur la citation complète.</em>',
));
