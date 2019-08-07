<?php
namespace GDO\Friends\Method;

use GDO\Core\GDO;
use GDO\Friends\GDO_FriendRequest;
use GDO\Friends\Module_Friends;
use GDO\Table\GDT_List;
use GDO\Table\MethodQueryList;
use GDO\User\GDO_User;

final class Requests extends MethodQueryList
{
	public function isGuestAllowed() { return Module_Friends::instance()->cfgGuestFriendships(); }
	
	/**
	 * @return GDO
	 */
	public function gdoTable() { return GDO_FriendRequest::table(); }
	
	public function gdoDecorateList(GDT_List $list)
	{
		$list->title(t('list_friends_requests', [$list->countItems()]));
	}
	
	public function gdoQuery()
	{
		$user = GDO_User::current();
		return $this->gdoTable()->select()->where("frq_friend={$user->getID()} AND frq_denied IS NULL");
	}
	
	public function execute()
	{
		$response = parent::execute();
		$tabs = Module_Friends::instance()->renderTabs();
		return $tabs->add($response);
	}
}
