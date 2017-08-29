<?php
use GDO\Friends\GDO_FriendRequest;
use GDO\Friends\GDO_Friendship;
use GDO\Template\GDT_Bar;
use GDO\UI\GDT_Link;
use GDO\User\GDO_User;
$user = GDO_User::current();
$bar = GDT_Bar::make();
$friends = GDO_Friendship::count($user);
$incoming = GDO_FriendRequest::countIncomingFor($user);
$bar->addFields(array(
	GDT_Link::make('link_add_friend')->icon('add')->href(href('Friends', 'Request')),
	GDT_Link::make('link_friends')->label('link_friends', [$friends])->icon('group')->href(href('Friends', 'FriendList')),
	GDT_Link::make('link_incoming_friend_requests')->label('link_incoming_friend_requests', [$incoming])->icon('notifications_active')->href(href('Friends', 'Requests')),
	GDT_Link::make('link_pending_friend_requests')->icon('notifications_paused')->href(href('Friends', 'Requesting')),
));
echo $bar->renderCell();
