<?php

class CI_message extends CI_Model {

        public $message_id = -1;
        public $from;
        public $date;
        public $chat;
        public $text;
        public $looked;

        public function __construct($ps = null)
        {
                parent::__construct();
		if (empty($ps))
			return $this;
		$this->message_id = (isset($ps['message_id'])) ? $ps['message_id'] : -1;
		$this->from = (isset($ps['from'])) ? $ps['from'] : -1;
		$this->date = (isset($ps['date'])) ? $ps['date'] : '';
		$this->chat = (isset($ps['chat'])) ? $ps['chat'] : -1;
		$this->text = (isset($ps['text'])) ? $ps['text'] : '';
		$this->looked = (isset($ps['looked'])) ? $ps['looked'] : false;
		return $this;
        }
	
	public function find_by($wheres, $limit = null, $offset = null)
	{
		$q = 'select * from message ';
		$n = count($wheres);
		$i = 0;
		foreach ($wheres as $key => $value) {
			$i += 1;
			if ($i === 1)
				$q = $q.'where ';
			$q = $q.$key.$value.' ';
			if (($i+1) <= $n)
				$q = $q.'and ';
			if ($i === $n){
				$q = $q.'order by message_id desc ';	
				if (empty($limit)){
					$q = $q.';';	
				} else {
					$q = $q.'limit '.$limit;	
				}
				if (empty($offset)){
					$q = $q.';';	
				} else {
					$q = $q.','.$offset.' ;';	
				}
			}
		}
		$result = $this->make_query($q);
		return $result;
	}

        public function all()
        {
                $q = 'SELECT * FROM message order by message_id desc;';
		$this->make_query($q);
        }

	public function save(){
		$this->update();
	}

        private function update()
        {
		if ($mysqli = self::get_mysqli()->prepare('update message set looked=? where message_id=? ;'))
		{
			$mysqli->bind_param('ii',$this->looked,$this->message_id);
			$mysqli->execute();
			return true;
		}  
		else
		{
			echo 'message';
			var_dump(self::get_mysqli());
			return false;
		}
        }

        public function insert()
        {
		if ($mysqli = self::get_mysqli()->prepare('INSERT INTO message VALUES (?,?,?,?,?,?)'))
		{
			$mysqli->bind_param('iisiss',$this->message_id,$this->from,$this->date,$this->chat,$this->text,$this->looked);
			$mysqli->execute();
			return true;
		}  
		else
		{
			echo 'message';
			var_dump(self::get_mysqli());
			return false;
		}
        }
}

?>
