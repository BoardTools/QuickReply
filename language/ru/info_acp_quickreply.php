<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2020 Татьяна5 and LavIgor
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
	$lang = [];
}

$lang = array_merge($lang, [
	'ACP_QUICKREPLY'                       => 'Быстрый ответ',
	'ACP_QUICKREPLY_EXPLAIN'               => 'Настройки быстрого ответа',
	'ACP_QUICKREPLY_TITLE'                 => 'Быстрый ответ',
	'ACP_QUICKREPLY_TITLE_EXPLAIN'         => 'Здесь вы можете задать основные и форумные настройки для формы быстрого ответа.<br />ПРИМЕЧАНИЕ: настройки «Разрешить быстрый ответ» и «Включить быстрый ответ» встроены в движок phpBB и представлены здесь для удобства и полноты. Другие представленные здесь настройки зависят от них.',
	//
	'ACP_QUICKREPLY_QN'                    => 'Настройки быстрой цитаты и обращения по нику',
	'ACP_QUICKREPLY_QN_EXPLAIN'            => 'Настройки быстрой цитаты и обращения по нику',
	'ACP_QUICKREPLY_QN_TITLE'              => 'Быстрый ответ',
	'ACP_QUICKREPLY_QN_TITLE_EXPLAIN'      => 'Здесь вы можете задать настройки быстрой цитаты и обращения по нику.<br />ПРИМЕЧАНИЕ: эти настройки не применяются в форумах, в которых быстрый ответ выключен, или в случае, если быстрый ответ не разрешён.',
	//
	'ACP_QUICKREPLY_PLUGINS'               => 'Дополнительные настройки',
	'ACP_QUICKREPLY_PLUGINS_EXPLAIN'       => 'Дополнительные настройки',
	'ACP_QUICKREPLY_PLUGINS_TITLE'         => 'Быстрый ответ',
	'ACP_QUICKREPLY_PLUGINS_TITLE_EXPLAIN' => 'Здесь вы можете задать настройки для особых функций, включённых в расширение для быстрого ответа.<br />ПРИМЕЧАНИЕ: эти настройки применяются независимо от того, включён ли быстрый ответ в конкретном форуме.',
	//
	'ACP_QR_AJAX_PAGINATION'               => 'Включить просмотр тем без перезагрузки страницы',
	'ACP_QR_AJAX_PAGINATION_EXPLAIN'       => 'Если эта настройка включена, навигация Ajax будет использована вместо обычной отправки формы при включении пользователями опции «Не очищать форму быстрого ответа при просмотре темы».',
	'ACP_QR_AJAX_SUBMIT'                   => 'Разрешить Ajax отправку сообщений',
	'ACP_QR_AJAX_SUBMIT_EXPLAIN'           => 'Разрешить отправку сообщений без перезагрузки страницы.<br />При включении данной опции в необходимых форумах также должна быть включена опция Ajax отправки.',
	'ACP_QR_ALLOW_FOR_GUESTS'              => 'Разрешить использовать быстрый ответ гостям, если он включён',
	'ACP_QR_ATTACH'                        => 'Разрешить загрузку вложений',
	'ACP_QR_ATTACH_EXPLAIN'                => 'Разрешить загрузку вложений из формы быстрого ответа.',
	'ACP_QR_BBCODE'                        => 'Отображать кнопки BBCode',
	'ACP_QR_BBCODE_EXPLAIN'                => 'Разрешить отображение кнопок BBCode в форме быстрого ответа.',
	'ACP_QR_CAPSLOCK'                      => 'Включить преобразование текста в верхний/нижний регистр',
	'ACP_QR_COLOUR_NICKNAME'               => 'Добавлять цвет при обращении по нику',
	'ACP_QR_COMMA'                         => 'Включить запятую',
	'ACP_QR_COMMA_EXPLAIN'                 => 'Автоматически добавлять запятую после ника при использовании опции «Обращение по никнейму».',
	'ACP_QR_CTRLENTER_NOTICE'              => 'Включить подсказку о возможности использования «Ctrl+Enter»',
	'ACP_QR_CTRLENTER_NOTICE_EXPLAIN'      => 'Подсказка будет показана при наведении курсора на кнопку «Отправить» в форме быстрого ответа. Отключение данной настройки не отключает функцию отправки сообщения при нажатии «Ctrl+Enter».',
	'ACP_QR_ENABLE_AJAX_SUBMIT'            => 'Включить Ajax отправку сообщений во всех форумах',
	'ACP_QR_ENABLE_AJAX_SUBMIT_EXPLAIN'    => 'Разрешить использовать Ajax отправку сообщений сразу во всех форумах.',
	'ACP_QR_ENABLE_RE'                     => 'Включить префикс «Re:»',
	'ACP_QR_ENABLE_RE_EXPLAIN'             => 'Автоматически добавлять префикс «Re:» в поле «Заголовок сообщения» в формах полного и быстрого ответа.',
	'ACP_QR_ENABLE_QUICK_REPLY'            => 'Включить быстрый ответ во всех форумах',
	'ACP_QR_ENABLE_QUICK_REPLY_EXPLAIN'    => 'Разрешить использовать быстрый ответ сразу во всех форумах.',
	'ACP_QR_FORM_TYPE'                     => 'Тип формы быстрого ответа',
	'ACP_QR_FORM_TYPE_EXPLAIN'             => 'При выборе типа «Фиксированный с подгрузкой сообщений» стандартная постраничная навигация будет дополнена возможностью подгружать сообщения на текущую страницу при помощи кнопок показа следующих/предыдущих сообщений.', // reserved
	'ACP_QR_FORM_TYPE_FIXED'               => 'Фиксированный',
	'ACP_QR_FORM_TYPE_SCROLL'              => 'Фиксированный с подгрузкой сообщений', // reserved
	'ACP_QR_FORM_TYPE_STANDARD'            => 'Стандартный',
	'ACP_QR_FORUM_AJAX_SUBMIT'             => 'Включить Ajax отправку сообщений',
	'ACP_QR_FORUM_AJAX_SUBMIT_EXPLAIN'     => 'Разрешить отправку сообщений без перезагрузки страницы.',
	'ACP_QR_FULL_QUOTE'                    => 'Включить полное цитирование',
	'ACP_QR_FULL_QUOTE_EXPLAIN'            => 'Разрешить вставку полных цитат в форму быстрого ответа по клику на кнопку «Ответить с цитатой».',
	'ACP_QR_HIDE_SUBJECT_BOX'              => 'Скрывать поле «Заголовок», если изменять заголовок сообщения не разрешено',
	'ACP_QR_HIDE_SUBJECT_BOX_EXPLAIN'      => 'Если у пользователя отсутствует право на изменение заголовка сообщения, поле «Заголовок» будет скрыто, а не заблокировано в форме отправки сообщения.',
	'ACP_QR_LAST_QUOTE'                    => 'Включить полное цитирование последних сообщений тем',
	'ACP_QR_LAST_QUOTE_EXPLAIN'            => 'Разрешить возможность полного цитирования с помощью стандартной кнопки цитаты.<br /><em>Обратите внимание на то, что кнопка цитаты будет скрыта, если эта настройка отключена вместе с настройкой для быстрой цитаты. Эта настройка переопределяет право доступа для полной цитаты.</em>',
	'ACP_QR_LEAVE_AS_IS'                   => 'Оставить как есть',
	'ACP_QR_LEAVE_AS_IS_EXPLAIN'           => '<em>При выборе «Оставить как есть» форумные настройки не поменяются.</em>',
	'ACP_QR_LEGEND_AJAX'                   => 'Настройки Ajax',
	'ACP_QR_LEGEND_DISPLAY'                => 'Настройки отображения',
	'ACP_QR_LEGEND_GENERAL'                => 'Основные настройки',
	'ACP_QR_LEGEND_QUICKNICK'              => 'Настройки обращения по никнейму',
	'ACP_QR_LEGEND_QUICKQUOTE'             => 'Настройки быстрой цитаты',
	'ACP_QR_LEGEND_SPECIAL'                => 'Особые функции',
	'ACP_QR_QUICKNICK'                     => 'Включить обращение по никнейму (в выпадающем меню)',
	'ACP_QR_QUICKNICK_EXPLAIN'             => 'Создаёт выпадающее меню со ссылкой «Обратиться по никнейму», позволяющей вставить никнейм пользователя в форму быстрого ответа. Выпадающее меню отображается после клика на никнейме автора сообщения и также содержит ссылки на профиль пользователя и «Ответить в ЛС» (если доступно).<br />Если эта настройка включена, а настройка «Включить обращение по никнейму (строка под аватарой)» отключена, пользователь в личном разделе сможет переключиться на вариант ссылки «Обратиться по никнейму» под аватарой.',
	'ACP_QR_QUICKNICK_STRING'              => 'Включить обращение по никнейму (строка под аватарой)',
	'ACP_QR_QUICKNICK_STRING_EXPLAIN'      => 'Отображает ссылку «Обратиться по никнейму» в минипрофилях пользователей в темах, позволяющую вставить никнейм пользователя в форму быстрого ответа.',
	'ACP_QR_QUICKNICK_PM'                  => 'Включить кнопку «Ответить в ЛС» в выпадающий список функции «Обратиться по никнейму»',
	'ACP_QR_QUICKNICK_REF'                 => 'Включить специальный тег для обращения по никнейму',
	'ACP_QR_QUICKNICK_REF_EXPLAIN'         => 'BBCode [ref] будет добавляться вместо [b] в функции «Обратиться по никнейму».<br /><em>Обратите внимание на то, что при отключении этой настройки пользователи будут получать уведомления об упоминаниях только в том случае, если тег [ref] был добавлен в сообщение вручную.</em>',
	'ACP_QR_QUICKQUOTE'                    => 'Включить быстрое цитирование через «всплывашку»',
	'ACP_QR_QUICKQUOTE_BUTTON'             => 'Включить быстрое цитирование по кнопке',
	'ACP_QR_QUICKQUOTE_BUTTON_EXPLAIN'     => 'Разрешить возможность цитирования по клику на кнопку «Ответить с цитатой».<br /><em>Обратите внимание на то, что кнопка цитаты будет скрыта, если эта настройка отключена и у пользователя отсутствует право доступа для использования полной цитаты.</em>',
	'ACP_QR_QUICKQUOTE_EXPLAIN'            => 'Разрешить возможность цитирования через «всплывашку», появляющуюся при выделении текста в сообщении.',
	'ACP_QR_SCROLL_TIME'                   => 'Время выполнения одного действия прокрутки и анимации',
	'ACP_QR_SCROLL_TIME_EXPLAIN'           => 'Время в миллисекундах для функции плавной прокрутки. Введите 0 для стандартной прокрутки.',
	'ACP_QR_SHOW_BUTTON_TRANSLIT'          => 'Включить кнопку «Транслит»',
	'ACP_QR_SHOW_SUBJECTS'                 => 'Отображать заголовки сообщений в темах',
	'ACP_QR_SHOW_SUBJECTS_IN_SEARCH'       => 'Отображать заголовки сообщений в результатах поиска',
	'ACP_QR_SMILIES'                       => 'Отображать смайлы',
	'ACP_QR_SMILIES_EXPLAIN'               => 'Разрешить отображение смайлов в форме быстрого ответа.',
]);
