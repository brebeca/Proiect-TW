<?php
  $ini = parse_ini_file('app.ini');
  define ('DB_HOST',  $ini['db_host']);
  define ('DB_NAME', $ini['db_name'] );
  define ('DB_NAME2',  $ini['db_name2']);
  define ('DB_USER2',  $ini['db_user2']);
  define ('DB_PASS2',  '');
  define ('DB_NAME3',  $ini['db_name3']);
  define ('DB_USER3',  $ini['db_user3']);
  define ('DB_PASS3',  '');
  define ('DB_USER',  $ini['db_user']);
  define ('DB_PASS',  $ini['db_pass']);
  define('APP_ID',$ini['app_id']);
  define('PORT_LOCAL_SERVER',$ini['port1']);
  define('PORT_SERVER2',$ini['port2']);
  define('APP_SESSION',$ini['app_session']);
  if($ini['sursa1_activ']==1){
      define('SURSA1',$ini['sursa1']);
  }
  if($ini['sursa2_activ']==1){
      define('SURSA2',$ini['sursa2']);
  }
