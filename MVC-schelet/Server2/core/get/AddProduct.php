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
            && isset($_GET['category']) && isset($_GET['source'])
        ) {

            $db = new DBManagement();
            $document=null;
            if($_GET['source']=='Ebay')
                $document=ebay_document($session);
            else if($_GET['source']=='emag')
                $document=emag_document($session);
            $db->insert_products($document);

            http_response_code(200);
            echo json_encode(array("Success" => "true"));
        } else {

            http_response_code(400); // bad request
            echo json_encode(array("Success" => "false","Reason" => "Need more data"));

        }
    }
}
function ebay_document($session){
    $key_word = "no_key_word";
    if (isset($_GET['key_word']))
        $key_word = $_GET['key_word'];
    $details=null;
    if(isset($_GET['details'])) {
        $details = trim($_GET['details'],'|');
        $details=explode('|', $details);
    }
    $price=-1;
    $rating=0;
    $h = file_get_html($_GET['link']);
    if (isset($h->find("h2.display-price", 0)->innertext))
        $price = floatval(explode('$', $h->find("h2.display-price", 0)->innertext)[1]);
    $rating = floatval(explode(' ',$h->find("span.star--rating", 0)->getAttribute("aria-label"))[0]);

    $document = ['category' => $_GET['category'],
        'key_word' => $key_word,
        'title' => $_GET['title'],
        'link' => $_GET['link'],
        'img_link' => $_GET['imglink'],
        'source' => $_GET['source'],
        'details' => $details,
        'owner' => $session,
        'price'=> $price*4.44,
        'id'=>1000,
        'rating'=>$rating
    ];
    return $document;
}
function emag_document($session){

    $price=-1;
    $rating=0;
    $details=Scrapping::detalii_emag($_GET['link']);

    $document = ['category' => $_GET['category'],
        'key_word' =>'-',
        'title' => $_GET['title'],
        'link' => $_GET['link'],
        'img_link' => $_GET['imglink'],
        'source' => $_GET['source'],
        'details' => $details,
        'owner' => $session,
        'price'=> $price,
        'id'=>1000,
        'rating'=>$rating
    ];
    return $document;
}