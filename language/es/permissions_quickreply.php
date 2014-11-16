<?php
/**
*
* quickreply [Spanish]
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
	'ACP_QUICKREPLY'				=> 'Respuesta Rápida',
	'ACP_QUICKREPLY_EXPLAIN'		=> 'Ajustes de Respuesta Rápida',

	'ACL_A_QUICKREPLY'			=> 'Puede cambiar los ajustes de Respuesta Rápida',
	'ACL_F_QR_CHANGE_SUBJECT'	=> 'Puede modificar el Asunto del mensaje',
));
