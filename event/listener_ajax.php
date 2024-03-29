<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2021 Татьяна5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace boardtools\quickreply\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class listener_ajax implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \boardtools\quickreply\functions\ajax_helper */
	protected $ajax_helper;

	/** @var \boardtools\quickreply\functions\listener_helper */
	protected $helper;

	/** @var bool */
	protected $img_status;

	/** @var bool */
	protected $flash_status;

	/** @var bool */
	protected $quote_status;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config                             $config
	 * @param \phpbb\template\template                         $template
	 * @param \phpbb\user                                      $user
	 * @param \phpbb\request\request                           $request
	 * @param \boardtools\quickreply\functions\ajax_helper     $ajax_helper
	 * @param \boardtools\quickreply\functions\listener_helper $helper
	 */
	public function __construct(\phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, \phpbb\request\request $request, \boardtools\quickreply\functions\ajax_helper $ajax_helper, \boardtools\quickreply\functions\listener_helper $helper)
	{
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->request = $request;
		$this->ajax_helper = $ajax_helper;
		$this->helper = $helper;
		$this->img_status = false;
		$this->flash_status = false;
		$this->quote_status = false;
	}

	/**
	 * Assign functions defined in this class to event listeners in the core
	 *
	 * @return array
	 */
	static public function getSubscribedEvents()
	{
		// We set lower priority for some events for the case if another extension wants to use those events.
		return [
			'core.viewtopic_get_post_data'          => ['viewtopic_modify_sql', -2],
			'core.viewtopic_modify_page_title'      => ['ajaxify_viewtopic_data', -2],
			'core.posting_modify_bbcode_status'     => ['get_bbcode_status', -2],
			'core.posting_modify_submission_errors' => 'detect_new_posts',
			'core.posting_modify_message_text'      => 'ajax_preview',
			'core.submit_post_end'                  => ['ajax_submit', -2],
			'rxu.postsmerging.posts_merging_end'    => ['ajax_submit', -2],
		];
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
		$current_post = (int) $this->request->variable('qr_cur_post_id', 0);
		if ($this->ajax_helper->no_refresh($current_post, $post_list))
		{
			$this->ajax_helper->qr_merged = $qr_get_current = $this->request->is_set('qr_get_current');
			$sql_ary = $event['sql_ary'];

			$compare = (
				$qr_get_current ||
				in_array($current_post, $post_list) && $this->ajax_helper->check_post_merge()
			) ? ' >= ' : ' > ';
			$sql_ary['WHERE'] .= ' AND p.post_id' . $compare . $current_post;

			$this->ajax_helper->qr_insert = true;
			$this->ajax_helper->qr_first = ($current_post == min($post_list)) && $qr_get_current;

			$event['sql_ary'] = $sql_ary;

			// Check whether no posts are found.
			if (!$qr_get_current && max($post_list) <= $current_post)
			{
				$this->ajax_helper->output_errors([$this->user->lang['NO_POSTS_TIME_FRAME']]);
			}
		}
	}

	/**
	 * Template data for Ajax submit
	 *
	 * @param object $event The event object
	 */
	public function ajaxify_viewtopic_data($event)
	{
		$forum_id = $event['forum_id'];
		$topic_data = $event['topic_data'];
		$post_list = $event['post_list'];

		// Ajaxify viewtopic data
		if ($this->ajax_helper->qr_is_ajax())
		{
			$this->ajax_helper->ajax_response($event['page_title'], max($post_list), $forum_id);
		}

		if ($this->helper->qr_is_enabled($forum_id, $topic_data))
		{
			if ($topic_data['qr_ajax_submit'])
			{
				$this->template->append_var('QR_HIDDEN_FIELDS', build_hidden_fields([
					'qr'             => 1,
					'qr_cur_post_id' => (int) max($post_list)
				]));
			}

			// Add lastclick for phpBB 3.2.4+
			if (phpbb_version_compare($this->config['version'], '3.2.4', '>='))
			{
				$qr_hidden_fields = [
					'lastclick' => (int) time(),
				];

				$this->template->append_var('QR_HIDDEN_FIELDS', build_hidden_fields($qr_hidden_fields));
			}

			$this->template->assign_vars($this->ajax_helper->template_variables_for_ajax($topic_data));
		}
	}

	/**
	 * Get bbcode status
	 *
	 * @param object $event The event object
	 */
	public function get_bbcode_status($event)
	{
		$this->img_status = $event['img_status'];
		$this->flash_status = $event['flash_status'];
		$this->quote_status = $event['quote_status'];
	}

	/**
	 * Do not post the message if there are some new ones
	 *
	 * @param object $event The event object
	 */
	public function detect_new_posts($event)
	{
		// Ajax submit
		if ($this->ajax_helper->qr_is_ajax_submit())
		{
			$post_data = $event['post_data'];
			$error = $event['error'];
			$lastclick = $this->request->variable('lastclick', time());

			$refresh_token = $this->ajax_helper->check_form_token($error, $post_data);
			$this->ajax_helper->check_errors($error);
			$event['error'] = $error;

			// New form token is always sent if needed.
			if ($this->helper->post_needs_review($lastclick, $post_data))
			{
				$forum_id = (int) $post_data['forum_id'];
				$topic_id = (int) $post_data['topic_id'];
				$this->ajax_helper->send_next_post_url($forum_id, $topic_id, $lastclick);
			}
			else if ($refresh_token)
			{
				// Send only the new form token as a response.
				$this->ajax_helper->send_form_token();
			}
		}
	}

	/**
	 * Ajax submit - error messages and preview
	 *
	 * @param object $event The event object
	 */
	public function ajax_preview($event)
	{
		// Ajax submit
		if ($this->ajax_helper->qr_is_ajax_submit() && $this->request->is_set_post('preview'))
		{
			$this->ajax_helper->ajax_preview_helper->check_preview_error($event, $this->img_status, $this->flash_status, $this->quote_status);
			$this->ajax_helper->ajax_preview_helper->ajax_preview($event, $this->img_status, $this->flash_status, $this->quote_status);
		}
	}

	/**
	 * Check Ajax submit
	 *
	 * @param object $event The event object
	 */
	public function ajax_submit($event)
	{
		if ($this->ajax_helper->qr_is_ajax_submit())
		{
			$this->ajax_helper->ajax_submit($event);
		}
	}
}
