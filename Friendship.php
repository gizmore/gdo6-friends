<?php
namespace GDO\Friends;

use GDO\DB\GDO;
use GDO\DB\GDT_CreatedAt;
use GDO\Template\GDT_Template;
use GDO\User\GDT_User;
use GDO\User\User;

final class Friendship extends GDO
{
	public function gdoCached() { return false; }
	public function gdoColumns()
	{
		return array(
			GDT_User::make('friend_user')->primary(),
			GDT_User::make('friend_friend')->primary(),
			GDT_FriendRelation::make('friend_relation')->notNull(),
			GDT_CreatedAt::make('friend_created'),
		);
	}
	
	/**
	 * @return User
	 */
	public function getUser() { return $this->getValue('friend_user'); }
	public function getUserID() { return $this->getVar('friend_user'); }
	
	/**
	 * @return User
	 */
	public function getFriend() { return $this->getValue('friend_friend'); }
	public function getFriendID() { return $this->getVar('friend_friend'); }

	public function getCreated() { return $this->getVar('friend_created'); }
	public function getRelation() { return $this->getVar('friend_relation'); }

	public function displayRelation() { return GDT_FriendRelation::displayRelation($this->getRelation()); }
	
	public function renderList() { return GDT_Template::php('Friends', 'listitem/friendship.php', ['gdo' => $this]); }
	public function renderCard() { return GDT_Template::responsePHP('Friends', 'card/friendship.php', ['gdo' => $this]); }
	
	##############
	### Static ###
	##############
	public static function getRelationBetween(User $user, User $friend)
	{
		return self::table()->select('friend_relation')->
			where("friend_user={$user->getID()} AND friend_friend={$friend->getID()}")->exec()->fetchValue();
	}
	
	public static function areRelated(User $user, User $friend)
	{
		return self::getRelationBetween($user, $friend) !== null;
	}
	
	public static function count(User $user)
	{
		if (null === ($cached = $user->tempGet('gwf_friendship_count')))
		{
			$cached = self::queryCount($user);
			$user->tempSet('gwf_friendship_count', $cached);
			$user->recache();
		}
		return $cached;
	}
	
	private static function queryCount(User $user)
	{
		return self::table()->countWhere('friend_user='.$user->getID());
	}
	
	public function gdoAfterCreate()
	{
		$user = $this->getUser();
		$user->tempUnset('gwf_friendship_count');
		$user->recache();
	}
}