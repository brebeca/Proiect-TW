<?php
include VIEW.'admin/adminView.php';
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
    public function getAllMessages(){
        $sql = "SELECT * FROM not_logged_messages  ";
        $cerere = $this->bd->obtine_conexiune()->prepare($sql);
        if( $cerere->execute()===false)
            return false;
        return $cerere->fetchAll();
    }
     public function getNewMessages(){
         $sql = "SELECT * FROM not_logged_messages where seen is null ";
         $cerere = $this->bd->obtine_conexiune()->prepare($sql);
         if( $cerere->execute()===false)
             return false;
         return $cerere->fetchAll();
     }
     public function updateToSeen(){
         $sql = "UPDATE not_logged_messages SET seen = 1 ";
         $cerere =$this->bd->obtine_conexiune()->prepare($sql);
         $cerere -> execute ();
     }
}