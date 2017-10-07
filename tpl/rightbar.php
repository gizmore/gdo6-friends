<?php
use GDO\Friends\GDO_FriendRequest;
use GDO\Friends\GDO_Friendship;
use GDO\UI\GDT_Bar;
use GDO\UI\GDT_Link;
use GDO\User\GDO_User;
$navbar instanceof GDT_Bar;
$user = GDO_User::current();
if ($user->isAuthenticated())
{
	$count = GDO_Friendship::count($user);
	$link = GDT_Link::make('link_friends')->label('link_friends', [$count])->href(href('Friends', 'FriendList'));
	if (GDO_FriendRequest::countIncomingFor($user))
	{
		$link->icon('notifications');
	}
	$navbar->addField($link);
}
