<?php
 class contactModel extends Model{

    public function addAnonimusContact($name, $email, $telephone, $message){
        $sql = "INSERT INTO not_logged_messages ( name ,  telephone, email, text) 
                                       VALUES (:username, :telephone, :email, :text)";
        $cerere =$this->bd->obtine_conexiune()->prepare($sql);
        return $cerere -> execute ([
            'username'=>$name,
            'telephone'=>$telephone,
            'email'=>$email,
            'text'=>$message
        ]);
    }

    public function addUserContact($email, $message){
        $sql = "INSERT INTO users_messages ( email ,  message ) VALUES (:email, :mes)";
        $cerere =$this->bd->obtine_conexiune()->prepare($sql);
        return $cerere -> execute ([
            'email'=>$email,
            'mes'=>$message
        ]);
    }
}