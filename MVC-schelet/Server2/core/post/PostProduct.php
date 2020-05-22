<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class PostProduct
{
    public static  function add($data,$session){
        if(isset($data['category'])&&isset($data['title'])&&isset($data['link'])&&isset($data['img_link'])
            &&isset($data['details'])&&isset($data['price'])) {
            $db = new DBManagement();
            $document = [
                'category' => $data['category'],
                'title' => $data['title'],
                'link' => $data['link'],
                'img_link' => $data['img_link'],
                'source' => 'outside',
                'details' => $data['details'],
                'price' => $data['price'],
                'owner' => $session,
                'id' => 1000
            ];
            $id=$db->insert_products($document);

            http_response_code(200);
            echo json_encode(array("message" => "Success","Product ID"=>$id));
        }
        else{
            http_response_code(400); // bad request
            echo json_encode(array("Error" => "Need more data . GET"));

        }
    }

}