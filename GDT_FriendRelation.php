<?php
namespace GDO\Friends;

use GDO\Core\GDOError;
use GDO\DB\GDT_Enum;

final class GDT_FriendRelation extends GDT_Enum
{
	public static $TYPES = array(
		'friend' => 'friend',
		'bestfriend' => 'bestfriend',
		'coworker' => 'coworker',
		'husband' => 'wife',
	);
	
	public static function displayRelation($relation)
	{
		return t('enum_'.$relation);
	}
	
	public static function reverseRelation($relation)
	{
		if (isset(self::$TYPES[$relation]))
		{
			return self::$TYPES[$relation];
		}
		elseif (false !== ($index = array_search($relation, self::$TYPES, true)))
		{
			return $index;
		}
		else
		{
			throw new GDOError('err_reverse_friends_relation');
		}
	}
	
	public function defaultLabel() { return $this->label('friend_relation'); }
	
	protected function __construct()
	{
		$this->enumValues(...array_unique(array_merge(array_keys(self::$TYPES), array_values(self::$TYPES))));
	}
}
