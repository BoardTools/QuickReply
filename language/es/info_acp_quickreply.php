<?php
/**
*
* quickreply [Spanish]
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
	'ACP_QUICKREPLY'				=> 'Respuesta Rápida',
	'ACP_QUICKREPLY_EXPLAIN'		=> 'Ajustes de Respuesta Rápida',

	'ACP_QR_BBCODE'					=> 'Habilitar BBcode',
	'ACP_QR_BBCODE_EXPLAIN'			=> 'Habilitar botones BBCode en el formulario de Respuesta Rápida',
	'ACP_QR_COMMA'					=> 'Añadir coma después del nombre de usuario',
	'ACP_QR_COMMA_EXPLAIN'			=> 'Agregar automáticamente una coma después del nombre de usuario cuando se utiliza “Referir por nombre de usuario”',
	'ACP_QR_CTRLENTER'				=> 'Habilitar el envío con “Ctrl+Enter”',
	'ACP_QR_CTRLENTER_EXPLAIN'		=> 'Permitir el envío de un mensaje pulsando “Ctrl+Enter”',
	'ACP_QR_ENABLE_RE'				=> 'Habilitar “Re:”',
	'ACP_QR_ENABLE_RE_EXPLAIN'		=> 'Añadir automáticamente el prefijo "Re:" en el "Asunto" del formulario de Respuesta Rápida',
	'ACP_QR_QUICKNICK'				=> 'Insertar nombre de usuario',
	'ACP_QR_QUICKNICK_EXPLAIN'		=> 'Permitir la inserción del nombre de usuario en el formulario de Respuesta Rápida cuando se hace clic en el enlace “Referir por nombre de usuario”',
	'ACP_QR_QUICKQUOTE'				=> 'Habilitar cita rápida',
	'ACP_QR_QUICKQUOTE_EXPLAIN'		=> 'Permitir citas a través de un "popup" que aparece cuando se selecciona texto en un mensaje',
	'ACP_QR_SMILIES'				=> 'Habilitar Smilies',
	'ACP_QR_SMILIES_EXPLAIN'		=> 'Permite mostrar los smilies en el formulario de Respuesta Rápida',
	'ACP_QR_CAPSLOCK'				=> 'Habilitar texto en mayúsculas / minúsculas',
	'ACP_QR_AJAX_SUBMIT'			=> 'Habilitar publicación Ajax',
	'ACP_QR_AJAX_SUBMIT_EXPLAIN'	=> 'Permitir el envío de mensajes sin tener que recargar la página',
	'ACP_QR_SHOW_SUBJECTS'			=> 'Mostrar asuntos de los mensajes',
	'ACP_QR_SHOW_BUTTON_TRANSLIT'			=> 'Show button “Транслит”',
	'ACP_QR_SOURCE_POST'			=> 'Añadir un enlace al mensaje citado al citar',
	'ACP_QR_ATTACH'					=> 'Habilitar adjuntos',
	'ACP_QR_COLOUR_NICKNAME'		=> 'Añadir color al referirse a un nombre de usuario',
));
