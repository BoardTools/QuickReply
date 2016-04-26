<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace boardtools\quickreply\functions;

class ajax_helper
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

	/** @var \phpbb\content_visibility */
	protected $phpbb_content_visibility;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $php_ext;

	/** @var ajax_preview_helper */
	public $ajax_preview_helper;

	/** @var bool */
	public $qr_insert;

	/** @var bool */
	public $qr_first;

	/**
	 * Constructor
	 *
	 * @param \phpbb\auth\auth                  $auth
	 * @param \phpbb\config\config              $config
	 * @param \phpbb\template\template          $template
	 * @param \phpbb\user                       $user
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\content_visibility         $phpbb_content_visibility
	 * @param \phpbb\request\request            $request
	 * @param string                            $phpbb_root_path Root path
	 * @param string                            $php_ext
	 * @param ajax_preview_helper               $ajax_preview_helper
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, \phpbb\db\driver\driver_interface $db, \phpbb\content_visibility $phpbb_content_visibility, \phpbb\request\request $request, $phpbb_root_path, $php_ext, ajax_preview_helper $ajax_preview_helper)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->db = $db;
		$this->phpbb_content_visibility = $phpbb_content_visibility;
		$this->request = $request;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->ajax_preview_helper = $ajax_preview_helper;
		$this->qr_insert = false;
		$this->qr_first = false;
	}

	/**
	 * Checks whether quick reply form was submitted using Ajax
	 *
	 * @return bool
	 */
	public function qr_is_ajax_submit()
	{
		return $this->config['qr_ajax_submit'] && $this->request->is_ajax() && $this->request->is_set_post('qr');
	}

	/**
	 * Checks whether this is a QuickReply Ajax request
	 *
	 * @return bool
	 */
	public function qr_is_ajax()
	{
		return $this->request->is_ajax() && $this->request->is_set('qr_request');
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
		$this->output_ajax_response($page_title, $forum_id);
	}

	public function output_ajax_response($page_title, $forum_id)
	{
		page_header($page_title, false, $forum_id);
		page_footer(false, false, false);
		$this->send_json(array(
			'success' => true,
			'result'  => $this->template->assign_display('@boardtools_quickreply/quickreply_template.html', '', true),
			'insert'  => $this->qr_insert
		));
	}

 	/**
	 * Ajax submit
	 *
	 * @param object $event The event object
	 */
	public function ajax_submit($event)
	{
		$data = $event['data'];
		if ($this->is_not_approved($data))
		{
			// No approve
			$this->send_approval_notify();
		}

		$qr_cur_post_id = $this->request->variable('qr_cur_post_id', 0);
		$url_hash = strpos($event['url'], '#');
		$result_url = ($url_hash !== false) ? substr($event['url'], 0, $url_hash) : $event['url'];

		$this->send_json(array(
			'success' => true,
			'url'     => $result_url,
			'merged'  => ($qr_cur_post_id === $data['post_id']) ? 'merged' : 'not_merged'
		));
	}

	/**
	 * Check approve
	 *
	 * @param array $data
	 */
	public function is_not_approved($data)
	{
		return (
				!$this->auth->acl_get('f_noapprove', $data['forum_id'])
					&& empty($data['force_approved_state'])
				) || (
				isset($data['force_approved_state'])
					&& !$data['force_approved_state']
				);
	}

	/**
	 * Sends ajax response in case of errors
	 *
	 * @param array $error Array with error strings
	 */
	public function check_errors($error)
	{
		if (sizeof($error))
		{
			$this->send_json(array(
				'error'         => true,
				'MESSAGE_TITLE' => $this->user->lang['INFORMATION'],
				'MESSAGE_TEXT'  => implode('<br />', $error),
			));
		}
	}

	/**
	 * Sends the URL to the next post
	 *
	 * @param int $forum_id  Forum ID
	 * @param int $topic_id  Topic ID
	 * @param int $lastclick Time of the last click
	 */
	public function send_next_post_url($forum_id, $topic_id, $lastclick)
	{
		$sql = 'SELECT post_id
				FROM ' . POSTS_TABLE . '
				WHERE topic_id = ' . (int) $topic_id . '
					AND ' . $this->phpbb_content_visibility->get_visibility_sql('post', (int) $forum_id, '') . '
					AND post_time > ' . (int) $lastclick . '
				ORDER BY post_time ASC';
		$result = $this->db->sql_query_limit($sql, 1);
		$post_id_next = (int) $this->db->sql_fetchfield('post_id');
		$this->db->sql_freeresult($result);

		$error_text = $this->user->lang['POST_REVIEW_EXPLAIN'];
		$url_next_post = append_sid("{$this->phpbb_root_path}viewtopic.$this->php_ext", "f=$forum_id&amp;t=$topic_id&amp;p=$post_id_next"); // #p$post_id_next
		$current_post = $this->request->variable('qr_cur_post_id', 0);

		$this->send_json(array(
			'error'         => true,
			'merged'        => ($post_id_next === $current_post) ? 'merged' : 'not_merged',
			'MESSAGE_TITLE' => $this->user->lang['INFORMATION'],
			'MESSAGE_TEXT'  => $error_text,
			'NEXT_URL'      => $url_next_post,
		));
	}

	/**
	 * Sends post approval notice
	 */
	public function send_approval_notify()
	{
		$this->send_json(array(
			'noapprove'     => true,
			'MESSAGE_TITLE' => $this->user->lang['INFORMATION'],
			'MESSAGE_TEXT'  => $this->user->lang['POST_STORED_MOD'] . (($this->user->data['user_id'] == ANONYMOUS) ? '' : ' ' . $this->user->lang['POST_APPROVAL_NOTIFY']),
			'REFRESH_DATA'  => array(
				'time' => 10,
			)
		));
	}

	/**
	 * Sends the ID of last post in the topic
	 *
	 * @param int $post_id Post ID
	 */
	public function send_last_post_id($post_id)
	{
		$this->send_json(array(
			'post_update' => true,
			'post_id'     => $post_id,
		));
	}

	/**
	 * Sends a JSON response
	 *
	 * @param array $data Array with JSON data
	 */
	public function send_json($data)
	{
		$json_response = new \phpbb\json_response;
		$json_response->send($data);
	}

	public function template_variables_for_ajax()
	{
		return array(
				'L_FULL_EDITOR'             => ($this->config['qr_ajax_submit']) ? $this->user->lang['PREVIEW'] : $this->user->lang['FULL_EDITOR'],
				'S_QR_AJAX_SUBMIT'          => $this->config['qr_ajax_submit'],

				'S_QR_AJAX_PAGINATION' => $this->config['qr_ajax_pagination'] && $this->user->data['ajax_pagination'],

				'S_QR_ENABLE_SCROLL'   => $this->user->data['qr_enable_scroll'],
				'S_QR_SCROLL_INTERVAL' => $this->config['qr_scroll_time'],
				'S_QR_SOFT_SCROLL'     => $this->config['qr_scroll_time'] && $this->user->data['qr_soft_scroll']
			);
	}

	public function no_refresh($current_post, $post_list)
	{
		return $this->request->is_ajax() && $this->request->variable('qr_no_refresh', 0) && in_array($current_post, $post_list);
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
