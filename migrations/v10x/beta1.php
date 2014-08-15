<?php
/**
*
* @package Quickedit
* @copyright (c) 2014 Marc Alexander ( www.m-a-styles.de )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace marc\quickedit\migrations\v10x;

class beta1 extends \phpbb\db\migration\migration
{
	/** @var int quickedit forums flag */
	const QUICKEDIT_FLAG = 128;

	public function effectively_installed()
	{
		return isset($this->config['allow_quick_edit']);
	}

	public function update_schema()
	{
		return array(
			'change_columns'	=> array(
				$this->table_prefix . 'forums'			=> array(
					'forum_flags'		=> array('UINT', 32),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'change_columns'	=> array(
				$this->table_prefix . 'forums'			=> array(
					'forum_flags'		=> array('TINT:4', 32),
				),
			),
		);
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('allow_quick_edit', 0)),
		);
	}

	public function revert_data()
	{
		return array(
			array('custom', array(array($this, 'revert_forum_flags'))),
		);
	}

	public function revert_forum_flags()
	{
		$sql = 'SELECT forum_id, forum_flags FROM ' . FORUMS_TABLE . '
				WHERE forum_flags >= ' . self::QUICKEDIT_FLAG;
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$sql = 'UPDATE ' . FORUMS_TABLE . '
					SET forum_flags = ' . (($row['forum_flags'] & self::QUICKEDIT_FLAG) ? $row['forum_flags'] - self::QUICKEDIT_FLAG : $row['forum_flags']) . '
					WHERE forum_id = ' . $row['forum_id'];
			$this->db->sql_query($sql);
		}
		$this->db->sql_freeresult($result);
	}
}
