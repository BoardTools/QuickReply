<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) Татьяна5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v0xx;

class v_0_1_2 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '0.1.2', '>=');
	}

	public static function depends_on()
	{
		return ['\boardtools\quickreply\migrations\v0xx\v_0_1_1'];
	}

	public function update_data()
	{
		return [
			// Add configs
			['config.add', ['qr_source_post', '1']],

			// Update existing configs
			['config.update', ['qr_version', '0.1.2']],
		];
	}
}
