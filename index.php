<?php

date_default_timezone_set('Asia/Ho_Chi_Minh');
// Define path to application directory

defined('BASE_PATH') || define('BASE_PATH', realpath(dirname(__FILE__)));

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', BASE_PATH . '/application');

define('TEMPLATE_PATH', APPLICATION_PATH . '/../public/templates');

// Define path to application cache
define('CACHE_PATH', APPLICATION_PATH . '/../public/data/cache/');

//defined('APPLICATION_PATH') || define('APPLICATION_PATH', BASE_PATH . '/epay');
// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
//set_include_path(implode(PATH_SEPARATOR, array(
//    realpath(APPLICATION_PATH . '/../library'),
//    get_include_path(),
//)));

require realpath(dirname(__FILE__) . '/library/library.php');

/** Zend_Application */
require_once 'Zend/Application.php';
require_once 'Nop/Application.php';

// Create application, bootstrap, and run
$application = new Nop_Application(
        APPLICATION_ENV, APPLICATION_PATH . '/configs/'
);

$application->bootstrap()->run();


