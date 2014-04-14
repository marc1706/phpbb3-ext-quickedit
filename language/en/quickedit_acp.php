<?php
/**
*
* Quickedit ACP [English]
*
* @package Quickedit
* @copyright (c) 2014 Marc Alexander ( www.m-a-styles.de )
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
	'ALLOW_QUICK_EDIT'		=> 'Allow quick edit',
	'ALLOW_QUICK_EDIT_EXPLAIN'	=> 'This switch allows for the quick edit to be disabled board-wide. When enabled, forum specific settings will be used to determine whether the quick edit is available in individual forums.',
	'ALLOW_QUICK_EDIT_BUTTON'	=> 'Submit and enable quick edit in all forums',
	'ENABLE_QUICK_EDIT'		=> 'Enable quick edit',
	'ENABLE_QUICK_EDIT_EXPLAIN'	=> 'Enables the quick edit in this forum. This setting is not considered if the quick edit is disabled board wide. The quick edit will only be available to users who have permission to edit posts in this forum.',
));
