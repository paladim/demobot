<?php

class CI_Controller {

	private static $instance;

	public function __construct()
	{
		self::$instance =& $this;
	}
	public static function &get_instance()
	{
		return self::$instance;
	}
	public function call($current_method)
	{
		if (method_exists($this, $current_method))
		{
			$this->$current_method();	
		} 
		else
		{
			show_error('Method not found', 502);
		}

	}
	public function view($view, $vars = array(), $get_return = false)
	{
		$view_file = HOMEPATH.'views/'.$view.'.php';

		if (! file_exists($view_file))
			show_error('Unable to load the requested file: '.$view_file);

		extract($vars);
		if ($get_return)
		{
		    ob_start();
		    include $view_file;
		    return ob_get_clean();
		}
		include($view_file);	
	}
}


?>
