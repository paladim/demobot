<div class="message" id="message<?php echo $message->message_id ?>" data-looked-<?php echo $message->looked ?>>
  <img class="message__img" src="/img/users/<?php echo $message->from ?>.jpg" alt="logo" />
  <div class="message__text">
	<?php echo $message->text ?>
  </div>
  <div class="message__time">
	<abbr title="<?php $date = new DateTime(); $date->setTimestamp(intval($message->date)); echo $date->format('Y-m-d H:i:sP') ?>" class="timeago"></abbr>
  </div>
</div>
