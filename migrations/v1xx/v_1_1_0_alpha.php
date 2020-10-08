<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) Татьяна5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v1xx;

class v_1_1_0_alpha extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '1.1.0-alpha', '>=');
	}

	public static function depends_on()
	{
		return ['\boardtools\quickreply\migrations\v1xx\v_1_1_0_dev'];
	}

	public function update_schema()
	{
		return [
			'add_columns' => [
				$this->table_prefix . 'forums' => [
					'qr_ajax_submit'    => ['BOOL', 1],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns'    => [
				$this->table_prefix . 'forums' => ['qr_ajax_submit'],
			],
		];
	}

	public function update_data()
	{
		return [
			// Update existing configs
			['config.update', ['qr_version', '1.1.0-alpha']],

			// Add ACP modules
			['module.add', ['acp', 'ACP_QUICKREPLY', [
					'module_basename'	=> '\boardtools\quickreply\acp\quickreply_qn_module',
					'module_langname'	=> 'ACP_QUICKREPLY_QN_EXPLAIN',
					'module_mode'		=> 'config_quickreply_qn',
					'module_auth'		=> 'ext_boardtools/quickreply && acl_a_quickreply',
			]]],
			['module.add', ['acp', 'ACP_QUICKREPLY', [
					'module_basename'	=> '\boardtools\quickreply\acp\quickreply_plugins_module',
					'module_langname'	=> 'ACP_QUICKREPLY_PLUGINS_EXPLAIN',
					'module_mode'		=> 'config_quickreply_plugins',
					'module_auth'		=> 'ext_boardtools/quickreply && acl_a_quickreply',
			]]],
		];
	}
}
