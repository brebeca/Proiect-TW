<?php

header("Access-Control-Allow-Origin: http://localhost:800");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
class  AddProduct
{
    public static function add($session)
    {
        if (
            isset($_GET['title'])
           && isset($_GET['link']) && isset($_GET['imglink'])
            && isset($_GET['details']) && isset($_GET['category']) && isset($_GET['source'])
        ) {

            $db = new DBManagement();
            $key_word = "no_key_word";
            if (isset($_GET['key_word']))
                $key_word = $_GET['key_word'];
            $details = trim($_GET['details'],'|');
            $details=explode('|', $details);
            $price=-1;
            if (isset($_GET['price']))
                $price=$_GET['price'];

            $document = ['category' => $_GET['category'],
                'key_word' => $key_word,
                'title' => $_GET['title'],
                'link' => $_GET['link'],
                'img_link' => $_GET['imglink'],
                'source' => $_GET['source'],
                'details' => $details,
                'owner' => $session,
                'price'=> $price,
                'id'=>1000
            ];
            $db->insert_products($document);

            http_response_code(200);
            echo json_encode(array("message" => "Product added.GET"));
        } else {

            http_response_code(400); // bad request
            echo json_encode(array("Error" => "Need more data . GET"));

        }
    }
}