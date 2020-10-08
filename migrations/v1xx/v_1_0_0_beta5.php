<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) Татьяна5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v1xx;

class v_1_0_0_beta5 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '1.0.0-beta5', '>=');
	}

	public static function depends_on()
	{
		return ['\boardtools\quickreply\migrations\v1xx\v_1_0_0_beta4'];
	}

	public function update_data()
	{
		return [
			// Add configs
			['config.add', ['qr_quicknick_ref', 1]],
			['config.add', ['qr_quicknick_pm', 1]],
			['config.add', ['qr_quickquote_link', 0]],
			['config.add', ['qr_full_quote', 1]],
			['config.add', ['qr_show_subjects_in_search', 1]],

			// Update existing configs
			['config.update', ['qr_version', '1.0.0-beta5']],
		];
	}
}
