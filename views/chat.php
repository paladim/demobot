<li class="chat" id="<?php echo $chat->id ?>" data-chat-id="<?php echo $chat->id ?>">
  <img class="chat__img" src="/img/users/<?php echo $chat->id ?>.jpg" alt="logo" />
  <div class="chat__info">
    <div class="chat__info__element">
	<div class="info-name text-left"><?php echo $chat->first_name ?>
	<?php if ($chat->selected == 0) { ?>
		<i id="star<?php echo $chat->id ?>" class="fa fa-star chat-star-img" aria-hidden="true"></i>
	<?php } ?>
	<?php if ($chat->selected == 1) { ?>
		<i id="star<?php echo $chat->id ?>" class="fa fa-star chat-star-img chat-star-img__selected" aria-hidden="true"></i>
	<?php } ?>
	</div>
	<div class="info-time text-right"><abbr title="<?php $date = new DateTime(); $date->setTimestamp(intval($message->date)); echo $date->format('Y-m-d H:i:sP') ?>" class="timeago"></abbr></div>
    </div>
    <div class="chat__info__element">
	<div class="info-msg-text text-left"> <?php echo $message->text ?></div>
	<div class="info-msg-event info-msg-event_hid"></div>
    </div>
  </div>
</li>
