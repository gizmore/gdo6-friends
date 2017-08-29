<?php
namespace GDO\Friends\Method;

use GDO\Friends\GDO_FriendRequest;
use GDO\Friends\GDO_Friendship;
use GDO\Friends\GDT_FriendRelation;
use GDO\Friends\MethodFriendRequest;

final class Accept extends MethodFriendRequest
{
	public function executeWithRequest(GDO_FriendRequest $request)
	{
		$request->delete();
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
		return $this->message('msg_friends_accepted');
	}
}
