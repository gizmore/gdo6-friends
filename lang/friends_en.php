<?php
return array(
##################################################
'link_friends' => 'Friends (%s)',
'link_add_friend' => 'Add a friend',
'link_incoming_friend_requests' => 'Incoming requests (%s)',
'link_pending_friend_requests' => 'Sent requests',
##################################################
'ft_friends_request' => '[%s] Add a friend',
'frq_friend' => 'Name of your friend',
'err_friend_self' => 'You cannot befriend yourself here.',
'err_already_pending_denied' => 'A request for %s has been denied or cancelled recently.',
'err_already_pending' => 'There is already a pending request for %s.',
'msg_friend_request_sent' => 'Your request has been sent.',
##################################################
'list_friends' => 'Your friends (%s)',
'friend_relation_since' => 'Is listed as your %s since %s',
'err_friend_request' => 'The relationship request could not been found.',
'msg_friends_accepted' => 'Your relationship status with %s has been accepted.',
'msg_friendship_deleted' => 'Your relationship status with %s has been deleted.',
##################################################
'list_friends_requests' => '[%s] Friend Requets (%s)',
'friend_request_from' => 'Request as %s from %s',
##################################################
'list_pending_friend_requests' => '[%s] Pending requests from you (%s)',
'friend_request_to' => 'Requested to be %s at %s',
'msg_request_revoked' => 'You have revoked your friend request. You cannot re-request until a cleanup.',
##################################################
'friend_relation' => 'Relation',
'enum_friend' => 'Friend',
'enum_bestfriend' => 'Best Friend',
##################################################
'mail_subj_friend_request' => '[%s] Relationship with %s',
'mail_body_friend_request' => '
Dear %s,

%s requested to add you as their %s on %s.

You can confirm this request by visiting this link.

%s

You can deny this request by visiting this link.

%s

It is safe to ignore the request.

Kind Regards,
The %4$s Team',
##################################################
'mail_subj_frq_denied' => '[%s] %s denied the relationship',
'mail_body_frq_denied' =>  '
Dear %s,
		
%s has denied your relationship request on %s.
		
Kind Regards,
The %3$s Team',
##################################################
'mail_subj_frq_accepted' => '[%s] %s accepted the relationship',
'mail_body_frq_accepted' =>  '
Dear %s,
		
%s has accepted your relationship request on %s and is now officially your %s.
		
Kind Regards,
The %3$s Team',
####
'mail_subj_friend_removed' => '[%s] %s removed your relationship',
'mail_body_friend_removed' => '
Dear %s,

%s removed their relationship status with you on %s.

Kind Regards,
The %3$s Team',
);
