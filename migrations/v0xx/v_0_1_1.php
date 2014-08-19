<?php
/**
*
* @package quickreply
* @copyright (c) 2014 Татьяна5
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace tatiana5\quickreply\migrations\v0xx;

class v_0_1_1 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '0.1.1', '>=');
	}

	static public function depends_on()
	{
		return array('\tatiana5\quickreply\migrations\v0xx\v_0_1_0');
	}

	public function update_schema()
	{
		return array();
	}

	public function revert_schema()
	{
		return array();
	}

	public function update_data()
	{
		return array(
			// Add configs
			array('config.add', array('qr_hide_subjects', '1')),

			// Update exisiting configs
			array('config.update', array('qr_version', '0.1.1')),
		);
	}
}
