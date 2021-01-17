<?php
/**
*
* @package Quickedit
* @copyright (c) 2014 Marc Alexander ( www.m-a-styles.de )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

class initialise_forums_flag_data_test extends \marc1706\quickedit\tests\event\listener_test_base
{
	public function test_initialise_forums_flag_data()
	{
		$data = new \phpbb\event\data(array(
			'forum_flags'	=> 255,
		));

		$this->listener->initialise_forums_flag_data($data);
		$this->assertSame($data['forum_data'], array(
			'forum_flags'	=> 0,
		));
	}
}
