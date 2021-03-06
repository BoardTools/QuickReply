<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) 2014 - 2021 Татьяна5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v0xx;

class v_0_1_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '0.1.0', '>=');
	}

	static public function depends_on()
	{
		return ['\boardtools\quickreply\migrations\v0xx\v_0_0_1'];
	}

	public function update_data()
	{
		return [
			// Add configs
			['config.add', ['qr_capslock_transfer', '1']],
			['config.add', ['qr_ajax_submit', '1']],

			// Update existing configs
			['config.update', ['qr_version', '0.1.0']],
		];
	}
}
