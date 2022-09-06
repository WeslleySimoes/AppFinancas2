<?php 

    namespace lib\database;
    
    /*
        Classe responsável por retornar uma instância unica de PDO
        @autor Weslley Simões
    */
    class Db
    {
        private static $conn;

        private function __construct(){}
        private function __clone(){}

        /*Retorna instância de PDO*/
        public static function getConnection()//\PDO
        {
            if(!isset(self::$conn))
            {
                $dbConfig = parse_ini_file(CONFIG_DIR.'/db.ini');

                self::$conn = new \PDO($dbConfig['drive'].":host=".$dbConfig['host'].";dbname=".$dbConfig['dbname'],$dbConfig['root'],$dbConfig['pass']);


                self::$conn->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
            }

            return self::$conn;
        }
    }   