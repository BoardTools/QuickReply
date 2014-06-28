<?php
/**
*
* @package quickreply
* @copyright (c) 2014 Татьяна5
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace tatiana5\quickreply\migrations\v0xx;

class v_0_1_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '0.1.0', '>=');
	}

	static public function depends_on()
	{
			return array('\tatiana5\quickreply\migrations\v0xx\v_0_0_1');
			
	}

	public function update_schema()
	{
		return array(
		);
	}

	public function revert_schema()
	{
		return array(
		);
	}

	public function update_data()
	{
		return array(
			// Add configs
			array('config.add', array('qr_capslock_transfer', '1')),
			array('config.add', array('qr_ajax_submit', '1')),

			// Add UCP modules
			//array('module.add', array('ucp', false, 'UCP_REPUTATION')),
			//array('module.add', array('ucp', 'UCP_REPUTATION', array(
			//		'module_basename'	=> '\pico88\reputation\ucp\reputation_module',
			//		'module_langname'	=> 'UCP_REPUTATION_FRONT',
			//		'module_mode'		=> 'front',
			//		'module_auth'		=> 'cfg_rs_enable',
			//))),
		);
	}
}