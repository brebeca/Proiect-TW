<?php
 class loginModel extends Model{

    public function performLogin( $password, $email){
        $sql = "SELECT * FROM users where password = :password and email = :email ";
        $cerere = $this->bd->obtine_conexiune()->prepare($sql);
        $cerere->execute([
            'password'=>$password,
            'email'=>$email
        ]);
        $result=$cerere->fetchAll();
       if(sizeof( $result)==0)
         return null;
        return $result;
    }
 }