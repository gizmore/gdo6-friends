<?php
namespace GDO\Friends\Method;

use GDO\Core\Method;
use GDO\Core\Website;
use GDO\Friends\GDO_Friendship;
use GDO\Friends\Module_Friends;
use GDO\Mail\Mail;
use GDO\User\GDO_User;
use GDO\Util\Common;

final class Remove extends Method
{
	public function isAlwaysTransactional() { return true; }
	
	public function execute()
	{
		$user = GDO_User::current();
		
		$friendship = GDO_Friendship::findById(Common::getRequestString('friend'), $user->getID());
		$friendship->delete();
		$friendship = GDO_Friendship::findById($user->getID(), Common::getRequestString('friend'));
		$friendship->delete();
		$this->sendMail($friendship);
		
		$tabs = Module_Friends::instance()->renderTabs();
		$response = $this->message('msg_friendship_deleted', [$friendship->getFriend()->displayNameLabel()]);
		$redirect = Website::redirect(href('Friends', 'List'));
		
		return $tabs->add($response)->add($redirect);
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
