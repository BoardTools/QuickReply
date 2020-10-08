<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) Татьяна5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v1xx;

class v_1_0_0_beta extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '1.0.0-beta', '>=');
	}

	public static function depends_on()
	{
		return ['\boardtools\quickreply\migrations\v0xx\v_0_1_3'];
	}

	public function update_schema()
	{
		return [
			'add_columns' => [
				$this->table_prefix . 'users' => [
					'ajax_pagination'    => ['BOOL', 1],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns'    => [
				$this->table_prefix . 'users' => ['ajax_pagination'],
			],
		];
	}

	public function update_data()
	{
		return [
			// Add configs
			['config.add', ['qr_ajax_pagination', '1']],

			// Update existing configs
			['config.update', ['qr_version', '1.0.0-beta']],
		];
	}
}
