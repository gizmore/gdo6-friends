<?php
namespace GDO\Friends\Method;

use GDO\Core\GDO;
use GDO\Friends\GDO_FriendRequest;
use GDO\Friends\Module_Friends;
use GDO\Table\GDT_List;
use GDO\Table\MethodQueryList;
use GDO\User\GDO_User;

final class Requesting extends MethodQueryList
{
	public function isGuestAllowed() { return Module_Friends::instance()->cfgGuestGDO_Friendships(); }
	
	/**
	 * @return GDO
	 */
	public function gdoTable() { return GDO_FriendRequest::table(); }
	
	public function gdoDecorateList(GDT_List $list)
	{
		$list->label('list_pending_friend_requests', [sitename(), $list->countItems()]);
// 		$list->itemTemplate(GDT_GDO_FriendshipItem::make());
	}
	
	public function execute()
	{
		$response = parent::execute();
		$tabs = Module_Friends::instance()->renderTabs();
		return $tabs->add($response);
	}
	
	public function gdoQuery()
	{
		$user = GDO_User::current();
		return $this->gdoTable()->select()->where("frq_user={$user->getID()} AND frq_denied IS NULL");
	}
	
}
