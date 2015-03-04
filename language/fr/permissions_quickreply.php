<?php
/**
*
* quickreply [French]
* French translation by Galixte (http://www.galixte.com)
*
* @package quickreply
* @copyright (c) 2014 William Jacoby (bonelifer)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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

	'ACL_A_QUICKREPLY'			=> 'Peut modifier les paramètres de la réponse rapide.',
	'ACL_F_QR_CHANGE_SUBJECT'	=> 'Peut modifier le sujet du message.',
));
