<?php
/**
*
* @package quickreply
* @copyright (c) 2014 Татьяна5
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace tatiana5\quickreply\acp;

class quickreply_info
{
	function module()
	{
		return array(
			'filename'	=> '\tatiana5\quickreply\acp\quickreply_module',
			'title'		=> 'ACP_QUICKREPLY',
			'version'	=> '0.0.1',
			'modes'		=> array(
				'config_quickreply'		=> array('title' => 'ACP_QR_CONFIG', 'auth' => 'acl_a_quickreply', 'cat' => array('ACP_QR_CONFIG')),
			),
		);
	}
}
