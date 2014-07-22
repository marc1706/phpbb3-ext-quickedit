<?php
/**
*
* @package Quickedit
* @copyright (c) 2014 Marc Alexander ( www.m-a-styles.de )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace marc\quickedit\tests\event;

class listener_test_base extends \phpbb_test_case
{
	/** @var \marc\quickedit\event\listener */
	protected $listener;

	static public $hidden_fields = array();

	public function setup()
	{
		parent::setUp();

		$this->setup_listener();
	}

	public function setup_listener()
	{
		if (!isset($this->user))
		{
			$this->user = new \phpbb_mock_user();
		}
		if (!isset($this->auth))
		{
			$this->auth = new \phpbb\auth\auth();
		}
		if (!isset($this->config))
		{
			$this->config = new \phpbb\config\config(array());
		}
		$this->template = $this->getMock('\phpbb\template', array('assign_vars', 'assign_var', 'set_filenames', 'set_style', 'assign_display'));
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
			$this->request = $this->getMock('\phpbb\request\request', array('is_ajax'));
			$this->request->expects($this->any())
				->method('is_ajax')
				->with()
				->will($this->returnValue(true));
			$this->request->enable_super_globals();
		}
		$this->helper = new \marc\quickedit\event\listener_helper($this->auth, $this->config, $this->request);

		$this->listener = new \marc\quickedit\event\listener(
			$this->config,
			$this->helper,
			$this->request,
			$this->template,
			$this->user
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
		), array_keys(\marc\quickedit\event\listener::getSubscribedEvents()));
	}
}
