<?php

require_once 'vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('545653588207-4q7had5ils4r0740urlghbu9sbboq9li.apps.googleusercontent.com');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('mR3cG5TjKScvmF2fshEeVQVy');

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri('http://localhost:800/');

//
$google_client->addScope('email');

$google_client->addScope('profile');

//start session on web page

