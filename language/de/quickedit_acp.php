<?php
/**
*
* Quickedit ACP [Deutsch]
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
	'ALLOW_QUICK_EDIT'		=> 'Schnelles Ändern erlauben',
	'ALLOW_QUICK_EDIT_EXPLAIN'	=> 'Nein deaktivert die Quick-Edit-Funktion boardweit. Wenn sie aktiviert ist, kann mit den Forum spezifischen Einstellungen definiert werden, wo der Quick-Edit benutzt werden kann.',
	'ALLOW_QUICK_EDIT_BUTTON'	=> 'Absenden und Quick-Edit wird für alle Foren aktiviert.',
	'ENABLE_QUICK_EDIT'		=> 'Quick-Edit aktivieren',
	'ENABLE_QUICK_EDIT_EXPLAIN'	=> 'Aktiviert Quick-Edit in diesem Forum. Diese Einstellung wird nicht berücksichtigt, wenn Quick-Edit global deaktiviert wurde. Die Quick-Edit Funktion ist nur Benutzern zugänglich, die das Rechte haben Beiträge in dem Forum zu ändern.',
));
