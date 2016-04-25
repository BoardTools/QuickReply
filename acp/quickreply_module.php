<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

namespace boardtools\quickreply\acp;

class quickreply_module
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
	protected function generate_display_vars()
	{
		$this->display_vars = array(
			'title' => 'ACP_QUICKREPLY',
			'vars'  => array(
				'legend1'              => '',
				'qr_bbcode'            => array('lang' => 'ACP_QR_BBCODE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_smilies'           => array('lang' => 'ACP_QR_SMILIES', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_ctrlenter'         => array('lang' => 'ACP_QR_CTRLENTER', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_capslock_transfer' => array('lang' => 'ACP_QR_CAPSLOCK', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_ajax_submit'       => array('lang' => 'ACP_QR_AJAX_SUBMIT', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_ajax_pagination'   => array('lang' => 'ACP_QR_AJAX_PAGINATION', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_scroll_time'       => array('lang' => 'ACP_QR_SCROLL_TIME', 'validate' => 'int:0:9999', 'type' => 'number:0:9999', 'explain' => true),
				'qr_attach'            => array('lang' => 'ACP_QR_ATTACH', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_allow_for_guests'  => array('lang' => 'ACP_QR_ALLOW_FOR_GUESTS', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),

				'legend2'            => '',
				'qr_quickquote'      => array('lang' => 'ACP_QR_QUICKQUOTE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_source_post'     => array('lang' => 'ACP_QR_SOURCE_POST', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_quickquote_link' => array('lang' => 'ACP_QR_QUICKQUOTE_LINK', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_full_quote'      => array('lang' => 'ACP_QR_FULL_QUOTE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_quicknick'       => array('lang' => 'ACP_QR_QUICKNICK', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_quicknick_ref'   => array('lang' => 'ACP_QR_QUICKNICK_REF', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_comma'           => array('lang' => 'ACP_QR_COMMA', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_color_nickname'  => array('lang' => 'ACP_QR_COLOUR_NICKNAME', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_quicknick_pm'    => array('lang' => 'ACP_QR_QUICKNICK_PM', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),

				'legend3'                    => '',
				'qr_enable_re'               => array('lang' => 'ACP_QR_ENABLE_RE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_show_subjects'           => array('lang' => 'ACP_QR_SHOW_SUBJECTS', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_show_subjects_in_search' => array('lang' => 'ACP_QR_SHOW_SUBJECTS_IN_SEARCH', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_show_button_translit'    => array('lang' => 'ACP_QR_SHOW_BUTTON_TRANSLIT', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_hide_subject_box'        => array('lang' => 'ACP_QR_HIDE_SUBJECT_BOX', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),

				'legend4' => 'ACP_SUBMIT_CHANGES',
			),
		);
	}

	/**
	 * When form is submitting
	 */
	protected function submit_form()
	{
		$this->submit = $this->request->is_set_post('submit');

		$cfg_array = ($this->request->is_set('config')) ? $this->request->variable('config', array('' => ''), true) : $this->new_config;
		$this->error = array();

		// We validate the complete config if wished
		validate_config_vars($this->display_vars['vars'], $cfg_array, $this->error);
		$this->check_form_valid();
		$this->set_config($cfg_array);

		if ($this->submit)
		{
			trigger_error($this->user->lang['CONFIG_UPDATED'] . adm_back_link($this->u_action));
		}

	}

	/**
	 * Check submitting errors
	 */
	private function check_form_valid()
	{
		if ($this->submit && !check_form_key($this->form_key))
		{
			$this->error[] = $this->user->lang['FORM_INVALID'];
		}

		// Do not write values if there is an error
		if (sizeof($this->error))
		{
			$this->submit = false;
		}
	}

	/**
	 * Set the new configuration array
	 *
	 * @param array                $cfg_array    Array with new values
	 */
	protected function set_config($cfg_array)
	{
		// We go through the display_vars to make sure no one is trying to set variables he/she is not allowed to...
		foreach ($this->display_vars['vars'] as $config_name => $null)
		{
			if (!isset($cfg_array[$config_name]) || strpos($config_name, 'legend') !== false)
			{
				continue;
			}

			$this->new_config[$config_name] = $config_value = $cfg_array[$config_name];

			if ($this->submit)
			{
				$this->config->set($config_name, $config_value);
			}
		}
	}

	/**
	 * Add language
	 *
	 * @param string                   $display_vars_lang
	 */
	protected function add_langs($display_vars_lang)
	{
		$this->user->add_lang_ext('boardtools/quickreply', 'quickreply');
		if (isset($display_vars_lang))
		{
			$this->user->add_lang($display_vars_lang);
		}
	}

	/**
	 * Get text for title (if exists)
	 *
	 * @param array       $vars Array of vars
	 * @return string
	 */
	protected function get_title($vars, $key, $key2 = '')
	{
		if (isset($this->user->lang[$vars[$key] . $key2]))
		{
			return $this->user->lang[$vars[$key] . $key2];
		}
		else
		{
			return ($key2 === '') ? $vars[$key] : '';
		}
	}

	/**
	 * Get text for title explanation (if exists)
	 *
	 * @param array       $vars Array of vars
	 * @return string
	 */
	protected function get_title_explain($vars)
	{
		$l_explain = '';
		if ($vars['explain'] && isset($vars['lang_explain']))
		{
			$l_explain = $this->get_title($vars, 'lang_explain');
		}
		else if ($vars['explain'])
		{
			$l_explain = $this->get_title($vars, 'lang', '_EXPLAIN');
		}
		return $l_explain;
	}

	/**
	 * Check vars for valid
	 *
	 * @param string 				   $config_key
	 * @param array                    $vars
	 */
	protected function invalid_vars($config_key, $vars)
	{
		return (!is_array($vars) && strpos($config_key, 'legend') === false);
	}

	/**
	 * Output title and errors
	 */
	protected function output_basic_vars()
	{
		$this->template->assign_vars(array(
			'L_TITLE'         => $this->user->lang[$this->display_vars['title']],
			'L_TITLE_EXPLAIN' => $this->user->lang[$this->display_vars['title'] . '_EXPLAIN'],

			'S_ERROR'   => (sizeof($this->error)) ? true : false,
			'ERROR_MSG' => implode('<br />', $this->error),
		));
	}

	/**
	 * Output legend
	 */
	protected function output_legend($vars)
	{
		$this->template->assign_block_vars('options', array(
			'S_LEGEND' => true,
			'LEGEND'   => $this->user->lang($vars)
		));
	}

	/**
	 * Output options
	 */
	protected function output_vars($config_key, $vars, $content)
	{
		$this->template->assign_block_vars('options', array(
			'KEY'           => $config_key,
			'TITLE'         => $this->get_title($vars, 'lang'),
			'S_EXPLAIN'     => $vars['explain'],
			'TITLE_EXPLAIN' => $this->get_title_explain($vars),
			'CONTENT'       => $content,
		));
	}

	/**
	 * Output the page
	 */
	protected function output_page()
	{
		$this->page_title = $this->display_vars['title'];
		$this->add_langs($this->display_vars['lang']);
		$this->output_basic_vars();

		foreach ($this->display_vars['vars'] as $config_key => $vars)
		{
			if ($this->invalid_vars($config_key, $vars))
			{
				continue;
			}

			if (strpos($config_key, 'legend') !== false)
			{
				$this->output_legend($vars);
				continue;
			}

			$type = explode(':', $vars['type']);

			$content = build_cfg_template($type, $config_key, $this->new_config, $config_key, $vars);

			if (empty($content))
			{
				continue;
			}

			$this->output_vars($config_key, $vars, $content);

			unset($this->display_vars['vars'][$config_key]);
		}
	}
}
