<?php
//var_dump($argv[1]);
//phpinfo();
//define('ENVIRONMENT', 'production');
define('ENVIRONMENT', 'development');
switch (ENVIRONMENT)
{
	case 'development':
		error_reporting(-1);
		ini_set('display_errors', 1);
	break;
	case 'production':
		ini_set('display_errors', 0);
	break;
	default:
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'The application environment is not set correctly.';
		exit(1); // EXIT_ERROR
}
$system_path = 'system';
$controllers_folder = 'controllers';
$views_folder = 'views';
$models_folder = 'models';
if (($_temp = realpath($system_path)) !== FALSE)
{
	$system_path = $_temp.DIRECTORY_SEPARATOR;
}
if ( ! is_dir($system_path))
{
	header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
	echo 'Your system folder path does not appear to be set correctly. Please open the following file and correct this: '.pathinfo(__FILE__, PATHINFO_BASENAME);
	exit(3);
}
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('BASEPATH', $system_path);
define('SYSDIR', basename(BASEPATH));
define('HOMEPATH', getcwd().DIRECTORY_SEPARATOR);
if (is_dir($controllers_folder))
{
	if (($_temp = realpath($controllers_folder)) !== FALSE)
	{
		$controllers_folder = $_temp;
	}
}
elseif (is_dir(BASEPATH.$controllers_folder.DIRECTORY_SEPARATOR))
{
	$controllers_folder = BASEPATH.strtr(
		trim($controllers_folder, '/\\'),
		'/\\',
		DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
	);
}
else
{
	header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
	echo 'Your controllers folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
	exit(3);
}
define('CONTROLLERSPATH', $views_folder.DIRECTORY_SEPARATOR);
if (is_dir($views_folder))
{
	if (($_temp = realpath($views_folder)) !== FALSE)
	{
		$views_folder = $_temp;
	}
}
elseif (is_dir(BASEPATH.$views_folder.DIRECTORY_SEPARATOR))
{
	$views_folder = BASEPATH.strtr(
		trim($views_folder, '/\\'),
		'/\\',
		DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
	);
}
else
{
	header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
	echo 'Your views folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
	exit(3);
}
define('VIEWSPATH', $views_folder.DIRECTORY_SEPARATOR);
if (is_dir($models_folder))
{
	if (($_temp = realpath($models_folder)) !== FALSE)
	{
		$models_folder = $_temp;
	}
}
elseif (is_dir(BASEPATH.$models_folder.DIRECTORY_SEPARATOR))
{
	$models_folder = BASEPATH.strtr(
		trim($models_folder, '/\\'),
		'/\\',
		DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
	);
}
else
{
	header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
	echo 'Your models folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
	exit(3);
}
define('MODELSPATH', $models_folder.DIRECTORY_SEPARATOR);
require_once BASEPATH.'core.php';
//print_r(array_keys(get_defined_vars()));
//print_r($_GET);
//echo HOMEPATH . "\n"; 
?>
