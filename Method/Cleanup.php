<?php
namespace GDO\Friends\Method;

use GDO\DB\Database;
use GDO\Date\Time;
use GDO\Friends\GDO_FriendRequest;
use GDO\Friends\Module_Friends;
use GDO\Core\Application;
use GDO\Cronjob\MethodCronjob;

final class Cleanup extends MethodCronjob
{
	public function run()
	{
		$module = Module_Friends::instance();
		$cut = Time::getDate(Application::$TIME - $module->cfgCleanupAge());
		GDO_FriendRequest::table()->deleteWhere("frq_denied < '$cut'");
		if ($affected = Database::instance()->affectedRows())
		{
			$this->logNotice(sprintf("Deleted %s old denied friend requests.", $affected));
		}
	}
}
