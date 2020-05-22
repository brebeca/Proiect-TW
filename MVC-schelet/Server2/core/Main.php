<?php
spl_autoload_register('spl_autoload', false);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require_once 'post/AddUser.php';
require_once 'post/PostProduct.php';
require_once 'get/AddProduct.php';
require_once 'Delete/DeleteProduct.php';

class Main
{

    public function __construct(){

        $session=get_session();
        if($session==false)
        {
            http_response_code(400); // bad request
            echo json_encode(array("Error" => "Session not valid or missing."));
            return;
        }else {
            $request = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                {
                    if (isset($request[0])) {
                        if (strpos($request[0], 'AppInsert') === 0) {
                            AddProduct::add($session);
                        }
                        else if(strpos($request[0],'GetMyProducts')===0){
                            GetForOwner::get($session);
                        }
                        else if(strpos($request[0],'GetProductsByName')===0){
                            GetForOwner::get_by_name();
                        }
                        else {
                            http_response_code(400);
                            echo json_encode(array("Error" => "Unrecognized path."));
                            return;

                        }
                    } else {
                        http_response_code(400);
                        echo json_encode(array("Error" => "Unrecognized path."));
                        return;
                    }
                    break;
                }
                case 'POST':
                {
                    $data = json_decode(file_get_contents('php://input'), true);

                    if (isset($request[0])) {
                        if (strpos($request[0], 'AddUser') === 0) {
                            AddUser::add($data['Session']);
                            return;
                        }
                         else if(strpos($request[0], 'AddProduct') === 0){
                             PostProduct::add($data,$session);
                             return;
                         }
                    } else {
                        http_response_code(400);
                        echo json_encode(array("Error" => "Unrecognized path."));
                        return;
                    }
                    break;
                }
                case 'DELETE':{
                    if(isset($request[0])){
                        if (strpos($request[0], 'DeleteProduct') === 0){
                             DeleteProduct::delete($session);
                             return;
                        }
                    } else {
                        http_response_code(400);
                        echo json_encode(array("Error" => "Unrecognized path."));
                        return;
                    }
                    break;
                }
                default:
                {
                    http_response_code(400);
                    echo json_encode(array("Error" => "Unrecognized path."));
                    return;
                    break;
                }
            }
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



