<?php
/**
*
* @package Quickedit
* @copyright (c) 2014 Marc Alexander ( www.m-a-styles.de )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

class listener_helper_test extends \marc\quickedit\tests\event\listener_test_base
{
	public function test_acp_board_settings()
	{
		$this->assertFalse($this->helper->check_topic_edit(array(
			'topic_data' => array('forum_status' => ITEM_LOCKED),
		)));

		$this->assertFalse($this->helper->check_forum_permissions(array(
			'topic_data' => array('forum_flags' => 0),
		)));
	}
}
