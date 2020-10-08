<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) Татьяна5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v1xx;

class v_1_1_0_beta1 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '1.1.0-beta1', '>=');
	}

	public static function depends_on()
	{
		return ['\boardtools\quickreply\migrations\v1xx\v_1_1_0_alpha2'];
	}

	public function update_schema()
	{
		return [
			'add_columns' => [
				$this->table_prefix . 'users' => [
					'qr_enable_warning'    => ['BOOL', 1],
					'qr_fix_empty_form'    => ['BOOL', 1],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns'    => [
				$this->table_prefix . 'users' => ['qr_enable_warning', 'qr_fix_empty_form'],
			],
		];
	}

	public function update_data()
	{
		return [
			// Update existing configs
			['config.update', ['qr_version', '1.1.0-beta1']],
		];
	}
}
