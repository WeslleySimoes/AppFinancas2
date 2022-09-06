<?php 

    namespace app\model;


    class Anotacao
    {
        private $conn;

        public function __construct(\PDO $db)
        {   
            $this->conn = $db;    
        }

        public function getAll():array
        {
            $resultado = $this->conn->query('SELECT * FROM anotacao');
            return $resultado->fetchAll(\PDO::FETCH_ASSOC); //FETCH_OBJ
        }
    }