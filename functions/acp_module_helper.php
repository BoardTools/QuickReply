<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

namespace boardtools\quickreply\functions;

abstract class acp_module_helper
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

	/**
	 * When form is submitting
	 */
	public function submit_form()
	{
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
	public function check_form_valid()
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
	 * @param array $cfg_array Array with new values
	 */
	public function set_config($cfg_array)
	{
		// We go through the display_vars to make sure no one is trying to set variables he/she is not allowed to...
		foreach ($this->display_vars['vars'] as $config_name => $null)
		{
			if ($this->invalid_var($config_name, $cfg_array))
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
	 * Check var for valid
	 *
	 * @param string $config_name
	 * @param array  $cfg_array
	 * @return bool
	 */
	public function invalid_var($config_name, $cfg_array)
	{
		return (!isset($cfg_array[$config_name]) || strpos($config_name, 'legend') !== false);
	}

	/**
	 * Add language
	 *
	 * @param string $display_vars_lang
	 */
	public function add_langs(&$display_vars_lang)
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
	 * @param array $vars Array of vars
	 * @return string
	 */
	public function get_title($vars, $key, $key2 = '')
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
	 * @param array $vars Array of vars
	 * @return string
	 */
	public function get_title_explain($vars)
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
	 * Output title and errors
	 */
	public function output_basic_vars()
	{
		$this->template->assign_vars(array(
			'L_TITLE'         => $this->user->lang[$this->display_vars['title']],
			'L_TITLE_EXPLAIN' => $this->user->lang[$this->display_vars['title'] . '_EXPLAIN'],

			'S_ERROR'   => (sizeof($this->error)) ? true : false,
			'ERROR_MSG' => implode('<br />', $this->error),
		));
	}

	public function output_options($config_key, $vars, $content)
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
	 * Output options
	 */
	public function output_vars($config_key, $vars)
	{
		if (strpos($config_key, 'legend') !== false)
		{
			$this->template->assign_block_vars('options', array(
				'S_LEGEND' => true,
				'LEGEND'   => $this->user->lang($vars)
			));
		}
		else
		{
			$type = explode(':', $vars['type']);

			$content = build_cfg_template($type, $config_key, $this->new_config, $config_key, $vars);

			if (!empty($content))
			{
				$this->output_options($config_key, $vars, $content);
				unset($this->display_vars['vars'][$config_key]);
			}
		}
	}

	/**
	 * Output the page
	 */
	public function output_page()
	{
		$this->page_title = $this->display_vars['title'];
		$this->add_langs($this->display_vars['lang']);
		$this->output_basic_vars();

		foreach ($this->display_vars['vars'] as $config_key => $vars)
		{
			if (!is_array($vars) && strpos($config_key, 'legend') === false)
			{
				continue;
			}

			$this->output_vars($config_key, $vars);
		}
	}
}
