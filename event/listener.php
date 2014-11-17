<?php
/**
*
* @package quickreply
* @copyright (c) 2014 Татьяна5
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

	/** @var \phpbb\plupload\plupload */
	protected $plupload;

	/** @var \phpbb\mimetype\guesser */
	protected $mimetype_guesser;

	/** @var string */
	protected $phpbb_root_path;
	protected $php_ext;

	/**
	* Constructor
	* 
	* @param \phpbb\auth\auth $auth
	* @param \phpbb\config\config $config
	* @param \phpbb\template\template $template
	* @param \phpbb\user $user
	* @param \phpbb\db\driver\driver $db
	* @param \phpbb\extension\manager $phpbb_extension_manager
	* @param \phpbb\request\request $request
	* @param \phpbb\content_visibility $phpbb_content_visibility
	* @param \phpbb\cache\service $cache
	* @param \phpbb\plupload\plupload $plupload
	* @param \phpbb\mimetype\guesser $mimetype_guesser
	* @param string $phpbb_root_path Root path
	* @param string $phpbb_ext
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, \phpbb\db\driver\driver_interface $db, \phpbb\extension\manager $phpbb_extension_manager, \phpbb\request\request $request, \phpbb\content_visibility $phpbb_content_visibility, \phpbb\cache\service $cache, \phpbb\plupload\plupload $plupload, \phpbb\mimetype\guesser $mimetype_guesser, $phpbb_root_path, $php_ext)
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
		$this->plupload = $plupload;
		$this->mimetype_guesser = $mimetype_guesser;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->files_uploaded = false;
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
			'core.user_setup'					=>	'load_language_on_setup',
			'core.viewtopic_modify_page_title'	=>	'viewtopic_modify_data',
			'core.modify_posting_parameters'	=>	'change_subject_tpl',
			'core.modify_submit_post_data'		=>	'change_subject_when_sending',
			'core.posting_modify_template_vars'	=>	'delete_re',
			'core.submit_post_end'				=>	array('ajax_submit', -2), // Set lower priority for the case another ext want to use 'core.submit_post_end'
			'rxu.postsmerging.posts_merging_end'=>	'ajax_submit',
			'core.search_get_posts_data'		=>	'hide_posts_subjects_in_searchresults_sql',
			'core.search_modify_tpl_ary'		=>	'hide_posts_subjects_in_searchresults_tpl',
		);
	}

	/**
	* Load common files during user setup
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'tatiana5/quickreply',
			'lang_set' => 'quickreply',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	* Show bbcodes and smilies in the quickreply
	* Template data for Ajax sumbit
	*
	* @return null
	* @access public
	*/
	public function viewtopic_modify_data($event)
	{
		$forum_id	= $event['forum_id'];
		$topic_data = $event['topic_data'];
		$topic_id = $topic_data['topic_id'];

		$s_quick_reply = false;
		if ($this->user->data['is_registered'] && $this->config['allow_quick_reply'] && ($topic_data['forum_flags'] & FORUM_FLAG_QUICK_REPLY) && $this->auth->acl_get('f_reply', $forum_id))
		{
			// Quick reply enabled forum
			$s_quick_reply = (($topic_data['forum_status'] == ITEM_UNLOCKED && $topic_data['topic_status'] == ITEM_UNLOCKED) || $this->auth->acl_get('m_edit', $forum_id)) ? true : false;
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

			if($bbcode_status || $smilies_status)
			{
				$this->user->add_lang('posting');
			}

			// Build custom bbcodes array
			if($bbcode_status)
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

			if ($this->config['qr_attach'] && $allowed)
			{
				$this->template->assign_vars(array(
					'U_QR_ACTION'			=> append_sid("{$this->phpbb_root_path}posting.$this->php_ext", "mode=reply&amp;f=$forum_id&amp;t=$topic_id") . $form_enctype,
				));

				include_once($this->phpbb_root_path . 'includes/message_parser.' . $this->php_ext);
				$this->user->add_lang('posting');
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

			$this->template->assign_vars(array(
				'S_QR_COLOUR_NICKNAME'		=> $this->config['qr_color_nickname'],
				'S_QR_NOT_CHANGE_SUBJECT'	=> ($this->auth->acl_get('f_qr_change_subject', $forum_id)) ? false : true,
				'S_QR_COMMA_ENABLE'		=> $this->config['qr_comma'],
				'S_QR_QUICKNICK_ENABLE'	=> $this->config['qr_quicknick'],
				'S_QR_QUICKQUOTE_ENABLE'=> $this->config['qr_quickquote'],
				'S_QR_CE_ENABLE'		=> $this->config['qr_ctrlenter'],
				'QR_SOURCE_POST'		=> $this->config['qr_source_post'],

				'S_BBCODE_ALLOWED'		=> ($bbcode_status) ? 1 : 0,
				'S_SMILIES_ALLOWED'		=> $smilies_status,
				'S_BBCODE_IMG'			=> $img_status,
				'S_LINKS_ALLOWED'		=> $url_status,
				'S_BBCODE_FLASH'		=> $flash_status,
				'S_BBCODE_QUOTE'		=> $quote_status,

				//begin mod CapsLock Transfer
				'S_QR_CAPS_ENABLE'		=> $this->config['qr_capslock_transfer'],
				//end mod CapsLock Transfer

				//begin mod Translit
				'S_QR_SHOW_BUTTON_TRANSLIT'		=> $this->config['qr_show_button_translit'],
				//end mod Translit

				//Ajax submit
				'CONFIG_POSTS_PER_PAGE'	=>  ($this->phpbb_extension_manager->is_enabled('rxu/FirstPostOnEveryPage') && $event['start'] > 0 && $topic_data['topic_first_post_show'] == 1) ? ($this->config['posts_per_page']+ 1) : $this->config['posts_per_page'],
				'L_FULL_EDITOR'			=> ($this->config['qr_ajax_submit']) ? $this->user->lang['PREVIEW'] : $this->user->lang['FULL_EDITOR'],
				'S_QR_AJAX_SUBMIT'		=> $this->config['qr_ajax_submit'],

				//ABBC3
				'S_ABBC3_INSTALLED'		=> $this->phpbb_extension_manager->is_enabled('vse/abbc3'),

				//Upload attachments
				'S_QR_SHOW_ATTACH_BOX'	=> $this->config['qr_attach'] && $allowed,
				'S_ATTACH_DATA'			=> (isset($message_parser->attachment_data)) ? json_encode($message_parser->attachment_data) : '[]',
			));

			if($this->config['qr_enable_re'] == 0)
			{
				$this->template->assign_vars(array(
					'SUBJECT'		=> $topic_data['topic_title'],
				));
			}
		}

		$this->template->assign_vars(array(
			'QR_HIDE_POSTS_SUBJECT'	=> ($this->config['qr_show_subjects']) ? false : true
		));
	}

	/**
	* User can change post subject or not
	*
	* @return null
	* @access public
	*/
	public function change_subject_tpl($event)
	{
		$forum_id	= $event['forum_id'];
		$topic_id	= $event['topic_id'];

		$can_change_subject = ($this->auth->acl_get('f_qr_change_subject', $forum_id)) ? true : false;

		if(!$can_change_subject && $event['mode'] != 'post' && !empty($topic_id))
		{
			$this->template->assign_vars(array(
				'S_QR_NOT_CHANGE_SUBJECT'	=> true,
			));
		};
	}

	/**
	* User can change post subject or not
	*
	* @return null
	* @access public
	*/
	public function change_subject_when_sending($event)
	{
		$data = $event['data'];

		$can_change_subject = ($this->auth->acl_get('f_qr_change_subject', $data['forum_id'])) ? true : false;

		if(!$can_change_subject && ($data['topic_first_post_id'] != $data['post_id']))
		{
			if($this->config['qr_enable_re'] == 0)
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
	* Delete Re:
	* Ctrl+Enter submit - template variables in the full editor
	* Ajax submit - error messages and preview
	*
	* @return null
	* @access public
	*/
	public function delete_re($event)
	{
		$forum_id	= $event['forum_id'];
		$topic_id	= $event['topic_id'];
		$page_data	= $event['page_data'];

		// Delete Re:
		if($this->config['qr_enable_re'] == 0)
		{
			$page_data['SUBJECT'] = preg_replace('/^Re: /', '', $page_data['SUBJECT']);
		}

		// Ctrl+Enter submit
		$page_data = array_merge($page_data, array(
			'S_QR_CE_ENABLE'		=> $this->config['qr_ctrlenter'],
		));

		$event['page_data'] = $page_data;

		//Ajax submit
		if($this->config['qr_ajax_submit'])
		{
			if ($this->request->is_ajax() && $this->request->is_set_post('qr'))
			{
				include_once($this->phpbb_root_path . 'includes/functions_posting.' . $this->php_ext);

				$error = $event['error'];
				$preview = $event['preview'];

				$post_data = $event['post_data'];
				$forum_id = $post_data['forum_id'];
				$topic_id = $post_data['topic_id'];
				$topic_cur_post_id = $post_data['topic_cur_post_id'];
				$message_parser = $event['message_parser'];

				if(sizeof($error))
				{
					$error_text = implode('<br />', $error);
					$url_next_post = 0;
				}
				else if (($post_data['topic_cur_post_id'] != $post_data['topic_last_post_id']) && $post_data['forum_flags'] && FORUM_FLAG_POST_REVIEW && topic_review($topic_id, $forum_id, 'post_review', $topic_cur_post_id))
				{
					$sql = 'SELECT post_id 
							FROM ' . POSTS_TABLE . '  
							WHERE topic_id = ' . $topic_id . ' 
								AND ' . $this->phpbb_content_visibility->get_visibility_sql('post', $forum_id, '') . '
								AND post_id > ' . $topic_cur_post_id . ' 
								ORDER BY post_time ASC';
					$result = $this->db->sql_query_limit($sql, 1);
					$post_id_next =  (int) $this->db->sql_fetchfield('post_id');
					$this->db->sql_freeresult($result);

					$error_text = $this->user->lang['POST_REVIEW_EXPLAIN'];
					$url_next_post = append_sid("{$this->phpbb_root_path}viewtopic.$this->php_ext", "f=$forum_id&amp;t=$topic_id&amp;p=$post_id_next#p$post_id_next");
				}

				// Preview
				if (!sizeof($error) && $preview)
				{
					$message_parser->message = html_entity_decode($this->request->variable('message', '', true), ENT_NOQUOTES);
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

				if(isset($error_text)) {
					$json_response = new \phpbb\json_response;

					if(!sizeof($error) && $preview)
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
							'NEXT_URL'			=> (isset($url_next_post)) ? $url_next_post : '',
							'REFRESH_DATA'	=> array(
								'time'	=> 3,
							)
						));
					}
				}
			}
		}
	}

	/**
	* Ajax submit
	*
	* @return array
	* @access public
	*/
	public function ajax_submit($event)
	{
		if($this->config['qr_ajax_submit'])
		{
			if ($this->request->is_ajax() && $this->request->is_set_post('qr'))
			{
				$json_response = new \phpbb\json_response;

				$data = $event['data'];
				$topic_cur_post_id	= $this->request->variable('topic_cur_post_id', 0);

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

				if ($topic_cur_post_id != $data['topic_last_post_id'] && $data['topic_id'] > 0)
				{
					$forum_id = $data['forum_id'];
					$topic_id = $data['topic_id'];
					$sql = 'SELECT post_id 
							FROM ' . POSTS_TABLE . '  
							WHERE topic_id = ' . $data['topic_id'] . ' 
								AND ' . $this->phpbb_content_visibility->get_visibility_sql('post', $data['forum_id'], '') . '
								AND post_id > ' . $topic_cur_post_id . ' 
								ORDER BY post_time ASC';
					$result = $this->db->sql_query_limit($sql, 1);
					$post_id_next =  (int) $this->db->sql_fetchfield('post_id');
					$this->db->sql_freeresult($result);

					$json_response->send(array(
						'success'		=> true,
						'url'			=> $event['url'],
						'prew_url'		=> append_sid("{$this->phpbb_root_path}viewtopic.$this->php_ext", "f=$forum_id&amp;t=$topic_id&amp;p=$post_id_next#p$post_id_next")
					));
				}

				$json_response->send(array(
					'success'		=> true,
					'url'			=> $event['url']
				));
			}
		}
	}

	/**
	* Hide posts subjects in searchresult
	*
	* @return null
	* @access public
	*/
	public function hide_posts_subjects_in_searchresults_sql($event)
	{
		if($this->config['qr_show_subjects'] == 0)
		{
			$sql_array = $event['sql_array'];
			if(!preg_match('/t\.topic_first_post_id/', $sql_array['SELECT'], $matches_t))
			{
				$sql_array['SELECT'] .= ', t.topic_first_post_id';
			}
			if(!preg_match('/p\.post_id/', $sql_array['SELECT'], $matches_p))
			{
				$sql_array['SELECT'] .= ', p.post_id';
			}
			$event['sql_array'] = $sql_array;
		}
	}

	public function hide_posts_subjects_in_searchresults_tpl($event)
	{
		if($this->config['qr_show_subjects'] == 0)
		{
			$row = $event['row'];
			$tpl_ary = $event['tpl_ary'];
			$show_results = $event['show_results'];

			if($show_results == 'posts')
			{
				$tpl_ary = array_merge($tpl_ary, array(
					'QR_NOT_FIRST_POST'	=> ($row['topic_first_post_id'] == $row['post_id']) ? false : true,
				));

				$event['tpl_ary'] = $tpl_ary;

				$this->template->assign_vars(array(
					'QR_HIDE_POSTS_SUBJECT'	=> true
				));
			}
		}
	}
}
