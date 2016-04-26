<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
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
	 * @param \phpbb\config\config         $config
	 * @param \phpbb\request\request       $request
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
		);
	}

	public function qr_get_user_prefs_data($user_row)
	{
		return array(
			'ajax_pagination'  => $this->request->variable('ajax_pagination', (int) $user_row['ajax_pagination']),
			'qr_enable_scroll' => $this->request->variable('qr_enable_scroll', (int) $user_row['qr_enable_scroll']),
			'qr_soft_scroll'   => $this->request->variable('qr_soft_scroll', (int) $user_row['qr_soft_scroll']),
		);
	}

	public function qr_set_user_prefs_data($data)
	{
		return array(
			'ajax_pagination'  => $data['ajax_pagination'],
			'qr_enable_scroll' => $data['qr_enable_scroll'],
			'qr_soft_scroll'   => $data['qr_soft_scroll'],
		);
	}
}
