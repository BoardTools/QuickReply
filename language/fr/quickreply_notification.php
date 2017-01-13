<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
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
	'NOTIFICATION_QUICKNICK'				=> array(
		1	=> '<strong>Mentioned</strong> by %1$s in the message:',
	),

	'NOTIFICATION_TYPE_QUICKNICK'	=> 'You have been mentioned in the message',

	'QR_BBPOST'                     => 'Source du message',
));
