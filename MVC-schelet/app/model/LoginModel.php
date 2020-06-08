<?php
 class LoginModel extends Model{
     /**
      * @param $password
      * @param $email
      * @return array|null
      * se catua in tabelul de useri userul cu emailul si parola din parametri
      * daca nu exsita nicio inregistrare se semnaleaza ca nu se poate inregistra returnand null
      * altfel se returneaza inregistratea pentru a fi prelucrate datle in controller
      */
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

     /**
      * @param $password
      * @return bool
      * se cauta in tabelul de useri userul admin care are paorla trimisa
      * daca nu exista nicio inregistrare se semnaleaza faptul ca nu se poate loga prin returnarea de false
      * altfel se returneaza true
      */
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

     /**
      * @param $new
      * @return bool
      * se updateaza parola pentru userul admin
      */
    public function adminChangePassword($new){
         $sql = "UPDATE users SET password = :new WHERE username='admin' ";
         $cerere =$this->bd->obtine_conexiune()->prepare($sql);
         return $cerere -> execute ([
             'new'=>$new
         ]);
     }
 }