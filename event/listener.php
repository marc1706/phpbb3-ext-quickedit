<?php
/**
*
* @package Quickedit
* @copyright (c) 2015 Marc Alexander ( www.m-a-styles.de )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace marc\quickedit\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \marc\quickedit\event\listener_helper */
	protected $helper;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\twig\twig */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/**
	* Constructor for listener
	*
	* @param \phpbb\config\config $config phpBB config
	* @param \marc\quickedit\event\listener_helper $helper Listener helper
	* @param \phpbb\request\request $request phpBB request
	* @param \phpbb\template\template $template phpBB template
	* @param \phpbb\user $user phpBB user
	* @access public
	*/
	public function __construct(\phpbb\config\config $config, \marc\quickedit\event\listener_helper $helper, \phpbb\request\request_interface $request, \phpbb\template\template $template, \phpbb\user $user)
	{
		$this->config = $config;
		$this->helper = $helper;
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
		if ($this->helper->is_catchable_request($event))
		{
			// Add hidden fields
			$this->helper->add_hidden_fields($event);

			// Update S_HIDDEN_FIELDS in page_data
			$this->template->assign_vars(array_merge($event['page_data'], array('S_HIDDEN_FIELDS' => $event['s_hidden_fields'])));
			$this->template->set_filenames(array(
				'body'	=> '@marc_quickedit/quickedit_body.html'
			));

			$json = new \phpbb\json_response();
			$json->send(array(
				'POST_ID'	=> $event['post_id'],
				'MESSAGE'	=> $this->template->assign_display('body'),
			));
		}
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
			$this->helper->modify_acp_display_vars($event);

			$this->user->add_lang_ext('marc/quickedit', 'quickedit_acp');

			if ($this->request->is_set_post('allow_quick_edit_enable'))
			{
				$this->helper->enable_quick_edit($event);
			}
		}
	}

	/**
	* Global quick edit enable/disable setting and button to enable in all forums
	*
	* @param bool $value Value of quickedit settings. 1 if enabled, 0 if disabled
	* @param string $key The key of the setting
	* @return string HTML for quickedit settings
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
		$forum_data['forum_flags'] += ($this->request->variable('enable_quick_edit', false)) ? listener_helper::QUICKEDIT_FLAG : 0;
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
		$template_data['S_ENABLE_QUICK_EDIT'] = ($event['forum_data']['forum_flags'] & listener_helper::QUICKEDIT_FLAG) ? true : false;
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
		$forum_data_sql['forum_flags'] += ($forum_data_sql['enable_quick_edit']) ? listener_helper::QUICKEDIT_FLAG : 0;
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
		$s_quick_edit = false;
		if ($this->user->data['is_registered'] && $this->config['allow_quick_edit'] && $this->helper->check_forum_permissions($event))
		{
			// Quick edit enabled forum
			$s_quick_edit = $this->helper->check_topic_edit($event);
		}
		$this->template->assign_var('S_QUICK_EDIT', $s_quick_edit);
	}
}
