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
	* @param string $phpbb_root_path Root path
	* @param string $phpbb_ext
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, \phpbb\db\driver\driver_interface $db, \phpbb\extension\manager $phpbb_extension_manager, \phpbb\request\request $request, \phpbb\content_visibility $phpbb_content_visibility, $phpbb_root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->db = $db;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->request = $request;
		$this->phpbb_content_visibility = $phpbb_content_visibility;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
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
			'core.viewtopic_modify_post_row'	=>	'show_quicknick',
			'core.viewtopic_modify_page_title'	=>	'show_bbcodes_and_smilies',
			'core.modify_posting_parameters'	=>	'change_subject',
			'core.posting_modify_template_vars'	=>	'delete_re',
			'core.submit_post_end'				=>	'ajax_submit',
			'rxu.postsmerging.posts_merging_end'=>	'ajax_submit',
		);
	}

	/**
	* Refer by username
	*
	* @return null
	* @access public
	*/
	public function show_quicknick($event)
	{
		$this->user->add_lang_ext('tatiana5/quickreply', 'quickreply');

		$user_poster_data = $event['user_poster_data'];
		$row = $event['row'];
		$topic_data = $event['topic_data'];

		//Refer by username
		if($this->config['qr_quicknick'])
		{
			$user_colour = ($user_poster_data['user_colour']) ? ' class="username-coloured username" style="color: #' . $user_poster_data['user_colour'] . ';" ' : ' class="username" ';

			$event['post_row'] = array_merge($event['post_row'], array(
				'POST_AUTHOR_FULL'		=> '<a href="javascript:void(0);" id="' . $row['user_id'] . '" ' . $user_colour  . '>' . $user_poster_data['author_username'] . '</a>',
			));
		}
	}

	/**
	* Show bbcodes and smilies in the quickreply
	* Template data for Ajax sumbit
	*
	* @return null
	* @access public
	*/
	public function show_bbcodes_and_smilies($event)
	{
		include_once($this->phpbb_root_path . 'includes/functions_posting.' . $this->php_ext);

		$forum_id	= $event['forum_id'];
		$topic_data = $event['topic_data'];

		$s_quick_reply = false;
		if ($this->user->data['is_registered'] && $this->config['allow_quick_reply'] && ($topic_data['forum_flags'] & FORUM_FLAG_QUICK_REPLY) && $this->auth->acl_get('f_reply', $forum_id))
		{
			// Quick reply enabled forum
			$s_quick_reply = (($topic_data['forum_status'] == ITEM_UNLOCKED && $topic_data['topic_status'] == ITEM_UNLOCKED) || $this->auth->acl_get('m_edit', $forum_id)) ? true : false;
		}

		if ($s_quick_reply)
		{
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

			$this->template->assign_vars(array(
				'S_QR_NOT_CHANGE_SUBJECT'	=> ($this->auth->acl_get('f_qr_change_subject', $forum_id) || $this->auth->acl_get('m_qr_change_subject', $forum_id)) ? false : true,
				'S_QR_COMMA_ENABLE'		=> $this->config['qr_comma'],
				'S_QR_QUICKNICK_ENABLE'	=> $this->config['qr_quicknick'],
				'S_QR_QUICKQUOTE_ENABLE'=> $this->config['qr_quickquote'],
				'S_QR_CE_ENABLE'		=> $this->config['qr_ctrlenter'],

				'S_BBCODE_ALLOWED'		=> ($bbcode_status) ? 1 : 0,
				'S_SMILIES_ALLOWED'		=> $smilies_status,
				'S_BBCODE_IMG'			=> $img_status,
				'S_LINKS_ALLOWED'		=> $url_status,
				'S_BBCODE_FLASH'		=> $flash_status,
				'S_BBCODE_QUOTE'		=> $quote_status,

				//begin mod CapsLock Transfer
				'S_QR_CAPS_ENABLE'		=> $this->config['qr_capslock_transfer'],
				//end mod CapsLock Transfer

				//Ajax submit
				'CONFIG_POSTS_PER_PAGE'	=>  ($this->phpbb_extension_manager->is_enabled('rxu/FirstPostOnEveryPage') && $event['start'] > 0 && $topic_data['topic_first_post_show'] == 1) ? ($this->config['posts_per_page']+ 1) : $this->config['posts_per_page'],
				'L_FULL_EDITOR'			=> ($this->config['qr_ajax_submit']) ? $this->user->lang['PREVIEW'] : $this->user->lang['FULL_EDITOR'],
				'S_QR_AJAX_SUBMIT'		=> $this->config['qr_ajax_submit'],

				//ABBC3
				'S_ABBC3_INSTALLED'		=> $this->phpbb_extension_manager->is_enabled('vse/abbc3'),
			));

			if($this->config['qr_enable_re'] == 0)
			{
				$this->template->assign_vars(array(
					'SUBJECT'		=> $topic_data['topic_title'],
				));
			}
		}
	}

	/**
	* User can change post subject or not
	*
	* @return null
	* @access public
	*/
	public function change_subject($event)
	{
		$forum_id	= $event['forum_id'];
		$topic_id	= $event['topic_id'];

		$can_change_subject = ($this->auth->acl_get('f_qr_change_subject', $forum_id) || $this->auth->acl_get('m_qr_change_subject', $forum_id)) ? true : false;

		//$post_subject = '';

		if(!$can_change_subject && $event['mode'] != 'post' && !empty($topic_id))
		{
			$this->template->assign_vars(array(
				'S_QR_NOT_CHANGE_SUBJECT'	=> true,
			));
		};
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
			//$sql = 'SELECT topic_title
			//			FROM ' . TOPICS_TABLE . '
			//			WHERE topic_id = ' . (int) $topic_id;
			//$result = $this->db->sql_query($sql);
			//$post_subject = $this->db->sql_fetchrow($result);
			//$this->db->sql_freeresult($result);

			//$post_subject = censor_text($post_subject['topic_title']);
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
			$message_parser = $event['message_parser'];

			if ($this->request->is_ajax() && $this->request->is_set_post('qr'))
			{
				$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
				include_once($phpbb_root_path . 'includes/functions_posting.' . $this->php_ext);

				$error = $event['error'];
				$preview = $event['preview'];

				$post_data = $event['post_data'];
				$forum_id = $post_data['forum_id'];
				$topic_id = $post_data['topic_id'];
				$topic_cur_post_id = $post_data['topic_cur_post_id'];

				if(sizeof($error))
				{
					$error_text = implode('<br />', $error);
					$url_next_post = 0;
				}
				else if ($post_data['topic_cur_post_id'] != $post_data['topic_last_post_id'])
				{
					$sql = 'SELECT post_id 
							FROM ' . POSTS_TABLE . '  
							WHERE topic_id = ' . $topic_id . ' 
								AND ' . $this->phpbb_content_visibility->get_visibility_sql('post', $forum_id, '') . '
								AND post_id > ' . $topic_cur_post_id . ' 
								ORDER BY post_time DESC';
						$result = $this->db->sql_query_limit($sql, 1);
						$post_id_next =  (int) $this->db->sql_fetchfield('post_id');
						$this->db->sql_freeresult($result);

					if($post_data['forum_flags'] && FORUM_FLAG_POST_REVIEW && topic_review($topic_id, $forum_id, 'post_review', $topic_cur_post_id))
					{
						$error_text = $this->user->lang['POST_REVIEW_EXPLAIN'];
						$url_next_post = append_sid("{$this->phpbb_root_path}viewtopic.$this->php_ext", "f=$forum_id&amp;t=$topic_id&amp;p=$post_id_next#p$post_id_next");
					}
					else
					{
						//$url_next_post = append_sid("{$this->phpbb_root_path}viewtopic.$this->php_ext", "f=$forum_id&amp;t=$topic_id&amp;p=$post_data['topic_id']#p$post_data['topic_id']");
					}
				}

				// Preview
				if (!sizeof($error) && $preview)
				{
					$message_parser->message = html_entity_decode($this->request->variable('message', '', true), ENT_NOQUOTES);
					$preview_message = $message_parser->format_display($post_data['enable_bbcode'], $post_data['enable_urls'], $post_data['enable_smilies'], false);

						// Attachment Preview
						//if (sizeof($message_parser->attachment_data))
						//{
							//$template->assign_var('S_HAS_ATTACHMENTS', true);

							//$update_count = array();
							//$attachment_data = $message_parser->attachment_data;

							//parse_attachments($forum_id, $preview_message, $attachment_data, $update_count, true);

							//foreach ($attachment_data as $i => $attachment)
							//{
								//$template->assign_block_vars('attachment', array(
								//	'DISPLAY_ATTACHMENT'	=> $attachment)
								//);
							//}
							//unset($attachment_data);
						//}

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
			$this->user->add_lang_ext('tatiana5/quickreply', 'quickreply');

			if ($this->request->is_ajax() && $this->request->is_set_post('qr'))
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

				$json_response->send(array(
					'success'		=> true,
					'url'			=> $event['url'],
					'REFRESH_DATA'	=> array(
						'time'	=> 1,
						'url'	=> html_entity_decode($event['url'])
					)
				));
			}
		}
	}
}
