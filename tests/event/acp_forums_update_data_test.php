<?php
/**
*
* @package Quickedit
* @copyright (c) 2015 - 2021 Marc Alexander ( www.m-a-styles.de )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

class acp_forums_update_data_test extends \marc1706\quickedit\tests\event\listener_test_base
{
	public function test_initialise_forums_flag_data()
	{
		$data = new \phpbb\event\data(array(
			'forum_data_sql'	=> array(
				'forum_flags'		=> 0,
				'enable_quick_edit'	=> true,
			),
		));

		$this->listener->acp_forums_update_data($data);
		$this->assertSame($data['forum_data_sql'], array(
			'forum_flags'	=> 128,
		));
	}
}
