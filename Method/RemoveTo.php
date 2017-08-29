<?php
namespace GDO\Friends\Method;

use GDO\Core\Method;
use GDO\Core\Website;
use GDO\Date\Time;
use GDO\Friends\FriendRequest;
use GDO\Friends\Module_Friends;
use GDO\User\User;
use GDO\Util\Common;

final class RemoveTo extends Method
{
	public function isAlwaysTransactional() { return true; }
	
	public function execute()
	{
		$user = User::current();
		$request = FriendRequest::findById($user->getID(), Common::getRequestString('friend'));
		$request->saveVar('frq_denied', Time::getDate());
		
		$tabs = Module_Friends::instance()->renderTabs();
		$redirect = Website::redirectMessage(href('Friends', 'Requesting'));
		return $tabs->add($this->message('msg_request_revoked'))->add($redirect);
	}
}