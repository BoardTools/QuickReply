<?php
/**
*
* quickreply [Ukrainian]
*
* @package language quickreply
* @copyright (c) 2013 Татьяна5
* @translation (c) 2014 www.phpbbukraine.net
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
	'ACP_QUICKREPLY'				=> 'Швидка відповідь',
	'ACP_QUICKREPLY_EXPLAIN'					=> 'Налаштування швидкої відповіді',
	
	'ACP_QR_BBCODE'					=> 'Включити BBcode',
	'ACP_QR_BBCODE_EXPLAIN'			=> 'Показувати BBcode у формі швидкої відповіді',
	'ACP_QR_COMMA'					=> 'Включити кому',
	'ACP_QR_COMMA_EXPLAIN'			=> 'Автоматично ставити кому при зверненні по ніку',
	'ACP_QR_CTRLENTER'				=> 'Включити відправку по Ctrl+Enter',
	'ACP_QR_CTRLENTER_EXPLAIN'		=> 'Включити відправку по Ctrl+Enter у формах повної та швидкої відповіді',
	'ACP_QR_ENABLE_RE'				=> 'Включити "Re:"',
	'ACP_QR_ENABLE_RE_EXPLAIN'		=> 'Включити "Re:" перед заголовком коментаря в повному і швидкому відповіді',
	'ACP_QR_QUICKNICK'				=> 'Включити звернення по ніку',
	'ACP_QR_QUICKNICK_EXPLAIN'		=> 'Включити вставку ніка в форму швидкої відповіді при натисканні на напис',
	'ACP_QR_QUICKQUOTE'				=> 'Включити швидке цитування',
	'ACP_QR_QUICKQUOTE_EXPLAIN'		=> 'Включити цитування через "вспливашку" при виділенні тексту в повідомленні',
	'ACP_QR_SMILIES'				=> 'Включити смайли',
	'ACP_QR_SMILIES_EXPLAIN'		=> 'Показувати смайли в формі швидкої відповіді',
));