<?php
namespace GDO\Friends;

use GDO\Core\Module;
use GDO\Date\GDO_Duration;
use GDO\Date\Time;
use GDO\Template\GDO_Bar;
use GDO\Type\GDO_Checkbox;
use GDO\Type\GDO_Int;
/**
 * Friendship and user relation module
 * 
 * @author gizmore
 * @version 6.0
 * @since 5.0
 */
final class Module_Friends extends Module
{
	##############
	### Module ###
	##############
	public $module_priority = 40;
	public function onLoadLanguage() { return $this->loadLanguage('lang/friends'); }
	public function getClasses()
	{
	    return array(
	        'GDO\Friends\Friendship',
	        'GDO\Friends\FriendRequest',
	    );
	}

	##############
	### Config ###
	##############
	public function getUserSettings()
	{
		return array(
			GDO_Checkbox::make('friendship_guests')->initial('0'),
			GDO_Int::make('friendship_level')->unsigned()->initial('0'),
		);
	}
	
	public function getConfig()
	{
		return array(
			GDO_Checkbox::make('friendship_guests')->initial('0'),
			GDO_Duration::make('friendship_cleanup_age')->initial(Time::ONE_DAY),
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

	public function hookRightBar(GDO_Bar $navbar)
	{
		$this->templatePHP('rightbar.php', ['navbar' => $navbar]);
	}
}
