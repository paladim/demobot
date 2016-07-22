<?php

class CI_update extends CI_Model {

        public $update_id = -1;
        public $message;

        public function __construct()
        {
                parent::__construct();
		if (empty($ps))
			return $this;
		$this->update_id = (isset($ps['update_id'])) ? $ps['update_id'] : -1;
		$this->message = (isset($ps['message'])) ? $ps['message'] : -1;
		return $this;
        }

        public function all()
        {
                $q = 'SELECT * FROM `update` order by update_id desc;';
		$this->make_query($q);
        }

	public function save() {
		$this->insert();
	}

        private function insert()
        {
                if ($mysqli = self::get_mysqli()->prepare('INSERT INTO `update` (update_id,message) VALUES (?,?)'))
		{
			$mysqli->bind_param('ii',$this->update_id,$this->message);
			$mysqli->execute();
			return true;
		}
		else
		{
			echo 'update';
			var_dump(self::get_mysqli());
			return false;
		} 
        }

	public function get_last_update_id()
	{
                if ($result = self::get_mysqli()->query('SELECT update_id FROM `update` order by update_id desc LIMIT 1;'))
		{
			$result = $result->fetch_assoc();
			return intval($result['update_id']);
		}
		else
		{
			return 0;
		}
	}

}

?>

