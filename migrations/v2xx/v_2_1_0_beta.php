<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) Татьяна5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v2xx;

class v_2_1_0_beta extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '2.1.0-beta', '>=');
	}

	static public function depends_on()
	{
		return ['\boardtools\quickreply\migrations\v2xx\v_2_1_0_alpha'];
	}

	public function update_data()
	{
		return [
			// Add configs
			['config.add', ['qr_read_next', '1']],

			// Update existing configs
			['config.update', ['qr_version', '2.1.0-beta']],
		];
	}
}
