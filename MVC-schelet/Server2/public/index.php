<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

define('ROOT', dirname( __DIR__ ) . DIRECTORY_SEPARATOR ) ;
require_once ROOT. 'core' . DIRECTORY_SEPARATOR ."DBConnection.php";
require_once ROOT. 'core' . DIRECTORY_SEPARATOR ."Main.php";
require_once ROOT. 'core' . DIRECTORY_SEPARATOR ."DBManagement.php";
require_once ROOT. 'public' . DIRECTORY_SEPARATOR ."config.php";
require_once 'vendor/autoload.php';


define('POSTS', ROOT . 'core' .DIRECTORY_SEPARATOR . 'post' . DIRECTORY_SEPARATOR);
define('GETS', ROOT . 'core' .DIRECTORY_SEPARATOR . 'get' . DIRECTORY_SEPARATOR);


$modules=[ROOT, POSTS, GETS];
require_once ROOT. 'core' . DIRECTORY_SEPARATOR ."Scrapping.php";
set_include_path(get_include_path().PATH_SEPARATOR.implode(PATH_SEPARATOR, $modules));

//new Main();
/*$db= new DBManagement();
$db->delete_temp_users();
print_r(Scrapping::detalii_altex("https://altex.ro/casti-promate-tribe-cu-fir-in-ear-microfon-rosu/cpd/CASTRIBERD/","casti"));
*/
//print_r(Scrapping::detalii_emag("https://www.emag.ro/laptop-gaming-hp-pavilion-15-ec0017nq-cu-procesor-amd-ryzentm-7-3750h-pana-la-4-00-ghz-15-6-full-hd-8gb-256gb-ssd-nvidiar-geforcer-gtx-1660-ti-max-q-6gb-free-dos-black-9qe36ea/pd/D7K5CMMBM/","calculatoare"));
//print_r(Scrapping::detaliiCel("https://www.cel.ro/telefoane-mobile/telefon-mobil-apple-iphone-7-32gb-rose-gold-pMiYwMDIr-l/","telefoane"));
print_r(Scrapping::detaliiCel("https://www.cel.ro/laptop-laptopuri/laptop-asus-vivobook-x543ma-intel-celeron-n4000-256gb-ssd-4gb-hd-endless-star-gray-pMyY3NTYqNQ-l/","calculatoare"));











