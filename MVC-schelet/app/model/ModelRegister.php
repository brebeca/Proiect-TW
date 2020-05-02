<?php
 class ModelRegister extends Model{

    public function addUser($username, $password, $email){ //create
        $sql = "INSERT INTO users (username,password, email) VALUES (:username, :password, :email)";
        $cerere =$this->bd->obtine_conexiune()->prepare($sql);
        return $cerere -> execute ([
            'username'=>$username,
            'password'=>$password,
            'email'=>$email
        ]);
    }

    public function sePoateIregistra( $username, $email){ 
        $sql = "SELECT * FROM users where username = :username and email = :email ";
        $cerere = $this->bd->obtine_conexiune()->prepare($sql);
        return  $cerere->execute([
            'username'=>$username,
            'email'=>$email
        ]);
        
    }
}

