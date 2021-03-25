<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) Татьяна5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v1xx;

class v_1_0_0_beta3 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '1.0.0-beta3', '>=');
	}

	static public function depends_on()
	{
		return ['\boardtools\quickreply\migrations\v1xx\v_1_0_0_beta2'];
	}

	public function update_schema()
	{
		return [
			'add_columns' => [
				$this->table_prefix . 'users' => [
					'qr_enable_scroll'    => ['BOOL', 1],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns'    => [
				$this->table_prefix . 'users' => ['qr_enable_scroll'],
			],
		];
	}

	public function update_data()
	{
		return [
			// Add configs
			['config.add', ['qr_scroll_time', 500]],

			// Update existing configs
			['config.update', ['qr_version', '1.0.0-beta3']],
		];
	}
}
