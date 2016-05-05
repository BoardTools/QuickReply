<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

namespace boardtools\quickreply\acp;

class quickreply_plugins_module extends \boardtools\quickreply\functions\acp_module_helper
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

		$this->tpl_name = 'acp_plugins_quickreply';
		$this->form_key = 'config_plugins_quickreply';
		add_form_key($this->form_key);

		$this->generate_display_vars();

		$this->submit = $this->request->is_set_post('submit');
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
			'title' => 'ACP_PLUGINS_QUICKREPLY',
			'vars'  => array(
				'legend1'                    => '',
				'qr_capslock_transfer'       => array('lang' => 'ACP_QR_CAPSLOCK', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_show_button_translit'    => array('lang' => 'ACP_QR_SHOW_BUTTON_TRANSLIT', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),

				'legend2'                    => '',
				'qr_enable_re'               => array('lang' => 'ACP_QR_ENABLE_RE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_show_subjects'           => array('lang' => 'ACP_QR_SHOW_SUBJECTS', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_show_subjects_in_search' => array('lang' => 'ACP_QR_SHOW_SUBJECTS_IN_SEARCH', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_hide_subject_box'        => array('lang' => 'ACP_QR_HIDE_SUBJECT_BOX', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),

				'legend3' => 'ACP_SUBMIT_CHANGES',
			),
		);
	}
}
