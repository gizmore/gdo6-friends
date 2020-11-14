<?php
namespace GDO\Friends\Method;

use GDO\Core\GDO;
use GDO\Friends\GDO_Friendship;
use GDO\Friends\Module_Friends;
use GDO\Table\GDT_List;
use GDO\Table\MethodQueryList;
use GDO\User\GDO_User;

final class FriendList extends MethodQueryList
{
	/**
	 * @return GDO
	 */
	public function gdoTable() { return GDO_Friendship::table(); }
	
	public function isGuestAllowed() { return Module_Friends::instance()->cfgGuestFriendships(); }
	
	public function gdoDecorateList(GDT_List $list)
	{
		$list->title(t('list_friends', [$list->countItems()]));
	}
	
	public function getQuery()
	{
		$user = GDO_User::current();
		return $this->gdoTable()->select()->where("friend_user={$user->getID()}");
	}
	
	public function execute()
	{
		$response = parent::execute();
		return Module_Friends::instance()->renderTabs()->add($response);
	}
}
