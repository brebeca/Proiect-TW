<?php

require_once '../public/config.php';
class DBConnection
{
    private static $conection = NULL;

    /**
     * @return \MongoDB\Database|null
     * daca nu exista o conexiunea deja se face conexiunea si se reurneaza
     * altfel se returneaza conexiunea deja stabilita
     */
    public static function obtineConexiune(){
        if (is_null(self::$conection))
        {
            $conn = new MongoDB\Client(
                'mongodb+srv://'.DB_NAME.':'.DB_PASS.'@cluster0-1dwy1.mongodb.net/test?retryWrites=true&w=majority');
            self::$conection=$conn->selectDatabase(DATA_BASE);
        }
        return self::$conection;
    }
}