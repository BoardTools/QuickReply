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
	'QR_BBPOST'					=> 'Fuente del mensaje',
	'QR_INSERT_TEXT'			=> 'Insertar cita en el formulario de Respuesta Rápida',
	'QR_PROFILE'				=> 'Ir al Perfil',
	'QR_QUICKNICK'				=> 'Referir por nombre de usuario',
	'QR_QUICKNICK_TITLE'		=> 'Insertar nombre de usuario en el formulario de Respuesta Rápida',
//begin mod Translit
	'QR_TRANSLIT_TEXT'			=> 'Translit:',
	'QR_TRANSLIT_TEXT_TOOLTIP'	=> 'For instant view in Russian click the button',
//end mod Translit
//begin mod CapsLock Transfer
	'QR_TRANSFORM_TEXT'			=> 'Cambiar mayúsculas y minúsculas del texto:',
	'QR_TRANSFORM_TEXT_TOOLTIP'	=> 'Presione un botón para cambiar el caso del texto resaltado',
	'QR_TRANSFORM_TEXT_LOWER'	=> '&#9660; abc',
	'QR_TRANSFORM_TEXT_UPPER'	=> '&#9650; ABC',
	'QR_TRANSFORM_TEXT_INVERS'	=> '&#9660;&#9650; aBC',
	'QR_TRANSFORM_TEXT_LOWER_TOOLTIP'	=> 'minúscula',
	'QR_TRANSFORM_TEXT_UPPER_TOOLTIP'	=> 'MAYÚSCULAS',
	'QR_TRANSFORM_TEXT_INVERS_TOOLTIP'	=> 'iNVERTIR cASO',
//end mod CapsLock Transfer
));
