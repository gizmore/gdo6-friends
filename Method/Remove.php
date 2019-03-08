<?php
namespace GDO\Friends\Method;

use GDO\Core\Method;
use GDO\Core\Website;
use GDO\Friends\GDO_Friendship;
use GDO\Friends\Module_Friends;
use GDO\Mail\Mail;
use GDO\User\GDO_User;
use GDO\Util\Common;
use GDO\Core\GDT_Hook;
use GDO\Core\Application;

final class Remove extends Method
{
	public function isAlwaysTransactional() { return true; }
	
	public function execute()
	{
		$user = GDO_User::current();
		$friendId = Common::getRequestString('friend');
		
		# Delete Friendship
		$friendship = GDO_Friendship::findById($friendId, $user->getID());
		$friendship->delete();
		$friendship = GDO_Friendship::findById($user->getID(), $friendId);
		$friendship->delete();
		
		# Call hook
		GDT_Hook::callWithIPC('FriendsRemove', $user->getID(), $friendId);
		
		# Send mail notes
		$this->sendMail($friendship);
		
		# Render and redirect
		$tabs = Module_Friends::instance()->renderTabs();
		$response = $this->message('msg_friendship_deleted', [$friendship->getFriend()->displayNameLabel()]);
		$tabs->add($response);
		
		if (Application::instance()->isHTML())
		{
			$redirect = Website::redirect(href('Friends', 'FriendList'));
			$tabs->add($redirect);
		}
		return $tabs;
	}
	
	private function sendMail(GDO_Friendship $friendship)
	{
		$user = GDO_User::current();
		$friend = $friendship->getFriend();
		$sitename = sitename();
		$mail = Mail::botMail();
		$mail->setSubject(tusr($friend, 'mail_subj_friend_removed', [$sitename, $user->displayNameLabel()]));
		$args = [$friend->displayNameLabel(), $user->displayNameLabel(), $sitename];
		$mail->setBody(tusr($friend, 'mail_body_friend_removed', $args));
		$mail->sendToUser($friend);
	}
}
