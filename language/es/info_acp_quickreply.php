<?php
/**
*
* quickreply [English]
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
	'ACP_QUICKREPLY'					=> 'Respuesta Rápida',
	'ACP_QUICKREPLY_EXPLAIN'			=> 'Ajustes de Respuesta Rápida',

	'ACP_QR_AJAX_PAGINATION'			=> 'Permitir navegar por los temas sin tener que recargar la página',
	'ACP_QR_AJAX_PAGINATION_EXPLAIN'	=> 'Permitir a los usuarios el ajuste de “No refrescar el formulario de Respuesta Rápida cuando se navega en el tema”.',
	'ACP_QR_AJAX_SUBMIT'				=> 'Habilitar publicacion Ajax',
	'ACP_QR_AJAX_SUBMIT_EXPLAIN'		=> 'Permitir el envío de mensajes sin tener que recargar la página',
	'ACP_QR_ALLOW_FOR_GUESTS'			=> 'Permitir Respuesta Rápida para invitados , si está habilitado',
	'ACP_QR_ATTACH'						=> 'Permitir adjuntos',
	'ACP_QR_BBCODE'						=> 'Habilitar BBCode',
	'ACP_QR_BBCODE_EXPLAIN'				=> 'Habilitar botones BBCode buttons en el formulario de Respuesta Rápida.',
	'ACP_QR_CAPSLOCK'					=> 'Habilitar texto de mayúsculas/minúsculas',
	'ACP_QR_COLOUR_NICKNAME'			=> 'Añadir color cuando se hace referencia al nombre de usuario',
	'ACP_QR_COMMA'						=> 'Aadir coma después del nombre de usuario',
	'ACP_QR_COMMA_EXPLAIN'				=> 'Agregar automáticamente una coma después del nombre de usuario cuando se utiliza “Referir el nombre de usuario”.',
	'ACP_QR_CTRLENTER'					=> 'Habilitar el envío con “CTRL+Enter”',
	'ACP_QR_CTRLENTER_EXPLAIN'			=> 'Habilitar el enviar un mensaje al cliquear “CTRL+Enter”.',
	'ACP_QR_ENABLE_RE'					=> 'Habilitar “Re:”',
	'ACP_QR_ENABLE_RE_EXPLAIN'			=> 'Añadir automáticamente el prefijo “Re:” en el “Asunto del Mensaje” en el formulario de Respuesta Rápida.',
	'ACP_QR_FULL_QUOTE'					=> 'Insertar citas completo en el formulario de Respuesta Rápida',
	'ACP_QR_FULL_QUOTE_EXPLAIN'			=> 'Reemplazar el comportamiento estándar del botón “Responder citando”.',
	'ACP_QR_HIDE_SUBJECT_BOX'			=> 'Ocultar la caja de Asunto, si la modificación de Asunto está deshabilitada',
	'ACP_QR_HIDE_SUBJECT_BOX_EXPLAIN'	=> 'Si un usuario no tiene permiso para modificar el Asunto de un mensaje, el campo de Asunto del formulario se oculta en vez de ser desactivado.',
	'ACP_QR_QUICKNICK'					=> 'Insertar nombre de usuario',
	'ACP_QR_QUICKNICK_EXPLAIN'			=> 'Allow insertion of the username in the form of a quick reply when you click on the link “Referir el nombre de usuario”.',
	'ACP_QR_QUICKNICK_PM'				=> 'Inluir el botón «Responder en MP» en el desplegable de la función “Referir el nombre de usuario”',
	'ACP_QR_QUICKNICK_REF'				=> 'Habilitar etiqueta especial para el usuario referenciado',
	'ACP_QR_QUICKNICK_REF_EXPLAIN'		=> 'BBCode [ref] se utilizará en lugar de [b] en la función “Referir el nombre de usuario”.',
	'ACP_QR_QUICKQUOTE'					=> 'Habilitar cita rápida',
	'ACP_QR_QUICKQUOTE_EXPLAIN'			=> 'Permitir citas en un “popup” que aparece cuando se selecciona el texto en un mensaje.',
	'ACP_QR_QUICKQUOTE_LINK'			=> 'Añadir un enlace al perfil del autor del mensaje al utilizar la cita rápida',
	'ACP_QR_SCROLL_TIME'				=> 'Tiempo para un solo evento de desplazamiento y animación',
	'ACP_QR_SCROLL_TIME_EXPLAIN'		=> 'Tiempo en milisegundos para la función de desplazamiento suave. Introducir 0 para el desplazamiento estándar.',
	'ACP_QR_SHOW_BUTTON_TRANSLIT'		=> 'Mostrar botón “Convertir”',
	'ACP_QR_SHOW_SUBJECTS'				=> 'Mostrar asuntos de los mensajes en los temas',
	'ACP_QR_SHOW_SUBJECTS_IN_SEARCH'	=> 'Mostrar asuntos de los mensajes en los resultados de búsqueda',
	'ACP_QR_SMILIES'					=> 'Habilitar Emoticonos',
	'ACP_QR_SMILIES_EXPLAIN'			=> 'Permite mostrar los Emoticonos en el formulario de Respuesta Rápida.',
	'ACP_QR_SOURCE_POST'				=> 'Añadir un enlace al mensaje citado al citar',
));
