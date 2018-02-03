<?php
namespace GDO\Friends\Websocket;

use GDO\Websocket\Server\GWS_Commands;
use GDO\Websocket\Server\GWS_Global;
use GDO\Websocket\Server\GWS_Message;
use GDO\User\GDO_User;
use GDO\Websocket\Server\GWS_Command;
use GDO\Friends\GDO_FriendRequest;
use GDO\Friends\Method\Accept;

class GWS_FriendsAccept extends GWS_Command
{
	/**
	 * Hook friend accept and notify targets.
	 */
	public function hookFriendsAccept($userid, $friendid)
	{
		$payload = GWS_Message::payload(0x0602);
		$payload .= GWS_Message::wr32($userid);
		$payload .= GWS_Message::wr32($friendid);
		
		GWS_Global::sendBinary(GDO_User::getById($userid), $payload);
		GWS_Global::sendBinary(GDO_User::getById($friendid), $payload);
	}

	public function execute(GWS_Message $msg)
	{
		$request = GDO_FriendRequest::findById($msg->read32(), $msg->read32());
		Accept::make()->executeWithRequest($request);
	}

}

GWS_Commands::register(0x0602, new GWS_FriendsAccept());
