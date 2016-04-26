<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace boardtools\quickreply\functions;

class listener_helper
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \boardtools\quickreply\functions\captcha_helper */
	public $captcha_helper;

	/** @var \boardtools\quickreply\functions\form_helper */
	public $form_helper;

	/** @var \boardtools\quickreply\functions\plugins_helper */
	public $plugins_helper;

	/** @var \boardtools\quickreply\functions\notifications_helper */
	public $notifications_helper;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $php_ext;

	/**
	 * Constructor
	 *
	 * @param \phpbb\auth\auth             $auth
	 * @param \phpbb\config\config         $config
	 * @param \phpbb\template\template     $template
	 * @param \phpbb\user                  $user
	 * @param \phpbb\request\request       $request
	 * @param captcha_helper               $captcha_helper
	 * @param form_helper                  $form_helper
	 * @param plugins_helper               $plugins_helper
	 * @param notifications_helper         $notifications_helper
	 * @param string                       $phpbb_root_path Root path
	 * @param string                       $php_ext
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, \phpbb\request\request $request, captcha_helper $captcha_helper, form_helper $form_helper, plugins_helper $plugins_helper, notifications_helper $notifications_helper, $phpbb_root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->request = $request;
		$this->captcha_helper = $captcha_helper;
		$this->form_helper = $form_helper;
		$this->plugins_helper = $plugins_helper;
		$this->notifications_helper = $notifications_helper;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
	}

	/**
	 * Checks whether quick reply is enabled
	 *
	 * @param int   $forum_id   Forum ID
	 * @param array $topic_data Array with topic data
	 * @return bool
	 */
	public function qr_is_enabled($forum_id, $topic_data)
	{
		if (($this->user->data['is_registered'] || $this->config['qr_allow_for_guests']) &&
			$this->config['allow_quick_reply'] &&
			($topic_data['forum_flags'] & FORUM_FLAG_QUICK_REPLY) &&
			$this->auth->acl_get('f_reply', $forum_id)
		)
		{
			// Quick reply enabled forum
			return (($topic_data['forum_status'] == ITEM_UNLOCKED && $topic_data['topic_status'] == ITEM_UNLOCKED) || $this->auth->acl_get('m_edit', $forum_id));
		}
		return false;
	}

	/**
	 * Sets keys for the specified array of hidden fields if their values in another array are true
	 *
	 * @param array $hidden_fields Reference to the array of hidden fields
	 * @param array $set_array     Array containing keys and values.
	 *                             Each key will be set to 1 if the corresponding value is true
	 */
	protected function set_hidden_fields(&$hidden_fields, $set_array)
	{
		foreach ($set_array as $key => $value)
		{
			if ($value)
			{
				$hidden_fields[$key] = 1;
			}
		}
	}

	/**
	 * Checks variety of specified options
	 *
	 * @param string $config_var  Configuration variable name
	 * @param string $user_option User option key
	 * @param array  $acl_perms   Array with ACL options to check (key => forum ID)
	 * @return bool
	 */
	protected function check_option($config_var, $user_option, $acl_perms = array())
	{
		if ($config_var && !$this->config[$config_var] ||
			$user_option && !$this->user->optionget($user_option)
		)
		{
			return false;
		}
		if (!sizeof($acl_perms))
		{
			return true;
		}
		foreach ($acl_perms as $key => $forum_id)
		{
			if (!$this->auth->acl_get($key, $forum_id))
			{
				return false;
			}
		}
		return true;
	}

	/**
	 * Assign template variables for guests if quick reply is available for them
	 *
	 * @param int   $forum_id   Forum ID
	 * @param array $topic_data Array with topic data
	 */
	public function enable_qr_for_guests($forum_id, $topic_data)
	{
		$topic_id = $topic_data['topic_id'];
		add_form_key('posting');

		$s_attach_sig = $this->check_option('allow_sig', 'attachsig', array(
			'f_sigs' => $forum_id,
			'u_sig'  => 0
		));
		$s_smilies = $this->check_option('allow_smilies', 'smilies', array(
			'f_smilies' => $forum_id,
		));
		$s_bbcode = $this->check_option('allow_bbcode', 'bbcode', array(
			'f_bbcode' => $forum_id,
		));
		$s_notify = false;

		$qr_hidden_fields = array(
			'topic_cur_post_id' => (int) $topic_data['topic_last_post_id'],
			'lastclick'         => (int) time(),
			'topic_id'          => (int) $topic_data['topic_id'],
			'forum_id'          => (int) $forum_id,
		);

		// Originally we use checkboxes and check with isset(), so we only provide them if they would be checked
		$this->set_hidden_fields($qr_hidden_fields, array(
			'disable_bbcode'    => !$s_bbcode,
			'disable_smilies'   => !$s_smilies,
			'disable_magic_url' => !$this->config['allow_post_links'],
			'attach_sig'        => $s_attach_sig,
			'notify'            => $s_notify,
			'lock_topic'        => $topic_data['topic_status'] == ITEM_LOCKED,
		));

		$this->template->assign_vars(array(
			'S_QUICK_REPLY'    => true,
			'U_QR_ACTION'      => append_sid("{$this->phpbb_root_path}posting.$this->php_ext", "mode=reply&amp;f=$forum_id&amp;t=$topic_id"),
			'QR_HIDDEN_FIELDS' => build_hidden_fields($qr_hidden_fields),
			'USERNAME'         => $this->request->variable('username', '', true),
		));

		if ($this->config['enable_post_confirm'])
		{
			$this->captcha_helper->set_captcha();
		}
	}

	/**
	 * Assign template variables if quick reply is enabled
	 *
	 * @param int $forum_id Forum ID
	 */
	public function assign_template_variables_for_qr($forum_id)
	{
		$template_variables = $this->template_variables_for_qr();
		$template_variables += $this->plugins_helper->template_variables_for_plugins($forum_id);
		$template_variables += $this->plugins_helper->template_variables_for_extensions();

		$this->template->assign_vars($template_variables);
	}

	public function template_variables_for_qr()
	{
		return array(
			'S_QR_COLOUR_NICKNAME'    => $this->config['qr_color_nickname'],
			'QR_HIDE_SUBJECT_BOX'     => $this->config['qr_hide_subject_box'],
			'S_QR_COMMA_ENABLE'       => $this->config['qr_comma'],
			'S_QR_QUICKNICK_ENABLE'   => $this->config['qr_quicknick'],
			'S_QR_QUICKNICK_REF'      => $this->config['qr_quicknick_ref'],
			'S_QR_QUICKNICK_PM'       => $this->config['qr_quicknick_pm'],
			'S_QR_QUICKQUOTE_ENABLE'  => $this->config['qr_quickquote'],
			'S_QR_QUICKQUOTE_LINK'    => $this->config['qr_quickquote_link'],
			'S_QR_FULL_QUOTE'         => $this->config['qr_full_quote'],
			'S_QR_CE_ENABLE'          => $this->plugins_helper->qr_ctrlenter_enabled(),
			'QR_SOURCE_POST'          => $this->config['qr_source_post'],
			'S_DISPLAY_USERNAME'      => !$this->user->data['is_registered'],

			'MESSAGE'                   => $this->request->variable('message', '', true),
			'READ_POST_IMG'             => $this->user->img('icon_post_target', 'POST'),

			'S_QR_ALLOWED_GUEST' => $this->config['qr_allow_for_guests'] && $this->user->data['user_id'] == ANONYMOUS,
		);
	}

	public function qr_user_prefs_data($data)
	{
		return array(
			'S_AJAX_PAGINATION'        => $this->config['qr_ajax_pagination'],
			'S_ENABLE_AJAX_PAGINATION' => $data['ajax_pagination'],
			'S_QR_ENABLE_SCROLL'       => $data['qr_enable_scroll'],
			'S_QR_ALLOW_SOFT_SCROLL'   => !!$this->config['qr_scroll_time'],
			'S_QR_SOFT_SCROLL'         => $data['qr_soft_scroll'],
		);
	}

	public function qr_get_user_prefs_data($user_row)
	{
		return array(
			'ajax_pagination'  => $this->request->variable('ajax_pagination', (int) $user_row['ajax_pagination']),
			'qr_enable_scroll' => $this->request->variable('qr_enable_scroll', (int) $user_row['qr_enable_scroll']),
			'qr_soft_scroll'   => $this->request->variable('qr_soft_scroll', (int) $user_row['qr_soft_scroll']),
		);
	}

	public function qr_set_user_prefs_data($data)
	{
		return array(
			'ajax_pagination'  => $data['ajax_pagination'],
			'qr_enable_scroll' => $data['qr_enable_scroll'],
			'qr_soft_scroll'   => $data['qr_soft_scroll'],
		);
	}

	public function review_is_enable($lastclick, $post_data)
	{
		return ($lastclick < $post_data['topic_last_post_time']) && ($post_data['forum_flags'] & FORUM_FLAG_POST_REVIEW);
	}

	public function post_is_not_last($post_data)
	{
		return $post_data['topic_cur_post_id'] && $post_data['topic_cur_post_id'] != $post_data['topic_last_post_id'];
	}

	public function sql_select_current($sql_ary, $qr_get_current, $current_post, $post_list)
	{
		$compare = ($qr_get_current) ? ' >= ' : ' > ';
		$sql_ary['WHERE'] .= ' AND p.post_id' . $compare . $current_post;
		$this->qr_insert = true;
		$this->qr_first = ($current_post == min($post_list)) && $qr_get_current;

		return $sql_ary;
	}
}
