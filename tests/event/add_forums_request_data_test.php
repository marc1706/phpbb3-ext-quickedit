<?php
/**
*
* @package Quickedit
* @copyright (c) 2014 Marc Alexander ( www.m-a-styles.de )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

class add_forums_request_data_test extends \marc\quickedit\tests\event\listener_test_base
{
	public function test_add_forums_request_data()
	{
		$data = new \phpbb\event\data(array(
			'forum_data'	=> array(),
		));

		$this->listener->add_forums_request_data($data);
		$this->assertSame($data['forum_data'], array(
			'enable_quick_edit'	=> false,
		));
	}
}
