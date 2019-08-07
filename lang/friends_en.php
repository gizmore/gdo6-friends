<?php
return array(
'cfg_friendship_who' => 'Who may send you friend requests?',
'cfg_friendship_visible' => 'Who may see your friends?',
'cfg_friendship_level' => 'What is your friend\'s minimum level?',
'cfg_friendship_guests' => 'Allow relations between guests?',
'cfg_friendship_relations' => 'Use extend relation states?',
'cfg_friendship_cleanup_age' => 'Clear deleted requests after N',
##################################################
'gdo_friendrequest' => 'Friendrequest',
##################################################
'enum_acl_all' => 'All',
'enum_acl_members' => 'Members',
'enum_acl_friends' => 'Friends',
'enum_acl_noone' => 'Noone',
'visibility' => 'Visibility',
##################################################
'err_only_member_access' => 'Only Members are allowed to access this.',
'err_only_friend_access' => 'Only their friends are allowed to access this.',
'err_only_private_access' => 'Only the user theyself is allowed to access this.',
'err_unknown_acl_setting' => 'Unknown ACL Setting: %s.',
	
##################################################
'link_friends' => 'Friends (%s)',
'link_add_friend' => 'Add a friend',
'link_incoming_friend_requests' => 'Incoming requests (%s)',
'link_pending_friend_requests' => 'Sent requests',
##################################################
'ft_friends_request' => 'Add a friend',
'frq_friend' => 'Name of your friend',
'err_friend_self' => 'You cannot befriend yourself here.',
'err_already_pending_denied' => 'A request for %s has been denied or cancelled recently.',
'err_already_pending' => 'There is already a pending request for %s.',
'err_requesting_denied' => 'You cannot request a friendship with this user: %s.',
'msg_friend_request_sent' => 'Your request has been sent.',
'err_already_related' => 'You and %s are already related.',
##################################################
'list_friends' => 'Your friends (%s)',
'friend_relation_since' => 'Is listed as your %s since %s',
'err_friend_request' => 'The relationship request could not been found.',
'msg_friends_accepted' => 'Your relationship status with %s has been accepted.',
'msg_friendship_deleted' => 'Your relationship status with %s has been deleted.',
##################################################
'list_friends_requests' => 'Friend Requests (%s)',
'friend_request_from' => 'Request as %s from %s',
##################################################
'list_pending_friend_requests' => 'Pending requests from you (%s)',
'friend_request_to' => 'Requested to be %s at %s',
'msg_request_revoked' => 'You have revoked your friend request. You cannot re-request until a cleanup.',
##################################################
'friend_relation' => 'Relation',
'enum_friend' => 'Friend',
'enum_bestfriend' => 'Best Friend',
##################################################
'mail_subj_friend_request' => '[%s] Relationship with %s',
'mail_subj_frq_denied' => '[%s] %s denied the relationship',
'mail_subj_frq_accepted' => '[%s] %s is now your %s',
'mail_subj_friend_removed' => '[%s] %s removed your relationship',
);
