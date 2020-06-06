<?php


class DeleteProduct
{

    public static function delete($session){

        if(isset($_GET['id'])){
            $db = new DBManagement();
            if($db->doseProductExist($session,$_GET['id'])==false)
            {
                http_response_code(400); // bad request
                echo json_encode(array("Success" => "false","Reason"=>"No product with this id is owned by this account."));
                return;
            }
            else {
                $db->deleteProduct($_GET['id']);
                http_response_code(200);
                echo json_encode(array("Success" => "true"));
                return;
            }
        }
        else{
            http_response_code(400); // bad request
            echo json_encode(array("Success" => "false","Reason"=>"No id provided."));
        }

    }
}