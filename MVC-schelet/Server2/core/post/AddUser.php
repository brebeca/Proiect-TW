<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class AddUser
{
    public static  function add($session){
        $db=new DBManagement();
        $document=[
            'session'=>$session,
            'temp'=>false
        ];
        $db->insert_users($document);

        http_response_code(200);
        echo json_encode(array("Success" => "true"));
        return;
    }
    public static function addCookie($cookie){
        $db=new DBManagement();
        $document=[
            'session'=>$cookie,
            'temp'=>true,
            'expire'=>time() + (86400 * 30) // o zi
        ];
        if($db->verify_session($cookie)===false)
            $db->insert_users($document);

        http_response_code(200);
        echo json_encode(array("Success" => "true"));
        return;
    }

}