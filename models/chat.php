<?php

class CI_chat extends CI_Model {

        public $id = -1;
        public $type;
        public $title;
        public $username;
        public $first_name;
        public $last_name;
        public $selected;

        public function __construct($ps = null)
        {
                parent::__construct();
		if (empty($ps))
			return $this;
		$this->id = (isset($ps['id'])) ? $ps['id'] : -1;
		$this->type = (isset($ps['type'])) ? $ps['type'] : '';
		$this->title = (isset($ps['title'])) ? $ps['title'] : '';
		$this->username = (isset($ps['username'])) ? $ps['username'] : '';
		$this->first_name = (isset($ps['first_name'])) ? $ps['first_name'] : '';
		$this->last_name = (isset($ps['last_name'])) ? $ps['last_name'] : '';
		$this->selected = (isset($ps['selected'])) ? $ps['selected'] : 0;
		return $this;
        }

	public function get_chats_update()
	{
                $q = 'select * from chat where id in (select chat from message where looked = false group by chat);';
		$result = $this->make_query($q);
		return $result;
	}

	public function save(){
		$this->insert();	
	}

        public function all()
        {
                $q = 'SELECT * FROM chat order by id desc;';
		$result = $this->make_query($q);
		return $result;
        }

	public function find($id)
	{
                $q = 'SELECT * FROM chat where id = '.$id.';';
		$result = $this->make_query($q);
		return $result;
	}

	private function insert()
	{
               	if ($mysqli = self::get_mysqli()->prepare('INSERT INTO chat (id,type,title,username,first_name,last_name,selected) VALUES (?,?,?,?,?,?,?);'))
		{
			$mysqli->bind_param('isssssi',$this->id,$this->type,$this->title,$this->username,$this->first_name,$this->last_name,$this->selected);
			$mysqli->execute();
			return true;
		} 
		else 
		{
			return false;
		}	
	}

	public function update()
        {
                if ($mysqli = self::get_mysqli()->prepare('update chat set selected=? where id=? ;'))
                {
                        $mysqli->bind_param('ii',$this->selected,$this->id);
                        $mysqli->execute();
                        return true;
                }
                else
                {
                        echo 'chat';
                        var_dump(self::get_mysqli());
                        return false;
                }
        }

}

?>
