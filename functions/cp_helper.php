<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2017 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace boardtools\quickreply\functions;

class cp_helper
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request */
	protected $request;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config   $config
	 * @param \phpbb\request\request $request
	 */
	public function __construct(\phpbb\config\config $config, \phpbb\request\request $request)
	{
		$this->config = $config;
		$this->request = $request;
	}

	public function qr_user_prefs_data($data)
	{
		return array(
			'S_AJAX_PAGINATION'        => $this->config['qr_ajax_pagination'],
			'S_ENABLE_AJAX_PAGINATION' => $data['ajax_pagination'],
			'S_QR_ENABLE_SCROLL'       => $data['qr_enable_scroll'],
			'S_QR_ALLOW_SOFT_SCROLL'   => !!$this->config['qr_scroll_time'],
			'S_QR_SOFT_SCROLL'         => $data['qr_soft_scroll'],
			'S_QUICKNICK_CHANGE_TYPE'  => $this->config['qr_quicknick'] && !$this->config['qr_quicknick_string'],
			'S_QR_QUICKNICK_USERTYPE'  => $data['qr_quicknick_string'],
			'S_QR_ENABLE_WARNING'      => $data['qr_enable_warning'],
			'S_QR_FIX_EMPTY_FORM'      => $data['qr_fix_empty_form'],
		);
	}

	public function qr_get_user_prefs_data($user_row)
	{
		return array(
			'ajax_pagination'     => $this->request->variable('ajax_pagination', (int) $user_row['ajax_pagination']),
			'qr_enable_scroll'    => $this->request->variable('qr_enable_scroll', (int) $user_row['qr_enable_scroll']),
			'qr_soft_scroll'      => $this->request->variable('qr_soft_scroll', (int) $user_row['qr_soft_scroll']),
			'qr_quicknick_string' => $this->request->variable('qr_quicknick_string', (int) $user_row['qr_quicknick_string']),
			'qr_enable_warning'   => $this->request->variable('qr_enable_warning', (int) $user_row['qr_enable_warning']),
			'qr_fix_empty_form'   => $this->request->variable('qr_fix_empty_form', (int) $user_row['qr_fix_empty_form']),
		);
	}

	public function qr_set_user_prefs_data($data)
	{
		return array(
			'ajax_pagination'     => $data['ajax_pagination'],
			'qr_enable_scroll'    => $data['qr_enable_scroll'],
			'qr_soft_scroll'      => $data['qr_soft_scroll'],
			'qr_quicknick_string' => $data['qr_quicknick_string'],
			'qr_enable_warning'   => $data['qr_enable_warning'],
			'qr_fix_empty_form'   => $data['qr_fix_empty_form'],
		);
	}

	public function qr_forums_data($forum_data)
	{
		return array(
			'S_QR_AJAX_SUBMIT'        => $this->config['qr_ajax_submit'],
			'S_ENABLE_QR_AJAX_SUBMIT' => $forum_data['qr_ajax_submit'],
			'S_QR_FORM_TYPE'          => $forum_data['qr_form_type'],
		);
	}

	public function qr_get_forums_data()
	{
		return array(
			'qr_ajax_submit' => $this->request->variable('qr_ajax_submit', false),
			'qr_form_type'   => $this->request->variable('qr_form_type', 0),
		);
	}

	public function qr_new_forums_data()
	{
		return array(
			'qr_ajax_submit' => false,
			'qr_form_type'   => 0,
		);
	}
}
