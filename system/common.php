<?php

if ( ! function_exists('load_class'))
{
	function &load_class($class, $directory = 'controllers', $param = NULL)
	{
		static $_classes = array();
		if (isset($_classes[$class]))
		{
			return $_classes[$class];
		}
		$name = FALSE;
		if (file_exists(HOMEPATH.$directory.'/'.$class.'.php'))
		{
			$name = 'CI_'.$class;
			if (class_exists($name, FALSE) === FALSE)
			{
				require_once(HOMEPATH.$directory.'/'.$class.'.php');
			}
		}
		if ($name === FALSE)
		{
			set_status_header(503);
			echo 'Unable to locate the specified class: '.$class.'.php';
			exit(5);
		}
		is_loaded($class);
		$_classes[$class] = isset($param)
			? new $name($param)
			: new $name();
		return $_classes[$class];
	}
}


if ( ! function_exists('set_status_header'))
{
	function set_status_header($code = 200, $text = '')
	{
		if (empty($code) OR ! is_numeric($code))
		{
			show_error('Status codes must be numeric', 500);
		}
		if (empty($text))
		{
			is_int($code) OR $code = (int) $code;
			$stati = array(
				100	=> 'Continue',
				101	=> 'Switching Protocols',
				200	=> 'OK',
				201	=> 'Created',
				202	=> 'Accepted',
				203	=> 'Non-Authoritative Information',
				204	=> 'No Content',
				205	=> 'Reset Content',
				206	=> 'Partial Content',
				300	=> 'Multiple Choices',
				301	=> 'Moved Permanently',
				302	=> 'Found',
				303	=> 'See Other',
				304	=> 'Not Modified',
				305	=> 'Use Proxy',
				307	=> 'Temporary Redirect',
				400	=> 'Bad Request',
				401	=> 'Unauthorized',
				402	=> 'Payment Required',
				403	=> 'Forbidden',
				404	=> 'Not Found',
				405	=> 'Method Not Allowed',
				406	=> 'Not Acceptable',
				407	=> 'Proxy Authentication Required',
				408	=> 'Request Timeout',
				409	=> 'Conflict',
				410	=> 'Gone',
				411	=> 'Length Required',
				412	=> 'Precondition Failed',
				413	=> 'Request Entity Too Large',
				414	=> 'Request-URI Too Long',
				415	=> 'Unsupported Media Type',
				416	=> 'Requested Range Not Satisfiable',
				417	=> 'Expectation Failed',
				422	=> 'Unprocessable Entity',
				500	=> 'Internal Server Error',
				501	=> 'Not Implemented',
				502	=> 'Bad Gateway',
				503	=> 'Service Unavailable',
				504	=> 'Gateway Timeout',
				505	=> 'HTTP Version Not Supported'
			);
			if (isset($stati[$code]))
			{
				$text = $stati[$code];
			}
			else
			{
				show_error('No status text available. Please check your status code number or supply your own message text.', 500);
			}
		}
		if (strpos(PHP_SAPI, 'cgi') === 0)
		{
			header('Status: '.$code.' '.$text, TRUE);
		}
		else
		{
			$server_protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
			header($server_protocol.' '.$code.' '.$text, TRUE, $code);
		}
	}
}




if ( ! function_exists('show_error'))
{
	function show_error($message, $status_code = 500, $heading = 'An Error Was Encountered')
	{
		$status_code = abs($status_code);
		if ($status_code < 100)
		{
			$exit_status = $status_code + 9;
			if ($exit_status > 125) 
			{
				$exit_status = 1;
			}
			$status_code = 500;
		}
		else
		{
			$exit_status = 1;
		}
		set_status_header($status_code);
		echo $heading.", ".$message;
		exit($exit_status);
	}
}





if ( ! function_exists('is_loaded'))
{
	function &is_loaded($class = '')
	{
		static $_is_loaded = array();
		if ($class !== '')
		{
			$_is_loaded[strtolower($class)] = $class;
		}
		return $_is_loaded;
	}
}



?>
