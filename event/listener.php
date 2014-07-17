<?php
/**
*
* @package Quickedit
* @copyright (c) 2014 Marc Alexander ( www.m-a-styles.de )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace marc\quickedit\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\twig\twig */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var quickedit forums flag */
	const QUICKEDIT_FLAG = 128;

	/**
	* Constructor for listener
	*
	* @param \phpbb\auth\auth $auth phpBB auth
	* @param \phpbb\config\config $config phpBB config
	* @param \phpbb\request\request $request phpBB request
	* @param \phpbb\template\twig\twig $template phpBB template
	* @param \phpbb\user $user phpBB user
	* @access public
	*/
	public function __construct($auth, $config, $request, $template, $user)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.posting_modify_template_vars'		=> 'catch_ajax_requests',
			'core.acp_board_config_edit_add'		=> 'acp_board_settings',
			'core.acp_manage_forums_request_data'		=> 'add_forums_request_data',
			'core.acp_manage_forums_initialise_data'	=> 'initialise_forums_flag_data',
			'core.acp_manage_forums_display_form'		=> 'acp_forums_settings',
			'core.acp_manage_forums_update_data_before'	=> 'acp_forums_update_data',
			'core.viewtopic_modify_page_title'		=> 'check_quickedit_enabled',
		);
	}

	/**
	* Check if request is ajax and output quickedit if it is
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function catch_ajax_requests($event)
	{
		// Parse page for quickedit window
		if ($this->is_catchable_request($event))
		{
			// Add hidden fields
			$this->add_hidden_fields($event);

			// Update S_HIDDEN_FIELDS in page_data
			$this->template->assign_vars(array_merge($event['page_data'], array('S_HIDDEN_FIELDS' => $event['s_hidden_fields'])));
			$this->template->set_filenames(array(
				'body'	=> 'quickedit_body.html'
			));

			// Make sure quickedit extension will be used for
			// assign_display()
			$this->template->set_style(array('styles', 'ext/marc/quickedit/styles/'));

			$json = new \phpbb\json_response();
			$json->send(array(
				'POST_ID'	=> $event['post_id'],
				'MESSAGE'	=> $this->template->assign_display('body'),
			));
		}
	}

	/**
	* Check if request is a catchable request
	*
	* @param object $event The event object
	* @return bool True if it's a catchable request, false if not
	* @access protected
	*/
	protected function is_catchable_request($event)
	{
		return $this->request->is_ajax() && !$event['submit'] && $event['mode'] == 'edit';
	}

	/**
	* Add hidden fields in order to prevent dropping the needed values upon
	* submission.
	*
	* @param object $event The event object
	* @return null
	* @access protected
	*/
	protected function add_hidden_fields(&$event)
	{
		$event['s_hidden_fields'] .= build_hidden_fields(array(
			'attachment_data' 		=> $event['message_parser']->attachment_data,
			'poll_vote_change'		=> $this->not_empty_or_default($event['post_data']['poll_vote_change'], ' checked="checked"', ''),
			'poll_title'			=> $this->isset_or_default($event['post_data']['poll_title'], ''),
			'poll_option_text'		=> $this->not_empty_or_default($event['post_data']['poll_options'], implode("\n", $event['post_data']['poll_options']), ''),
			'poll_max_options'		=> $this->isset_or_default((int) $event['post_data']['poll_max_options'], 1),
			'poll_length'			=> $event['post_data']['poll_length'],
		));
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
	* Set ACP board settings
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function acp_board_settings($event)
	{
		if ($event['mode'] == 'features')
		{
			$this->modify_acp_display_vars($event);

			if ($this->request->is_set_post('allow_quick_edit_enable'))
			{
				$this->enable_quick_edit($event);
			}
		}
	}

	/**
	* Enable quick edit
	*
	* @param object $event The event object
	* @return null
	* @access protected
	*/
	protected function enable_quick_edit($event)
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
	* @access protected
	*/
	protected function modify_acp_display_vars($event)
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
	* Global quick edit enable/disable setting and button to enable in all forums
	*
	* @param bool $value Value of quickedit settings. 1 if enabled, 0 if disabled
	* @param string $key The key of the setting
	* @return HTML for quickedit settings
	* @access public
	*/
	static public function quickedit_settings($value, $key)
	{
		// Called statically so can't use $this->user
		global $user;

		$user->add_lang_ext('marc/quickedit', 'quickedit_acp');

		$radio_ary = array(1 => 'YES', 0 => 'NO');

		return h_radio('config[allow_quick_edit]', $radio_ary, $value) .
			'<br /><br /><input class="button2" type="submit" id="' . $key . '_enable" name="' . $key . '_enable" value="' . $user->lang('ALLOW_QUICK_EDIT_BUTTON') . '" />';
	}

	/**
	* Add quickedit settings to forums request data
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function add_forums_request_data($event)
	{
		$forum_data = $event['forum_data'];
		$forum_data += array('enable_quick_edit' => $this->request->variable('enable_quick_edit', false));
		$event->offsetSet('forum_data', $forum_data);
	}

	/**
	* Add quickedit flag to forums_flag
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function initialise_forums_flag_data($event)
	{
		$forum_data = $event['forum_data'];
		$forum_data['forum_flags'] += ($this->request->variable('enable_quick_edit', false)) ? self::QUICKEDIT_FLAG : 0;
		$event->offsetSet('forum_data', $forum_data);
	}

	/**
	* Add quickedit setting to acp_forums settings
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function acp_forums_settings($event)
	{
		$this->user->add_lang_ext('marc/quickedit', 'quickedit_acp');

		$template_data = $event['template_data'];
		$template_data['S_ENABLE_QUICK_EDIT'] = ($event['forum_data']['forum_flags'] & self::QUICKEDIT_FLAG) ? true : false;
		$event->offsetSet('template_data', $template_data);
	}

	/**
	* Update the forum_data_sql with the correct flag before submitting
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function acp_forums_update_data($event)
	{
		$forum_data_sql = $event['forum_data_sql'];
		$forum_data_sql['forum_flags'] += ($forum_data_sql['enable_quick_edit']) ? self::QUICKEDIT_FLAG : 0;
		unset($forum_data_sql['enable_quick_edit']);
		$event->offsetSet('forum_data_sql', $forum_data_sql);
	}

	/**
	* Check if quickedit is enabled and assign S_QUICK_EDIT accordingly
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function check_quickedit_enabled($event)
	{
		// Check if quick edit is available
		$s_quick_edit = 0;
		if ($this->user->data['is_registered'] && $this->config['allow_quick_edit'] && $this->check_forum_permissions($event))
		{
			// Quick edit enabled forum
			$s_quick_edit = $this->check_topic_edit($event);
		}
		$this->template->assign_var('S_QUICK_EDIT', $s_quick_edit);
	}

	/**
	* Check whether user can edit in this topic and forum
	*
	* @param object $event The event object
	* @return null
	* @access protected
	*/
	protected function check_topic_edit($event)
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
	* @return null
	* @access protected
	*/
	protected function check_forum_permissions($event)
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
}
