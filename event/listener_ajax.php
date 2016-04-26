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

	/** @var \boardtools\quickreply\functions\listener_helper */
	protected $helper;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config                             $config
	 * @param \phpbb\template\template                         $template
	 * @param \phpbb\user                                      $user
	 * @param \phpbb\request\request                           $request
	 * @param \boardtools\quickreply\functions\listener_helper $helper
	 */
	public function __construct(\phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, \phpbb\request\request $request, \boardtools\quickreply\functions\listener_helper $helper)
	{
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->request = $request;
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
			'core.viewtopic_get_post_data'              => array('viewtopic_modify_sql', -2),
			'core.viewtopic_modify_page_title'          => array('ajaxify_viewtopic_data', -2),
			'core.posting_modify_submission_errors'     => 'detect_new_posts',
			'core.posting_modify_template_vars'         => 'ajax_preview',
			'core.submit_post_end'                      => array('ajax_submit', -2),
			'rxu.postsmerging.posts_merging_end'        => 'ajax_submit',
		);
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
		if ($this->helper->ajax_helper->no_refresh($current_post, $post_list))
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
		if ($this->helper->ajax_helper->qr_is_ajax())
		{
			$this->helper->ajax_helper->ajax_response($event['page_title'], max($post_list), $forum_id);
		}

		if ($this->helper->qr_is_enabled($forum_id, $topic_data))
		{
			$this->template->append_var('QR_HIDDEN_FIELDS', build_hidden_fields(array(
				'qr'             => 1,
				'qr_cur_post_id' => (int) max($post_list)
			)));
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
	 * Ajax submit - error messages and preview
	 *
	 * @param object $event The event object
	 */
	public function ajax_preview($event)
	{
		// Ajax submit
		if ($this->helper->ajax_helper->qr_is_ajax_submit())
		{
			$this->helper->ajax_helper->check_errors($event['error']);
			if ($event['preview'])
			{
				$this->helper->ajax_helper->ajax_preview($event);
			}
		}
	}

	/**
	 * Check Ajax submit
	 *
	 * @param object $event The event object
	 */
	public function ajax_submit($event)
	{
		if ($this->helper->ajax_helper->qr_is_ajax_submit())
		{
			$this->helper->ajax_helper->ajax_submit($event);
		}
	}
}
