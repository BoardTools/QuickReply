<?php
/**
*
* @package QuickReply Reloaded
* @copyright (c) 2014 - 2015 Tatiana5 and LavIgor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace tatiana5\quickreply\migrations\v0xx;

class v_0_1_0 extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return array('\tatiana5\quickreply\migrations\v0xx\v_0_0_1');
	}

	public function update_data()
	{
		return array();
	}
}
