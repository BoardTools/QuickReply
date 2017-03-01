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
	'QR_ENABLE_AJAX_PAGINATION' => 'No refrescar el formulario de Respuesta Rápida cuando se navega en el tema',
	'QR_ENABLE_SCROLL'          => 'Habilitar desplazamiento automático al navegar en el tema',
	'QR_ENABLE_SOFT_SCROLL'     => 'Habilitar desplazamiento suave y animaciones al navegar por el tema, y después de la Respuesta Rápida',
));
