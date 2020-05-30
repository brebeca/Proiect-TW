<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require_once 'post/AddUser.php';
require_once 'post/PostProduct.php';
require_once 'get/AddProduct.php';
require_once 'Delete/DeleteProduct.php';
require_once 'put/UpdatePrice.php';

class Main
{

    public function __construct(){

        $session=get_session();
        if($session==false)
        {
            http_response_code(400); // bad request
            echo json_encode(array("Success" => "false","Reason" => "Session not valid or missing."));
            return;
        }else {
            $data = json_decode(file_get_contents('php://input'), true);
            $request = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
            if (!isset($request[0]))
            {
                http_response_code(400);
                echo json_encode(array("Success" => "false","Reason" => "Unrecognized path."));
                return;
            }
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                {
                        if (strpos($request[0], 'AppInsert') === 0) {
                            AddProduct::add($session);
                            return;
                        }
                        else if(strpos($request[0],'GetMyProducts')===0){
                            GetForOwner::get($session);
                            return;
                        }
                        else if(strpos($request[0],'GetProductsByName')===0){
                            GetForOwner::get_by_name();
                            return;
                        }
                        else if(strpos($request[0],'GetProductsByCategory')===0){
                            GetForOwner::get_by_category($session);
                            return;
                        }
                        else if(strpos($request[0],'GetProductsAllCategory')===0){
                          //  echo "intra in if ";
                            GetForOwner::get_all_category($session);
                            return;
                        }
                        else {
                            http_response_code(400);
                            echo json_encode(array("Success" => "false","Reason" => "Unrecognized path."));
                            return;

                        }
                    break;
                }
                case 'POST':
                {

                   if (strpos($request[0], 'AddUser') === 0) {
                            AddUser::add($data['Session']);
                            return;
                   }
                    if (strpos($request[0], 'AddCookieUser') === 0) {
                        AddUser::addCookie($data['Cookie']);
                        return;
                    }
                    if (strpos($request[0], 'AppInsert') === 0) {
                        PostProduct::add($data,$session);
                       // echo json_encode($data);
                        return;
                    }
                    else if(strpos($request[0], 'AddProduct') === 0){
                             PostProduct::add($data,$session);
                             return;
                    }else {
                        http_response_code(400);
                        echo json_encode(array("Success" => "false","Reason" => "Unrecognized path."));
                        return;

                    }
                    break;
                }
                case 'DELETE':{
                        if (strpos($request[0], 'DeleteProduct') === 0){
                             DeleteProduct::delete($session);
                             return;
                        }else {
                            http_response_code(400);
                            echo json_encode(array("Success" => "false","Reason" => "Unrecognized path."));
                            return;

                        }
                    break;
                }
                case 'PUT':{
                        if(strpos($request[0],'UpdatePrice')===0){
                            UpdatePrice::update($session,$data);
                            return;
                        }
                        else {
                        http_response_code(400);
                        echo json_encode(array("Success" => "false","Reason" => "Unrecognized path."));
                        return;
                    }
                    break;
                }
            }
            http_response_code(400);
            echo json_encode(array("Success" => "false","Reason" => "Unrecognized path."));
        }
    }

}
 function get_session(){
    $headers = array();
    foreach($_SERVER as $key => $value) {
        if (substr($key, 0, 5) <> 'HTTP_') {
            continue;
        }
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
        $headers[$header] = $value;
    }
    if(!isset($headers['Session']))
    {
        return false;
    }
    $db = new DBManagement();
    return $db->verify_session($headers['Session']);
}



