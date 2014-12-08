<?php
/**
*
* Quickedit ACP [Arabic]
*
* @package Quickedit
* @copyright (c) 2014 Marc Alexander ( www.m-a-styles.de )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
* Translated by Basil Taha Alhitary - www.alhitary.net
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

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	'ALLOW_QUICK_EDIT'		=> 'السماح بالتعديل السريع',
	'ALLOW_QUICK_EDIT_EXPLAIN'	=> 'عند اختيارك "نعم" , يجب عليك أيضاً الذهاب إلى إدارة المنتدىات والدخول إلى إعدادات كل منتدى على حده لتفعيل التعديل السريع. واختيارك " لا " يعني تعطيل التعديل السريع في كل المنتدى.',
	'ALLOW_QUICK_EDIT_BUTTON'	=> 'تفعيل التعديل السريع في جميع المنتديات',
	'ENABLE_QUICK_EDIT'		=> 'تفعيل التعديل السريع',
	'ENABLE_QUICK_EDIT_EXPLAIN'	=> 'تفعيل التعديل السريع في هذا المنتدى فقط. هذا الخيار لا يعمل في حالة تم تعطيل التعديل السريع من الإعدادات العامة للمنتدى ( خصائص المنتدى ). وسيتوفر التعديل السريع فقط للأعضاء الذين يملكون الصلاحية لتعديل المشاركات في هذا المنتدى.',
));
