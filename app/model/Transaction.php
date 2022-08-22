<?php 

namespace app\model;

use app\model\Connection;

final class Transaction
{
    private static $conn; //Conexão ativa

    private function __construct(){}

    //$database - recebe o nome do arquivo de configuracação(INI)
    public static function open($database){
        if(empty(self::$conn))        
        {
            self::$conn = Connection::open($database);
            self::$conn->beginTransaction(); //Inicia a transação
        }
    } 

    //Método responsavel por retornar a conexão ao banco dados
    public static function get(){
        return self::$conn;
    }

    //Método Responsavel por desfazer todas as operações com query  
    public static function rollback(){
        if(self::$conn)
        {
            self::$conn->rollback(); //Desfaz todas as operações 
            self::$conn = null;
        }
    }

    //Responsavel por realizar as operações após nenhumas delas ocorrer uma excessão.
    public static function close()
    {
        if(self::$conn)
        {
            self::$conn->commit(); //aplica as operações realizadas
            self::$conn = null;
        }
    }    
}