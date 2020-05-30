<?php

define('ROOT', 'C:\Users\iRebeca\PhpstormProjects\Proiect-TW5\MVC-schelet' . DIRECTORY_SEPARATOR ) ;
define('APP', ROOT . 'app' . DIRECTORY_SEPARATOR );
define('VIEW', ROOT . 'app' .DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR);
define('MODEL', ROOT . 'app' .DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR);
define('DATA', ROOT . 'public' .DIRECTORY_SEPARATOR );
define('CORE', ROOT . 'app' .DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR);
define('CONTROLLER', ROOT . 'app' .DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR);

$modules=[ROOT, APP, CORE, CONTROLLER,DATA];

set_include_path(get_include_path().PATH_SEPARATOR.implode(PATH_SEPARATOR, $modules));
spl_autoload_register('spl_autoload', false);
define ('DB_HOST',  'localhost');
define ('DB_NAME',  'app');
define ('DB_NAME2',  'produse_emag');
define ('DB_NAME3',  'produse_altex');
define ('DB_USER2',  'root');
define ('DB_PASS2',  '');
define ('DB_USER',  'app');
define ('DB_PASS',  'app');
define('APP_ID','birleanu-CompIT-PRD-4c545f399-aad3d24d');
