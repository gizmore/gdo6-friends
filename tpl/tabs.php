<?php
use GDO\Friends\GDO_FriendRequest;
use GDO\Friends\GDO_Friendship;
use GDO\UI\GDT_Bar;
use GDO\UI\GDT_Link;
use GDO\User\GDO_User;
$user = GDO_User::current();
$bar = GDT_Bar::make()->horizontal();
$friends = GDO_Friendship::count($user);
$incoming = GDO_FriendRequest::countIncomingFor($user);
$link3 = GDT_Link::make('link_incoming_friend_requests')->label('link_incoming_friend_requests', [$incoming])->href(href('Friends', 'Requests'));
if ($incoming) $link3->icon('alert');
$bar->addFields(array(
	GDT_Link::make('link_add_friend')->icon('add')->href(href('Friends', 'Request')),
	GDT_Link::make('link_friends')->label('link_friends', [$friends])->icon('group')->href(href('Friends', 'FriendList')),
	$link3,
	GDT_Link::make('link_pending_friend_requests')->icon('wait')->href(href('Friends', 'Requesting')),
));
echo $bar->renderCell();
