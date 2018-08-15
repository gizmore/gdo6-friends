<?php
namespace GDO\Friends\Websocket;

use GDO\Friends\GDO_FriendRequest;
use GDO\Websocket\Server\GWS_CommandForm;
use GDO\Websocket\Server\GWS_Commands;
use GDO\Websocket\Server\GWS_Global;
use GDO\Websocket\Server\GWS_Message;
use GDO\Friends\Method\Request;
use GDO\Core\Logger;
use GDO\Form\GDT_Form;
use GDO\Core\GDT_Response;
/**
 * WebSocket command for friend requests.
 * 
 * 1. Map websocket to friend request form.
 * 2. Hook friend requests and send websocket packet to notify friend target.
 * @author gizmore
 */
class GWS_FriendsRequest extends GWS_CommandForm
{
	# Map to form
	public function getMethod() { return Request::make(); }
	
//	 /**
//	  * Not needed as we hook into ws... not as nice but meh. Correct would be only IPC hook. Now only websocket. no other hook into this yet
//	  */
//	 public function hookFriendsRequest($id)
//	 {
//		 $request = GDO_FriendRequest::findByGID($id);
//		 $this->hookFriendsRequestB($request);
//	 }
	
	
	/**
	 * Hook friend requests and notify target.
	 * @param int $requestId
	 */
	public function hookFriendsRequestB(GDO_FriendRequest $request)
	{
		$friend = $request->getFriend();
		$payload = GWS_Message::payload(0x0601);
		$payload .= GWS_Message::wr32($request->getUserID());
		GWS_Global::sendBinary($friend, $payload);
	}
	
	public function postExecute(GWS_Message $msg, GDT_Form $form, GDT_Response $response)
	{
		$request = GDO_FriendRequest::getById($msg->user()->getID(), $form->getFormVar('frq_friend'));
		$this->hookFriendsRequestB($request);
	}
}

GWS_Commands::register(0x0601, new GWS_FriendsRequest());
