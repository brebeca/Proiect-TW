<?php
header("Access-Control-Allow-Origin: http://localhost:800");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class GetProducts
{
    public static function getMyProducts($session)
    {
            $db = new DBManagement();
            $products=$db->getProductsForOwner($session);
            http_response_code(200);
            echo json_encode(array("message" => "success","products"=>$products));

    }
    public static function getByName(){
        if (
            isset($_GET['word'])
        )
        {  $db = new DBManagement();
           $products=$db->getProductsByName($_GET['word']);
           http_response_code(200);
          echo json_encode(array("Success" => "true","products"=>$products));
        }
        else{
            http_response_code(400);
            echo json_encode(array("Success" => "true","Reason" => "Need mode data "));
        }

    }

    public static function getByCategory($session)
    {
        if (
        isset($_GET['category'])
        )
        {
            $db = new DBManagement();
            $products=$db->getProductsByBategory($_GET['category'],$session);
            http_response_code(200);
            echo json_encode(array("Success" => "true","products"=>$products));
        }
        else{
            http_response_code(400);
            echo json_encode(array("Success" => "true","Reason" => "Need mode data "));
        }
    }

    public static function getAllCategory($session)
    {
            $db = new DBManagement();
            $tel=$db->getProductsByBategory('telefoane',$session);
            $elect=$db->getProductsByBategory('electrocasnice',$session);
            $calc=$db->getProductsByBategory('calculatoare',$session);
            $div=$db->getProductsByBategory('search',$session);
            $imbr=$db->getProductsByBategory('imbracaminte',$session);
            $casti=$db->getProductsByBategory('casti',$session);
            $produse=array("electrocasnice"=>$elect,"telefoane"=>$tel,"calculatoare"=>$calc,
                "diverse"=>$div,"imbracaminte"=>$imbr,"casti"=>$casti);
            http_response_code(200);
            echo json_encode(array("Success" => "true","produse"=>$produse));
    }

}