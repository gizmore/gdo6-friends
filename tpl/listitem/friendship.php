<?php
use GDO\Avatar\GDO_Avatar;
use GDO\Friends\GDO_Friendship;
use GDO\UI\GDT_IconButton;

$gdo instanceof GDO_Friendship;
$friendship = $gdo;
$friend = $friendship->getFriend();
?>
<md-list-item class="md-2-line">
  <?= GDO_Avatar::renderAvatar($friend); ?>
  <div class="md-list-item-text" layout="column">
	<h3><?= $friend->displayName(); ?></h3>
	<p><?= t('friend_relation_since', [$friendship->displayRelation(), tt($friendship->getCreated())]); ?></p>
  </div>
  <?= GDT_IconButton::make()->icon('delete')->href(href('Friends', 'Remove', '&friend='.$friend->getID()))->render(); ?>
</md-list-item>
