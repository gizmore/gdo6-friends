<?php
namespace GDO\Friends\Method;

use GDO\Cronjob\MethodCronjob;
use GDO\DB\Database;
use GDO\Date\Time;
use GDO\Friends\FriendRequest;
use GDO\Friends\Module_Friends;

final class Cleanup extends MethodCronjob
{
	public function run()
	{
		$module = Module_Friends::instance();
		$cut = Time::getDate(time() - $module->cfgCleanupAge());
		FriendRequest::table()->deleteWhere("frq_denied < '$cut'");
		if ($affected = Database::instance()->affectedRows())
		{
			$this->logNotice(sprintf("Deleted %s old denied friend requests.", $affected));
		}
	}
}
