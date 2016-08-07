<?php

class CI_telegram extends CI_Controller {
	public function selected() {
		header('Content-Type: application/json');
		$chat =& load_class('chat', 'models');
		$chats = $chat->find(intval(htmlspecialchars($_GET['chat_id'])));
		if (count($chats) > 0) {
			$_chat = $chats[0];
			$_chat->selected = ($_chat->selected == 0) ? 1 : 0;
			$_chat->update();
			echo json_encode(array("status" => true, "chat_id" => $_chat->id, "selected" => $_chat->selected));
		} else {
			echo json_encode(array("status" => false));
		}
	}

	public function looked(){
		$message =& load_class('message', 'models');
		$messages = $message->find_by(array('message_id = ' => intval(htmlspecialchars($_GET['message_id']))),1);
		$msg = $messages[0];
		$msg->looked = 1; 
		echo $msg->save();
	}

	public function get_chats_update()
	{
		$chat =& load_class('chat', 'models');
		$chats = $chat->get_chats_update();
		$rows = array();
		foreach ($chats as $chat_item) {
			$rows[] = array("chat_id" => $chat_item->id);
		}
		header('Content-Type: application/json');
		echo json_encode($rows);
	}


	public function get_messages()
	{
		$message =& load_class('message', 'models');
		if (isset($_GET['message_id'])) {
			$messages = $message->find_by(array('chat = ' => intval(htmlspecialchars($_GET['chat_id'])), 'message_id > ' => intval(htmlspecialchars($_GET['message_id']))),100);
		} else {
			$messages = $message->find_by(array('chat = ' => intval(htmlspecialchars($_GET['chat_id']))),100);
		}
		$rows = array();
		foreach ($messages as $msg_item) {
			$data['message'] = $msg_item;	
			$rows[] = array("message_id" => $msg_item->message_id, "message_looked" => $msg_item->looked, "message_html" => $this->view('message',$data,true));
		}
		header('Content-Type: application/json');
		echo json_encode(array_reverse($rows));
	}

	public function get_chat()
	{
		$data = array();
		$chat =& load_class('chat', 'models');
		$message =& load_class('message', 'models');
		$chats = $chat->find(intval(htmlspecialchars($_GET['chat_id'])));
		$rows = array();
		foreach ($chats as $chat_item) {
			$data['chat'] = $chat_item;	
			$messages = $message->find_by(array('chat = ' => $chat_item->id),1);
			$data['message'] = $messages[0];
			$rows[] = array("chat_id" => $chat_item->id, "chat_html" => $this->view('chat',$data,true));
		}
		header('Content-Type: application/json');
		echo json_encode($rows);
	}
	
	public function get_chats()
	{
		$data = array();
		$chat =& load_class('chat', 'models');
		$message =& load_class('message', 'models');
		$chats = $chat->all();
		$rows = array();
		foreach ($chats as $chat_item) {
			$data['chat'] = $chat_item;	
			$messages = $message->find_by(array('chat = ' => $chat_item->id),1);
			$data['message'] = (isset($messages[0]) ? $messages[0] : new CI_message());
			$rows[] = array("chat_id" => $chat_item->id, "chat_html" => $this->view('chat',$data,true));
		}
		header('Content-Type: application/json');
		echo json_encode($rows);
	}


	public function send_message()
	{
		$chat_id=htmlspecialchars($_GET['chat_id']);
		$tb =& load_class('telegram_bot', 'utils');
		$user =& load_class('user', 'models');
		$chat =& load_class('chat', 'models');
		$message =& load_class('message', 'models');
		$return = $tb->sendMessage($chat_id,htmlspecialchars($_GET['text']));	

		if ($return["ok"] == "true") {

			$m = $return['result'];
			$f = $m['from'];
			$c = $m['chat'];

			$user_id = $f['id'];
			$user_first_name = $f['first_name'];
			$user_last_name = (isset($f['last_name'])) ? $f['last_name'] : '';
			$user_username = (isset($f['username'])) ? $f['username'] : '';

			$chat_id = $c['id'];
			$chat_type = $c['type'];
			$chat_title = (isset($c['title'])) ? $c : '';
			$chat_username = (isset($c['username'])) ? $c['username'] : '';
			$chat_first_name = (isset($c['first_name'])) ? $c['first_name'] : '';
			$chat_last_name = (isset($c['last_name'])) ? $c['last_name'] : '';

			$message_id = $m['message_id'];
			$message_from = $user_id;
			$message_date = (isset($m['date'])) ? $m['date'] : '';
			$message_chat = $chat_id;
			$message_text = (isset($m['text'])) ? $m['text'] : '';

			$new_user = new CI_user();
			$new_user->id = $user_id;
			$new_user->username = $user_username;
			$new_user->first_name = $user_first_name;
			$new_user->last_name = $user_last_name;
			$new_user->save();

			$new_chat = new CI_chat(
				array(
					'id' => $chat_id,
					'type' => $chat_type,
					'title' => $chat_title,
					'username' => $chat_username,
					'first_name' => $chat_first_name,
					'last_name' => $chat_last_name
					)
			);
			$new_chat->save();

			$new_message = new CI_message(
					array(
					'message_id' => $message_id,
					'from' => $message_from,
					'date' => $message_date,
					'chat' => $message_chat,
					'text' => htmlentities(strip_tags(htmlspecialchars($message_text))),
					'looked' => true 
					)
			);	
			$new_message->insert();
				
			if (!file_exists('img/users'.$message_from.".jpg")) $tb->getUserProfilePhotos($user_id);

			header('Content-Type: application/json');
			echo json_encode($return);
		} else {
			header('Content-Type: application/json');
			echo json_encode(array("status" => false));
		}
	}

        public function index()
        {
		$tb =& load_class('telegram_bot', 'utils');
		require_once(MODELSPATH.'user.php');
		require_once(MODELSPATH.'chat.php');
		require_once(MODELSPATH.'message.php');
		require_once(MODELSPATH.'update.php');
		//$_user =& load_class('user', 'models');
		//$_chat =& load_class('chat', 'models');
		//$_message =& load_class('message', 'models');
		//$_update =& load_class('update', 'models');
		
		$_update = new CI_update();
		$last_update_id = $_update->get_last_update_id();

		while(true)
		{
		try {
			$updates = $tb->pollUpdates($last_update_id,60);

			$count = count($updates['result']);
			if ($count > 0 )
				$last_update_id = $updates['result'][$count - 1]['update_id'] + 1;

			foreach($updates['result'] as $t_update)
			{
				$m = $t_update['message'];
				$f = $m['from'];
				$c = $m['chat'];
				
				$user_id = $f['id'];
				$user_first_name = $f['first_name'];
				$user_last_name = (isset($f['last_name'])) ? $f['last_name'] : '';
				$user_username = (isset($f['username'])) ? $f['username'] : '';

				$chat_id = $c['id'];
				$chat_type = $c['type'];
				$chat_title = (isset($c['title'])) ? $c : '';
				$chat_username = (isset($c['username'])) ? $c['username'] : '';
				$chat_first_name = (isset($c['first_name'])) ? $c['first_name'] : '';
				$chat_last_name = (isset($c['last_name'])) ? $c['last_name'] : '';

				$message_id = $m['message_id'];
				$message_from = $user_id;
				$message_date = (isset($m['date'])) ? $m['date'] : '';
				$message_chat = $chat_id;
				$message_text = (isset($m['text'])) ? $m['text'] : '';

				$update_id = $t_update['update_id'];
				$update_message = $message_id;

				$new_user = new CI_user();
				$new_user->id = $user_id;
				$new_user->first_name = $user_first_name;
				$new_user->last_name = $user_last_name;
				$new_user->username = $user_username;
				$new_user->save();

				$new_chat = new CI_chat(
					array(
						'id' => $chat_id,
						'type' => $chat_type,
						'title' => $chat_title,
						'username' => $chat_username,
						'first_name' => $chat_first_name,
						'last_name' => $chat_last_name
						)
				);
				$new_chat->save();

				$new_message = new CI_message(
					array(
						'message_id' => $message_id,
						'from' => $message_from,
						'date' => $message_date,
						'chat' => $message_chat,
						'text' => htmlentities(strip_tags(htmlspecialchars($message_text))),
						)
				);	
				$new_message->insert();

				$new_update = new CI_update(
					array(
						'id' => $update_id,
						'message' => $update_message
						)
				);
				$new_update->save();
				
				if (!file_exists('img/users'.$message_from.".jpg")) $tb->getUserProfilePhotos($user_id);

			}
				echo "last update id ====== ".$last_update_id;
				var_dump($updates);
		} catch (Exception $e) { 
			echo 'exception ',  $e->getMessage(), "\n";
		}
	}
        }

}

?>




