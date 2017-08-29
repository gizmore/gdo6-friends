<?php
use GDO\Friends\FriendRequest;
use GDO\Friends\Friendship;
use GDO\Template\GDT_Bar;
use GDO\UI\GDT_Link;
use GDO\User\User;
$navbar instanceof GDT_Bar;
$user = User::current();
if ($user->isAuthenticated())
{
	$count = Friendship::count($user);
	$link = GDT_Link::make('link_friends')->label('link_friends', [$count])->href(href('Friends', 'FriendList'));
	if (FriendRequest::countIncomingFor($user))
	{
		$link->icon('notifications');
	}
	$navbar->addField($link);
}
