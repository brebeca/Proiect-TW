<?php


class Update
{
    public static function updatePrice($session , $data){
       if(isset($data['id'])&&isset($data['new_price'])){
           $db = new DBManagement();
           if($db->doseProductExist($session,$data['id'])==false)
           {
               http_response_code(400); // bad request
               echo json_encode(array("Success" => "false","Reason"=>"No product with this id is owned by this account."));
               return;
           }
           else {
               $db->updatePrice($data['id'],$data['new_price']);
               http_response_code(200);
               echo json_encode(array("Success" => "true"));
               return;
           }
       }
       else{
           http_response_code(400); // bad request
           echo json_encode(array("Success" => "false","Reason"=>"No product id or new price provided."));
       }
    }

}