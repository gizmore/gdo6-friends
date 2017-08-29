<?php
use GDO\Avatar\Avatar;
use GDO\Friends\FriendRequest;
use GDO\UI\GDT_IconButton;
use GDO\User\User;

$gdo instanceof FriendRequest;
$friendship = $gdo;
$friend = $friendship->getFriend();
$user = User::current();
if ($friendship->isFrom($user)) :
?>
<md-list-item class="md-2-line">
  <?= Avatar::renderAvatar($friend); ?>
  <div class="md-list-item-text" layout="column">
    <h3><?= $friend->displayName(); ?></h3>
    <p><?= t('friend_request_to', [$friendship->displayRelation(), tt($friendship->getCreated())]); ?></p>
  </div>
  <?= GDT_IconButton::make()->icon('delete')->href(href('Friends', 'RemoveTo', '&friend='.$friend->getID())); ?>
</md-list-item>
<?php else : ?>
<md-list-item class="md-2-line">
  <?= Avatar::renderAvatar($friend); ?>
  <div class="md-list-item-text" layout="column">
    <h3><?= $friendship->getUser()->displayName(); ?></h3>
    <p><?= t('friend_request_from', [$friendship->displayRelation(), tt($friendship->getCreated())]); ?></p>
  </div>
  <?= GDT_IconButton::make()->icon('person_add')->href(href('Friends', 'AcceptFrom', '&user='.$friendship->getUser()->getID())); ?>
  <?= GDT_IconButton::make()->icon('block')->href(href('Friends', 'RemoveFrom', '&user='.$friendship->getUser()->getID())); ?>
</md-list-item>
<?php endif; ?>
