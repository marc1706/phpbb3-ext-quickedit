<?php
/**
*
* @package Quickedit
* @copyright (c) 2014 Marc Alexander ( www.m-a-styles.de )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace marc1706\quickedit\tests\event;

use marc1706\quickedit\event\listener;
use phpbb\language\language;
use phpbb\user;

class listener_test_base extends \phpbb_test_case
{
	/** @var language */
	protected $language;

	/** @var user */
	protected $user;

	/** @var listener */
	protected $listener;

	static public $hidden_fields = array();

	public function setup() : void
	{
		parent::setUp();

		$this->setup_listener();
	}

	public function setup_listener()
	{
		global $phpbb_root_path, $phpEx, $phpbb_extension_manager;

		if (!isset($this->user))
		{
			$this->language = new language(new \phpbb\language\language_file_loader($phpbb_root_path, $phpEx));
			$this->user = new user($this->language, '\phpbb\datetime');
			$this->user->data['user_lang'] = 'en';
			$this->user->lang_name = 'en';
		}
		if (!isset($this->auth))
		{
			$this->auth = new \phpbb\auth\auth();
		}
		if (!isset($this->config))
		{
			$this->config = new \phpbb\config\config(array());
		}
		$this->template = $this->getMockBuilder('\phpbb\template\template')
			->disableOriginalConstructor()
			->getMock();
		$this->template->expects($this->any())
			->method('assign_vars')
			->with($this->anything());
		$this->template->expects($this->any())
			->method('set_filenames')
			->with($this->anything());
		$this->template->expects($this->any())
			->method('set_style')
			->with($this->anything());
		$this->template->expects($this->any())
			->method('assign_display')
			->with($this->anything())
			->will($this->returnValue(''));

		if (!isset($this->request))
		{
			$this->request = $this->getMockBuilder('\phpbb\request\request')
				->disableOriginalConstructor()
				->setMethods(['is_ajax'])
				->getMock();
			$this->request->expects($this->any())
				->method('is_ajax')
				->with()
				->will($this->returnValue(true));
			$this->request->enable_super_globals();
		}
		$this->helper = new \marc1706\quickedit\event\listener_helper($this->auth, $this->config, $this->request);

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

	public function test_construct()
	{
		$this->setup_listener();
		$this->assertInstanceOf('\Symfony\Component\EventDispatcher\EventSubscriberInterface', $this->listener);
	}

	public function test_getSubscribedEvents()
	{
		$this->assertEquals(array(
			'core.posting_modify_template_vars',
			'core.acp_board_config_edit_add',
			'core.acp_manage_forums_request_data',
			'core.acp_manage_forums_initialise_data',
			'core.acp_manage_forums_display_form',
			'core.acp_manage_forums_update_data_before',
			'core.viewtopic_modify_page_title',
		), array_keys(listener::getSubscribedEvents()));
	}
}
