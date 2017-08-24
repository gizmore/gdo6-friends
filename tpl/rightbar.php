<?php
use GDO\Friends\FriendRequest;
use GDO\Friends\Friendship;
use GDO\Template\GDO_Bar;
use GDO\UI\GDO_Link;
use GDO\User\User;
$navbar instanceof GDO_Bar;
$user = User::current();
if ($user->isAuthenticated())
{
	$count = Friendship::count($user);
	$link = GDO_Link::make('link_friends')->label('link_friends', [$count])->href(href('Friends', 'FriendList'));
	if (FriendRequest::countIncomingFor($user))
	{
		$link->icon('notifications');
	}
	$navbar->addField($link);
}
