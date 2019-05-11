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
	'ACP_QUICKREPLY'          => 'Respuesta Rápida',
	'ACP_QUICKREPLY_EXPLAIN'  => 'Ajustes de Respuesta Rápida',
	//
	'ACL_A_QUICKREPLY'        => 'Puede gestionar los ajustes de la Respuesta Rápida',
	'ACL_F_QR_CHANGE_SUBJECT' => 'Puede modificar el Asunto del mensaje',
	'ACL_F_QR_FULL_QUOTE'     => 'Puede usar la cita completa en los temas<br /><em>Se sugiere usar cita rápida si el usuario no tiene este permiso y la característica de cita rápida está habilitada.</em>',
));
