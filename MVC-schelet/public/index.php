<?php 
define('ROOT', dirname( __DIR__ ) . DIRECTORY_SEPARATOR ) ;
define('APP', ROOT . 'app' . DIRECTORY_SEPARATOR );
define('VIEW', ROOT . 'app' .DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR);
define('MODEL', ROOT . 'app' .DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR);
define('DATA', ROOT . 'app' .DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR);
define('CORE', ROOT . 'app' .DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR);
define('CONTROLLER', ROOT . 'app' .DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR);


$modules=[ROOT, APP, CORE, CONTROLLER,DATA];

require_once ROOT. 'public' . DIRECTORY_SEPARATOR ."config.php";
require_once ROOT. 'public' . DIRECTORY_SEPARATOR ."parseXML.php";
require_once ROOT. 'public' . DIRECTORY_SEPARATOR ."simple_html_dom.php";
require_once ROOT. 'public' . DIRECTORY_SEPARATOR ."ebayClass.php";
require_once ROOT. 'public' . DIRECTORY_SEPARATOR .'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
require_once ROOT. 'public' . DIRECTORY_SEPARATOR ."googleConfig.php";

set_include_path(get_include_path().PATH_SEPARATOR.implode(PATH_SEPARATOR, $modules));
spl_autoload_register('spl_autoload', false);

new Application();
