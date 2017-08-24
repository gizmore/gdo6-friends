<?php
namespace GDO\Friends\Method;

use GDO\Core\Method;
use GDO\Core\Website;
use GDO\Friends\FriendRequest;
use GDO\Friends\Module_Friends;
use GDO\User\User;
use GDO\Util\Common;

final class AcceptFrom extends Method
{
	public function isAlwaysTransactional() { return true; }
	
	public function execute()
	{
		$user = User::current();
		$fromId = Common::getRequestString('user');
		if (!($request = FriendRequest::table()->getById($fromId, $user->getID())))
		{
			return $this->error('err_friend_request');
		}
		
		method('Friends', 'Accept')->executeWithRequest($request);
		
		$tabs = Module_Friends::instance()->renderTabs();
		$response = $this->message('msg_friends_accepted');
		$redirect = Website::redirect(href('Friends', 'Requests'));
		
		return $tabs->add($response)->add($redirect);
	}
}
