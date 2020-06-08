<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class AddUser
{
    /**
     * @param $session
     * se insereaza un nou user neteporar
     */
    public static  function add($session){
        $db=new DBManagement();
        $document=[
            'session'=>$session,
            'temp'=>false
        ];
        $db->insertUsers($document);

        http_response_code(200);
        echo json_encode(array("Success" => "true"));
        return;
    }

    /**
     * @param $cookie
     * se insereaza un user temporar si ii se adauga o data de expirare de o zi
     * se verifica cu funtia verifySession() daca exista deja in baza de date
     * se insereaza daca nu exista
     */
    public static function addCookie($cookie){
        $db=new DBManagement();
        $document=[
            'session'=>$cookie,
            'temp'=>true,
            'expire'=>time() + (86400 * 30) // o zi
        ];
        if($db->verifySession($cookie)===false)
            $db->insertUsers($document);

        http_response_code(200);
        echo json_encode(array("Success" => "true"));
        return;
    }

}