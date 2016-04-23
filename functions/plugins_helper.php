<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace boardtools\quickreply\functions;

class plugins_helper
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\extension\manager */
	protected $phpbb_extension_manager;

	/**
	 * Constructor
	 *
	 * @param \phpbb\auth\auth             $auth
	 * @param \phpbb\config\config         $config
	 * @param \phpbb\template\template     $template
	 * @param \phpbb\user                  $user
	 * @param \phpbb\extension\manager     $phpbb_extension_manager
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, \phpbb\extension\manager $phpbb_extension_manager)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
	}

	/**
	 * Assign template variables for extensions if quick reply is enabled
	 */
	public function assign_template_variables_for_extensions()
	{
		if (
			$this->phpbb_extension_manager->is_enabled('rxu/PostsMerging') &&
			$this->user->data['is_registered'] &&
			$this->config['merge_interval']
		)
		{
			// Always show the checkbox if PostsMerging extension is installed.
			$this->user->add_lang_ext('rxu/PostsMerging', 'posts_merging');
			$this->template->assign_var('POSTS_MERGING_OPTION', true);
		}

		$this->template->assign_vars(array(
			// ABBC3
			'S_ABBC3_INSTALLED' => $this->phpbb_extension_manager->is_enabled('vse/abbc3'),
		));
	}

	public function cannot_change_subject($forum_id, $mode, $topic_first_post_id, $post_id, $not_mode)
	{
		return (
				!$this->auth->acl_get('f_qr_change_subject', $forum_id)
				&& ($mode != 'post' || $not_mode)
				&& ($topic_first_post_id != $post_id)
				);
	}

	/**
	 * Detects whether Ctrl+Enter feature is enabled in QuickReply extension.
	 * We need to disable this feature in phpBB 3.1.9 and higher
	 * as it has been added to the core.
	 *
	 * @deprecated 1.0.2 added only for backwards compatibility reasons
	 * @return bool
	 */
	public function qr_ctrlenter_enabled()
	{
		$qr_ctrlenter = $this->config['qr_ctrlenter'];
		if ($qr_ctrlenter)
		{
			if (version_compare($this->config['version'], '3.1.8', '>'))
			{
				$this->config->set('qr_ctrlenter', 0);
			}
			else
			{
				return $qr_ctrlenter;
			}
		}
		return false;
	}
}
