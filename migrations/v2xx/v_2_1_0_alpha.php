<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) 2014 - 2021 Татьяна5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v2xx;

class v_2_1_0_alpha extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '2.1.0-alpha', '>=');
	}

	static public function depends_on()
	{
		return ['\boardtools\quickreply\migrations\v2xx\v_2_0_0_beta2'];
	}

	public function update_data()
	{
		return [
			// Update existing configs
			['config.update', ['qr_version', '2.1.0-alpha']],
		];
	}
}
