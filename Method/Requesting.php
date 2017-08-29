<?php
namespace GDO\Friends\Method;

use GDO\DB\GDO;
use GDO\Friends\FriendRequest;
use GDO\Friends\Module_Friends;
use GDO\Table\GDT_List;
use GDO\Table\MethodQueryList;
use GDO\User\User;

final class Requesting extends MethodQueryList
{
	public function isGuestAllowed() { return Module_Friends::instance()->cfgGuestFriendships(); }
	
	/**
	 * @return GDO
	 */
	public function gdoTable() { return FriendRequest::table(); }
	
	public function gdoDecorateList(GDT_List $list)
	{
		$list->label('list_pending_friend_requests', [sitename(), $list->countItems()]);
// 		$list->itemTemplate(GDT_FriendshipItem::make());
	}
	
	public function execute()
	{
		$response = parent::execute();
		$tabs = Module_Friends::instance()->renderTabs();
		return $tabs->add($response);
	}
	
	public function gdoQuery()
	{
		$user = User::current();
		return $this->gdoTable()->select()->where("frq_user={$user->getID()} AND frq_denied IS NULL");
	}
	
}
