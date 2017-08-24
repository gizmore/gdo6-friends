<?php
namespace GDO\Friends\Method;

use GDO\Core\Method;
use GDO\Core\Website;
use GDO\Friends\Friendship;
use GDO\Friends\Module_Friends;
use GDO\Mail\Mail;
use GDO\User\User;
use GDO\Util\Common;

final class Remove extends Method
{
	public function isAlwaysTransactional() { return true; }
	
	public function execute()
	{
		$user = User::current();
		
		$friendship = Friendship::findById(Common::getRequestString('friend'), $user->getID());
		$friendship->delete();
		$friendship = Friendship::findById($user->getID(), Common::getRequestString('friend'));
		$friendship->delete();
		$this->sendMail($friendship);
		
		$tabs = Module_Friends::instance()->renderTabs();
		$response = $this->message('msg_friendship_deleted', [$friendship->getFriend()->displayNameLabel()]);
		$redirect = Website::redirect(href('Friends', 'List'));
		
		return $tabs->add($response)->add($redirect);
	}
	
	private function sendMail(Friendship $friendship)
	{
		$user = User::current();
		$friend = $friendship->getFriend();
		$sitename = sitename();
		$mail = Mail::botMail();
		$mail->setSubject(tusr($friend, 'mail_subj_friend_removed', [$sitename, $user->displayNameLabel()]));
		$args = [$friend->displayNameLabel(), $user->displayNameLabel(), $sitename];
		$mail->setBody(tusr($friend, 'mail_body_friend_removed', $args));
		$mail->sendToUser($friend);
	}
}
