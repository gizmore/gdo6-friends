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
 * @author gizmore@wechall.net
 * @version 6.08
 * @since 6.08
 */
final class GDT_ACL extends GDT_Enum
{
	public function defaultLabel() { return $this->label('visibility'); }
	
	public function __construct()
	{
		$this->enumValues = ['acl_all', 'acl_members', 'acl_friends', 'acl_noone'];
		$this->initial = 'acl_noone';
		$this->notNull = true;
	}
	
	/**
	 * Check if a userpair allows access for this setting.
	 * @param GDO_User $user
	 * @param GDO_User $target
	 * @throws GDOException
	 * @return boolean
	 */
	public function hasAccess(GDO_User $user, GDO_User $target)
	{
		if ($user === $target) { return true; }
		switch ($this->var)
		{
			case 'acl_all'; return true;
			case 'acl_members'; return $user->isMember();
			case 'acl_friends'; return module_enabled('Friends') ? GDO_Friendship::areRelated($user, $target) : false;
			case 'acl_noone'; return false;
			default: throw new GDOException('ACL enum value is invalid: '.$this->var);
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
