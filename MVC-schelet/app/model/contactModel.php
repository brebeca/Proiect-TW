<?php
 class ModelRegister extends Model{

    public function addAnonimusContact($name, $email, $telephone_nr, $message){ 
        $sql = "INSERT INTO not_logged_messages ( name , email, telephone_nr, message) VALUES (:username, :tel, :email, :mes)";
        $cerere =$this->bd->obtine_conexiune()->prepare($sql);
        return $cerere -> execute ([
            'name'=>$name,
            'tel'=>$telephone_nr,
            'email'=>$email,
            'mes'=>$message
        ]);
    }

    public function addUserContact($id, $message){ 
        $sql = "INSERT INTO users_messages ( id ,  message ) VALUES (:id, :mes)";
        $cerere =$this->bd->obtine_conexiune()->prepare($sql);
        return $cerere -> execute ([
            'id'=>$id,
            'mes'=>$message
        ]);
    }
}