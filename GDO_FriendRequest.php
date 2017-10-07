<?php
namespace GDO\Friends;

use GDO\Core\GDO;
use GDO\DB\GDT_CreatedAt;
use GDO\Date\GDT_DateTime;
use GDO\Template\GDT_Template;
use GDO\User\GDT_User;
use GDO\User\GDO_User;

final class GDO_FriendRequest extends GDO
{
	public function gdoCached() { return false; }
	public function gdoColumns()
	{
		return array(
			GDT_User::make('frq_user')->primary(),
			GDT_User::make('frq_friend')->primary(),
			GDT_FriendRelation::make('frq_relation')->initial('friend'),
			GDT_CreatedAt::make('frq_created'),
			GDT_DateTime::make('frq_denied'),
		);
	}
	
	public function gdoHashcode() { return self::gdoHashcodeS($this->getVars(['frq_user', 'frq_friend', 'frq_relation'])); }
	
	public function getRelation() { return $this->getVar('frq_relation'); }
	public function getReverseRelation() { return GDT_FriendRelation::reverseRelation($this->getRelation()); }
	public function getCreated() { return $this->getVar('frq_created'); }
	public function getDenied() { return $this->getVar('frq_denied'); }
	public function isDenied() { return $this->getDenied() !== null; }
	
	public function displayRelation() { return GDT_FriendRelation::displayRelation($this->getRelation()); }
	
	public function isFrom(GDO_User $user) { return $this->getUserID() === $user->getID(); }
	
	/**
	 * @return GDO_User
	 */
	public function getUser() { return $this->getValue('frq_user'); }
	public function getUserID() { return $this->getVar('frq_user'); }
	
	/**
	 * @return GDO_User
	 */
	public function getFriend() { return $this->getValue('frq_friend'); }
	public function getFriendID() { return $this->getVar('frq_friend'); }
	
	public function renderCard() { return GDT_Template::responsePHP('Friends', 'card/friendrequest.php', ['gdo' => $this]); }
	public function renderList() { return GDT_Template::php('Friends', 'listitem/friendrequest.php', ['gdo' => $this]); }
	
	##############
	### Static ###
	##############
	public static function getPendingFor(GDO_User $user, GDO_User $friend)
	{
		return self::getById($user->getID(), $friend->getID());
	}
	
	public static function countIncomingFor(GDO_User $user)
	{
		if (null === ($cached = $user->tempGet('gdo_friendrequest_count')))
		{
			$cached = self::table()->countWhere("frq_friend={$user->getID()} AND frq_denied IS NULL");
			$user->tempSet('gdo_friendrequest_count', $cached);
			$user->recache();
		}
		return $cached;
	}
	public function gdoAfterCreate()
	{
		$user = $this->getFriend();
		$user->tempUnset('gdo_friendrequest_count');
		$user->recache();
	}
	
}
