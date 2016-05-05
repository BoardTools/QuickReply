<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) 2014 - 2016 Tatiana5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace boardtools\quickreply\acp;

class quickreply_plugins_info
{
	public function module()
	{
		return array(
			'filename'	=> '\boardtools\quickreply\acp\quickreply_plugins_module',
			'title'		=> 'ACP_PLUGINS_QUICKREPLY',
			'version'	=> '0.0.1',
			'modes'		=> array(
				'config_plugins_quickreply'		=> array('title' => 'ACP_PLUGINS_QUICKREPLY_EXPLAIN', 'auth' => 'ext_boardtools/quickreply && acl_a_quickreply', 'cat' => array('ACP_QUICKREPLY_EXPLAIN')),
			),
		);
	}
}
