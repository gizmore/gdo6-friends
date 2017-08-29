<?php
namespace GDO\Friends\Method;

use GDO\DB\GDO;
use GDO\Friends\Friendship;
use GDO\Friends\Module_Friends;
use GDO\Table\GDT_List;
use GDO\Table\MethodQueryList;
use GDO\User\User;

final class FriendList extends MethodQueryList
{
	/**
	 * @return GDO
	 */
	public function gdoTable() { return Friendship::table(); }
	
	public function isGuestAllowed() { return Module_Friends::instance()->cfgGuestFriendships(); }
	
	public function gdoDecorateList(GDT_List $list)
	{
		$list->label('list_friends', [$list->countItems()]);
// 		$list->itemTemplate(GDT_FriendshipItem::make());
	}
	
	public function gdoQuery()
	{
		$user = User::current();
		return $this->gdoTable()->select()->where("friend_user={$user->getID()}");
	}
	
	public function execute()
	{
		$response = parent::execute();
		return Module_Friends::instance()->renderTabs()->add($response);
	}
}
