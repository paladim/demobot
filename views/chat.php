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
	<div id="date<?php echo $chat->id ?>" class="info-time text-right" data-date="<?php $date = new DateTime(); $date->setTimestamp(intval($message->date)); echo $date->format('Y-m-d H:i:sP'); ?>"></div>
	<script type="text/javascript">
		var dnow = new Date();
		var dmsg = new Date("<?php echo $date->format('Y-m-d H:i:sP')?>");
		var returnDate;
		var values = [dmsg.getHours(), dmsg.getMinutes(),dmsg.getMonth()+1,dmsg.getHours(),dmsg.getFullYear()];
		for( var id in values ) {
		  values[id] = values[id].toString().replace( /^([0-9])$/, '0$1' );
		}
		if ((dnow.getDate() == dmsg.getDate()) && (dnow.getMonth() == dmsg.getMonth()) && (dnow.getYear() == dmsg.getYear())) {
			returnDate = values[0]+":"+values[1];
		} else {
			returnDate = values[3]+"."+values[2]+"."+values[4].substr(2,3);
		}
		$("#date<?php echo $chat->id?>").html(returnDate);
	</script>
    </div>
    <div class="chat__info__element">
	<div class="info-msg-text text-left"> <?php echo $message->text ?></div>
	<div class="info-msg-event info-msg-event_hid"></div>
    </div>
  </div>
</li>
