<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) 2014 - 2015 Tatiana5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\acp;

class quickreply_module
{
	public $u_action;
	public $new_config = array();

	public function main($id, $mode)
	{
		global $cache, $config, $db, $user, $auth, $template, $request;
		global $phpbb_root_path, $phpEx, $phpbb_admin_path, $phpbb_container;

		$this->page_title = 'ACP_QUICKREPLY';
		$this->tpl_name = 'acp_quickreply';
		$user->add_lang_ext('boardtools/quickreply', 'quickreply');

		$submit = ($request->is_set_post('submit')) ? true : false;
		$form_key = 'config_quickreply';
		add_form_key($form_key);

		$display_vars = array(
			'title'	=> 'ACP_QUICKREPLY',
			'vars'	=> array(
				'legend1'			=> '',
				'qr_bbcode'				=> array('lang' => 'ACP_QR_BBCODE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_smilies'			=> array('lang' => 'ACP_QR_SMILIES', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_ctrlenter'			=> array('lang' => 'ACP_QR_CTRLENTER', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_capslock_transfer'	=> array('lang' => 'ACP_QR_CAPSLOCK', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_ajax_submit'		=> array('lang' => 'ACP_QR_AJAX_SUBMIT', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_ajax_pagination'	=> array('lang' => 'ACP_QR_AJAX_PAGINATION', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_scroll_time'		=> array('lang' => 'ACP_QR_SCROLL_TIME', 'validate' => 'int:0:9999', 'type' => 'number:0:9999', 'explain' => true),
				'qr_attach'				=> array('lang' => 'ACP_QR_ATTACH', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_allow_for_guests'	=> array('lang' => 'ACP_QR_ALLOW_FOR_GUESTS', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),

				'legend2'			=> '',
				'qr_quickquote'			=> array('lang' => 'ACP_QR_QUICKQUOTE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_source_post'		=> array('lang' => 'ACP_QR_SOURCE_POST', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_quickquote_link'	=> array('lang' => 'ACP_QR_QUICKQUOTE_LINK', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_full_quote'			=> array('lang' => 'ACP_QR_FULL_QUOTE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_quicknick'			=> array('lang' => 'ACP_QR_QUICKNICK', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_quicknick_ref'		=> array('lang' => 'ACP_QR_QUICKNICK_REF', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_comma'				=> array('lang' => 'ACP_QR_COMMA', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_color_nickname'		=> array('lang' => 'ACP_QR_COLOUR_NICKNAME', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_quicknick_pm'		=> array('lang' => 'ACP_QR_QUICKNICK_PM', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),

				'legend3'			=> '',
				'qr_enable_re'			=> array('lang' => 'ACP_QR_ENABLE_RE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_show_subjects'		=> array('lang' => 'ACP_QR_SHOW_SUBJECTS', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_show_subjects_in_search'	=> array('lang' => 'ACP_QR_SHOW_SUBJECTS_IN_SEARCH', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_show_button_translit'		=> array('lang' => 'ACP_QR_SHOW_BUTTON_TRANSLIT', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_hide_subject_box'			=> array('lang' => 'ACP_QR_HIDE_SUBJECT_BOX', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),

				'legend4'					=> 'ACP_SUBMIT_CHANGES',
			),
		);

		if (isset($display_vars['lang']))
		{
			$user->add_lang($display_vars['lang']);
		}

		$this->new_config = $config;
		$cfg_array = ($request->is_set('config')) ? utf8_normalize_nfc($request->variable('config', array('' => ''), true)) : $this->new_config;
		$error = array();

		// We validate the complete config if wished
		validate_config_vars($display_vars['vars'], $cfg_array, $error);

		if ($submit && !check_form_key($form_key))
		{
			$error[] = $user->lang['FORM_INVALID'];
		}
		// Do not write values if there is an error
		if (sizeof($error))
		{
			$submit = false;
		}

		// We go through the display_vars to make sure no one is trying to set variables he/she is not allowed to...
		foreach ($display_vars['vars'] as $config_name => $null)
		{
			if (!isset($cfg_array[$config_name]) || strpos($config_name, 'legend') !== false)
			{
				continue;
			}

			$this->new_config[$config_name] = $config_value = $cfg_array[$config_name];

			if ($submit)
			{
				$config->set($config_name, $config_value);
			}
		}

		if ($submit)
		{
			trigger_error($user->lang['CONFIG_UPDATED'] . adm_back_link($this->u_action));
		}

		$this->page_title = $display_vars['title'];

		$template->assign_vars(array(
			'L_TITLE'			=> $user->lang[$display_vars['title']],
			'L_TITLE_EXPLAIN'	=> $user->lang[$display_vars['title'] . '_EXPLAIN'],

			'S_ERROR'			=> (sizeof($error)) ? true : false,
			'ERROR_MSG'			=> implode('<br />', $error),
		));

		// Output relevant page
		foreach ($display_vars['vars'] as $config_key => $vars)
		{
			if (!is_array($vars) && strpos($config_key, 'legend') === false)
			{
				continue;
			}

			if (strpos($config_key, 'legend') !== false)
			{
				$template->assign_block_vars('options', array(
					'S_LEGEND'		=> true,
					'LEGEND'		=> $user->lang($vars)
				));

				continue;
			}

			$type = explode(':', $vars['type']);

			$l_explain = '';
			if ($vars['explain'] && isset($vars['lang_explain']))
			{
				$l_explain = (isset($user->lang[$vars['lang_explain']])) ? $user->lang[$vars['lang_explain']] : $vars['lang_explain'];
			}
			else if ($vars['explain'])
			{
				$l_explain = (isset($user->lang[$vars['lang'] . '_EXPLAIN'])) ? $user->lang[$vars['lang'] . '_EXPLAIN'] : '';
			}

			$content = build_cfg_template($type, $config_key, $this->new_config, $config_key, $vars);

			if (empty($content))
			{
				continue;
			}

			$template->assign_block_vars('options', array(
				'KEY'			=> $config_key,
				'TITLE'			=> (isset($user->lang[$vars['lang']])) ? $user->lang[$vars['lang']] : $vars['lang'],
				'S_EXPLAIN'		=> $vars['explain'],
				'TITLE_EXPLAIN'	=> $l_explain,
				'CONTENT'		=> $content,
				)
			);

			unset($display_vars['vars'][$config_key]);
		}
	}
}
