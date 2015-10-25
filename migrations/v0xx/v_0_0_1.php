<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) 2014 - 2015 Tatiana5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace tatiana5\quickreply\migrations\v0xx;

class v_0_0_1 extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
			return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_data()
	{
		return array();
	}

	public function revert_data()
	{
		return array(
			array('module.remove', array('acp', 'ACP_QUICKREPLY', array(
					'module_basename'	=> '\tatiana5\quickreply\acp\quickreply_module',
					'module_langname'	=> 'ACP_QUICKREPLY_EXPLAIN',
					'module_mode'		=> 'config_quickreply',
					'module_auth'		=> 'ext_tatiana5/quickreply && acl_a_quickreply',
			))),
			array('module.add', array('acp', 'ACP_QUICKREPLY', array(
					'module_basename'	=> '\boardtools\quickreply\acp\quickreply_module',
					'module_langname'	=> 'ACP_QUICKREPLY_EXPLAIN',
					'module_mode'		=> 'config_quickreply',
					'module_auth'		=> 'ext_boardtools/quickreply && acl_a_quickreply',
			))),
		);
	}
}
