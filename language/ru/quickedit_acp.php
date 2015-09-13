<?php
/**
*
* Quickedit ACP [Russian]
*
* @package Quickedit
* @copyright (c) 2014 Marc Alexander ( www.m-a-styles.de )
* @translated by LavIgor (https://github.com/LavIgor)
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
	'ALLOW_QUICK_EDIT'		=> 'Разрешить быструю правку',
	'ALLOW_QUICK_EDIT_EXPLAIN'	=> 'При включении данной опции в необходимых форумах также должна быть включена опция быстрой правки.',
	'ALLOW_QUICK_EDIT_BUTTON'	=> 'Отправить и включить быструю правку во всех форумах',
	'ENABLE_QUICK_EDIT'		=> 'Включить быструю правку',
	'ENABLE_QUICK_EDIT_EXPLAIN'	=> 'Включает форму быстрой правки для этого форума. Настройка не действует, если функция быстрой правки на конференции не включена. Быстрая правка будет доступна только для пользователей, имеющих право редактировать сообщения в данном форуме.',
));
