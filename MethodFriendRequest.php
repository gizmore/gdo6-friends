<?php
namespace GDO\Friends;
use GDO\Core\Method;
use GDO\User\GDO_User;
use GDO\Util\Common;
use GDO\Core\GDT_Response;

abstract class MethodFriendRequest extends Method
{
	/**
	 * @param GDO_FriendRequest $request
	 * @return GDT_Response
	 */
	public abstract function executeWithRequest(GDO_FriendRequest $request);
	
	public function isAlwaysTransactional() { return true; }
	
	public function execute()
	{
		$forId = Common::getRequestInt('for', GDO_User::current()->getID());
		$fromId = Common::getRequestInt('from');
		
		$tokenRequired = GDO_User::current()->getID() !== $forId;
		
		$table = GDO_FriendRequest::table();
		$query = $table->select()->where("frq_user=$fromId AND frq_friend=$forId");
		if (!($request = $query->first()->exec()->fetchObject()))
		{
			return $this->error('err_friend_request');
		}
		
		if ( ($tokenRequired) && (Common::getRequestString('token') !== $request->gdoHashcode()) )
		{
			return $this->error('err_friend_request');
		}
		
		return $this->executeWithRequest($request);
	}
}
