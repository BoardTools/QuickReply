<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) 2014 - 2021 Татьяна5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v0xx;

class v_0_0_1 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '0.0.1', '>=');
	}

	static public function depends_on()
	{
			return ['\phpbb\db\migration\data\v310\dev'];
	}

	public function update_data()
	{
		return [
			// Add configs
			['config.add', ['qr_bbcode', '1']],
			['config.add', ['qr_comma', '1']],
			['config.add', ['qr_quicknick', '1']],
			['config.add', ['qr_quickquote', '1']],
			['config.add', ['qr_smilies', '1']],
			['config.add', ['qr_enable_re', '0']],
			['config.add', ['qr_ctrlenter', '1']],

			// Current version
			['config.add', ['qr_version', '0.0.1']],

			// Add ACP modules
			['module.add', ['acp', 'ACP_CAT_DOT_MODS', 'ACP_QUICKREPLY']],
			['module.add', ['acp', 'ACP_QUICKREPLY', [
					'module_basename'	=> '\boardtools\quickreply\acp\quickreply_module',
					'module_langname'	=> 'ACP_QUICKREPLY_EXPLAIN',
					'module_mode'		=> 'config_quickreply',
					'module_auth'		=> 'ext_boardtools/quickreply && acl_a_quickreply',
			]]],

			// Add permissions
			['permission.add', ['a_quickreply', true]],
			['permission.add', ['f_qr_change_subject', false]],

			// Set permissions
			['permission.permission_set', ['ROLE_ADMIN_FULL', 'a_quickreply']],
			['permission.permission_set', ['ROLE_ADMIN_STANDARD', 'a_quickreply']],
			['permission.permission_set', ['ROLE_FORUM_FULL', 'f_qr_change_subject']],
		];
	}
}
