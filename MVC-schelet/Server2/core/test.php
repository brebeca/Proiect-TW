<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


//$data = json_decode(file_get_contents("php://input"));




    //$file = 'newFile.txt';
    // file_put_contents($file, $_GET['details']);
    $db=new DBManagement();
   $result= $db->insert(null);
    http_response_code(200);
    echo json_encode(array("message" => "Contact added.","result"=>$result));
