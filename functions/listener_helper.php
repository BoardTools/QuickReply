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

	/** @var \phpbb\extension\manager */
	protected $phpbb_extension_manager;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \boardtools\quickreply\functions\ajax_helper */
	public $ajax_helper;

	/** @var \boardtools\quickreply\functions\captcha_helper */
	public $captcha_helper;

	/** @var \boardtools\quickreply\functions\form_helper */
	public $form_helper;

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
	 * @param \phpbb\extension\manager     $phpbb_extension_manager
	 * @param \phpbb\request\request       $request
	 * @param ajax_helper                  $ajax_helper
	 * @param captcha_helper               $captcha_helper
	 * @param form_helper                  $form_helper
	 * @param notifications_helper         $notifications_helper
	 * @param string                       $phpbb_root_path Root path
	 * @param string                       $php_ext
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, \phpbb\extension\manager $phpbb_extension_manager, \phpbb\request\request $request, ajax_helper $ajax_helper, captcha_helper $captcha_helper, form_helper $form_helper, notifications_helper $notifications_helper, $phpbb_root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->request = $request;
		$this->ajax_helper = $ajax_helper;
		$this->captcha_helper = $captcha_helper;
		$this->form_helper = $form_helper;
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
		$this->assign_template_variables_for_extensions();
		$this->template->assign_vars(array(
			'S_QR_COLOUR_NICKNAME'    => $this->config['qr_color_nickname'],
			'S_QR_NOT_CHANGE_SUBJECT' => !$this->auth->acl_get('f_qr_change_subject', $forum_id),
			'QR_HIDE_SUBJECT_BOX'     => $this->config['qr_hide_subject_box'],
			'S_QR_COMMA_ENABLE'       => $this->config['qr_comma'],
			'S_QR_QUICKNICK_ENABLE'   => $this->config['qr_quicknick'],
			'S_QR_QUICKNICK_REF'      => $this->config['qr_quicknick_ref'],
			'S_QR_QUICKNICK_PM'       => $this->config['qr_quicknick_pm'],
			'S_QR_QUICKQUOTE_ENABLE'  => $this->config['qr_quickquote'],
			'S_QR_QUICKQUOTE_LINK'    => $this->config['qr_quickquote_link'],
			'S_QR_FULL_QUOTE'         => $this->config['qr_full_quote'],
			'S_QR_CE_ENABLE'          => $this->config['qr_ctrlenter'],
			'QR_SOURCE_POST'          => $this->config['qr_source_post'],
			'S_DISPLAY_USERNAME'      => !$this->user->data['is_registered'],

			'MESSAGE'                   => $this->request->variable('message', '', true),
			'READ_POST_IMG'             => $this->user->img('icon_post_target', 'POST'),

			// begin mod CapsLock Transfer
			'S_QR_CAPS_ENABLE'          => $this->config['qr_capslock_transfer'],
			// end mod CapsLock Transfer

			// begin mod Translit
			'S_QR_SHOW_BUTTON_TRANSLIT' => $this->config['qr_show_button_translit'],
			// end mod Translit

			// Ajax submit
			'L_FULL_EDITOR'             => ($this->config['qr_ajax_submit']) ? $this->user->lang['PREVIEW'] : $this->user->lang['FULL_EDITOR'],
			'S_QR_AJAX_SUBMIT'          => $this->config['qr_ajax_submit'],

			'S_QR_AJAX_PAGINATION' => $this->config['qr_ajax_pagination'] && $this->user->data['ajax_pagination'],

			'S_QR_ENABLE_SCROLL'   => $this->user->data['qr_enable_scroll'],
			'S_QR_SCROLL_INTERVAL' => $this->config['qr_scroll_time'],
			'S_QR_SOFT_SCROLL'     => $this->config['qr_scroll_time'] && $this->user->data['qr_soft_scroll'],

			'S_QR_ALLOWED_GUEST' => $this->config['qr_allow_for_guests'] && $this->user->data['user_id'] == ANONYMOUS,
		));
	}

	/**
	 * Assign template variables for extensions if quick reply is enabled
	 */
	public function assign_template_variables_for_extensions()
	{
		if (
			$this->phpbb_extension_manager->is_enabled('rxu/PostsMerging') &&
			$this->user->data['is_registered'] &&
			$this->config['merge_interval']
		)
		{
			// Always show the checkbox if PostsMerging extension is installed.
			$this->user->add_lang_ext('rxu/PostsMerging', 'posts_merging');
			$this->template->assign_var('POSTS_MERGING_OPTION', true);
		}

		$this->template->assign_vars(array(
			// ABBC3
			'S_ABBC3_INSTALLED' => $this->phpbb_extension_manager->is_enabled('vse/abbc3'),
		));
	}
}
