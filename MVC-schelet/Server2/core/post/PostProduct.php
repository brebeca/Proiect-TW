<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class PostProduct
{
    public static  function add($data,$session){
        if(isset($data['category'])&&isset($data['title'])&&isset($data['link'])
            &&isset($data['img_link'])&&isset($data['price'])) {

            $db = new DBManagement();

            if(!isset($data['source'])) {
                $source = "outside";
                $document=getDocument($session,$data,$source);
            }
            else {
                    $document=getDocument($session,$data,$data['source']);
            }
            $id=$db->insertProducts($document);

            http_response_code(200);
            echo json_encode(array("Success" => "true","Product ID"=>$id));
        }
        else{
            http_response_code(400); // bad request
            echo json_encode(array("Success" => "true","Reason" => "Need more data"));

        }
    }


}
function getDocument($session,$data,$source){
   $details=array();
    if($source=='emag')
        $details=Scrapping::detaliiEmag($data['link'],$data['category']);
    else if($source=='altex'){
        $details=Scrapping::detaliiAltex($data['link'],$data['category']);
    }
    else if($source=='cel'){
        $details=Scrapping::detaliiCel($data['link'],$data['category']);
    }
    else if($source=='outside'){
        $details=$details['details'];
    }
    if(isset($data['rating']))
        $rating=$data['rating'];
    else
        $rating=0;
    $document=[
        'category' => $data['category'],
        'title' => $data['title'],
        'link' => $data['link'],
        'img_link' => $data['img_link'],
        'source' => $source,
        'details' => $details,
        'price' => $data['price'],
        'owner' => $session,
        'id' => 1000,
        'rating'=>$rating
    ];
    return $document;
}