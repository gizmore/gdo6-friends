<?php
use GDO\Avatar\GDO_Avatar;
use GDO\Friends\GDO_FriendRequest;
use GDO\UI\GDT_IconButton;
use GDO\User\GDO_User;
use GDO\Profile\GDT_ProfileLink;

$gdo instanceof GDO_FriendRequest;
$friendship = $gdo;
$friend = $friendship->getFriend();
$user = GDO_User::current();
if ($friendship->isFrom($user)) :
?>
<md-list-item class="md-2-line">
  <?= GDT_ProfileLink::make()->forUser($friend)->render(); ?>
  <div class="md-list-item-text" layout="column">
    <h3><?= $friend->displayNameLabel(); ?></h3>
    <p><?= t('friend_request_to', [$friendship->displayRelation(), tt($friendship->getCreated())]); ?></p>
  </div>
  <?= GDT_IconButton::make()->icon('delete')->href(href('Friends', 'RemoveTo', '&friend='.$friend->getID()))->render(); ?>
</md-list-item>
<?php else : ?>
<md-list-item class="md-2-line">
  <?= GDT_ProfileLink::make()->forUser($friend)->render(); ?>
  <div class="md-list-item-text" layout="column">
    <h3><?= $friendship->getUser()->displayNameLabel(); ?></h3>
    <p><?= t('friend_request_from', [$friendship->displayRelation(), tt($friendship->getCreated())]); ?></p>
  </div>
  <?= GDT_IconButton::make()->icon('person_add')->href(href('Friends', 'AcceptFrom', '&user='.$friendship->getUser()->getID()))->render(); ?>
  <?= GDT_IconButton::make()->icon('block')->href(href('Friends', 'RemoveFrom', '&user='.$friendship->getUser()->getID()))->render(); ?>
</md-list-item>
<?php endif; ?>
