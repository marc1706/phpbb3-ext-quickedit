<?php

use marc1706\quickedit\tests\event\listener_test_base;
use phpbb\language\language;
use phpbb\language\language_file_loader;

/**
*
* @package Quickedit
* @copyright (c) 2014 Marc Alexander ( www.m-a-styles.de )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

class acp_forums_settings_test extends listener_test_base
{
	public function setUp()
	{
		global $phpbb_root_path, $phpEx;

		parent::setUp();

		$this->language = new language(new language_file_loader($phpbb_root_path, $phpEx));
		$this->user = $this->getMockBuilder('\phpbb\user')
			->setMethods(['add_lang_ext'])
			->setConstructorArgs([$this->language, '\phpbb\datetime'])
			->getMock();
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
