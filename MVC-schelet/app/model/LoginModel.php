<?php
 class LoginModel extends Model{

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
    public function adminLogin($password){
        $sql = "SELECT * FROM users where password = :password and username='admin' ";
        $cerere = $this->bd->obtine_conexiune()->prepare($sql);
        $cerere->execute([
            'password'=>$password
        ]);
        $result=$cerere->fetchAll();
        if(sizeof( $result)==0)
            return false;
        return true;
    }
    public function adminChangePassword($new){
         $sql = "UPDATE users SET password = :new WHERE username='admin' ";
         $cerere =$this->bd->obtine_conexiune()->prepare($sql);
         return $cerere -> execute ([
             'new'=>$new
         ]);
     }
 }