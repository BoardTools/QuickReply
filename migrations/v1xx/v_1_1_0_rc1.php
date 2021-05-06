<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) 2014 - 2021 Татьяна5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v1xx;

class v_1_1_0_rc1 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '1.1.0-RC1', '>=');
	}

	static public function depends_on()
	{
		return ['\boardtools\quickreply\migrations\v1xx\v_1_1_0_beta1'];
	}

	public function update_data()
	{
		return [
			// Update existing configs
			['config.update', ['qr_version', '1.1.0-RC1']],
		];
	}
}
