<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace boardtools\quickreply\event;

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

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\notification\manager */
	protected $notification_manager;

	/** @var \boardtools\quickreply\functions\listener_helper */
	protected $helper;

	/**
	 * Constructor
	 *
	 * @param \phpbb\auth\auth                                 $auth
	 * @param \phpbb\config\config                             $config
	 * @param \phpbb\template\template                         $template
	 * @param \phpbb\user                                      $user
	 * @param \phpbb\request\request                           $request
	 * @param \phpbb\notification\manager                      $notification_manager
	 * @param \boardtools\quickreply\functions\listener_helper $helper
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, \phpbb\request\request $request, \phpbb\notification\manager $notification_manager, \boardtools\quickreply\functions\listener_helper $helper)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->request = $request;
		$this->notification_manager = $notification_manager;
		$this->helper = $helper;
	}

	/**
	 * Assign functions defined in this class to event listeners in the core
	 *
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		// We set lower priority for some events for the case if another extension wants to use those events.
		return array(
			'core.user_setup'                           => 'load_language_on_setup',
			'core.viewtopic_get_post_data'              => array('viewtopic_modify_sql', -2),
			'core.viewtopic_modify_post_row'            => 'viewtopic_modify_post_row',
			'core.viewtopic_modify_page_title'          => array('viewtopic_modify_data', -2),
			'core.modify_submit_post_data'              => 'change_subject_when_sending',
			'core.posting_modify_submission_errors'     => 'detect_new_posts',
			'core.posting_modify_template_vars'         => 'delete_re',
			'core.submit_post_end'                      => array('ajax_submit', -2),
			'rxu.postsmerging.posts_merging_end'        => 'ajax_submit',
			'core.search_get_posts_data'                => 'hide_posts_subjects_in_searchresults_sql',
			'core.search_modify_tpl_ary'                => 'hide_posts_subjects_in_searchresults_tpl',
			'core.ucp_prefs_view_data'                  => 'ucp_prefs_get_data',
			'core.ucp_prefs_view_update_data'           => 'ucp_prefs_set_data',
			'core.acp_users_prefs_modify_data'          => 'acp_prefs_get_data',
			'core.acp_users_prefs_modify_template_data' => 'acp_prefs_template_data',
			'core.acp_users_prefs_modify_sql'           => 'ucp_prefs_set_data', // For the ACP.
			'core.permissions'                          => 'add_permission',
		);
	}

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'boardtools/quickreply',
			'lang_set' => 'quickreply_notification',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	 * Reduce the set of elements to the one that we need to retrieve.
	 *
	 * @param object $event The event object
	 */
	public function viewtopic_modify_sql($event)
	{
		$this->helper->captcha_helper->check_captcha_refresh();

		$post_list = $event['post_list'];
		$current_post = $this->request->variable('qr_cur_post_id', 0);
		if ($this->request->is_ajax() && $this->request->variable('qr_no_refresh', 0) && in_array($current_post, $post_list))
		{
			$sql_ary = $event['sql_ary'];
			$qr_get_current = $this->request->is_set('qr_get_current');
			$compare = ($qr_get_current) ? ' >= ' : ' > ';
			$sql_ary['WHERE'] .= ' AND p.post_id' . $compare . $current_post;
			$event['sql_ary'] = $sql_ary;
			$this->helper->ajax_helper->qr_insert = true;
			$this->helper->ajax_helper->qr_first = ($current_post == min($post_list)) && $qr_get_current;

			// Check whether no posts are found.
			if ($compare == ' > ' && max($post_list) <= $current_post)
			{
				$this->helper->ajax_helper->check_errors(array($this->user->lang['NO_POSTS_TIME_FRAME']));
			}
		}
		$this->user->add_lang_ext('boardtools/quickreply', 'quickreply');
	}

	/**
	 * Add decoded message text if full quotes are enabled.
	 *
	 * @param object $event The event object
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
				'DECODED_MESSAGE' => $decoded_message,
			));
			$event['post_row'] = $post_row;
		}
	}

	/**
	 * Show bbcodes and smilies in the quickreply
	 * Template data for Ajax submit
	 *
	 * @param object $event The event object
	 */
	public function viewtopic_modify_data($event)
	{
		$forum_id = $event['forum_id'];
		$topic_data = $event['topic_data'];
		$post_list = $event['post_list'];
		$topic_id = $topic_data['topic_id'];

		// Mark post notifications read for this user in this topic
		$this->notification_manager->mark_notifications_read('boardtools.quickreply.notification.type.quicknick', $post_list, $this->user->data['user_id']);

		$s_quick_reply = $this->helper->qr_is_enabled($forum_id, $topic_data);

		if (!$this->user->data['is_registered'] && $s_quick_reply)
		{
			$this->helper->enable_qr_for_guests($forum_id, $topic_data);
		}

		// Ajaxify viewtopic data
		if ($this->helper->ajax_helper->qr_is_ajax())
		{
			$this->helper->ajax_helper->ajax_response($event['page_title'], max($post_list), $forum_id);
		}

		if ($s_quick_reply)
		{
			$this->helper->form_helper->prepare_qr_form($forum_id, $topic_id);

			$this->template->append_var('QR_HIDDEN_FIELDS', build_hidden_fields(array(
				'qr'             => 1,
				'qr_cur_post_id' => (int) max($post_list)
			)));

			$this->helper->assign_template_variables_for_qr($forum_id);

			$add_re = ($this->config['qr_enable_re']) ? 'Re: ' : '';
			$this->template->assign_var('SUBJECT', $this->request->variable('subject', $add_re . censor_text($topic_data['topic_title']), true));
		}

		$this->template->assign_vars(array(
			'QR_HIDE_POSTS_SUBJECT' => !$this->config['qr_show_subjects'],
		));
	}

	/**
	 * Lock post subject if the user cannot change it.
	 *
	 * @param object $event The event object
	 */
	public function change_subject_when_sending($event)
	{
		$data = $event['data'];

		if (
			!$this->auth->acl_get('f_qr_change_subject', $data['forum_id']) &&
			isset($data['topic_first_post_id']) &&
			isset($data['post_id']) &&
			($data['topic_first_post_id'] != $data['post_id'])
		)
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
	 */
	public function detect_new_posts($event)
	{
		// Ajax submit
		if ($this->helper->ajax_helper->qr_is_ajax_submit())
		{
			$this->helper->ajax_helper->check_errors($event['error']);

			$post_data = $event['post_data'];
			$lastclick = $this->request->variable('lastclick', time());

			if (($lastclick < $post_data['topic_last_post_time']) && ($post_data['forum_flags'] & FORUM_FLAG_POST_REVIEW))
			{
				$forum_id = (int) $post_data['forum_id'];
				$topic_id = (int) $post_data['topic_id'];
				$this->helper->ajax_helper->send_next_post_url($forum_id, $topic_id, $lastclick);
			}
			else if ($post_data['topic_cur_post_id'] && $post_data['topic_cur_post_id'] != $post_data['topic_last_post_id'])
			{
				// Send new post number as a response.
				$this->helper->ajax_helper->send_last_post_id($post_data['topic_last_post_id']);
			}
		}
		// This is needed for BBCode QR_BBPOST.
		$this->user->add_lang_ext('boardtools/quickreply', 'quickreply');
	}

	/**
	 * Delete Re:, lock post subject
	 * Ctrl+Enter submit - template variables in the full editor
	 * Ajax submit - error messages and preview
	 *
	 * @param object $event The event object
	 */
	public function delete_re($event)
	{
		$forum_id = $event['forum_id'];
		$page_data = $event['page_data'];
		$post_data = $event['post_data'];

		// Delete Re:
		if ($this->config['qr_enable_re'] == 0)
		{
			$page_data['SUBJECT'] = preg_replace('/^Re: /', '', $page_data['SUBJECT']);
		}

		// Whether the user can change post subject or not
		if (!$this->auth->acl_get('f_qr_change_subject', $forum_id) && $event['mode'] != 'post' && $post_data['topic_first_post_id'] != $event['post_id'])
		{
			$this->template->assign_vars(array(
				'S_QR_NOT_CHANGE_SUBJECT' => true,
				'QR_HIDE_SUBJECT_BOX'     => $this->config['qr_hide_subject_box'],
			));
		};

		// Ctrl+Enter submit
		$page_data = array_merge($page_data, array(
			'S_QR_CE_ENABLE' => $this->config['qr_ctrlenter'],
		));

		$event['page_data'] = $page_data;

		// Ajax submit
		$this->helper->ajax_helper->check_ajax_preview($event);
	}

	/**
	 * Check Ajax submit
	 *
	 * @param object $event The event object
	 */
	public function ajax_submit($event)
	{
		$mode = $event['mode'];
		$data = $event['data'];
		$subject = $event['subject'];
		$username = $event['username'];

		$notification_data = array_merge($data, array(
			'topic_title'		=> (isset($data['topic_title'])) ? $data['topic_title'] : $subject,
			'post_username'		=> $username,
			'poster_id'			=> $data['poster_id'],
			'post_text'			=> $data['message'],
			'post_time'			=> time(),
			'post_subject'		=> $subject,
		));

		if ($this->auth->acl_get('f_noapprove', $data['forum_id']))
		{
			switch ($mode)
			{
				case 'post':
				case 'reply':
				case 'quote':
					$this->notification_manager->add_notifications(array(
						'boardtools.quickreply.notification.type.quicknick',
					), $notification_data);
				break;

				case 'edit_topic':
				case 'edit_first_post':
				case 'edit':
				case 'edit_last_post':
					$this->notification_manager->update_notifications('boardtools.quickreply.notification.type.quicknick', $notification_data);
				break;
			}
		}

		if ($this->helper->ajax_helper->qr_is_ajax_submit())
		{
			$this->helper->ajax_helper->ajax_submit($event);
		}
	}

	/**
	 * Hide posts subjects in search results
	 *
	 * @param object $event The event object
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
				'QR_HIDE_POSTS_SUBJECT' => true
			));
		}
	}

	/**
	 * Hide posts subjects in search results
	 *
	 * @param object $event The event object
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
					'QR_NOT_FIRST_POST' => ($row['topic_first_post_id'] == $row['post_id']) ? false : true,
				));

				$event['tpl_ary'] = $tpl_ary;
			}
		}
	}

	/**
	 * Get user's options and display them in UCP Prefs View page
	 *
	 * @param object $event The event object
	 */
	public function ucp_prefs_get_data($event)
	{
		// Request the user option vars and add them to the data array
		$event['data'] = array_merge($event['data'], array(
			'ajax_pagination'  => $this->request->variable('ajax_pagination', (int) $this->user->data['ajax_pagination']),
			'qr_enable_scroll' => $this->request->variable('qr_enable_scroll', (int) $this->user->data['qr_enable_scroll']),
			'qr_soft_scroll'   => $this->request->variable('qr_soft_scroll', (int) $this->user->data['qr_soft_scroll']),
		));

		// Output the data vars to the template
		$this->user->add_lang_ext('boardtools/quickreply', 'quickreply_ucp');
		$this->template->assign_vars(array(
			'S_AJAX_PAGINATION'        => $this->config['qr_ajax_pagination'],
			'S_ENABLE_AJAX_PAGINATION' => $event['data']['ajax_pagination'],
			'S_QR_ENABLE_SCROLL'       => $event['data']['qr_enable_scroll'],
			'S_QR_ALLOW_SOFT_SCROLL'   => !!$this->config['qr_scroll_time'],
			'S_QR_SOFT_SCROLL'         => $event['data']['qr_soft_scroll'],
		));
	}

	/**
	 * Add user options' state into the sql_array
	 *
	 * @param object $event The event object
	 */
	public function ucp_prefs_set_data($event)
	{
		$event['sql_ary'] = array_merge($event['sql_ary'], array(
			'ajax_pagination'  => $event['data']['ajax_pagination'],
			'qr_enable_scroll' => $event['data']['qr_enable_scroll'],
			'qr_soft_scroll'   => $event['data']['qr_soft_scroll'],
		));
	}

	/**
	 * Get user's options and display them in ACP Prefs View page
	 *
	 * @param object $event The event object
	 */
	public function acp_prefs_get_data($event)
	{
		$data = $event['data'];
		$user_row = $event['user_row'];
		$data = array_merge($data, array(
			'ajax_pagination'  => $this->request->variable('ajax_pagination', (int) $user_row['ajax_pagination']),
			'qr_enable_scroll' => $this->request->variable('qr_enable_scroll', (int) $user_row['qr_enable_scroll']),
			'qr_soft_scroll'   => $this->request->variable('qr_soft_scroll', (int) $user_row['qr_soft_scroll']),
		));
		$event['data'] = $data;
	}

	/**
	 * Assign template data in the ACP
	 *
	 * @param object $event The event object
	 */
	public function acp_prefs_template_data($event)
	{
		$this->user->add_lang_ext('boardtools/quickreply', 'quickreply_ucp');
		$data = $event['data'];
		$user_prefs_data = $event['user_prefs_data'];
		$user_prefs_data = array_merge($user_prefs_data, array(
			'S_AJAX_PAGINATION'        => $this->config['qr_ajax_pagination'],
			'S_ENABLE_AJAX_PAGINATION' => $data['ajax_pagination'],
			'S_QR_ENABLE_SCROLL'       => $data['qr_enable_scroll'],
			'S_QR_ALLOW_SOFT_SCROLL'   => !!$this->config['qr_scroll_time'],
			'S_QR_SOFT_SCROLL'         => $data['qr_soft_scroll'],
		));
		$event['user_prefs_data'] = $user_prefs_data;
	}

	/**
	 * Add permissions
	 *
	 * @param object $event The event object
	 */
	public function add_permission($event)
	{
		$permissions = $event['permissions'];
		$permissions['a_quickreply'] = array('lang' => 'ACL_A_QUICKREPLY', 'cat' => 'misc');
		$permissions['f_qr_change_subject'] = array('lang' => 'ACL_F_QR_CHANGE_SUBJECT', 'cat' => 'post');
		$event['permissions'] = $permissions;
	}
}
