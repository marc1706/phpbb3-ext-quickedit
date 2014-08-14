<?php
/**
*
* @package Quickedit
* @copyright (c) 2014 Marc Alexander ( www.m-a-styles.de )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

class acp_forums_settings_test extends \marc\quickedit\tests\event\listener_test_base
{
	public function setUp()
	{
		parent::setUp();

		$this->user = $this->getMock('\phpbb\user', array('add_lang_ext'), array('\phpbb\datetime'));
		$this->setup_listener();
	}

	public function test_acp_forums_settings()
	{
		$data = new \phpbb\event\data(array(
			'template_data'	=> array(),
			'forum_data'	=> array(
				'forum_flags'	=> 128,
			),
		));

		$this->listener->acp_forums_settings($data);

		$this->assertEquals($data['template_data'], array('S_ENABLE_QUICK_EDIT'	=> true));
	}
}
