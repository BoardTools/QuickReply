<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

namespace boardtools\quickreply\acp;

use boardtools\quickreply\functions\acp_module_helper;

class quickreply_module extends acp_module_helper
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	public function main($id, $mode)
	{
		global $db;

		$this->db = $db;

		$this->tpl_name = 'acp_quickreply';
		$this->form_key = 'config_quickreply';
		add_form_key($this->form_key);

		parent::main($id, $mode);
	}

	/**
	 * Generates the array of display_vars
	 */
	protected function generate_display_vars()
	{
		$this->display_vars = array(
			'lang'  => array('acp/board', 'acp/forums'),
			'title' => 'ACP_QUICKREPLY_TITLE',
			'vars'  => array(
				'legend1'                 => 'ACP_QR_LEGEND_GENERAL',
				'allow_quick_reply'       => array('lang' => 'ALLOW_QUICK_REPLY', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_allow_for_guests'     => array('lang' => 'ACP_QR_ALLOW_FOR_GUESTS', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_ctrlenter'            => array('lang' => 'ACP_QR_CTRLENTER', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_attach'               => array('lang' => 'ACP_QR_ATTACH', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_bbcode'               => array('lang' => 'ACP_QR_BBCODE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_smilies'              => array('lang' => 'ACP_QR_SMILIES', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_capslock_transfer'    => array('lang' => 'ACP_QR_CAPSLOCK', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_show_button_translit' => array('lang' => 'ACP_QR_SHOW_BUTTON_TRANSLIT', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				//
				'legend2'                 => 'ACP_QR_LEGEND_AJAX',
				'qr_ajax_pagination'      => array('lang' => 'ACP_QR_AJAX_PAGINATION', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_scroll_time'          => array('lang' => 'ACP_QR_SCROLL_TIME', 'validate' => 'int:0:9999', 'type' => 'number:0:9999', 'explain' => true),
				'qr_ajax_submit'          => array('lang' => 'ACP_QR_AJAX_SUBMIT', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				//
				'legend3'                 => array('ACP_QR_LEGEND_FORUM', 'ACP_QR_LEGEND_FORUM_EXPLAIN'),
				'forum_qr_enable'         => array('lang' => 'ENABLE_QUICK_REPLY', 'validate' => 'bool', 'type' => 'custom', 'method' => 'enable_only_radio', 'explain' => true),
				'forum_qr_ajax_submit'    => array('lang' => 'ACP_QR_AJAX_SUBMIT', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'forum_qr_form_type'      => array('lang' => 'ACP_QR_FORM_TYPE', 'validate' => 'int', 'type' => 'custom', 'method' => 'select_qr_form_type', 'explain' => true),
				//
				'legend4'                 => 'ACP_SUBMIT_CHANGES',
			),
		);

		/**
		 * We need to disable this feature in phpBB 3.1.9 and higher
		 * as it has been added to the core.
		 */
		if (version_compare($this->config['version'], '3.1.8', '>'))
		{
			unset($this->display_vars['vars']['qr_ctrlenter']);
		}

		// Set default values.
		$this->new_config['forum_qr_enable'] = true;
		$this->new_config['forum_qr_ajax_submit'] = true;
		$this->new_config['forum_qr_form_type'] = 2;
	}

	/**
	 * Sets the requested submission state
	 */
	protected function set_submit()
	{
		$this->submit = $this->request->is_set_post('submit') || $this->request->is_set_post('apply_forum_settings');
	}

	/**
	 * Generates radio option yes/leave as is
	 */
	public function enable_only_radio($value, $key)
	{
		$radio_ary = array(1 => 'YES', 0 => 'ACP_QR_LEAVE_AS_IS');

		return h_radio('config[' . $key . ']', $radio_ary, $value);
	}

	/**
	 * Select quick reply form type.
	 * Also generate the button to apply forum based settings
	 *
	 * @param string $value Current value
	 * @param string $key   Current config key
	 * @return string
	 */
	function select_qr_form_type($value, $key = '')
	{
		return '<select id="qr_form_type" name="config[forum_qr_form_type]"><option value="0"' . (($value == 0) ? ' selected="selected"' : '') . '>' . $this->user->lang('ACP_QR_FORM_TYPE_STANDARD') . '</option><option value="1"' . (($value == 1) ? ' selected="selected"' : '') . '>' . $this->user->lang('ACP_QR_FORM_TYPE_FIXED') . '</option><option value="2"' . (($value == 2) ? ' selected="selected"' : '') . '>' . $this->user->lang('ACP_QR_FORM_TYPE_SCROLL') . '</option></select><br /><br /><input class="button2" type="submit" id="apply_forum_settings" name="apply_forum_settings" value="' . $this->user->lang['ACP_QR_APPLY_FORUM_SETTINGS'] . '" />';
	}

	/**
	 * Applies forum based settings to all forums if requested
	 *
	 * @param array $cfg_array Array with new values
	 */
	public function apply_forum_settings($cfg_array)
	{
		if ($this->request->is_set_post('apply_forum_settings'))
		{
			if ($cfg_array['forum_qr_enable'])
			{
				enable_bitfield_column_flag(FORUMS_TABLE, 'forum_flags', log(FORUM_FLAG_QUICK_REPLY, 2));
			}

			$sql = 'UPDATE ' . FORUMS_TABLE . '
					SET qr_ajax_submit = ' . (int) $cfg_array['forum_qr_ajax_submit'] . ',
						qr_form_type = ' . (int) $cfg_array['forum_qr_form_type'];
			$this->db->sql_query($sql);
		}
	}

	/**
	 * Sets the new configuration array
	 *
	 * @param array $cfg_array Array with new values
	 */
	public function set_config($cfg_array)
	{
		if (!$this->invalid_var('qr_ajax_submit', $cfg_array))
		{
			$this->apply_forum_settings($cfg_array);
		}
		parent::set_config($cfg_array);
	}
}
