<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2021 Татьяна5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace boardtools\quickreply\functions;

class ajax_preview_helper
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var string */
	protected $preview_message = '';

	/** @var string|bool */
	protected $preview_attachments = false;

	/**
	 * Constructor
	 *
	 * @param \phpbb\auth\auth       $auth
	 * @param \phpbb\config\config   $config
	 * @param \phpbb\user            $user
	 * @param \phpbb\request\request $request
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config,
								\phpbb\user $user, \phpbb\request\request $request)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->user = $user;
		$this->request = $request;
	}

	/**
	 * Checks whether this is an ajax request and handles ajax preview
	 *
	 * @param object $event The event object
	 * @param bool	img_status		Image BBCode status
	 * @param bool	flash_status	Flash BBCode status
	 * @param bool	quote_status	Quote BBCode status
	 */
	public function ajax_preview($event, $img_status, $flash_status, $quote_status)
	{
		$post_data = $event['post_data'];
		$forum_id = (int) $post_data['forum_id'];
		/** @var \parse_message $message_parser */
		$message_parser = $event['message_parser'];

		$message_parser->message = $this->request->variable('message', '', true);
		if (!empty($message_parser->message))
		{
			$message_parser->parse($post_data['enable_bbcode'], ($this->config['allow_post_links']) ? $post_data['enable_urls'] : false, $post_data['enable_smilies'], $img_status, $flash_status, $quote_status, $this->config['allow_post_links']);
		}
		$this->preview_message = $message_parser->format_display($post_data['enable_bbcode'], $post_data['enable_urls'], $post_data['enable_smilies'], false);

		// Attachment Preview
		$this->preview_attachments($message_parser, $forum_id);

		ajax_helper::send_json([
			'status'         => 'preview',
			'PREVIEW_TITLE'  => $this->user->lang['PREVIEW'],
			'PREVIEW_TEXT'   => $this->preview_message,
			'PREVIEW_ATTACH' => $this->preview_attachments,
		]);
	}

	/**
	 * Attachments preview
	 *
	 * @param \parse_message $message_parser Message parser object
	 * @param int            $forum_id       Forum ID
	 */
	public function preview_attachments($message_parser, $forum_id)
	{
		if (sizeof($message_parser->attachment_data))
		{
			$update_count = [];
			$attachment_data = $message_parser->attachment_data;

			parse_attachments($forum_id, $this->preview_message, $attachment_data, $update_count, true);
			$this->build_attach_box($attachment_data);

			unset($attachment_data);
		}
	}

	/**
	 * Build attach box for not-inline attachments
	 *
	 * @param array $attachment_data
	 */
	public function build_attach_box($attachment_data)
	{
		$this->preview_attachments = '';
		foreach ($attachment_data as $i => $attachment)
		{
			$this->preview_attachments .= '<dd>' . $attachment . '</dd>';
		}
		if (!empty($this->preview_attachments))
		{
			$this->preview_attachments = '<dl class="attachbox"><dt>' . $this->user->lang['ATTACHMENTS'] . '</dt>' . $this->preview_attachments . '</dl>';
		}
	}

	//
	public function check_preview_error($event)
	{
		$message_parser = $event['message_parser'];
		$error = $event['error'];

		// check form
		if (!check_form_key('posting'))
		{
			$error[] = $this->user->lang['FORM_INVALID'];
		}

		/**
		 * Replace Emojis and other 4bit UTF-8 chars not allowed by MySQL to UCR/NCR.
		 * Using their Numeric Character Reference's Hexadecimal notation.
		 * Check the permissions for posting Emojis first.
		 */

		if (!$this->auth->acl_get('u_emoji'))
		{
			/**
			 * Check for out-of-bounds characters that are currently
			 * not supported by utf8_bin in MySQL
			 */
			if (preg_match_all('/[\x{10000}-\x{10FFFF}]/u', $post_data['post_subject'], $matches))
			{
				$character_list = implode('<br>', $matches[0]);

				$error[] = $this->user->lang('UNSUPPORTED_CHARACTERS_SUBJECT', $character_list);
			}
		}

		if (count($message_parser->warn_msg))
		{
			$error[] = implode('<br />', $message_parser->warn_msg);
			$message_parser->warn_msg = array();
			$event['message_parser'] = $message_parser;
		}

		if (sizeof($error))
		{
			ajax_helper::send_json([
				'status'         => 'preview',
				'error'          => true,
				'title'          => $this->user->lang['INFORMATION'],
				'message'        => implode('<br />', $error),
			]);
		}
	}
}
