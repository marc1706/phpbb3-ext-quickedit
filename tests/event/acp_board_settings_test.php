<?php
/**
*
* @package Quickedit
* @copyright (c) 2014 Marc Alexander ( www.m-a-styles.de )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

class acp_board_settings_test extends \marc1706\quickedit\tests\event\listener_test_base
{
	public function setUp()
	{
		parent::setUp();

		$this->request = $this->getMockBuilder('\phpbb\request\request')
			->disableOriginalConstructor()
			->setMethods(['is_ajax', 'is_set_post', 'is_set', 'variable'])
			->getMock();
		$this->request->expects($this->any())
			->method('is_ajax')
			->with()
			->will($this->returnValue(true));
		$this->request->expects($this->any())
			->method('is_set_post')
			->with()
			->will($this->returnValue(true));
		$this->request->expects($this->any())
			->method('is_set')
			->with()
			->will($this->returnValue(true));
		$this->request->expects($this->any())
			->method('variable')
			->with()
			->will($this->returnValue(array(
				'allow_quick_edit'	=> true,
			)));
		$this->request->enable_super_globals();
		$this->setup_listener();
	}

	public function test_acp_board_settings()
	{
		$data = new \phpbb\event\data(array(
			'mode'	=> 'features',
			'display_vars'	=> array(
				'title'	=> 'foobar',
				'vars'	=> array(
					'legend1'		=> 'ACP_BOARD_FEATURES',
					'allow_privmsg'		=> array('lang' => 'BOARD_PM', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
					'allow_quick_reply'	=> array('lang' => 'ALLOW_QUICK_REPLY', 'validate' => 'bool', 'type' => 'custom', 'method' => 'quick_reply', 'explain' => true),

					'legend2'		=> 'ACP_LOAD_SETTINGS',
					'load_birthdays'	=> array('lang' => 'YES_BIRTHDAYS', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				),
			),
		));

		$this->listener->acp_board_settings($data);
		$this->assertEquals(true, $data['submit']);
		$this->assertSame($data['display_vars']['vars'], array(
			'legend1'		=> 'ACP_BOARD_FEATURES',
			'allow_privmsg'		=> array('lang' => 'BOARD_PM', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
			'allow_quick_reply'	=> array('lang' => 'ALLOW_QUICK_REPLY', 'validate' => 'bool', 'type' => 'custom', 'method' => 'quick_reply', 'explain' => true),
			'allow_quick_edit'	=> array('lang' => 'ALLOW_QUICK_EDIT', 'validate' => 'bool', 'type' => 'custom', 'function' => array('marc1706\quickedit\event\listener', 'quickedit_settings'), 'explain' 	=> true),
			'legend2'		=> 'ACP_LOAD_SETTINGS',
			'load_birthdays'	=> array('lang' => 'YES_BIRTHDAYS', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
		));
	}
}

/**
* Mock of enable bitfield column flag
*
* @param string $table Table name
* @param string $flag_type Flag type
* @param double $flag Flag value
*/
function enable_bitfield_column_flag($table, $flag_type, $flag)
{
	acp_board_settings_test::assertEquals(FORUMS_TABLE, $table);
	acp_board_settings_test::assertEquals('forum_flags', $flag_type);
	acp_board_settings_test::assertEquals(log(128, 2), $flag);
}
