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
	'QR_INSERT_TEXT'                   => 'Insertar cita en el formulario de Respuesta Rápida',
	'QR_PROFILE'                       => 'Ir al Perfil',
	'QR_QUICKNICK'                     => 'Referir el nombre de usuario',
	'QR_QUICKNICK_TITLE'               => 'Insertar nombre de usuario en el formulario de Respuesta Rápida',
	'QR_REPLY_IN_PM'                   => 'Responder en MP',
	//begin mod Translit
	'QR_TRANSLIT_TEXT'                 => 'Traducir',
	'QR_TRANSLIT_TEXT_TO_RU'           => 'a Ruso',
	'QR_TRANSLIT_TEXT_TOOLTIP'         => 'Para visión instantánea en Ruso haga clic en el botón',
	//end mod Translit
	//begin mod CapsLock Transform
	'QR_TRANSFORM_TEXT'                => 'Cambiar caso del texto',
	'QR_TRANSFORM_TEXT_TOOLTIP'        => 'Presione un botón para cambiar el caso del texto seleccionado',
	'QR_TRANSFORM_TEXT_LOWER'          => '&#9660; abc',
	'QR_TRANSFORM_TEXT_UPPER'          => '&#9650; ABC',
	'QR_TRANSFORM_TEXT_INVERS'         => '&#9660;&#9650; aBC',
	'QR_TRANSFORM_TEXT_LOWER_TOOLTIP'  => 'minúscula',
	'QR_TRANSFORM_TEXT_UPPER_TOOLTIP'  => 'MAYÚSCULAS',
	'QR_TRANSFORM_TEXT_INVERS_TOOLTIP' => 'cSASO iNVERTIDO',
	//end mod CapsLock Transform
));
