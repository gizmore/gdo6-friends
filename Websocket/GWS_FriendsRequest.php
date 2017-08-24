<?php
namespace GDO\Friends\Websocket;

use GDO\Friends\FriendRequest;
use GDO\Websocket\Server\GWS_CommandForm;
use GDO\Websocket\Server\GWS_Commands;
use GDO\Websocket\Server\GWS_Global;
use GDO\Websocket\Server\GWS_Message;
/**
 * WebSocket command for friend requests.
 * 
 * 1. Map websocket to friend request form.
 * 2. Hook friend requests and send websocket packet to notify friend target.
 * @author gizmore
 */
final class GWS_FriendsRequest extends GWS_CommandForm
{
    # Map to form
    public function getMethod() { return method('Friends', 'Request'); }
    
    /**
     * Hook friend requests and notify target.
     * @param unknown $requestId
     */
    public function hookFriendsRequest($requestId)
    {
        $request = FriendRequest::findByGID($requestId);
        $friend = $request->getFriend();
        $payload = GWS_Message::payload(0x0601);
        $payload .= GWS_Message::wr32($request->getUserID());
        GWS_Global::sendBinary($friend, $payload);
    }
}

GWS_Commands::register(0x0601, new GWS_FriendsRequest());
