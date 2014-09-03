<?php
/**
*
* @package quickreply
* @copyright (c) 2014 Татьяна5
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace tatiana5\quickreply\migrations\v0xx;

class v_0_0_1 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '0.0.1', '>=');
	}

	static public function depends_on()
	{
			return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_data()
	{
		return array(
			// Add configs
			array('config.add', array('qr_bbcode', '1')),
			array('config.add', array('qr_comma', '1')),
			array('config.add', array('qr_quicknick', '1')),
			array('config.add', array('qr_quickquote', '1')),
			array('config.add', array('qr_smilies', '1')),
			array('config.add', array('qr_enable_re', '0')),
			array('config.add', array('qr_ctrlenter', '1')),

			// Current version
			array('config.add', array('qr_version', '0.0.1')),

			// Add ACP modules
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_QUICKREPLY')),
			array('module.add', array('acp', 'ACP_QUICKREPLY', array(
					'module_basename'	=> '\tatiana5\quickreply\acp\quickreply_module',
					'module_langname'	=> 'ACP_QUICKREPLY_EXPLAIN',
					'module_mode'		=> 'config_quickreply',
					'module_auth'		=> 'ext_tatiana5/quickreply && acl_a_quickreply',
			))),

			// Add permissions
			array('permission.add', array('a_quickreply', true)),
			array('permission.add', array('f_qr_change_subject', false)),

			// Set permissions
			array('permission.permission_set', array('ROLE_ADMIN_FULL', 'a_quickreply')),
			array('permission.permission_set', array('ROLE_ADMIN_STANDARD', 'a_quickreply')),
			array('permission.permission_set', array('ROLE_FORUM_FULL', 'f_qr_change_subject')),
		);
	}
}
