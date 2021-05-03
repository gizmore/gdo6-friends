<?php
namespace GDO\Friends;

use GDO\DB\GDT_Enum;
use GDO\User\GDO_User;
use GDO\Core\GDOException;
use GDO\DB\Query;

/**
 * An ACL field has default ACL options.
 * It helps to construct queries to reflect ACL permission.
 * 
 * @TODO: move to Module_Core
 * 
 * @author gizmore@wechall.net
 * @version 6.10.1
 * @since 6.8.0
 */
final class GDT_ACL extends GDT_Enum
{
	const ALL = 'acl_all';
	const MEMBERS = 'acl_members';
	const FRIENDS = 'acl_friends';
	const NOONE = 'acl_noone';
	
	protected function __construct()
	{
	    parent::__construct();
		$this->enumValues = [self::ALL, self::MEMBERS, self::FRIENDS, self::NOONE];
		$this->initial = self::NOONE;
		$this->notNull = true;
	}
	
	/**
	 * Check if a userpair allows access for this setting.
	 * @param GDO_User $user
	 * @param GDO_User $target
	 * @param string $reason
	 * @throws GDOException
	 * @return boolean
	 */
	public function hasAccess(GDO_User $user, GDO_User $target, &$reason, $throwException=true)
	{
		# Self is fine
		if ($user === $target) { return true; }
		
		# Other cases
		switch ($this->var)
		{
			case self::ALL:
				return true;
			
			case self::MEMBERS:
				if (!$result = $user->isMember())
				{
					$reason = t('err_only_member_access');
				}
				return $result;
			
			case self::FRIENDS:
				$result = module_enabled('Friends') ? GDO_Friendship::areRelated($user, $target) : false;
				if (!$result)
				{
					$reason = t('err_only_friend_access');
				}
				return $result;
			
			case self::NOONE:
				$reason = t('err_only_private_access');
				return false;
			
			default: # Should never happen.
				$reason = t('err_unknown_acl_setting', [$this->var]);
				if ($throwException)
				{
					throw new GDOException($reason);
				}
				return false;
		}
	}
	
	/**
	 * Add where conditions to a query that reflect acl settings.
	 * @param Query $query
	 * @param GDO_User $user
	 * @param string $creatorColumn
	 * @return self
	 */
	public function aclQuery(Query $query, GDO_User $user, $creatorColumn)
	{
		# All
		$idf = $this->identifier();
		$condition = "$idf = 'acl_all'";

		# Members
		if ($user->isMember())
		{
			$condition .= " OR $idf = 'acl_members'";
		}
		
		# Friends and own require a owner column
		if ($creatorColumn)
		{
			# Own
			$uid = $user->getID();
			$condition .= " OR $creatorColumn = {$uid}";

			# Friends
			if (module_enabled('Friends'))
			{
				$subquery = "SELECT 1 FROM gdo_friendship WHERE friend_user=$uid AND friend_friend=$creatorColumn";
				$condition .= " OR ( $idf = 'acl_friends' AND ( $subquery ) )";
			}
		}
		
		# Apply condition
		$query->where($condition);
		return $this;
	}
	
}
