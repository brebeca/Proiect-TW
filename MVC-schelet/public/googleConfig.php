<?php

require_once 'vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();
$google_client->setClientId(GOOGLE_CLIENT);
$google_client->setClientSecret(GOOGLE_SECRET);
$google_client->setRedirectUri('http://localhost:800/signUp/google');
$google_client->addScope('email');
$google_client->addScope('profile');

