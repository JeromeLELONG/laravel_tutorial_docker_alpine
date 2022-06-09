<?php


 //   error_reporting(0);
 //   ini_set("display_errors", 1);
 error_reporting(E_ALL ^ E_USER_DEPRECATED);
 ini_set("display_errors", 1);


// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

/*if(strpos($_SERVER["HTTP_HOST"],"dev") || $_SERVER["HTTP_HOST"]=="localhost"){
    //echo "Environnement de développement";
    define('APPLICATION_ENV','development');
}else if(strpos($_SERVER["HTTP_HOST"],"int"))
{
    define('APPLICATION_ENV','integration');
}else{ define('APPLICATION_ENV','production');}
*/
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));


if(APPLICATION_ENV!='local')
    set_include_path(get_include_path().':/var/www/html/applications/siscolidentifiant/vendor/zendframework/zendframework1/library');

date_default_timezone_set('Europe/Paris');
// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';
// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$front = Zend_Controller_Front::getInstance();
$front->setControllerDirectory("../application/controllers");
$application->bootstrap()
            ->run();
            
            
                