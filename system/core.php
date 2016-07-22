<?php
require_once(BASEPATH.'common.php');
require_once(BASEPATH.'controller.php');
require_once(BASEPATH.'model.php');

$default_controller = 'default_controller';
$default_method = 'index';

if ($_GET)
{
	if (array_key_exists('c',$_GET))
		$default_controller = $_GET['c'];
	if (array_key_exists('m',$_GET))
		$default_method = $_GET['m'];
}
else
{
	if ($argv[1] and $argv[2])
	{
		$default_controller = $argv[1];
		$default_method = $argv[2];
	}
}

$CNT =& load_class($default_controller, 'controllers');
$CNT->call($default_method);
?>
