<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) 2014 - 2015 Tatiana5 and LavIgor
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
		return array('\boardtools\quickreply\migrations\v0xx\v_0_1_1');
	}

	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'install_bbcode_for_qr'))),

			// Add configs
			array('config.add', array('qr_source_post', '1')),

			// Update existing configs
			array('config.update', array('qr_version', '0.1.2')),
		);
	}

	public function install_bbcode_for_qr()
	{
		// Load the acp_bbcode class
		if (!class_exists('acp_bbcodes'))
		{
			include($this->phpbb_root_path . 'includes/acp/acp_bbcodes.' . $this->php_ext);
		}
		$bbcode_tool = new \acp_bbcodes();

		$bbcode_name = 'post';
		$bbcode_array = array(
			'bbcode_helpline'	=> 'BBCode for QuickReply extension',
			'bbcode_match'		=> '[post]{NUMBER}[/post]',
			'bbcode_tpl'		=> '<a href="./viewtopic.php?p={NUMBER}#p{NUMBER}"><span class="imageset icon_topic_latest" title="{L_QR_BBPOST}">{L_QR_BBPOST}</span></a>',
			'display_on_posting'=> 0,
		);

		// Build the BBCodes
		$data = $bbcode_tool->build_regexp($bbcode_array['bbcode_match'], $bbcode_array['bbcode_tpl']);

		$bbcode_array += array(
			'bbcode_tag'			=> $data['bbcode_tag'],
			'first_pass_match'		=> $data['first_pass_match'],
			'first_pass_replace'	=> $data['first_pass_replace'],
			'second_pass_match'		=> $data['second_pass_match'],
			'second_pass_replace'	=> $data['second_pass_replace']
		);

		$sql = 'SELECT bbcode_id
			FROM ' . $this->table_prefix . "bbcodes
			WHERE LOWER(bbcode_tag) = '" . strtolower($bbcode_name) . "'
			OR LOWER(bbcode_tag) = '" . strtolower($bbcode_array['bbcode_tag']) . "'";
		$result = $this->db->sql_query($sql);
		$row_exists = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($row_exists)
		{
			// Update existing BBCode
			$bbcode_id = $row_exists['bbcode_id'];

			$sql = 'UPDATE ' . $this->table_prefix . 'bbcodes
				SET ' . $this->db->sql_build_array('UPDATE', $bbcode_array) . '
				WHERE bbcode_id = ' . $bbcode_id;
			$this->db->sql_query($sql);
		}
		else
		{
			// Create new BBCode
			$sql = 'SELECT MAX(bbcode_id) AS max_bbcode_id
				FROM ' . $this->table_prefix . 'bbcodes';
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			if ($row)
			{
				$bbcode_id = $row['max_bbcode_id'] + 1;

				// Make sure it is greater than the core BBCode ids...
				if ($bbcode_id <= NUM_CORE_BBCODES)
				{
					$bbcode_id = NUM_CORE_BBCODES + 1;
				}
			}
			else
			{
				$bbcode_id = NUM_CORE_BBCODES + 1;
			}

			if ($bbcode_id <= BBCODE_LIMIT)
			{
				$bbcode_array['bbcode_id'] = (int) $bbcode_id;
				//$bbcode_array['display_on_posting'] = 1;

				$this->db->sql_query('INSERT INTO ' . $this->table_prefix . 'bbcodes ' . $this->db->sql_build_array('INSERT', $bbcode_array));
			}
		}
	}
}
