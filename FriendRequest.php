<?php
namespace GDO\Friends;

use GDO\DB\GDO;
use GDO\DB\GDO_CreatedAt;
use GDO\Date\GDO_DateTime;
use GDO\Template\GDO_Template;
use GDO\User\GDO_User;
use GDO\User\User;

final class FriendRequest extends GDO
{
	public function gdoCached() { return false; }
	public function gdoColumns()
	{
		return array(
			GDO_User::make('frq_user')->primary(),
			GDO_User::make('frq_friend')->primary(),
			GDO_FriendRelation::make('frq_relation')->initial('friend'),
			GDO_CreatedAt::make('frq_created'),
			GDO_DateTime::make('frq_denied'),
		);
	}
	
	public function gdoHashcode() { return self::gdoHashcodeS($this->getVars(['frq_user', 'frq_friend', 'frq_relation'])); }
	
	public function getRelation() { return $this->getVar('frq_relation'); }
	public function getReverseRelation() { return GDO_FriendRelation::reverseRelation($this->getRelation()); }
	public function getCreated() { return $this->getVar('frq_created'); }
	public function getDenied() { return $this->getVar('frq_denied'); }
	public function isDenied() { return $this->getDenied() !== null; }
	
	public function displayRelation() { return GDO_FriendRelation::displayRelation($this->getRelation()); }
	
	public function isFrom(User $user) { return $this->getUserID() === $user->getID(); }
	
	/**
	 * @return User
	 */
	public function getUser() { return $this->getValue('frq_user'); }
	public function getUserID() { return $this->getVar('frq_user'); }
	
	/**
	 * @return User
	 */
	public function getFriend() { return $this->getValue('frq_friend'); }
	public function getFriendID() { return $this->getVar('frq_friend'); }
	
	public function renderCard() { return GDO_Template::responsePHP('Friends', 'card/friendrequest.php', ['gdo' => $this]); }
	public function renderList() { return GDO_Template::php('Friends', 'listitem/friendrequest.php', ['gdo' => $this]); }
	
	##############
	### Static ###
	##############
	public static function getPendingFor(User $user, User $friend)
	{
		return self::getById($user->getID(), $friend->getID());
	}
	
	public static function countIncomingFor(User $user)
	{
		if (null === ($cached = $user->tempGet('gwf_friendrequest_count')))
		{
			$cached = self::table()->countWhere("frq_friend={$user->getID()} AND frq_denied IS NULL");
			$user->tempSet('gwf_friendrequest_count', $cached);
			$user->recache();
		}
		return $cached;
	}
	public function gdoAfterCreate()
	{
		$user = $this->getFriend();
		$user->tempUnset('gwf_friendrequest_count');
		$user->recache();
	}
	
}
