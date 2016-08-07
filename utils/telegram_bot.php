<?php
class CI_telegram_bot
{
	private $token = "238947509:AAGt1MCtkShbL8n4PVNKNOw8wSEPOLXN7YY";
	
	public function __construct()
	{
		if (is_null($this->token))
		{
			print('Required "token" key not supplied');
		}
		$this->baseURLfile = 'https://api.telegram.org/file/bot' . $this->token . "/";
		$this->baseURL = 'https://api.telegram.org/bot' . $this->token . "/";
	}

	public function getMe()
	{
		return $this->sendRequest('getMe', array());
	}

	public function pollUpdates($offset = null, $timeout = null, $limit = null)
	{
		$params = compact('offset', 'limit', 'timeout');
		return $this->sendRequest('getUpdates', $params);
	}

	public function sendMessage($chat_id, $text, $disable_web_page_preview = false, $reply_to_message_id = null, $reply_markup = null)
	{
		$params = compact('chat_id', 'text', 'disable_web_page_preview', 'reply_to_message_id', 'reply_markup');
		return $this->sendRequest('sendMessage', $params);
	}

	public function getUserProfilePhotos($user_id, $offset = null, $limit = null)
	{
		$timeout = 0;
		$params = compact('user_id', 'timeout');
		$return = $this->sendRequest('getUserProfilePhotos', $params);
		if ($return['ok'] == true and $return['result']['total_count'] >= 1) {
			$file_id = $return['result']['photos'][0][0]['file_id'];
			$file_path = $return['result']['photos'][0][0]['file_path'];
			$params = compact('file_id');
			$return = $this->sendRequest('getFile', $params);
			$file_path = $return['result']['file_path'];
			$file_content =	file_get_contents($this->baseURLfile.$file_path);
			$fp = fopen("img/users/".$user_id.".jpg", "w");
			fwrite($fp, $file_content);
			fclose($fp);
		}
	}

	private function sendRequest($method, $params)
	{
		return json_decode(file_get_contents($this->baseURL . $method . '?' . http_build_query($params)), true);
	}
}
?>
