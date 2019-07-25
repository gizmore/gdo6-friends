<?php
namespace GDO\Friends\Method;

use GDO\Friends\GDO_FriendRequest;
use GDO\Friends\GDO_Friendship;
use GDO\Friends\GDT_FriendRelation;
use GDO\Friends\MethodFriendRequest;
use GDO\Core\GDT_Hook;
use GDO\User\GDO_User;
use GDO\Mail\Mail;
use GDO\Core\GDT_Template;

final class Accept extends MethodFriendRequest
{
	public function executeWithRequest(GDO_FriendRequest $request)
	{
		$forRequester = GDO_Friendship::blank(array(
			'friend_user' => $request->getUserID(),
			'friend_friend' => $request->getFriendID(),
			'friend_relation' => $request->getRelation(),
		))->insert();
		$forHisFriend = GDO_Friendship::blank(array(
			'friend_user' => $request->getFriendID(),
			'friend_friend' => $request->getUserID(),
			'friend_relation' => GDT_FriendRelation::reverseRelation($request->getRelation()),
		))->insert();
		GDT_Hook::callWithIPC('FriendsAccept', $request->getUserID(), $request->getFriendID());
		$this->sendMail($request);
		$request->delete();
		return $this->message('msg_friends_accepted');
	}
	
	protected function sendMail(GDO_FriendRequest $request)
	{
		$sitename = sitename();
		$user = GDO_User::current();
		$username = $user->displayNameLabel();
		$friend = $request->getFriend();
		
		$mail = Mail::botMail();
		$mail->setSubject(tusr($friend, 'mail_subj_frq_accepted', [$sitename, $username, $request->displayRelation()]));
		
		$tVars = array(
			'user' => $user,
			'friend' => $friend,
			'relation' => $request->displayRelation(),
		);
		$body = GDT_Template::phpUser($friend, 'Recovery', 'mail/friend_accepted.php', $tVars);
		$mail->setBody($body);
		
		$mail->sendToUser($friend);
	}
}
