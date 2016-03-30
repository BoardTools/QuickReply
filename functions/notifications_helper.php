<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace boardtools\quickreply\functions;

class notifications_helper
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\notification\manager */
	protected $notification_manager;

	/**
	 * Constructor
	 *
	 * @param \phpbb\auth\auth             $auth
	 * @param \phpbb\user                  $user
	 * @param \phpbb\notification\manager  $notification_manager
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\user $user, \phpbb\notification\manager $notification_manager)
	{
		$this->auth = $auth;
		$this->user = $user;
		$this->notification_manager = $notification_manager;
	}

	public function mark_qr_notifications_read($post_list)
	{
		$this->notification_manager->mark_notifications_read('boardtools.quickreply.notification.type.quicknick', $post_list, $this->user->data['user_id']);
	}

	public function add_qr_notifications($event)
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
	}
}
