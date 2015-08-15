<?php
/**
*
* quickreply [Arabic]
*
* @package language quickreply
* @copyright (c) 2014 William Jacoby (bonelifer)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
* Translated By : Bassel Taha Alhitary - www.alhitary.net
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
	'ACP_QUICKREPLY'				=> 'الرد السريع',
	'ACP_QUICKREPLY_EXPLAIN'		=> 'الإعدادات',

	'ACP_QR_BBCODE'					=> 'تفعيل الأكواد BBcode',
	'ACP_QR_BBCODE_EXPLAIN'			=> 'إضافة أزرار الـ BBcode في الرد السريع.',
	'ACP_QR_COMMA'					=> 'إضافة الفاصلة بعد إسم العضو ',
	'ACP_QR_COMMA_EXPLAIN'			=> 'إضافة علامة الفاصلة بعد إسم العضو تلقائياً عند استخدام خيار "الإشارة إلى الإسم".',
	'ACP_QR_CTRLENTER'				=> 'تفعيل الإرسال بواسطة "كنترول + إدخال" ',
	'ACP_QR_CTRLENTER_EXPLAIN'		=> 'السماح بإرسال الردود بواسطة النقر بإستمرار على زر الكنترول Ctrl وثم النقر على زر الإدخال Enter.',
	'ACP_QR_ENABLE_RE'				=> 'تفعيل الإختصار “Re:” ',
	'ACP_QR_ENABLE_RE_EXPLAIN'		=> 'إضافة المقدمة “Re:” تلقائياً في عنوان المشاركة بالرد السريع.',
	'ACP_QR_QUICKNICK'				=> 'ادخال إسم العضو ',
	'ACP_QR_QUICKNICK_EXPLAIN'		=> 'السماح بإدحال إسم العضو في الرد السريع عند النقر على "الإشارة إلى الإسم".',
	'ACP_QR_QUICKNICK_REF'			=> 'تفعيل وسم خاص لـ "الإشارة إلى الإسم" ',
	'ACP_QR_QUICKNICK_REF_EXPLAIN'	=> 'سيتم أستخدام كود البي بي [ref] بدلاً من الكود [b] للخيار "الإشارة إلى الإسم".',
	'ACP_QR_QUICKNICK_PM'			=> 'إضافة "الرد برسالة خاصة" من ضمن خيارات "الإشارة إلى الإسم" ',
	'ACP_QR_QUICKQUOTE'				=> 'تفعيل الإقتباس السريع ',
	'ACP_QR_QUICKQUOTE_EXPLAIN'		=> 'السماح بالإقتباسات بواسطة قائمة منسدلة تظهر عند تحديد نص في الردود التي تريد الإقتباس منها.',
	'ACP_QR_QUICKQUOTE_LINK'		=> 'إضافة رابط إلى الملف الشخصي للعضو الذي تم اقتباس مشاركته ',
	'ACP_QR_FULL_QUOTE'				=> 'دمج الإقتباس في الرد السريع ',
	'ACP_QR_FULL_QUOTE_EXPLAIN'		=> 'سيتم إضافة الإقتباس القياسي إلى الرد السريع عند النقر على زر الأقتباس في المنشور.',
	'ACP_QR_SMILIES'				=> 'تفعيل الإبتسامات ',
	'ACP_QR_SMILIES_EXPLAIN'		=> 'اظهار الإبتسامات في الرد السريع.',
	'ACP_QR_CAPSLOCK'				=> 'تفعيل تغيير حالة النص (حرف كبير/صغير) ',
	'ACP_QR_SHOW_BUTTON_TRANSLIT'	=> 'إظهار زر الترجمة إلى “الروسية” ',
	'ACP_QR_AJAX_SUBMIT'			=> 'تفعيل الرد بالأجاكس ',
	'ACP_QR_AJAX_SUBMIT_EXPLAIN'	=> 'السماح بإرسال الردود بدون إعادة تحميل الصفحة ( ajax ).',
	'ACP_QR_AJAX_PAGINATION'		=> 'السماح بتحريك الموضوع بدون إعادة تحميل الصفحة ',
	'ACP_QR_AJAX_PAGINATION_EXPLAIN'=> 'السماح للأعضاء بتفعيل الخيار “عدم تحديث الرد السريع عند تحريك الموضوع”.',
	'ACP_QR_SCROLL_TIME'			=> 'وقت التأثيرات المُتحركة ',
	'ACP_QR_SCROLL_TIME_EXPLAIN'	=> 'حدد الوقت بالملي ثانية للتأثيرات المُتحركة بعد إضافة الرد السريع. القيمة صفر ( 0 ) تعني إستخدام القيمة القياسية للمؤثرات.',
	'ACP_QR_SHOW_SUBJECTS'			=> 'إظهار عناوين المشاركات في الموضوع ',
	'ACP_QR_SHOW_SUBJECTS_IN_SEARCH'=> 'إظهار عناوين المشاركات في نتائج البحث ',
	'ACP_QR_SOURCE_POST'			=> 'إضافة رابط للمُشاركة التي تم اقتباسها ',
	'ACP_QR_ATTACH'					=> 'السماح بالمرفقات ',
	'ACP_QR_ALLOW_FOR_GUESTS'		=> 'السماح بالرد السريع للزائرين إذا مسموح لهم ',
	'ACP_QR_COLOUR_NICKNAME'		=> 'إضافة اللون عند الإشارة إلى إسم العضو ',
));
