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

new Main();
/*$db= new DBManagement();
$db->delete_temp_users();
print_r(Scrapping::detalii_altex("https://altex.ro/casti-promate-tribe-cu-fir-in-ear-microfon-rosu/cpd/CASTRIBERD/","casti"));
print_r(Scrapping::detalii_emag("https://www.emag.ro/casti-audio-in-ear-skullcandy-smokin-buds-2-wireless-black-red-s2pghw-521/pd/D35M03BBM/?ref=other_customers_viewed_go_3_2&provider=rec&recid=rec_52_32d11851b67005b4713c24c1a9e0c7dbcfabeb130d923359c1b6c215329799a5_1591195294&scenario_ID=52","casti"));

*/










