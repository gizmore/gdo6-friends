<?php
namespace GDO\Friends;

use GDO\Core\GDO_Module;
use GDO\Date\GDT_Duration;
use GDO\Date\Time;
use GDO\Template\GDT_Bar;
use GDO\Type\GDT_Checkbox;
use GDO\Type\GDT_Int;
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
			GDT_Checkbox::make('friendship_guests')->initial('0'),
			GDT_Int::make('friendship_level')->unsigned()->initial('0'),
		);
	}
	
	public function getConfig()
	{
		return array(
			GDT_Checkbox::make('friendship_guests')->initial('0'),
			GDT_Duration::make('friendship_cleanup_age')->initial(Time::ONE_DAY),
		);
	}
	public function cfgGuestFriendships() { return $this->getConfigValue('friendship_guests'); }
	public function cfgCleanupAge() { return $this->getConfigValue('friendship_cleanup_age'); }
	
	##############
	### Render ###
	##############
	public function renderTabs()
	{
		return $this->templatePHP('tabs.php');
	}

	public function hookRightBar(GDT_Bar $navbar)
	{
		$this->templatePHP('rightbar.php', ['navbar' => $navbar]);
	}
}
