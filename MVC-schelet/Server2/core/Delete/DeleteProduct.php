<?php


class DeleteProduct
{

    /**
     * @param $session
     * verifica daca a fost trimis un id pentru recuoasterea pordusului
     * se verifica daca produsul exista in lista de comparare au utilizatorului cu id-ul $session in baza de date
     * daca nu exista se trmite mesaj de eroare
     * daca estista apeleaza funtia de stergere si inoarce mesaj de succes
     */
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