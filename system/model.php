<?php
class CI_Model {
	private static $_mysqli = 0;
	private static $_inc_class = 0;
	public function __construct()
	{
		self::$_inc_class += 1;
		if (self::$_mysqli === 0) 
		{
			self::$_mysqli = new mysqli("localhost", "telegram", "password", "telegram");
			if (mysqli_connect_errno()) {
			    printf("Mysql error: %s\n", mysqli_connect_error());
			    exit();
			}
		}
	}

	public function __destruct()
	{
		self::$_inc_class -= 1;
		if (self::$_inc_class === 0)
		{
			self::$_mysqli->close();
			self::$_mysqli = 0;
		}
	}


	public static function get_mysqli() 
	{
		return self::$_mysqli;
	}

	public function make_query($q){
                $rows = array();
		$class_name = get_class($this);
                $result = self::get_mysqli()->query($q);
                if (!$result)
                {
			echo var_dump($result);
                        printf("Query failed: %s\n", $result->error);
                        exit;
                }

                while($row = $result->fetch_assoc())
                {
                        $rows[] = new $class_name($row);
                }
                $result->close();
                return $rows;
	}



}
?>
