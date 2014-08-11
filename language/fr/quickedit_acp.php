<?php
/**
*
* Quickedit ACP [Français]
*
* @package Quickedit
* @copyright (c) 2014 Marc Alexander ( www.m-a-styles.de )
* @translation by Georges.L (geolim4.com)
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
	'ALLOW_QUICK_EDIT'		=> 'Autoriser la modification rapide',
	'ALLOW_QUICK_EDIT_EXPLAIN'	=> 'Ce bouton permet de désactiver la modification rapide sur l’ensemble du forum. Si activé, les paramètres spécifiques du forum seront utilisés pour déterminer si la modification rapide est disponible dans les forums individuels.',
	'ALLOW_QUICK_EDIT_BUTTON'	=> 'Valider et activer la modification rapide dans tous les forums',
	'ENABLE_QUICK_EDIT'		=> 'Activer la modification rapide',
	'ENABLE_QUICK_EDIT_EXPLAIN'	=> 'Active la modification rapide dans ce forum. Ce paramètre n’est pas pris en compte si la modification rapide est désactivée sur l’ensemble du forum. La modification rapide ne sera disponible que pour les utilisateurs qui ont la permission de modifier les messages dans ce forum.',
));
