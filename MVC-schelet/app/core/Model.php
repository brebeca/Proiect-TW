<?php

class BD{
    private static $conexiune_bd = NULL;
    public static function obtine_conexiune(){
        if (is_null(self::$conexiune_bd))
        {
            self::$conexiune_bd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
        }
        return self::$conexiune_bd;
    }    
}
class Model{
 
    protected $name=null;
    protected $bd;
 

    public function __construct(){
        $this->bd= new BD;
    }


}
