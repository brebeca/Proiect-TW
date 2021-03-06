<?php

//require 'Model.php';
 class ModelRegister extends Model{
     /**
      * @param $username
      * @param $password
      * @param $email
      * @return bool|string
      * se inseraza in tabela users o noua inregistrare cu indormatiile noului user
      * se semnaleaza seuccesul sau esecul prin forma de return
      */
    public function addUser($username, $password, $email){ //create
        $id=uniqid();
        $sql = "INSERT INTO users (username,password, email,session) VALUES (:username, :password, :email, :id)";
        $cerere =$this->bd->obtine_conexiune()->prepare($sql);
        if( $cerere -> execute ([
            'username'=>$username,
            'password'=>$password,
            'email'=>$email,
            'id'=>$id
        ])!=false) {

            $this->registerUserSession($id);
            return $id;
        }
        else return false;
    }

     /**
      * @param $cookie
      * se trimite o cerere la al doilea server pentru a inregistra un user dupa un cookie
      */
    public function sendCookie($cookie){
        $postData = array(
            'Cookie' => $cookie
        );
        $session=md5(APP_SESSION);
        $ch = curl_init('http://localhost:'.PORT_SERVER2.'/AddCookieUser');
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Content-Type:application/json',
                'Session:'.$session
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));
        $response = curl_exec($ch);
        if($response === FALSE){
            die(curl_error($ch));
        }
        echo $response;

    }

     /**
      * @param $email
      * @return bool|mixed
      * se verifica in baza de date daca exista o inregistrare cu emailul din parametrii
      * daca exista se intoarce sesiunea utilizatorului
      * altfel se intoarce true
      */
    public function sePoateIregistra( $email){
        $sql = "SELECT * FROM users where  email = :email ";
        $cerere = $this->bd->obtine_conexiune()->prepare($sql);
        $cerere->execute([
            'email'=>$email
        ]);
        $result=$cerere->fetchAll();
        if(sizeof( $result)!=0)
            return $result[0]['session'];
        return true;
        
    }
    public function registerUserSession($id){

        $postData = array(
            'Session' => $id
        );
        $session=md5("dGs0bXJqOTh1bmRlZmluZWQxNTg4NDEzMjE4ODA4Y3c");
        $ch = curl_init('http://localhost:'.PORT_SERVER2.'/AddUsers');
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Content-Type:application/json',
                'Session:'.$session
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));
        $response = curl_exec($ch);
        if($response === FALSE){
            die(curl_error($ch));
        }
        $responseData = json_decode($response, TRUE);
        return $responseData["Success"];
    }

}

