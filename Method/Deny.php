<?php
namespace GDO\Friends\Method;

use GDO\Core\Website;
use GDO\Date\Time;
use GDO\Friends\GDO_FriendRequest;
use GDO\Friends\MethodFriendRequest;
use GDO\Friends\Module_Friends;
use GDO\Mail\Mail;
use GDO\User\GDO_User;
use GDO\Core\GDT_Template;

final class Deny extends MethodFriendRequest
{
	public function executeWithRequest(GDO_FriendRequest $request)
	{
		$request->saveVar('frq_denied', Time::getDate());
		
		$this->sendMail($request);
		
		$tabs = Module_Friends::instance()->renderTabs();
		$response = $this->message('msg_friends_denied');
		$redirect = Website::redirect(href('Friends', 'Requests'));
		
		return $tabs->addField($response)->addField($redirect);
	}
	
	private function sendMail(GDO_FriendRequest $request)
	{
		$sitename = sitename();
		$user = GDO_User::current();
		$username = $user->displayNameLabel();
		$friend = $request->getFriend();
		
		$mail = Mail::botMail();
		$mail->setSubject(tusr($friend, 'mail_subj_frq_denied', [$sitename, $username]));
		
		$tVars = array(
			'user' => $user,
			'friend' => $friend,
		);
		$body = GDT_Template::phpUser($friend, 'Recovery', 'mail/friend_denied.php', $tVars);
		$mail->setBody($body);
		
		$mail->sendToUser($friend);
	}
}
