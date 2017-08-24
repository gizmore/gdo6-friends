<?php
namespace GDO\Friends\Method;

use GDO\DB\GDO;
use GDO\Friends\FriendRequest;
use GDO\Friends\Module_Friends;
use GDO\Table\GDO_List;
use GDO\Table\MethodQueryList;
use GDO\User\User;

final class Requests extends MethodQueryList
{
	public function isGuestAllowed() { return Module_Friends::instance()->cfgGuestFriendships(); }
	
	/**
	 * @return GDO
	 */
	public function gdoTable() { return FriendRequest::table(); }
	
	public function gdoDecorateList(GDO_List $list)
	{
		$list->label('list_friends_requests', [sitename(), $list->countItems()]);
	}
	
	public function gdoQuery()
	{
		$user = User::current();
		return $this->gdoTable()->select()->where("frq_friend={$user->getID()} AND frq_denied IS NULL");
	}
	
	public function execute()
	{
		$response = parent::execute();
		$tabs = Module_Friends::instance()->renderTabs();
		return $tabs->add($response);
	}
}
