<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace boardtools\quickreply\functions;

class ajax_preview_helper
{
	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\request\request */
	protected $request;

	/**
	 * Constructor
	 *
	 * @param \phpbb\user                       $user
	 * @param \phpbb\request\request            $request
	 */
	public function __construct(\phpbb\user $user, \phpbb\request\request $request)
	{
		$this->user = $user;
		$this->request = $request;
	}

	/**
	 * Checks whether this is an ajax request and handles ajax preview
	 *
	 * @param object $event The event object
	 */
	public function ajax_preview($event)
	{
		$post_data = $event['post_data'];
		$forum_id = (int) $post_data['forum_id'];
		/** @var \parse_message $message_parser */
		$message_parser = $event['message_parser'];

		$message_parser->message = $this->request->variable('message', '', true);
		$preview_message = $message_parser->format_display($post_data['enable_bbcode'], $post_data['enable_urls'], $post_data['enable_smilies'], false);

		// Attachment Preview
		$attach_array = $this->preview_attachments($message_parser, $forum_id, $preview_message);
		$preview_message = $attach_array[0];
		$preview_attachments = $attach_array[1];

		ajax_helper::send_json(array(
			'preview'        => true,
			'PREVIEW_TITLE'  => $this->user->lang['PREVIEW'],
			'PREVIEW_TEXT'   => $preview_message,
			'PREVIEW_ATTACH' => $preview_attachments,
		));
	}

	/**
	 * Attachments preview
	 *
	 * @param
	 */
	public function preview_attachments($message_parser, $forum_id, $preview_message)
	{
		$preview_attachments = false;
		if (sizeof($message_parser->attachment_data))
		{
			$update_count = array();
			$attachment_data = $message_parser->attachment_data;

			parse_attachments($forum_id, $preview_message, $attachment_data, $update_count, true);
			$preview_attachments = $this->build_attach_box($attachment_data);

			unset($attachment_data);
		}

		return array($preview_message, $preview_attachments);
	}

	/**
	 * Build attach box for not-inline attachmets
	 *
	 * @param array $attachment_data
	 */
	public function build_attach_box($attachment_data)
	{
		$preview_attachments = '';
		foreach ($attachment_data as $i => $attachment)
		{
			$preview_attachments .= '<dd>' . $attachment . '</dd>';
		}
		if (!empty($preview_attachments))
		{
			$preview_attachments = '<dl class="attachbox"><dt>' . $this->user->lang['ATTACHMENTS'] . '</dt>' . $preview_attachments . '</dl>';
		}

		return $preview_attachments;
	}
}
