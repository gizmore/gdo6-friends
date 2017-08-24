<?php
namespace GDO\Friends\Method;

use GDO\Core\Website;
use GDO\Date\Time;
use GDO\Friends\FriendRequest;
use GDO\Friends\MethodFriendRequest;
use GDO\Friends\Module_Friends;
use GDO\Mail\Mail;
use GDO\User\User;

final class Deny extends MethodFriendRequest
{
	public function executeWithRequest(FriendRequest $request)
	{
		$request->saveVar('frq_denied', Time::getDate());
		
		$this->sendMail($request);
		
		$tabs = Module_Friends::instance()->renderTabs();
		$response = $this->message('msg_friends_denied');
		$redirect = Website::redirect(href('Friends', 'Requests'));
		
		return $tabs->add($response)->add($redirect);
	}
	
	private function sendMail(FriendRequest $request)
	{
		$sitename = sitename();
		$user = User::current();
		$username = $user->displayNameLabel();
		$friend = $request->getFriend();
		
		$mail = Mail::botMail();
		$mail->setSubject(tusr($user, 'mail_subj_frq_denied', [$sitename, $username]));
		$args = [$friend->displayNameLabel(), $username, $sitename];
		$mail->setBody(tusr($friend, 'mail_body_frq_denied', $args));
	}
}
