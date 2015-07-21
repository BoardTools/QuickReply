<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) 2014 - 2015 Tatiana5 and LavIgor
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tatiana5\quickreply\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\extension\manager */
	protected $phpbb_extension_manager;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\content_visibility */
	protected $phpbb_content_visibility;

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
	protected $qr_insert;

	/**
	* Constructor
	*
	* @param \phpbb\auth\auth					$auth
	* @param \phpbb\config\config				$config
	* @param \phpbb\template\template			$template
	* @param \phpbb\user						$user
	* @param \phpbb\db\driver\driver_interface	$db
	* @param \phpbb\extension\manager			$phpbb_extension_manager
	* @param \phpbb\request\request				$request
	* @param \phpbb\content_visibility			$phpbb_content_visibility
	* @param \phpbb\cache\service				$cache
	* @param \phpbb\captcha\factory				$captcha
	* @param \phpbb\plupload\plupload			$plupload
	* @param \phpbb\mimetype\guesser			$mimetype_guesser
	* @param string								$phpbb_root_path Root path
	* @param string								$php_ext
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, \phpbb\db\driver\driver_interface $db, \phpbb\extension\manager $phpbb_extension_manager, \phpbb\request\request $request, \phpbb\content_visibility $phpbb_content_visibility, \phpbb\cache\service $cache, \phpbb\captcha\factory $captcha, \phpbb\plupload\plupload $plupload, \phpbb\mimetype\guesser $mimetype_guesser, $phpbb_root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->db = $db;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->request = $request;
		$this->phpbb_content_visibility = $phpbb_content_visibility;
		$this->cache = $cache;
		$this->captcha = $captcha;
		$this->plupload = $plupload;
		$this->mimetype_guesser = $mimetype_guesser;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->qr_insert = false;
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
		// We set lower priority for some events for the case if another extension wants to use those events.
		return array(
			'core.viewtopic_get_post_data'				=> 'viewtopic_modify_sql',
			'core.viewtopic_modify_post_row'			=> 'viewtopic_modify_post_row',
			'core.viewtopic_modify_page_title'			=> array('viewtopic_modify_data', -2),
			'core.modify_submit_post_data'				=> 'change_subject_when_sending',
			'core.posting_modify_submission_errors'		=> 'detect_new_posts',
			'core.posting_modify_template_vars'			=> 'delete_re',
			'core.submit_post_end'						=> array('ajax_submit', -2),
			'rxu.postsmerging.posts_merging_end'		=> 'ajax_submit',
			'core.search_get_posts_data'				=> 'hide_posts_subjects_in_searchresults_sql',
			'core.search_modify_tpl_ary'				=> 'hide_posts_subjects_in_searchresults_tpl',
			'core.ucp_prefs_view_data'					=> 'ucp_prefs_get_data',
			'core.ucp_prefs_view_update_data'			=> 'ucp_prefs_set_data',
			'core.acp_users_prefs_modify_data'			=> 'acp_prefs_get_data',
			'core.acp_users_prefs_modify_template_data'	=> 'acp_prefs_template_data',
			'core.acp_users_prefs_modify_sql'			=> 'ucp_prefs_set_data', // For the ACP.
			'core.permissions'							=> 'add_permission',
		);
	}

	/**
	* Reduce the set of elements to the one that we need to retrieve.
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_modify_sql($event)
	{
		if ($this->request->is_ajax() && $this->request->is_set('qr_captcha_refresh'))
		{
			if ($this->config['enable_post_confirm'])
			{
				$captcha = $this->captcha->get_instance($this->config['captcha_plugin']);
				$captcha->init(CONFIRM_POST);

				if (isset($captcha) && $captcha->is_solved() === false)
				{
					$this->template->assign_vars(array(
						'S_CONFIRM_CODE'			=> true,
						'CAPTCHA_TEMPLATE'			=> $captcha->get_template(),
					));
				}
			}
			$json_response = new \phpbb\json_response;
			$json_response->send(array(
				'captcha_refreshed' => true,
				'captcha_result' => $this->template->assign_display('@tatiana5_quickreply/quickreply_captcha_template.html', '', true),
			));
		}

		$post_list = $event['post_list'];
		$current_post = $this->request->variable('qr_cur_post_id', 0);
		if ($this->request->is_ajax() && $this->request->variable('qr_no_refresh', 0) && in_array($current_post, $post_list))
		{
			$sql_ary = $event['sql_ary'];
			$compare = ($this->request->is_set('qr_get_current')) ? ' >= ' : ' > ';
			$sql_ary['WHERE'] .= ' AND p.post_id' . $compare . $current_post;
			$event['sql_ary'] = $sql_ary;
			$this->qr_insert = true;

			/* Check whether no posts are found. */
			if ($compare == ' > ' && max($post_list) <= $current_post)
			{
				$json_response = new \phpbb\json_response;
				$json_response->send(array(
					'error' => true,
					'MESSAGE_TITLE'	=> $this->user->lang['INFORMATION'],
					'MESSAGE_TEXT'	=> $this->user->lang['NO_POSTS_TIME_FRAME'],
				));
			}
		}
		$this->user->add_lang_ext('tatiana5/quickreply', 'quickreply');
	}

	/**
	* Add decoded message text if full quotes are enabled.
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_modify_post_row($event)
	{
		$topic_data = $event['topic_data'];
		if ($this->config['qr_full_quote'] && $this->auth->acl_get('f_reply', $topic_data['forum_id']))
		{
			$row = $event['row'];
			$post_row = $event['post_row'];
			$decoded_message = censor_text($row['post_text']);
			decode_message($decoded_message, $row['bbcode_uid']);

			$decoded_message = bbcode_nl2br($decoded_message);
			$post_row = array_merge($post_row, array(
				'DECODED_MESSAGE'	=> $decoded_message,
			));
			$event['post_row'] = $post_row;
		}
	}

	/**
	* Show bbcodes and smilies in the quickreply
	* Template data for Ajax submit
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_modify_data($event)
	{
		$forum_id	= $event['forum_id'];
		$topic_data = $event['topic_data'];
		$post_list = $event['post_list'];
		$topic_id = $topic_data['topic_id'];

		$s_quick_reply = false;
		if (($this->user->data['is_registered'] || $this->config['qr_allow_for_guests']) && $this->config['allow_quick_reply'] && ($topic_data['forum_flags'] & FORUM_FLAG_QUICK_REPLY) && $this->auth->acl_get('f_reply', $forum_id))
		{
			// Quick reply enabled forum
			$s_quick_reply = (($topic_data['forum_status'] == ITEM_UNLOCKED && $topic_data['topic_status'] == ITEM_UNLOCKED) || $this->auth->acl_get('m_edit', $forum_id)) ? true : false;
		}

		if (!$this->user->data['is_registered'] && $s_quick_reply)
		{
			add_form_key('posting');

			$s_attach_sig	= $this->config['allow_sig'] && $this->user->optionget('attachsig') && $this->auth->acl_get('f_sigs', $forum_id) && $this->auth->acl_get('u_sig');
			$s_smilies		= $this->config['allow_smilies'] && $this->user->optionget('smilies') && $this->auth->acl_get('f_smilies', $forum_id);
			$s_bbcode		= $this->config['allow_bbcode'] && $this->user->optionget('bbcode') && $this->auth->acl_get('f_bbcode', $forum_id);
			$s_notify		= false;

			$qr_hidden_fields = array(
				'topic_cur_post_id'		=> (int) $topic_data['topic_last_post_id'],
				'lastclick'				=> (int) time(),
				'topic_id'				=> (int) $topic_data['topic_id'],
				'forum_id'				=> (int) $forum_id,
			);

			// Originally we use checkboxes and check with isset(), so we only provide them if they would be checked
			(!$s_bbcode)							? $qr_hidden_fields['disable_bbcode'] = 1		: true;
			(!$s_smilies)							? $qr_hidden_fields['disable_smilies'] = 1		: true;
			(!$this->config['allow_post_links'])	? $qr_hidden_fields['disable_magic_url'] = 1	: true;
			($s_attach_sig)							? $qr_hidden_fields['attach_sig'] = 1			: true;
			($s_notify)								? $qr_hidden_fields['notify'] = 1				: true;
			($topic_data['topic_status'] == ITEM_LOCKED) ? $qr_hidden_fields['lock_topic'] = 1 : true;

			$this->template->assign_vars(array(
				'S_QUICK_REPLY'			=> true,
				'U_QR_ACTION'			=> append_sid("{$this->phpbb_root_path}posting.$this->php_ext", "mode=reply&amp;f=$forum_id&amp;t=$topic_id"),
				'QR_HIDDEN_FIELDS'		=> build_hidden_fields($qr_hidden_fields),
				'USERNAME'				=> $this->request->variable('username', '', true),
			));

			if ($this->config['enable_post_confirm'])
			{
				$captcha = $this->captcha->get_instance($this->config['captcha_plugin']);
				$captcha->init(CONFIRM_POST);
			}

			if ($this->config['enable_post_confirm'] && (isset($captcha) && $captcha->is_solved() === false))
			{
				$this->template->assign_vars(array(
					'S_CONFIRM_CODE'			=> true,
					'CAPTCHA_TEMPLATE'			=> $captcha->get_template(),
				));
			}

			// Add the confirm id/code pair to the hidden fields, else an error is displayed on next submit/preview
			if (isset($captcha) && $captcha->is_solved() !== false)
			{
				$this->template->append_var('QR_HIDDEN_FIELDS', build_hidden_fields($captcha->get_hidden_fields()));
			}
		}

		// Ajaxify viewtopic data
		if ($this->request->is_ajax() && $this->request->is_set('qr_request'))
		{
			if (!$this->user->data['is_registered'] && $this->config['enable_post_confirm'])
			{
				$captcha = $this->captcha->get_instance($this->config['captcha_plugin']);
				$captcha->init(CONFIRM_POST);

				// Add the confirm id/code pair to the hidden fields, else an error is displayed on next submit/preview
				if (isset($captcha) && $captcha->is_solved() !== false)
				{
					$this->template->append_var('QR_HIDDEN_FIELDS', build_hidden_fields($captcha->get_hidden_fields()));
				}
			}
			$page_title = $event['page_title'];
			$this->template->assign_vars(array(
				'S_QUICKREPLY_REQUEST'	=> true,
				'S_QR_NO_FIRST_POST'	=> $this->qr_insert,
				'S_QR_FULL_QUOTE'		=> $this->config['qr_full_quote'],
			));
			$this->template->append_var('QR_HIDDEN_FIELDS', build_hidden_fields(array(
				'qr'				=> 1,
				'qr_cur_post_id'	=> (int) max($post_list)
			)));
			// Output the page
			page_header($page_title, false, $forum_id);
			page_footer(false, false, false);
			$json_response = new \phpbb\json_response();
			$json_response->send(array(
				'success'			=> true,
				'result'			=> $this->template->assign_display('@tatiana5_quickreply/quickreply_template.html', '', true),
				'insert'			=> $this->qr_insert
				// 'current_max_id'	=> max($event['post_list']),
			));
		}

		if ($s_quick_reply)
		{
			include_once($this->phpbb_root_path . 'includes/functions_posting.' . $this->php_ext);

			// HTML, BBCode, Smilies, Images and Flash status
			$bbcode_status	= ($this->config['allow_bbcode'] && $this->config['qr_bbcode'] && $this->auth->acl_get('f_bbcode', $forum_id)) ? true : false;
			$smilies_status	= ($this->config['allow_smilies'] && $this->config['qr_smilies'] && $this->auth->acl_get('f_smilies', $forum_id)) ? true : false;
			$img_status		= ($bbcode_status && $this->auth->acl_get('f_img', $forum_id)) ? true : false;
			$url_status		= ($this->config['allow_post_links']) ? true : false;
			$flash_status	= ($bbcode_status && $this->auth->acl_get('f_flash', $forum_id) && $this->config['allow_post_flash']) ? true : false;
			$quote_status	= true;

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

			// Show attachment box for adding attachments if true
			$form_enctype = (@ini_get('file_uploads') == '0' || strtolower(@ini_get('file_uploads')) == 'off' || !$this->config['allow_attachments'] || !$this->auth->acl_get('u_attach') || !$this->auth->acl_get('f_attach', $forum_id)) ? '' : '" enctype="multipart/form-data';
			$allowed = ($this->auth->acl_get('f_attach', $forum_id) && $this->auth->acl_get('u_attach') && $this->config['allow_attachments'] && $form_enctype);

			if ($bbcode_status || $smilies_status || $this->config['qr_attach'] && $allowed)
			{
				$this->user->add_lang('posting');
			}

			if ($this->config['qr_attach'] && $allowed)
			{
				$this->template->assign_vars(array(
					'U_QR_ACTION'			=> append_sid("{$this->phpbb_root_path}posting.$this->php_ext", "mode=reply&amp;f=$forum_id&amp;t=$topic_id") . $form_enctype,
				));

				include_once($this->phpbb_root_path . 'includes/message_parser.' . $this->php_ext);
				$message_parser = new \parse_message();
				$message_parser->set_plupload($this->plupload);
				$message_parser->set_mimetype_guesser($this->mimetype_guesser);

				$message_parser->get_submitted_attachment_data($this->user->data['user_id']);

				$attachment_data = $message_parser->attachment_data;
				$filename_data = $message_parser->filename_data;

				posting_gen_inline_attachments($attachment_data);

				$max_files = ($this->auth->acl_get('a_') || $this->auth->acl_get('m_', $forum_id)) ? 0 : (int) $this->config['max_attachments'];
				$topic_id = $topic_data['topic_id'];
				$s_action = append_sid("{$this->phpbb_root_path}posting.$this->php_ext", "mode=reply&amp;f=$forum_id&amp;t=$topic_id");
				$this->plupload->configure($this->cache, $this->template, $s_action, $forum_id, $max_files);

				posting_gen_attachment_entry($attachment_data, $filename_data, $allowed);
			}

			$this->template->append_var('QR_HIDDEN_FIELDS', build_hidden_fields(array(
				'qr'				=> 1,
				'qr_cur_post_id'	=> (int) max($post_list)
			)));

			if ($this->phpbb_extension_manager->is_enabled('rxu/PostsMerging') && $this->user->data['is_registered'])
			{
				// Always show the checkbox if PostsMerging extension is installed.
				$this->user->add_lang_ext('rxu/PostsMerging', 'posts_merging');
				$this->template->assign_var('POSTS_MERGING_OPTION', true);
			}

			$this->template->assign_vars(array(
				'S_QR_COLOUR_NICKNAME'		=> $this->config['qr_color_nickname'],
				'S_QR_NOT_CHANGE_SUBJECT'	=> ($this->auth->acl_get('f_qr_change_subject', $forum_id)) ? false : true,
				'S_QR_COMMA_ENABLE'		=> $this->config['qr_comma'],
				'S_QR_QUICKNICK_ENABLE'	=> $this->config['qr_quicknick'],
				'S_QR_QUICKNICK_REF'	=> $this->config['qr_quicknick_ref'],
				'S_QR_QUICKNICK_PM'		=> $this->config['qr_quicknick_pm'],
				'S_QR_QUICKQUOTE_ENABLE'=> $this->config['qr_quickquote'],
				'S_QR_QUICKQUOTE_LINK'	=> $this->config['qr_quickquote_link'],
				'S_QR_FULL_QUOTE'		=> $this->config['qr_full_quote'],
				'S_QR_CE_ENABLE'		=> $this->config['qr_ctrlenter'],
				'QR_SOURCE_POST'		=> $this->config['qr_source_post'],
				'S_DISPLAY_USERNAME'	=> !$this->user->data['is_registered'],

				'S_BBCODE_ALLOWED'		=> ($bbcode_status) ? 1 : 0,
				'S_SMILIES_ALLOWED'		=> $smilies_status,
				'S_BBCODE_IMG'			=> $img_status,
				'S_LINKS_ALLOWED'		=> $url_status,
				'S_BBCODE_FLASH'		=> $flash_status,
				'S_BBCODE_QUOTE'		=> $quote_status,

				'MESSAGE'				=> $this->request->variable('message', '', true),
				'READ_POST_IMG'			=> $this->user->img('icon_post_target', 'POST'),

				// begin mod CapsLock Transfer
				'S_QR_CAPS_ENABLE'		=> $this->config['qr_capslock_transfer'],
				// end mod CapsLock Transfer

				// begin mod Translit
				'S_QR_SHOW_BUTTON_TRANSLIT'		=> $this->config['qr_show_button_translit'],
				// end mod Translit

				// Ajax submit
				'CONFIG_POSTS_PER_PAGE'	=>  ($this->phpbb_extension_manager->is_enabled('rxu/FirstPostOnEveryPage') && $event['start'] > 0 && $topic_data['topic_first_post_show'] == 1) ? ($this->config['posts_per_page']+ 1) : $this->config['posts_per_page'],
				'L_FULL_EDITOR'			=> ($this->config['qr_ajax_submit']) ? $this->user->lang['PREVIEW'] : $this->user->lang['FULL_EDITOR'],
				'S_QR_AJAX_SUBMIT'		=> $this->config['qr_ajax_submit'],

				'S_QR_AJAX_PAGINATION'	=> $this->config['qr_ajax_pagination'] && $this->user->data['ajax_pagination'],

				'S_QR_ENABLE_SCROLL'	=> $this->user->data['qr_enable_scroll'],
				'S_QR_SCROLL_INTERVAL'	=> $this->config['qr_scroll_time'],
				'S_QR_SOFT_SCROLL'		=> $this->config['qr_scroll_time'] && $this->user->data['qr_soft_scroll'],

				'S_QR_ALLOWED_GUEST'	=> $this->config['qr_allow_for_guests'] && $this->user->data['user_id'] == ANONYMOUS,

				// ABBC3
				'S_ABBC3_INSTALLED'		=> $this->phpbb_extension_manager->is_enabled('vse/abbc3'),

				// Upload attachments
				'S_QR_SHOW_ATTACH_BOX'	=> $this->config['qr_attach'] && $allowed,
				'S_ATTACH_DATA'			=> (isset($message_parser->attachment_data)) ? json_encode($message_parser->attachment_data) : '[]',
			));

			$add_re = ($this->config['qr_enable_re']) ? 'Re: ' : '';
			$this->template->assign_var('SUBJECT', $this->request->variable('subject', $add_re . censor_text($topic_data['topic_title']), true));
		}

		$this->template->assign_vars(array(
			'QR_HIDE_POSTS_SUBJECT'	=> ($this->config['qr_show_subjects']) ? false : true
		));
	}

	/**
	* Lock post subject if the user cannot change it.
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function change_subject_when_sending($event)
	{
		$data = $event['data'];

		if (!$this->auth->acl_get('f_qr_change_subject', $data['forum_id']) && ($data['topic_first_post_id'] != $data['post_id']))
		{
			if ($this->config['qr_enable_re'] == 0)
			{
				$subject = $data['topic_title'];
			}
			else
			{
				$subject = 'Re: ' . $data['topic_title'];
			}

			$event['subject'] = $subject;
		}
	}

	/**
	* Do not post the message if there are some new ones
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function detect_new_posts($event)
	{
		// Ajax submit
		if ($this->config['qr_ajax_submit'] && $this->request->is_ajax() && $this->request->is_set_post('qr'))
		{
			include_once($this->phpbb_root_path . 'includes/functions_posting.' . $this->php_ext);

			$error = $event['error'];
			$post_data = $event['post_data'];
			$forum_id = (int) $post_data['forum_id'];
			$topic_id = (int) $post_data['topic_id'];
			// $topic_cur_post_id = (int) $post_data['topic_cur_post_id'];
			$current_post = $this->request->variable('qr_cur_post_id', 0);
			$lastclick = $this->request->variable('lastclick', time());

			if (sizeof($error))
			{
				$json_response = new \phpbb\json_response;
				$json_response->send(array(
					'error'			=> true,
					'MESSAGE_TITLE'	=> $this->user->lang['INFORMATION'],
					'MESSAGE_TEXT'	=> implode('<br />', $error),
				));
			}
			else if (($lastclick < $post_data['topic_last_post_time']) && ($post_data['forum_flags'] & FORUM_FLAG_POST_REVIEW))
			{
				$sql = 'SELECT post_id
						FROM ' . POSTS_TABLE . '
						WHERE topic_id = ' . $topic_id . '
							AND ' . $this->phpbb_content_visibility->get_visibility_sql('post', $forum_id, '') . '
							AND post_time > ' . (int) $lastclick . '
							ORDER BY post_time ASC';
				$result = $this->db->sql_query_limit($sql, 1);
				$post_id_next =  (int) $this->db->sql_fetchfield('post_id');
				$this->db->sql_freeresult($result);

				$error_text = $this->user->lang['POST_REVIEW_EXPLAIN'];
				$url_next_post = append_sid("{$this->phpbb_root_path}viewtopic.$this->php_ext", "f=$forum_id&amp;t=$topic_id&amp;p=$post_id_next"); // #p$post_id_next

				$json_response = new \phpbb\json_response;
				$json_response->send(array(
					'error'			=> true,
					'merged'		=> ($post_id_next === $current_post) ? 'merged' : 'not_merged',
					'MESSAGE_TITLE'	=> $this->user->lang['INFORMATION'],
					'MESSAGE_TEXT'	=> $error_text,
					'NEXT_URL'		=> $url_next_post,
				));
			}
			else if ($post_data['topic_cur_post_id'] && $post_data['topic_cur_post_id'] != $post_data['topic_last_post_id'])
			{
				// Send new post number as a response.
				// @todo Add the possibility to reload the page.
				$json_response = new \phpbb\json_response;
				$json_response->send(array(
					'post_update'	=> true,
					'post_id'		=> $post_data['topic_last_post_id'],
				));
			}
		}
		// This is needed for BBCode QR_BBPOST.
		$this->user->add_lang_ext('tatiana5/quickreply', 'quickreply');
	}

	/**
	* Delete Re:, lock post subject
	* Ctrl+Enter submit - template variables in the full editor
	* Ajax submit - error messages and preview
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function delete_re($event)
	{
		$forum_id	= $event['forum_id'];
		$page_data	= $event['page_data'];
		$post_data	= $event['post_data'];

		// Delete Re:
		if ($this->config['qr_enable_re'] == 0)
		{
			$page_data['SUBJECT'] = preg_replace('/^Re: /', '', $page_data['SUBJECT']);
		}

		// Whether the user can change post subject or not
		if(!$this->auth->acl_get('f_qr_change_subject', $forum_id) && $event['mode'] != 'post' && $post_data['topic_first_post_id'] != $event['post_id'])
		{
			$this->template->assign_var('S_QR_NOT_CHANGE_SUBJECT', true);
		};

		// Ctrl+Enter submit
		$page_data = array_merge($page_data, array(
			'S_QR_CE_ENABLE'		=> $this->config['qr_ctrlenter'],
		));

		$event['page_data'] = $page_data;

		//Ajax submit
		if ($this->config['qr_ajax_submit'] && $this->request->is_ajax() && $this->request->is_set_post('qr'))
		{
			include_once($this->phpbb_root_path . 'includes/functions_posting.' . $this->php_ext);

			$error = $event['error'];
			$preview = $event['preview'];

			$post_data = $event['post_data'];
			$forum_id = (int) $post_data['forum_id'];
			$message_parser = $event['message_parser'];

			if (sizeof($error))
			{
				$error_text = implode('<br />', $error);
				$url_next_post = '';
			}

			// Preview
			if (!sizeof($error) && $preview)
			{
				$message_parser->message = $this->request->variable('message', '', true);
				$preview_message = $message_parser->format_display($post_data['enable_bbcode'], $post_data['enable_urls'], $post_data['enable_smilies'], false);

				$preview_attachments = false;
				// Attachment Preview
				if (sizeof($message_parser->attachment_data))
				{
					$preview_attachments = '<dl class="attachbox"><dt>' . $this->user->lang['ATTACHMENTS'] . '</dt>';

					//$this->template->assign_var('S_HAS_ATTACHMENTS', true);

					$update_count = array();
					$attachment_data = $message_parser->attachment_data;

					parse_attachments($forum_id, $preview_message, $attachment_data, $update_count, true);

					foreach ($attachment_data as $i => $attachment)
					{
						//$this->template->assign_block_vars('attachment', array(
						//	'DISPLAY_ATTACHMENT'	=> $attachment)
						//);
						$preview_attachments .= '<dd>' . $attachment . '</dd>';
					}
					$preview_attachments .= '</dl>';
					unset($attachment_data);
				}

				$error_text = $preview_message;
			}

			if (isset($error_text))
			{
				$json_response = new \phpbb\json_response;

				if (!sizeof($error) && $preview)
				{
					$json_response->send(array(
						'preview' => true,
						'PREVIEW_TITLE'	=> $this->user->lang['PREVIEW'],
						'PREVIEW_TEXT'	=> $preview_message,
						'PREVIEW_ATTACH'=> $preview_attachments,
					));
				}
				else
				{
					$json_response->send(array(
						'error' => true,
						'MESSAGE_TITLE'	=> $this->user->lang['INFORMATION'],
						'MESSAGE_TEXT'	=> $error_text,
						'NEXT_URL'		=> (isset($url_next_post)) ? $url_next_post : '',
					));
				}
			}
		}
	}

	/**
	* Ajax submit
	*
	* @param object $event The event object
	* @return array
	* @access public
	*/
	public function ajax_submit($event)
	{
		if ($this->config['qr_ajax_submit'] && $this->request->is_ajax() && $this->request->is_set_post('qr'))
		{
			$json_response = new \phpbb\json_response;

			$data = $event['data'];

			if ((!$this->auth->acl_get('f_noapprove', $data['forum_id']) && empty($data['force_approved_state'])) || (isset($data['force_approved_state']) && !$data['force_approved_state']))
			{
				// No approve
				$json_response->send(array(
					'noapprove'		=> true,
					'MESSAGE_TITLE'	=> $this->user->lang['INFORMATION'],
					'MESSAGE_TEXT'	=> $this->user->lang['POST_STORED_MOD'] . (($this->user->data['user_id'] == ANONYMOUS) ? '' : ' '. $this->user->lang['POST_APPROVAL_NOTIFY']),
					'REFRESH_DATA'	=> array(
						'time'	=> 10,
					)
				));
			}

			$qr_cur_post_id	= $this->request->variable('qr_cur_post_id', 0);
			$url_hash = strpos($event['url'], '#');
			$result_url = ($url_hash !== false) ? substr($event['url'], 0, $url_hash) : $event['url'];
			$json_response->send(array(
				'success'		=> true,
				'url'			=> $result_url,
				'merged'		=> ($qr_cur_post_id === $data['post_id']) ? 'merged' : 'not_merged'
			));
		}
	}

	/**
	* Hide posts subjects in search results
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function hide_posts_subjects_in_searchresults_sql($event)
	{
		if (!$this->config['qr_show_subjects_in_search'])
		{
			$sql_array = $event['sql_array'];
			if (!preg_match('/t\.topic_first_post_id/', $sql_array['SELECT'], $matches_t))
			{
				$sql_array['SELECT'] .= ', t.topic_first_post_id';
			}
			if (!preg_match('/p\.post_id/', $sql_array['SELECT'], $matches_p))
			{
				$sql_array['SELECT'] .= ', p.post_id';
			}
			$event['sql_array'] = $sql_array;

			$this->template->assign_vars(array(
				'QR_HIDE_POSTS_SUBJECT'	=> true
			));
		}
	}

	/**
	* Hide posts subjects in search results
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function hide_posts_subjects_in_searchresults_tpl($event)
	{
		if (!$this->config['qr_show_subjects_in_search'])
		{
			$row = $event['row'];
			$tpl_ary = $event['tpl_ary'];
			$show_results = $event['show_results'];

			if ($show_results == 'posts')
			{
				$tpl_ary = array_merge($tpl_ary, array(
					'QR_NOT_FIRST_POST'	=> ($row['topic_first_post_id'] == $row['post_id']) ? false : true,
				));

				$event['tpl_ary'] = $tpl_ary;
			}
		}
	}

	/**
	* Get user's options and display them in UCP Prefs View page
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function ucp_prefs_get_data($event)
	{
		// Request the user option vars and add them to the data array
		$event['data'] = array_merge($event['data'], array(
			'ajax_pagination'    => $this->request->variable('ajax_pagination', (int) $this->user->data['ajax_pagination']),
			'qr_enable_scroll'   => $this->request->variable('qr_enable_scroll', (int) $this->user->data['qr_enable_scroll']),
			'qr_soft_scroll'     => $this->request->variable('qr_soft_scroll', (int) $this->user->data['qr_soft_scroll']),
		));

		// Output the data vars to the template
		$this->user->add_lang_ext('tatiana5/quickreply', 'quickreply_ucp');
		$this->template->assign_vars(array(
			'S_AJAX_PAGINATION'			=> $this->config['qr_ajax_pagination'],
			'S_ENABLE_AJAX_PAGINATION'	=> $event['data']['ajax_pagination'],
			'S_QR_ENABLE_SCROLL'		=> $event['data']['qr_enable_scroll'],
			'S_QR_ALLOW_SOFT_SCROLL'	=> !!$this->config['qr_scroll_time'],
			'S_QR_SOFT_SCROLL'			=> $event['data']['qr_soft_scroll'],
		));
	}

	/**
	* Add user options' state into the sql_array
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function ucp_prefs_set_data($event)
	{
		$event['sql_ary'] = array_merge($event['sql_ary'], array(
			'ajax_pagination'	=> $event['data']['ajax_pagination'],
			'qr_enable_scroll'	=> $event['data']['qr_enable_scroll'],
			'qr_soft_scroll'	=> $event['data']['qr_soft_scroll'],
		));
	}

	/**
	* Get user's options and display them in ACP Prefs View page
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function acp_prefs_get_data($event)
	{
		$data = $event['data'];
		$user_row = $event['user_row'];
		$data = array_merge($data, array(
			'ajax_pagination'    => $this->request->variable('ajax_pagination', (int) $user_row['ajax_pagination']),
			'qr_enable_scroll'   => $this->request->variable('qr_enable_scroll', (int) $user_row['qr_enable_scroll']),
			'qr_soft_scroll'     => $this->request->variable('qr_soft_scroll', (int) $user_row['qr_soft_scroll']),
		));
		$event['data'] = $data;
	}

	/**
	* Assign template data in the ACP
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function acp_prefs_template_data($event)
	{
		$this->user->add_lang_ext('tatiana5/quickreply', 'quickreply_ucp');
		$data = $event['data'];
		$user_prefs_data = $event['user_prefs_data'];
		$user_prefs_data = array_merge($user_prefs_data, array(
			'S_AJAX_PAGINATION'			=> $this->config['qr_ajax_pagination'],
			'S_ENABLE_AJAX_PAGINATION'	=> $data['ajax_pagination'],
			'S_QR_ENABLE_SCROLL'		=> $data['qr_enable_scroll'],
			'S_QR_ALLOW_SOFT_SCROLL'	=> !!$this->config['qr_scroll_time'],
			'S_QR_SOFT_SCROLL'			=> $data['qr_soft_scroll'],
		));
		$event['user_prefs_data'] = $user_prefs_data;
	}

	/**
	* Add permissions
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function add_permission($event)
	{
		$permissions = $event['permissions'];
		$permissions['a_quickreply'] = array('lang' => 'ACL_A_QUICKREPLY', 'cat' => 'misc');
		$permissions['f_qr_change_subject'] = array('lang' => 'ACL_F_QR_CHANGE_SUBJECT', 'cat' => 'post');
		$event['permissions'] = $permissions;
	}
}
