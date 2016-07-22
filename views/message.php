<div class="message" id="message<?php echo $message->message_id ?>" data-looked-<?php echo $message->looked ?>>
  <img class="message__img" src="/img/users/<?php echo $message->from ?>.jpg" alt="logo" />
  <div class="message__text">
	<?php echo $message->text ?>
  </div>
	<div id="date<?php echo $message->message_id ?>" class="message__time" data-date="<?php $date = new DateTime(); $date->setTimestamp(intval($message->date)); echo $date->format('Y-m-d H:i:sP'); ?>"></div>
	<script type="text/javascript">
		var dnow = new Date();
		var dmsg = new Date("<?php echo $date->format('Y-m-d H:i:sP')?>");
		var returnDate;
		var values = [dmsg.getHours(), dmsg.getMinutes(),dmsg.getMonth()+1,dmsg.getDate(),dmsg.getFullYear()];
		for( var id in values ) {
		  values[id] = values[id].toString().replace( /^([0-9])$/, '0$1' );
		}
		if ((dnow.getDate() == dmsg.getDate()) && (dnow.getMonth() == dmsg.getMonth()) && (dnow.getYear() == dmsg.getYear())) {
			returnDate = values[0]+":"+values[1];
		} else {
			returnDate = values[3]+"."+values[2]+"."+values[4].substr(2,3);
		}
		$("#date<?php echo $message->message_id?>").html(returnDate);
	</script>
  </div>
</div>
