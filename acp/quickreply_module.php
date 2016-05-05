<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

namespace boardtools\quickreply\acp;

class quickreply_module extends \boardtools\quickreply\functions\acp_module_helper
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var string */
	protected $form_key;

	/** @var bool */
	protected $submit;

	/** @var array */
	protected $error;

	/** @var array */
	protected $display_vars;

	/** @var string */
	public $u_action;

	/** @var string */
	public $page_title;

	/** @var string */
	public $tpl_name;

	/** @var array */
	public $new_config = array();

	public function main($id, $mode)
	{
		global $config, $template, $user, $request;

		$this->config = $config;
		$this->new_config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->request = $request;

		$this->tpl_name = 'acp_quickreply';
		$this->form_key = 'config_quickreply';
		add_form_key($this->form_key);

		$this->generate_display_vars();

		/**
		 * We need to disable this feature in phpBB 3.1.9 and higher
		 * as it has been added to the core.
		 */
		if (version_compare($this->config['version'], '3.1.8', '>'))
		{
			unset($this->display_vars['vars']['qr_ctrlenter']);
		}

		$this->submit_form();

		// Output relevant page
		$this->output_page();
	}

	/**
	 * Generates the array of display_vars
	 *
	 * @return array
	 */
	private function generate_display_vars()
	{
		$this->display_vars = array(
			'title' => 'ACP_QUICKREPLY',
			'vars'  => array(
				'legend1'              => '',
				'qr_bbcode'            => array('lang' => 'ACP_QR_BBCODE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_smilies'           => array('lang' => 'ACP_QR_SMILIES', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_ctrlenter'         => array('lang' => 'ACP_QR_CTRLENTER', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_attach'            => array('lang' => 'ACP_QR_ATTACH', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_allow_for_guests'  => array('lang' => 'ACP_QR_ALLOW_FOR_GUESTS', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),

				'legend2'              => '',
				'qr_ajax_submit'       => array('lang' => 'ACP_QR_AJAX_SUBMIT', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_ajax_pagination'   => array('lang' => 'ACP_QR_AJAX_PAGINATION', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_scroll_time'       => array('lang' => 'ACP_QR_SCROLL_TIME', 'validate' => 'int:0:9999', 'type' => 'number:0:9999', 'explain' => true),

				'legend3' => 'ACP_SUBMIT_CHANGES',
			),
		);
	}
}
