<?php
use GDO\Friends\FriendRequest;
use GDO\Friends\Friendship;
use GDO\Template\GDT_Bar;
use GDO\UI\GDT_Link;
use GDO\User\User;
$user = User::current();
$bar = GDT_Bar::make();
$friends = Friendship::count($user);
$incoming = FriendRequest::countIncomingFor($user);
$bar->addFields(array(
	GDT_Link::make('link_add_friend')->icon('add')->href(href('Friends', 'Request')),
	GDT_Link::make('link_friends')->label('link_friends', [$friends])->icon('group')->href(href('Friends', 'FriendList')),
	GDT_Link::make('link_incoming_friend_requests')->label('link_incoming_friend_requests', [$incoming])->icon('notifications_active')->href(href('Friends', 'Requests')),
	GDT_Link::make('link_pending_friend_requests')->icon('notifications_paused')->href(href('Friends', 'Requesting')),
));
echo $bar->renderCell();
