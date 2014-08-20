<?php
/**
*
* @package Quickedit
* @copyright (c) 2014 Marc Alexander ( www.m-a-styles.de )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

class quickedit_settings_test extends \marc\quickedit\tests\event\listener_test_base
{
	public function test_acp_board_settings()
	{
		global $user;

		$user = $this->getMock('\phpbb\user', array('add_lang_ext', 'lang'), array('\phpbb\datetime'));
		$user->expects($this->any())
			->method('lang')
			->with()
			->will($this->returnValue('barfoo'));

		$expected_result = 'foobar<br /><br /><input class="button2" type="submit" id="bar_enable" name="bar_enable" value="barfoo" />';
		$this->assertEquals($expected_result, \marc\quickedit\event\listener::quickedit_settings(true, 'bar'));
	}
}

/**
* Mock of h_radio function
*
* @param string $config_name Config name
* @param array $radio_ary Array of radio values
* @param mixed $value Value of field
*
* @return string Foobar string
*/
function h_radio($config_name, $radio_ary, $value)
{
	quickedit_settings_test::assertEquals($config_name, 'config[allow_quick_edit]');
	quickedit_settings_test::assertEquals($radio_ary, array(1 => 'YES', 0 => 'NO'));
	quickedit_settings_test::assertEquals($value, true);

	return 'foobar';
}
