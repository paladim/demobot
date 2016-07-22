<?php

class CI_user extends CI_Model {

	public $id = -1;
	public $username;
	public $first_name;
	public $last_name;

	public function __construct()
	{
		parent::__construct();
		if (empty($ps))
			return $this;
		$this->id = (isset($ps['id'])) ? $ps['id'] : -1;
		$this->username = (isset($ps['username'])) ? $ps['username'] : '';
		$this->first_name = (isset($ps['first_name'])) ? $ps['first_name'] : '';
		$this->last_name = (isset($ps['last_name'])) ? $ps['last_name'] : '';
		return $this;
	}
	
	public function all()
	{
		$q = 'SELECT * FROM user order by id desc;';
		$this->make_query($q);
	}

	public function save() {
		$this->insert();
	}

        private function insert()
        {
                if ($mysqli = self::get_mysqli()->prepare('INSERT INTO user (id,first_name,last_name,username) VALUES (?,?,?,?);') )
		{
			$mysqli->bind_param('isss',$this->id,$this->first_name,$this->last_name,$this->username);
			$mysqli->execute();
			return true;
		} else 
		{ 
			echo "user";
			var_dump(self::get_mysqli());
			return false;
		}
        }

}

?>
