<?php
namespace GDO\Friends\Websocket;

use GDO\Websocket\Server\GWS_Commands;
use GDO\Websocket\Server\GWS_Message;
use GDO\Websocket\Server\GWS_Command;
use GDO\User\GDO_User;
use GDO\Friends\GDO_Friendship;
use GDO\Table\GDT_PageMenu;
use GDO\Friends\Module_Friends;
/**
 * WebSocket command for friend requests.
 *
 * 1. Map websocket to friend request form.
 * 2. Hook friend requests and send websocket packet to notify friend target.
 * @author gizmore
 */
class GWS_FriendsList extends GWS_Command
{
    public function execute(GWS_Message $msg)
    {
        $user = GDO_User::findById($msg->read32u());
        
        $page = $msg->read16u();
        $_REQUEST['f']['page'] = $page; # Hack
        
        if (!Module_Friends::instance()->canViewFriends($user))
        {
            return $msg->replyErrorMessage($msg->cmd(), t("err_not_allowed"));
        }

        $query = GDO_Friendship::table()->select('user_id')->joinObject('friend_friend')->where('friend_user='.$user->getID());
        $pagemenu = GDT_PageMenu::make()->query($query);
        $pagemenu->filterQuery($query);
        $result = $query->exec();
        $payload = $this->pagemenuToBinary($pagemenu);
        while ($friendid = $result->fetchValue())
        {
            $payload .= $msg->write32($friendid);
        }
        
        return $msg->replyBinary($msg->cmd(), $payload);
    }
}

GWS_Commands::register(0x0603, new GWS_FriendsList());
