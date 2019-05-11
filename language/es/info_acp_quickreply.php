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
	'ACP_QUICKREPLY'                       => 'Respuesta Rápida',
	'ACP_QUICKREPLY_EXPLAIN'               => 'Ajustes de Respuesta Rápida',
	'ACP_QUICKREPLY_TITLE'                 => 'Respuesta Rápida',
	'ACP_QUICKREPLY_TITLE_EXPLAIN'         => 'Here you can set general and forum based settings for quick reply form itself.<br />NOTE: “Allow quick reply” and “Enable quick reply” are built-in phpBB settings listed here for convenience and completeness purposes. Other settings listed here depend on them.',
	//
	'ACP_QUICKREPLY_QN'                    => 'Ajustes de QuickQuote y QuickNick',
	'ACP_QUICKREPLY_QN_EXPLAIN'            => 'Ajustes de QuickQuote y QuickNick',
	'ACP_QUICKREPLY_QN_TITLE'              => 'Respuesta Rápida',
	'ACP_QUICKREPLY_QN_TITLE_EXPLAIN'      => 'Here you can set QuickQuote and QuickNick settings.<br />NOTE: these settings have no effect in forums where quick reply is disabled or if quick reply is disallowed.',
	//
	'ACP_QUICKREPLY_PLUGINS'               => 'Ajustes adicionales',
	'ACP_QUICKREPLY_PLUGINS_EXPLAIN'       => 'Ajustes adicionales',
	'ACP_QUICKREPLY_PLUGINS_TITLE'         => 'Respuesta Rápida',
	'ACP_QUICKREPLY_PLUGINS_TITLE_EXPLAIN' => 'Here you can set the settings for special features included in QuickReply extension.<br />NOTE: these settings work regardless of whether quick reply is enabled in a certain forum.',
	//
	'ACP_QR_AJAX_PAGINATION'               => 'Permitir navegar por los temas sin tener que recargar la página',
	'ACP_QR_AJAX_PAGINATION_EXPLAIN'       => 'If this setting is enabled, Ajax pagination will be used instead of standard form submissions when users enable the option “Do not refresh quick reply form when navigating the topic”.',
	'ACP_QR_AJAX_SUBMIT'                   => 'Allow Ajax posting',
	'ACP_QR_AJAX_SUBMIT_EXPLAIN'           => 'Allow sending messages without reloading the page.<br />When enabled, forum specific settings will be used to determine whether the Ajax posting is used in individual forums.',
	'ACP_QR_ALLOW_FOR_GUESTS'              => 'Permitir Respuesta Rápida para invitados , si está habilitado',
	'ACP_QR_ATTACH'                        => 'Permitir adjuntos',
	'ACP_QR_ATTACH_EXPLAIN'                => 'Permitir adjuntos en el formulario de Respuesta Rápida.',
	'ACP_QR_BBCODE'                        => 'Mostrar botones BBCode',
	'ACP_QR_BBCODE_EXPLAIN'                => 'Habilitar botones BBCode en el formulario de Respuesta Rápida.',
	'ACP_QR_CAPSLOCK'                      => 'Habilitar transformaciones de texto',
	'ACP_QR_COLOUR_NICKNAME'               => 'Añadir color cuando se hace referencia al nombre de usuario',
	'ACP_QR_COMMA'                         => 'Añadir coma después del nombre de usuario',
	'ACP_QR_COMMA_EXPLAIN'                 => 'Agregar automáticamente una coma después del nombre de usuario cuando se utiliza “Referir el nombre de usuario”.',
	'ACP_QR_CTRLENTER'                     => 'Habilitar el envío con “CTRL+Enter”',
	'ACP_QR_CTRLENTER_EXPLAIN'             => 'Allow sending a message by clicking “Ctrl+Enter” in quick reply and full reply forms. The tooltip about this functionality will be shown after hovering the cursor over the “Submit” button in quick reply form.',
	'ACP_QR_CTRLENTER_NOTICE'              => 'Enable “Ctrl+Enter” tooltip in quick reply form',
	'ACP_QR_CTRLENTER_NOTICE_EXPLAIN'      => 'The tooltip will be shown after hovering the cursor over the “Submit” button in quick reply form. Disabling this setting does not disable “Ctrl+Enter” functionality.',
	'ACP_QR_ENABLE_AJAX_SUBMIT'            => 'Enable Ajax posting in all forums',
	'ACP_QR_ENABLE_AJAX_SUBMIT_EXPLAIN'    => 'Allow Ajax posting in all forums right away.',
	'ACP_QR_ENABLE_RE'                     => 'Habilitar “Re:”',
	'ACP_QR_ENABLE_RE_EXPLAIN'             => 'Automatically add prefix “Re:” in the “Post subject” in quick reply and full reply forms.',
	'ACP_QR_ENABLE_QUICK_REPLY'            => 'Habilitar la Respuesta Rápida en todos los foros',
	'ACP_QR_ENABLE_QUICK_REPLY_EXPLAIN'    => 'Permitir la Respuesta Rápida en todos los foros de inmediato.',
	'ACP_QR_FORM_TYPE'                     => 'Tipo de formulario de Respuesta Rápida',
	'ACP_QR_FORM_TYPE_EXPLAIN'             => 'Si se selecciona la opción “Fijo con recargas de mensajes”, la capacidad de cargar los mensajes en la página actual utilizando los botones “mostrar los mensajes siguientes/anteriores” complementará la paginación estándar.', // reserved
	'ACP_QR_FORM_TYPE_FIXED'               => 'Fijo',
	'ACP_QR_FORM_TYPE_SCROLL'              => 'Fijo con recargas de mensajes', // reserved
	'ACP_QR_FORM_TYPE_STANDARD'            => 'Estándar',
	'ACP_QR_FORUM_AJAX_SUBMIT'             => 'Habilitar la publicación Ajax',
	'ACP_QR_FORUM_AJAX_SUBMIT_EXPLAIN'     => 'Permitir el envío de mensajes sin recargar la página.',
	'ACP_QR_FULL_QUOTE'                    => 'Insertar citas completas en el formulario de Respuesta Rápida',
	'ACP_QR_FULL_QUOTE_EXPLAIN'            => 'Reemplazar el comportamiento estándar del botón “Responder citando”.',
	'ACP_QR_HIDE_SUBJECT_BOX'              => 'Ocultar la caja de Asunto, si la modificación de Asunto está deshabilitada',
	'ACP_QR_HIDE_SUBJECT_BOX_EXPLAIN'      => 'Si un usuario no tiene permiso para modificar el Asunto de un mensaje, el campo de Asunto del formulario se oculta en vez de ser desactivado.',
	'ACP_QR_LAST_QUOTE'                    => 'Habilitar citas completas de los últimos mensajes de los temas',
	'ACP_QR_LAST_QUOTE_EXPLAIN'            => 'Allow full quotes through a standard quote button.<br /><em>Note that quote button will be hidden if this setting is disabled together with the setting for quick quote. This setting overrides user permission for full quotes.</em>',
	'ACP_QR_LEAVE_AS_IS'                   => 'Leave as is',
	'ACP_QR_LEAVE_AS_IS_EXPLAIN'           => '<em>If you select “Leave as is”, forum settings will not be changed.</em>',
	'ACP_QR_LEGEND_AJAX'                   => 'Ajax settings',
	'ACP_QR_LEGEND_DISPLAY'                => 'Display settings',
	'ACP_QR_LEGEND_GENERAL'                => 'General settings',
	'ACP_QR_LEGEND_QUICKNICK'              => 'Quick nick settings',
	'ACP_QR_LEGEND_QUICKQUOTE'             => 'Quick quote settings',
	'ACP_QR_LEGEND_SPECIAL'                => 'Special features',
	'ACP_QR_QUICKNICK'                     => 'Enable quick nick (in the dropdown menu)',
	'ACP_QR_QUICKNICK_EXPLAIN'             => 'Creates a dropdown with a link “Refer by username” that inserts the post author’s username in the quick reply form. That dropdown is triggered by a click on post author’s username and also contains links to user’s profile and “Reply in PM” (when available).<br />If this setting is enabled and the setting “Enable quick nick (under avatar)” is disabled, the user can switch to the version of the link “Refer by username” under avatar in the User Control Panel.',
	'ACP_QR_QUICKNICK_STRING'              => 'Enable quick nick (under avatar)',
	'ACP_QR_QUICKNICK_STRING_EXPLAIN'      => 'Shows a link “Refer by username” in users’ postprofiles that inserts the username in the quick reply form.',
	'ACP_QR_QUICKNICK_PM'                  => 'Include button “Reply in PM” into the dropdown of the function “Refer by username”',
	'ACP_QR_QUICKNICK_REF'                 => 'Habilitar etiqueta especial para el usuario referenciado',
	'ACP_QR_QUICKNICK_REF_EXPLAIN'         => 'BBCode [ref] will be used instead of [b] in the function “Refer by username”.<br /><em>Note that if this option is disabled, users will only receive notifications about being mentioned only when [ref] tag is inserted in a message manually.</em>',
	'ACP_QR_QUICKQUOTE'                    => 'Habilitar popup en cita rápida',
	'ACP_QR_QUICKQUOTE_BUTTON'             => 'Habilitar cita rápida usando el botón',
	'ACP_QR_QUICKQUOTE_BUTTON_EXPLAIN'     => 'Permita citas a través de un botón de cita estándar.<br /><em>Tenga en cuenta que el botón de citar se oculta si esta configuración está deshabilitada, y el usuario no tiene permiso para utilizarla para la cita completa.</em>',
	'ACP_QR_QUICKQUOTE_EXPLAIN'            => 'Permitir citas en un “popup” que aparece cuando se selecciona el texto en un mensaje.',
	'ACP_QR_QUICKQUOTE_LINK'               => 'Añadir un enlace al perfil del autor del mensaje al utilizar la cita rápida',
	'ACP_QR_SCROLL_TIME'                   => 'Tiempo para un solo evento de desplazamiento y animación',
	'ACP_QR_SCROLL_TIME_EXPLAIN'           => 'Tiempo en milisegundos para la función de desplazamiento suave. Introducir 0 para el desplazamiento estándar.',
	'ACP_QR_SHOW_BUTTON_TRANSLIT'          => 'Mostrar botón “Convertir”',
	'ACP_QR_SHOW_SUBJECTS'                 => 'Mostrar asuntos de los mensajes en los temas',
	'ACP_QR_SHOW_SUBJECTS_IN_SEARCH'       => 'Mostrar asuntos de los mensajes en los resultados de búsqueda',
	'ACP_QR_SMILIES'                       => 'Mostrar emoticonos',
	'ACP_QR_SMILIES_EXPLAIN'               => 'Permitir mostrar los Emoticonos en el formulario de Respuesta Rápida.',
	'ACP_QR_SOURCE_POST'                   => 'Añadir un enlace al mensaje citado al citar',
));
