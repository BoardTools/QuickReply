<?php
/**
*
* quickreply [Ukrainian]
*
* @package language quickreply
* @copyright (c) 2014 Alex Fryschyn (Sherlock)
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
	'ACP_QUICKREPLY_EXPLAIN'		=> 'Налаштування швидкої відповіді',

	'ACP_QR_BBCODE'					=> 'Включити BBcode',
	'ACP_QR_BBCODE_EXPLAIN'			=> 'Дозволити відображення кнопок bbcode у формі швидкої відповіді',
	'ACP_QR_COMMA'					=> 'Включити кому',
	'ACP_QR_COMMA_EXPLAIN'			=> 'Автоматично додавати кому після ника при використанні опції «Звернення за ніком»',
	'ACP_QR_CTRLENTER'				=> 'Включити відправку по «Ctrl+Enter»',
	'ACP_QR_CTRLENTER_EXPLAIN'		=> 'Дозволити відправку повідомлення при натисканні «Ctrl+Enter» у формах повної та швидкого відповіді',
	'ACP_QR_ENABLE_RE'				=> 'Включити «Re:»',
	'ACP_QR_ENABLE_RE_EXPLAIN'		=> 'Автоматично додавати префікс «Re:» в поле «Заголовок повідомлення» у формах повної та швидкого відповіді',
	'ACP_QR_QUICKNICK'				=> 'Включити звернення за ніком',
	'ACP_QR_QUICKNICK_EXPLAIN'		=> 'Дозволити вставку ніка в форму швидкої відповіді при кліці на напис «Звернення за ніком»',
	'ACP_QR_QUICKQUOTE'				=> 'Включити швидке цитування',
	'ACP_QR_QUICKQUOTE_EXPLAIN'		=> 'Дозволити можливість цитування через «вспливашку», з&#39;являється при виділенні тексту в повідомленні',
	'ACP_QR_SMILIES'				=> 'Включити смайли',
	'ACP_QR_SMILIES_EXPLAIN'		=> 'Дозволити відображення смайлів в формі швидкої відповіді',
	'ACP_QR_CAPSLOCK'				=> 'Включить преобразование текста в верхний/нижний регистр',
	'ACP_QR_AJAX_SUBMIT'			=> 'Включить ajax-отправку сообщений',
	'ACP_QR_AJAX_SUBMIT_EXPLAIN'	=> 'Разрешить отправку сообщений без перезагрузки страницы',
	'ACP_QR_SHOW_SUBJECTS'			=> 'Отображать заголовки сообщений',
	'ACP_QR_SHOW_BUTTON_TRANSLIT'			=> 'Отображать кнопку «Транслит»',
	'ACP_QR_SOURCE_POST'			=> 'Добавлять в цитату ссылку на процитированное сообщение',
	'ACP_QR_ATTACH'					=> 'Разрешить загрузку вложений',
	'ACP_QR_COLOUR_NICKNAME'		=> 'Добавлять цвет при обращении по нику',
));
