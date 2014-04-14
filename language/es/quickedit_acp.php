<?php
/**
*
* Quickedit ACP [Spanish]
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
	'ALLOW_QUICK_EDIT'      => 'Permitir edición rápida',
	'ALLOW_QUICK_EDIT_EXPLAIN'   => 'Este interruptor permite deshabilitar la edición rápida en el foro entero. Cuando está activada, se puede utilizar la configuración específica del foro para determinar si la edición rápida está disponible en los foros individualmente.',
	'ALLOW_QUICK_EDIT_BUTTON'   => 'Enviar y habilitar la edición rápida en todos los foros',
	'ENABLE_QUICK_EDIT'      => 'Habilitar edición rápida',
	'ENABLE_QUICK_EDIT_EXPLAIN'   => 'Permite la edición rápida en este foro. Este ajuste no se considera si la edición rápida es para foros desactivados. La edición rápida sólo estará disponible para los usuarios que tienen permiso para editar mensajes en este foro.',
));
