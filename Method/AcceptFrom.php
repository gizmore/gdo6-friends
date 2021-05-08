<?php
namespace GDO\Friends\Method;

use GDO\Core\Method;
use GDO\Core\Website;
use GDO\Friends\GDO_FriendRequest;
use GDO\Friends\Module_Friends;
use GDO\User\GDO_User;
use GDO\Util\Common;
use GDO\Profile\GDT_User;

final class AcceptFrom extends Method
{
	public function isAlwaysTransactional() { return true; }
	
	public function gdoParameters()
	{
		return array(
			GDT_User::make('user')->notNull(),
		);
	}
	
	public function execute()
	{
		$user = GDO_User::current();
		$fromId = Common::getRequestString('user');
		if (!($request = GDO_FriendRequest::table()->getById($fromId, $user->getID())))
		{
			return $this->error('err_friend_request');
		}
		
		Accept::make()->executeWithRequest($request);
		
		$tabs = Module_Friends::instance()->renderTabs();
		$response = $this->message('msg_friends_accepted');
		$redirect = Website::redirect(href('Friends', 'Requests'));
		
		return $tabs->add($response)->addField($redirect);
	}
}
