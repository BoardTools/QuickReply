<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) Татьяна5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v1xx;

class v_1_1_0_dev extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '1.1.0-dev', '>=');
	}

	public static function depends_on()
	{
		return ['\boardtools\quickreply\migrations\v1xx\v_1_0_2'];
	}

	public function update_schema()
	{
		return [
			'add_columns' => [
				$this->table_prefix . 'users' => [
					'qr_quicknick_string'    => ['BOOL', 0],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns'    => [
				$this->table_prefix . 'users' => ['qr_quicknick_string'],
			],
		];
	}

	public function update_data()
	{
		return [
			// Update existing configs
			['config.update', ['qr_version', '1.1.0-dev']],

			// Add configs
			['config.add', ['qr_quicknick_string', '0']],
		];
	}
}
