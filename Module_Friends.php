<?php
namespace GDO\Friends;

use GDO\Core\GDO_Module;
use GDO\Date\GDT_Duration;
use GDO\Date\Time;
use GDO\UI\GDT_Bar;
use GDO\DB\GDT_Checkbox;
use GDO\DB\GDT_Int;
use GDO\User\GDO_User;
use GDO\User\GDO_UserSetting;

/**
 * GDO_Friendship and user relation module
 * 
 * @author gizmore
 * @version 6.0
 * @since 5.0
 */
final class Module_Friends extends GDO_Module
{
	##############
	### Module ###
	##############
	public $module_priority = 40;
	public function onLoadLanguage() { return $this->loadLanguage('lang/friends'); }
	public function getClasses()
	{
		return array(
			'GDO\Friends\GDO_Friendship',
			'GDO\Friends\GDO_FriendRequest',
		);
	}

	##############
	### Config ###
	##############
	public function getUserSettings()
	{
		return array(
			GDT_ACL::make('friendship_who')->initial('acl_all'),
			GDT_ACL::make('friendship_visible')->initial('acl_noone'),
			GDT_Int::make('friendship_level')->unsigned()->initial('0'),
		);
	}
	
	public function getConfig()
	{
		return array(
			GDT_Checkbox::make('friendship_friendslink')->initial('0'),
			GDT_Checkbox::make('friendship_guests')->initial('0'),
			GDT_Checkbox::make('friendship_relations')->initial('1'),
			GDT_Duration::make('friendship_cleanup_age')->initial('1d'),
		);
	}
	public function cfgFriendsLink() { return $this->getConfigValue('friendship_friendslink'); }
	public function cfgGuestFriendships() { return $this->getConfigValue('friendship_guests'); }
	public function cfgRelations() { return $this->getConfigValue('friendship_relations'); }
	public function cfgCleanupAge() { return $this->getConfigValue('friendship_cleanup_age'); }
	
	##############
	### Render ###
	##############
	public function renderTabs()
	{
		return $this->responsePHP('tabs.php');
	}

	public function hookRightBar(GDT_Bar $navbar)
	{
		if ($this->cfgFriendsLink())
		{
			$this->templatePHP('rightbar.php', ['navbar' => $navbar]);
		}
	}
	
	#####################
	### Setting Perms ###
	#####################
	public function canRequest(GDO_User $to, &$reason)
	{
		$user = GDO_User::current();
		
		# Check level
		$level = GDO_UserSetting::userGet($to, 'friendship_level')->var;
		if ($level > $user->getLevel())
		{
			$reason = t('err_level_required', [$level]);
			return false;
		}
		
		# Check user
		/**
		 * @var \GDO\Friends\GDT_ACL $setting
		 */
		$setting = GDO_UserSetting::userGet($to, 'friendship_who');
		return $setting->hasAccess($user, $to, $reason);
	}
	
	public function canViewFriends(GDO_User $from, &$reason)
	{
		# Self
		$user = GDO_User::current();
		if ($user === $from)
		{
			return true;
		}

		# Other
		/**
		 * @var \GDO\Friends\GDT_ACL $setting
		 */
		$setting = GDO_UserSetting::userGet($from, 'friendship_visible');
		return $setting->hasAccess($user, $from, $reason);
	}
}
