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
	'QR_ATTACH_NOTICE'                 => 'К этому ответу прикреплено по крайней мере одно вложение.',
	'QR_BBCODE'                        => 'BBCode',
	'QR_CANCEL_SUBMISSION'             => 'Отменить отправку',
	'QR_CTRL_ENTER'                    => 'Вы также можете отправить свой ответ, одновременно нажав клавиши Ctrl и Enter на клавиатуре.',
	'QR_FORM_HIDE'                     => 'Свернуть форму быстрого ответа',
	'QR_FULLSCREEN'                    => 'Полноэкранный редактор',
	'QR_FULLSCREEN_EXIT'               => 'Выйти из полноэкранного режима',
	'QR_INSERT_TEXT'                   => 'Вставить цитату в окно ответа',
	'QR_QUICKQUOTE'                    => 'Быстрая цитата',
	'QR_QUICKQUOTE_TITLE'              => 'Ответить с быстрой цитатой',
	'QR_LOADING'                       => 'Загрузка',
	'QR_LOADING_ATTACHMENTS'           => 'Ожидание окончания загрузки вложений...',
	'QR_LOADING_NEW_FORM_TOKEN'        => 'Временная метка формы устарела и была обновлена.<br />Повторная отправка формы...',
	'QR_LOADING_NEW_POSTS'             => 'В тему было добавлено как минимум одно новое сообщение.<br />Ваше сообщение не было отправлено, потому что, возможно, вы захотите изменить его текст.<br />Получение новых сообщений...',
	'QR_LOADING_PREVIEW'               => 'Обработка предпросмотра...',
	'QR_LOADING_SUBMITTED'             => 'Ваше сообщение было успешно отправлено.<br />Обработка результата...',
	'QR_LOADING_SUBMITTING'            => 'Отправка вашего ответа...',
	'QR_LOADING_WAIT'                  => 'Ожидание ответа сервера...',
	'QR_MORE'                          => 'Другие действия',
	'QR_NO_FULL_QUOTE'                 => 'Пожалуйста, выделите часть сообщения, которую хотите процитировать.',
	'QR_PREVIEW_CLOSE'                 => 'Закрыть блок предпросмотра',
	'QR_PROFILE'                       => 'Перейти в профиль',
	'QR_QUICKNICK'                     => 'Обратиться по никнейму',
	'QR_QUICKNICK_TITLE'               => 'Вставить имя пользователя в окно быстрого ответа',
	'QR_REPLY_IN_PM'                   => 'Ответить в ЛС',
	'QR_TYPE_REPLY'                    => 'Введите свой ответ здесь...',
	'QR_WARN_BEFORE_UNLOAD'            => 'Введённый вами ответ не был отправлен и может быть потерян!',
	// begin mod Translit
	'QR_TRANSLIT_TEXT'                 => 'Транслит',
	'QR_TRANSLIT_TEXT_TO_RU'           => 'на русский', // can be changed to your language here and below
	'QR_TRANSLIT_TEXT_TOOLTIP'         => 'Для мгновенного отображения на русском языке нажмите на кнопку',
	'QR_TRANSLIT_FROM'                 => 'je,jo,ayu,ay,aj,oju,oje,oja,oj,uj,yi,ya,ja,ju,yu,ja,y,zh,i\',shch,sch,ch,sh,ea,a,b,v,w,g,d,e,z,i,k,l,m,n,o,p,r,s,t,u,f,x,c,\'e,\',`,j,h', // language specific adaptation required (do not use spaces or line breaks), use commas as separators here and below
	'QR_TRANSLIT_TO'                   => 'э,ё,aю,ай,ай,ою,ое,оя,ой,уй,ый,я,я,ю,ю,я,ы,ж,й,щ,щ,ч,ш,э,а,б,в,в,г,д,е,з,и,к,л,м,н,о,п,р,с,т,у,ф,х,ц,э,ь,ъ,й,х',
	'QR_TRANSLIT_FROM_CAPS'            => 'Yo,Jo,Ey,Je,Ay,Oy,Oj,Uy,Uj,Ya,Ja,Ju,Yu,Ja,Y,Zh,I\',Sch,Ch,Sh,Ea,Tz,A,B,V,W,G,D,E,Z,I,K,L,M,N,O,P,R,S,T,U,F,X,C,EA,J,H',
	'QR_TRANSLIT_TO_CAPS'              => 'Ё,Ё,Ей,Э,Ай,Ой,Ой,Уй,Уй,Я,Я,Ю,Ю,Я,Ы,Ж,Й,Щ,Ч,Ш,Э,Ц,А,Б,В,В,Г,Д,Е,З,И,К,Л,М,Н,О,П,Р,С,Т,У,Ф,Х,Ц,Э,Й,Х',
	// end mod Translit
	// begin mod CapsLock Transform
	'QR_TRANSFORM_TEXT'                => 'Изменение регистра текста',
	'QR_TRANSFORM_TEXT_TOOLTIP'        => 'Для изменения регистра выделите часть текста и нажмите нужную кнопку',
	'QR_TRANSFORM_TEXT_LOWER'          => '&#9660; абв',
	'QR_TRANSFORM_TEXT_UPPER'          => '&#9650; АБВ',
	'QR_TRANSFORM_TEXT_INVERS'         => '&#9660;&#9650; аБВ',
	'QR_TRANSFORM_TEXT_LOWER_TOOLTIP'  => 'нижний регистр',
	'QR_TRANSFORM_TEXT_UPPER_TOOLTIP'  => 'ВЕРХНИЙ РЕГИСТР',
	'QR_TRANSFORM_TEXT_INVERS_TOOLTIP' => 'иНВЕРСИЯ рЕГИСТРА',
	// end mod CapsLock Transform
));
