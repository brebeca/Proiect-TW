<?php

require_once '../public/config.php';
class DBConnection
{
    private static $conexiune_bd = NULL;
    public static function obtine_conexiune(){
        if (is_null(self::$conexiune_bd))
        {
            $conn = new MongoDB\Client(
                'mongodb+srv://'.DB_NAME.':'.DB_PASS.'@cluster0-1dwy1.mongodb.net/test?retryWrites=true&w=majority');
            self::$conexiune_bd=$conn->selectDatabase(DATA_BASE);

        }
        return self::$conexiune_bd;
    }
}