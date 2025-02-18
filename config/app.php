<?php
date_default_timezone_set ( "Asia/Jakarta" );
define ( 'CUR_TIME', date ( 'H:i:s' ) );
define ( 'CUR_DATE', date ( 'Y-m-d' ) );
define ( 'METHOD', 'direct' );
define ( 'ENABLE_LOGS', true );
define ( 'RELOAD_INTERVAL', 30000 );
define ( 'ENVIRONMENT', 'dev' );

switch (ENVIRONMENT) {
	case 'development' :
		error_reporting ( - 1 );
		ini_set ( 'display_errors', 1 );
		break;
  case 'dev':
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT ^ E_DEPRECATED ^ E_WARNING);
    break;
	case 'testing' :
	case 'production' :
		ini_set ( 'display_errors', 0 );
		if (version_compare ( PHP_VERSION, '5.3', '>=' )) {
			error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_USER_NOTICE & ~ E_USER_DEPRECATED );
		} else {
			error_reporting ( E_ALL & ~ E_NOTICE & ~ E_STRICT & ~ E_USER_NOTICE );
		}
		break;

	default :
		header ( 'HTTP/1.1 503 Service Unavailable.', TRUE, 503 );
		echo 'The application environment is not set correctly.';
		exit ( 1 ); // EXIT_ERROR
}

// set session config
$sid = session_id ();

// datateble dom config
$addom = 'frtip';

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
    header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
  exit(0);
}

?>