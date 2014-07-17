<?php
/**
*
* @package Quickedit
* @copyright (c) 2014 Marc Alexander ( www.m-a-styles.de )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace marc\quickedit\tests\event;

class listener_test extends \phpbb_test_case
{
	/** @var \marc\quickedit\event\listener */
	protected $listener;

	public function setup_listener()
	{
		$this->user = new \phpbb_mock_user();
		$this->auth = new \phpbb\auth\auth();
		$this->config = new \phpbb\config\config(array());
		$this->template = $this->getMock('\phpbb\template');
		$this->request = new \phpbb_mock_request();
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
