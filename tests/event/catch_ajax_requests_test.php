<?php
/**
*
* @package Quickedit
* @copyright (c) 2014 Marc Alexander ( www.m-a-styles.de )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace marc\quickedit\event;

class catch_ajax_requests_test extends \marc\quickedit\tests\event\listener_test_base
{
	static public $hidden_fields = array();

	public function data_catch_ajax_requests()
	{
		return array(
			array(true, array(
				'submit'	=> false,
				'mode'		=> 'edit',
				'post_data'	=> array(
					'poll_vote_change'	=> '',
					'poll_options'		=> array(),
					'poll_max_options'	=> 0,
					'poll_length'		=> 0,
					'enable_sig'		=> 1,
					'topic_status'		=> 0,
				),
				'page_data'	=> array(),
				's_hidden_fields',
			),
			array(
				'attachment_data'	=> null,
				'poll_vote_change'	=> '',
				'poll_title'		=> '',
				'poll_option_text'	=> '',
				'poll_max_options'	=> 0,
				'poll_length'		=> 0,
				'attach_sig'		=> 1,
				'topic_status'		=> 0,
			)),
			array(true, array(
				'submit'	=> false,
				'mode'		=> 'edit',
				'post_data'	=> array(
					'poll_vote_change'	=> '',
					'poll_options'		=> array(),
					'poll_max_options'	=> 0,
					'poll_length'		=> 0,
					'enable_sig'		=> 1,
					'topic_status'		=> 1,
					'topic_desc'		=> 'foo',
				),
				'page_data'	=> array(),
				's_hidden_fields',
			),
				array(
					'attachment_data'	=> null,
					'poll_vote_change'	=> '',
					'poll_title'		=> '',
					'poll_option_text'	=> '',
					'poll_max_options'	=> 0,
					'poll_length'		=> 0,
					'attach_sig'		=> 1,
					'topic_status'		=> 1,
					'lock_topic'		=> true,
				)),
			array(false, array(
				'submit'	=> true,
				'mode'		=> 'edit',
				'post_data'	=> array(
					'poll_vote_change'	=> '',
					'poll_options'		=> array(),
					'poll_max_options'	=> 0,
					'poll_length'		=> 0,
					'attach_sig'		=> 1,
					'topic_status'		=> 1,
				),
				'page_data'	=> array(),
			)),
		);
	}

	/**
	* @dataProvider data_catch_ajax_requests
	*/
	public function test_catch_ajax_requests($error = false, $event_data, $hidden_fields = array())
	{
		self::$hidden_fields = $hidden_fields;

		if ($error)
		{
			$this->setExpectedTriggerError(E_WARNING);
		}

		$this->assertNull($this->listener->catch_ajax_requests($event_data));

		$this->assertEquals(self::$hidden_fields, $hidden_fields);
	}
}

function build_hidden_fields($hidden_fields)
{
	catch_ajax_requests_test::$hidden_fields += $hidden_fields;

	return $hidden_fields;
}
