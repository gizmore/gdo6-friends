<?php
namespace GDO\Friends;
use GDO\Core\Method;
use GDO\User\User;
use GDO\Util\Common;
use GDO\Template\Response;

abstract class MethodFriendRequest extends Method
{
	/**
	 * @param FriendRequest $request
	 * @return Response
	 */
	public abstract function executeWithRequest(FriendRequest $request);
	
	public function isAlwaysTransactional() { return true; }
	
	public function execute()
	{
		$forId = Common::getRequestInt('for', User::current()->getID());
		$fromId = Common::getRequestInt('from');
		
		$tokenRequired = User::current()->getID() !== $forId;
		
		$table = FriendRequest::table();
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
