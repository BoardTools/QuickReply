<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) 2014 - 2021 Татьяна5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\migrations\v1xx;

class v_1_1_0_alpha2 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['qr_version']) && version_compare($this->config['qr_version'], '1.1.0-alpha2', '>=');
	}

	static public function depends_on()
	{
		return ['\boardtools\quickreply\migrations\v1xx\v_1_1_0_alpha'];
	}

	public function update_schema()
	{
		return [
			'add_columns' => [
				$this->table_prefix . 'forums' => [
					'qr_form_type'    => ['TINT:2', 1],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns'    => [
				$this->table_prefix . 'forums' => ['qr_form_type'],
			],
		];
	}

	public function update_data()
	{
		return [
			// Add configs
			['config.add', ['qr_quickquote_button', 1]],
			['config.add', ['qr_last_quote', 1]],

			// Update existing configs
			['config.update', ['qr_version', '1.1.0-alpha2']],

			// Add permissions
			['permission.add', ['f_qr_full_quote', false]],

			// Set permissions
			['permission.permission_set', ['ROLE_FORUM_FULL', 'f_qr_full_quote']],
			['permission.permission_set', ['ROLE_FORUM_POLLS', 'f_qr_full_quote']],
			['permission.permission_set', ['ROLE_FORUM_STANDARD', 'f_qr_full_quote']],
			['permission.permission_set', ['ROLE_FORUM_NEW_MEMBER', 'f_qr_full_quote', 'role', false]],
		];
	}
}
