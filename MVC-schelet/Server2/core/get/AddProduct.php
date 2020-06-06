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
            $document = null;
            if ($_GET['source'] == 'Ebay')
                $document = ebayDocument($session);
            else if ($_GET['source'] == 'emag')
                $document = emagDocument($session);
            $db->insertProducts($document);

            http_response_code(200);
            echo json_encode(array("Success" => "true"));
        } else {
            http_response_code(400); // bad request
            echo json_encode(array("Success" => "false", "Reason" => "Need more data"));
        }
    }
}
function ebayDocument($session){
    $key_word = "no_key_word";
    if (isset($_GET['key_word']))
        $key_word = $_GET['key_word'];
    $details=null;
    $price=1;
    $rating=0;
    $category=$_GET['category'];
    $html = file_get_html($_GET['link']);
    if (isset($html->find("h2.display-price", 0)->innertext)) {
        $category=Scrapping::ebayCategory($_GET['link']);
        $details=Scrapping::detaliiEbay($_GET['link'],$category);
        $price_aux=explode('$', $html->find("h2.display-price", 0)->innertext)[1];
        $price = round(floatval(str_replace(',','',$price_aux)),2);
        $rating = floatval(explode(' ', $html->find("span.star--rating", 0)->getAttribute("aria-label"))[0]);
    }
    $document = ['category' => $category,
        'key_word' => $key_word,
        'title' => $_GET['title'],
        'link' => $_GET['link'],
        'img_link' => $_GET['imglink'],
        'source' => $_GET['source'],
        'details' => $details,
        'owner' => $session,
        'price'=> round($price*4.44,2),
        'id'=>1000,
        'rating'=>$rating
    ];
    return $document;
}

function emagDocument($session){

    $price=-1;
    $rating=0;
    $details=Scrapping::detaliiEmag($_GET['link'],$_GET['category']);

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