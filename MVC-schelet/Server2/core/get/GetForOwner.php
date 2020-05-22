<?php
header("Access-Control-Allow-Origin: http://localhost:800");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class GetForOwner
{
    public static function get($session)
    {
            $db = new DBManagement();
            $products=$db->get_products_for_owner($session);
            http_response_code(200);
            echo json_encode(array("message" => "success","products"=>$products));

    }
    public static function get_by_name(){
        if (
            isset($_GET['word'])
        )
        {  $db = new DBManagement();
           $products=$db->get_products_by_name($_GET['word']);
           http_response_code(200);
          echo json_encode(array("message" => "success","products"=>$products));
        }
        else{
            http_response_code(400);
            echo json_encode(array("Error" => "Need mode data "));
        }

    }

}