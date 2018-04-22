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
	'ACP_QUICKREPLY'          => 'Schnellantwort',
	'ACP_QUICKREPLY_EXPLAIN'  => 'Schnellantwort Einstellungen',
	//
	'ACL_A_QUICKREPLY'        => 'Kann die Einstellungen der Schnellantwort ändern',
	'ACL_F_QR_CHANGE_SUBJECT' => 'Kann den Beitragstitel ändern',
	'ACL_F_QR_FULL_QUOTE'     => 'Kann Komplettzitat in Themen benutzen <br><em>Es wird vorgeschlagen, Schnellzitate zu verwenden, wenn der Benutzer diese Berechtigung nicht besitzt, und die Funktion für Schnellzitate aktiviert ist.</em>',
));
