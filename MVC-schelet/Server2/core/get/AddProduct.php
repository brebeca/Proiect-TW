<?php

header("Access-Control-Allow-Origin: http://localhost:800");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require_once 'simple_html_dom.php';
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
            else {
                $h=file_get_html($_GET['link']);
                if(isset($h->find("h2.display-price",0)->innertext))
                $price=intval(explode('$',$h->find("h2.display-price",0)->innertext)[1]);
                }

            $document = ['category' => $_GET['category'],
                'key_word' => $key_word,
                'title' => $_GET['title'],
                'link' => $_GET['link'],
                'img_link' => $_GET['imglink'],
                'source' => $_GET['source'],
                'details' => $details,
                'owner' => $session,
                'price'=> $price*4.44,
                'id'=>1000
            ];
            $db->insert_products($document);

            http_response_code(200);
            echo json_encode(array("Success" => "true"));
        } else {

            http_response_code(400); // bad request
            echo json_encode(array("Success" => "true","Reason" => "Need more data"));

        }
    }
}