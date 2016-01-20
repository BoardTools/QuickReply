<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace boardtools\quickreply\event;

/**
 * Event listener
 */
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

	/** @var \phpbb\cache\service */
	protected $cache;

	/** @var \phpbb\captcha\factory */
	protected $captcha;

	/** @var \phpbb\plupload\plupload */
	protected $plupload;

	/** @var \phpbb\mimetype\guesser */
	protected $mimetype_guesser;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $php_ext;

	/** @var bool */
	public $qr_insert;

	/** @var bool */
	public $qr_first;

	/**
	 * Constructor
	 *
	 * @param \phpbb\auth\auth         $auth
	 * @param \phpbb\config\config     $config
	 * @param \phpbb\template\template $template
	 * @param \phpbb\user              $user
	 * @param \phpbb\extension\manager $phpbb_extension_manager
	 * @param \phpbb\request\request   $request
	 * @param \phpbb\cache\service     $cache
	 * @param \phpbb\captcha\factory   $captcha
	 * @param \phpbb\plupload\plupload $plupload
	 * @param \phpbb\mimetype\guesser  $mimetype_guesser
	 * @param string                   $phpbb_root_path Root path
	 * @param string                   $php_ext
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, \phpbb\extension\manager $phpbb_extension_manager, \phpbb\request\request $request, \phpbb\cache\service $cache, \phpbb\captcha\factory $captcha, \phpbb\plupload\plupload $plupload, \phpbb\mimetype\guesser $mimetype_guesser, $phpbb_root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->request = $request;
		$this->cache = $cache;
		$this->captcha = $captcha;
		$this->plupload = $plupload;
		$this->mimetype_guesser = $mimetype_guesser;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->qr_insert = false;
		$this->qr_first = false;
	}

	/**
	 * Initialize captcha instance
	 *
	 * @param bool $set_hidden_fields Whether we need to assign hidden fields to the template
	 */
	public function set_captcha($set_hidden_fields = true)
	{
		$captcha = $this->captcha->get_instance($this->config['captcha_plugin']);
		$captcha->init(CONFIRM_POST);

		if ($captcha->is_solved() === false)
		{
			$this->template->assign_vars(array(
				'S_CONFIRM_CODE'   => true,
				'CAPTCHA_TEMPLATE' => $captcha->get_template(),
			));
		}

		// Add the confirm id/code pair to the hidden fields, else an error is displayed on next submit/preview
		if ($set_hidden_fields && $captcha->is_solved() !== false)
		{
			$this->template->append_var('QR_HIDDEN_FIELDS', build_hidden_fields($captcha->get_hidden_fields()));
		}
	}

	/**
	 * Output the page for QuickReply
	 *
	 * @param string $page_title      The title of the page
	 * @param int    $current_post_id ID of the current last post
	 * @param int    $forum_id        Forum ID
	 */
	public function ajax_response($page_title, $current_post_id, $forum_id)
	{
		// Fix issues if the inserted post is not the first.
		if ($this->qr_insert && !$this->qr_first)
		{
			$this->template->alter_block_array('postrow', array(
				'S_FIRST_ROW' => false,
			), false, 'change');
		}
		$this->template->assign_vars(array(
			'S_QUICKREPLY_REQUEST' => true,
			'S_QR_FULL_QUOTE'      => $this->config['qr_full_quote'],
		));
		$this->template->append_var('QR_HIDDEN_FIELDS', build_hidden_fields(array(
			'qr'             => 1,
			'qr_cur_post_id' => (int) $current_post_id
		)));
		// Output the page
		page_header($page_title, false, $forum_id);
		page_footer(false, false, false);
		$json_response = new \phpbb\json_response();
		$json_response->send(array(
			'success' => true,
			'result'  => $this->template->assign_display('@boardtools_quickreply/quickreply_template.html', '', true),
			'insert'  => $this->qr_insert
		));
	}

	/**
	 * Sends ajax response in case of errors
	 *
	 * @param array $error Array with error strings
	 */
	public function ajax_check_errors($error)
	{
		if (sizeof($error))
		{
			$json_response = new \phpbb\json_response;
			$json_response->send(array(
				'error'         => true,
				'MESSAGE_TITLE' => $this->user->lang['INFORMATION'],
				'MESSAGE_TEXT'  => implode('<br />', $error),
			));
		}
	}

	/**
	 * Define BBCode and smilies status, handle attachments
	 *
	 * @param int $forum_id Forum ID
	 * @param int $topic_id Topic ID
	 */
	public function prepare_qr_form($forum_id, $topic_id)
	{
		if (!function_exists('generate_smilies'))
		{
			include($this->phpbb_root_path . 'includes/functions_posting.' . $this->php_ext);
		}

		// HTML, BBCode, Smilies, Images, Url, Flash and Quote status
		$bbcode_status = ($this->config['allow_bbcode'] && $this->config['qr_bbcode'] && $this->auth->acl_get('f_bbcode', $forum_id)) ? true : false;
		$smilies_status = ($this->config['allow_smilies'] && $this->config['qr_smilies'] && $this->auth->acl_get('f_smilies', $forum_id)) ? true : false;
		$img_status = ($bbcode_status && $this->auth->acl_get('f_img', $forum_id)) ? true : false;
		$url_status = ($this->config['allow_post_links']) ? true : false;
		$flash_status = ($bbcode_status && $this->auth->acl_get('f_flash', $forum_id) && $this->config['allow_post_flash']) ? true : false;
		$quote_status = true;

		// Build custom bbcodes array
		if ($bbcode_status)
		{
			display_custom_bbcodes();
		}

		// Generate smiley listing
		if ($smilies_status)
		{
			generate_smilies('inline', $forum_id);
		}

		$this->template->assign_vars(array(
			'S_BBCODE_ALLOWED'  => ($bbcode_status) ? 1 : 0,
			'S_SMILIES_ALLOWED' => $smilies_status,
			'S_BBCODE_IMG'      => $img_status,
			'S_LINKS_ALLOWED'   => $url_status,
			'S_BBCODE_FLASH'    => $flash_status,
			'S_BBCODE_QUOTE'    => $quote_status,
		));

		// Show attachment box for adding attachments
		$show_attach_box = (
			@ini_get('file_uploads') != '0' &&
			strtolower(@ini_get('file_uploads')) != 'off' &&
			$this->config['allow_attachments'] &&
			$this->auth->acl_get('u_attach') &&
			$this->auth->acl_get('f_attach', $forum_id)
		);

		if ($bbcode_status || $smilies_status || $this->config['qr_attach'] && $show_attach_box)
		{
			$this->user->add_lang('posting');
		}

		if ($this->config['qr_attach'] && $show_attach_box)
		{
			$this->handle_attachments($forum_id, $topic_id, $show_attach_box);
		}
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
	 * Assign template variables for guests if quick reply is available for them
	 *
	 * @param int   $forum_id   Forum ID
	 * @param array $topic_data Array with topic data
	 */
	public function enable_qr_for_guests($forum_id, $topic_data)
	{
		$topic_id = $topic_data['topic_id'];
		add_form_key('posting');

		$s_attach_sig = $this->config['allow_sig'] && $this->user->optionget('attachsig') && $this->auth->acl_get('f_sigs', $forum_id) && $this->auth->acl_get('u_sig');
		$s_smilies = $this->config['allow_smilies'] && $this->user->optionget('smilies') && $this->auth->acl_get('f_smilies', $forum_id);
		$s_bbcode = $this->config['allow_bbcode'] && $this->user->optionget('bbcode') && $this->auth->acl_get('f_bbcode', $forum_id);
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
			$this->set_captcha();
		}
	}

	/**
	 * Parse and display attachments
	 *
	 * @param int  $forum_id        Forum ID
	 * @param int  $topic_id        Topic ID
	 * @param bool $show_attach_box Whether we need to display the attachment box
	 */
	public function handle_attachments($forum_id, $topic_id, $show_attach_box)
	{
		if (!class_exists('parse_message'))
		{
			include($this->phpbb_root_path . 'includes/message_parser.' . $this->php_ext);
		}
		$message_parser = new \parse_message();
		$message_parser->set_plupload($this->plupload);
		$message_parser->set_mimetype_guesser($this->mimetype_guesser);

		$message_parser->get_submitted_attachment_data($this->user->data['user_id']);

		$attachment_data = $message_parser->attachment_data;
		$filename_data = $message_parser->filename_data;

		posting_gen_inline_attachments($attachment_data);

		$max_files = ($this->auth->acl_get('a_') || $this->auth->acl_get('m_', $forum_id)) ? 0 : (int) $this->config['max_attachments'];
		$s_action = append_sid("{$this->phpbb_root_path}posting.$this->php_ext", "mode=reply&amp;f=$forum_id&amp;t=$topic_id");
		$this->plupload->configure($this->cache, $this->template, $s_action, $forum_id, $max_files);

		posting_gen_attachment_entry($attachment_data, $filename_data, $show_attach_box);

		$this->template->assign_vars(array(
			// Upload attachments
			'S_QR_SHOW_ATTACH_BOX' => $this->config['qr_attach'] && $show_attach_box,
			'S_ATTACH_DATA'        => ($attachment_data) ? json_encode($attachment_data) : '[]',
		));
	}

	/**
	 * Assign template variables if quick reply is enabled
	 *
	 * @param int $forum_id Forum ID
	 */
	public function assign_template_variables_for_qr($forum_id)
	{
		if ($this->phpbb_extension_manager->is_enabled('rxu/PostsMerging') && $this->user->data['is_registered'] && $this->config['merge_interval'])
		{
			// Always show the checkbox if PostsMerging extension is installed.
			$this->user->add_lang_ext('rxu/PostsMerging', 'posts_merging');
			$this->template->assign_var('POSTS_MERGING_OPTION', true);
		}

		$this->template->assign_vars(array(
			'S_QR_COLOUR_NICKNAME'    => $this->config['qr_color_nickname'],
			'S_QR_NOT_CHANGE_SUBJECT' => ($this->auth->acl_get('f_qr_change_subject', $forum_id)) ? false : true,
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

			// ABBC3
			'S_ABBC3_INSTALLED'  => $this->phpbb_extension_manager->is_enabled('vse/abbc3'),
		));
	}
}
