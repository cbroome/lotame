<?php


# necessary requires

define( "DIR_BASE", realpath("../") );
define( "DIR_LIB", DIR_BASE . "/lib" );
define( "DIR_WWW", DIR_BASE . "/www" );
define( "DIR_APP", DIR_WWW . "/app" );

require DIR_LIB . "/autoloader.php";

# determine proper controller

$page   = isset($_GET['p']) ? $_GET['p'] : 'default';
$action = isset($_GET['a']) ? $_GET['a'] : 'default';




# $controller = new \Controller\Default_Controller;

$classname = "\Controller\\" . ucwords($page) . "_Controller";  
$method = $action . "_action";
$controller = new $classname;
$controller->$method();


