<?php
/**
*
* @package Quickedit
* @copyright (c) 2015 - 2021 Marc Alexander ( www.m-a-styles.de )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

use marc1706\quickedit\event\listener;
use marc1706\quickedit\event\listener_helper;

class initialise_forums_flag_data_test extends \marc1706\quickedit\tests\event\listener_test_base
{
	public function setup() : void
	{
		parent::setup();

		$this->request = $this->getMockBuilder('\phpbb\request\request')
			->disableOriginalConstructor()
			->setMethods(['is_ajax', 'variable'])
			->getMock();
		$this->request->expects($this->any())
			->method('is_ajax')
			->with()
			->will($this->returnValue(true));
		$this->request->expects($this->any())
			->method('variable')
			->will($this->returnValue(true));
		$this->request->enable_super_globals();

		$phpbb_extension_manager = $this->getMockBuilder('\phpbb\extensions\manager')
			->disableOriginalConstructor()
			->setMethods(array('get_extension_path'))
			->getMock();
		$phpbb_extension_manager->expects($this->any())
			->method('get_extension_path')
			->with($this->anything())
			->will($this->returnValue('phpBB/ext/marc1706/quickedit/'));

		$this->listener = new listener(
			$this->config,
			$this->helper,
			$this->request,
			$this->template,
			$this->user,
			$this->language
		);
	}

	public function test_initialise_forums_flag_data()
	{
		$data = new \phpbb\event\data(array(
			'forum_data' => [
				'forum_flags'	=> 255,
			],
		));

		$this->listener->initialise_forums_flag_data($data);
		$this->assertSame($data['forum_data'], array(
			'forum_flags'	=> 255 + listener_helper::QUICKEDIT_FLAG,
		));
	}
}
