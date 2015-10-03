<?php
/**
*
* @package Quickedit
* @copyright (c) 2015 Marc Alexander ( www.m-a-styles.de )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace marc\quickedit\event;

class listener_helper
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var int quickedit forums flag */
	const QUICKEDIT_FLAG = 128;

	/**
	* Constructor for listener
	*
	* @param \phpbb\auth\auth $auth phpBB auth
	* @param \phpbb\config\config $config phpBB config
	* @param \phpbb\request\request $request phpBB request
	* @access public
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\request\request_interface $request)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->request = $request;
	}

	/**
	* Check if request is a catchable request
	*
	* @param object $event The event object
	* @return bool True if it's a catchable request, false if not
	* @access public
	*/
	public function is_catchable_request($event)
	{
		return $this->request->is_ajax() && !$event['submit'] && $event['mode'] == 'edit';
	}

	/**
	* Add hidden fields in order to prevent dropping the needed values upon
	* submission.
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function add_hidden_fields(&$event)
	{
		$event['s_hidden_fields'] .= build_hidden_fields(array(
			'attachment_data' 		=> $event['message_parser']->attachment_data,
			'poll_vote_change'		=> $this->not_empty_or_default($event['post_data']['poll_vote_change'], ' checked="checked"', ''),
			'poll_title'			=> $this->isset_or_default($event['post_data']['poll_title'], ''),
			'poll_option_text'		=> $this->not_empty_or_default($event['post_data']['poll_options'], implode("\n", $event['post_data']['poll_options']), ''),
			'poll_max_options'		=> $this->isset_or_default((int) $event['post_data']['poll_max_options'], 1),
			'poll_length'			=> $event['post_data']['poll_length'],
			'attach_sig'			=> $event['post_data']['enable_sig'],
			'topic_status'			=> $event['post_data']['topic_status'],
		));

		if (!empty($event['post_data']['topic_status']))
		{
			$event['s_hidden_fields'] .= build_hidden_fields(array(
				'lock_topic'			=> true,
			));
		}

		// Add hidden fields for kinerity/topicdescriptions
		$event['s_hidden_fields'] = $this->add_hidden_if_exists($event['s_hidden_fields'], $event['post_data'], 'topic_desc');
	}

	/**
	* Returns value if it is set, otherwise the default
	*
	* @param mixed $value The variable to check
	* @param mixed $default The default value to use if variable is not set
	* @return mixed Value if variable is set, default value if not
	* @access protected
	*/
	protected function isset_or_default($value, $default)
	{
		return (isset($value)) ? $value : $default;
	}

	/**
	* Returns value if it's not empty, otherwise the default
	*
	* @param mixed $check_value The variable to check
	* @param mixed $value The value if $check_value is not empty
	* @param mixed $default The default value to use if variable is empty
	* @return mixed Value if $check_value is not empty, default value if not
	* @access protected
	*/
	protected function not_empty_or_default($check_value, $value, $default)
	{
		return (!empty($check_value)) ? $value : $default;
	}

	/**
	* Enable quick edit
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function enable_quick_edit($event)
	{
		$cfg_array = ($this->request->is_set('config')) ? $this->request->variable('config', array('' => '')) : '';
		if (isset($cfg_array['allow_quick_edit']))
		{
			$this->config->set('allow_quick_edit', (bool) $cfg_array['allow_quick_edit']);
			\enable_bitfield_column_flag(FORUMS_TABLE, 'forum_flags', log(self::QUICKEDIT_FLAG, 2));
		}
		$event->offsetSet('submit', true);
	}

	/**
	* Add quickedit settings to acp settings by modifying the display vars
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function modify_acp_display_vars($event)
	{
		$new_display_var = array(
			'title'	=> $event['display_vars']['title'],
			'vars'	=> array(),
		);

		foreach ($event['display_vars']['vars'] as $key => $content)
		{
			$new_display_var['vars'][$key] = $content;
			if ($key == 'allow_quick_reply')
			{
				$new_display_var['vars']['allow_quick_edit'] = array(
					'lang'		=> 'ALLOW_QUICK_EDIT',
					'validate'	=> 'bool',
					'type'		=> 'custom',
					'function'	=> array('marc\quickedit\event\listener', 'quickedit_settings'),
					'explain' 	=> true,
				);
			}
		}
		$event->offsetSet('display_vars', $new_display_var);
	}

	/**
	* Check whether user can edit in this topic and forum
	*
	* @param object $event The event object
	* @return bool True if user can edit in this topic or forum, else false
	* @access public
	*/
	public function check_topic_edit($event)
	{
		if (($event['topic_data']['forum_status'] == ITEM_UNLOCKED && $event['topic_data']['topic_status'] == ITEM_UNLOCKED) || $this->auth->acl_get('m_edit', $event['forum_id']))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	* Check forum_permissions and flag
	*
	* @param object $event The event object
	* @return bool True if quickedit is enabled and user can reply in forum,
	*		false if not
	* @access public
	*/
	public function check_forum_permissions($event)
	{
		if (($event['topic_data']['forum_flags'] & self::QUICKEDIT_FLAG) && $this->auth->acl_get('f_reply', $event['forum_id']))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Add data to hidden fields if column exists in post_data array
	 *
	 * @param string $hidden_fields Hidden fields data
	 * @param array $data_array post_data array
	 * @param string $column Column name
	 *
	 * @return string Hidden fields data
	 */
	protected function add_hidden_if_exists($hidden_fields, $data_array, $column)
	{
		if (isset($data_array[$column]))
		{
			$hidden_fields .= build_hidden_fields(array(
				$column		=> $data_array[$column],
			));
		}

		return $hidden_fields;
	}
}
