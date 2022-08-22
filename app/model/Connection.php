<?php 

namespace app\model;

final class Connection
{
    private function __construct(){}

    public static function open($dirname)
    {
        $conn = null;
        
        //Verifica se existe o arquivo de configuração (.INI)
        if(file_exists(CONFIG_DIR."/{$dirname}.ini"))
        {
            $db = parse_ini_file(CONFIG_DIR."/{$dirname}.ini");
        }
        else{
            throw new \Exception("Arquivo {$dirname}.ini não Encontrado!");
        }

        //Lê as informações contidas no arquivo
        $user = isset($db['user']) ? $db['user'] : null;
        $pass = isset($db['pass']) ? $db['pass'] : null;
        $name = isset($db['name']) ? $db['name'] : null;
        $host = isset($db['host']) ? $db['host'] : null;
        $type = isset($db['type']) ? $db['type'] : null;
        $port = isset($db['port']) ? $db['port'] : null;

        //Descobre qual tipo de Driver de banco a ser utilizado
        switch($type)
        {
            case 'pgsql':
                $port = $port ? $port : '5432';
                $conn = new \PDO("pgsql:dbname ={$name};user{$user}; password={$pass}; host={$host}; port={$port}");
                break;
            case 'mysql':
                $port = $port ? $port : '80';
                $conn = new \PDO("mysql:host ={$host};dbname={$name}",$user,$pass); //Se for utilizar a o valor da port tem que utilizar o 127.0.0.1 no host e não o localhost, se não irá ocorrer um erro
                break;
            case 'sqlite':
                $conn = new \PDO("sqlite:{$name}");
                $conn->query('PRAGMA foreign_keys = ON');
                break;
            case 'ibase':
                $conn = new \PDO("firebird:dbname={$name}",$user,$pass);
                break;
            case 'oci8':
                $conn = new \PDO("dblib:host={$host},1433;dbname={$name}",$user,$pass);
                break;
            case 'mssql':
                $conn = new \PDO("dblib:host={$host},1433;dbname={$name}",$user,$pass);
                break;
        }

        //Define para que o PDO lance exceções na ocorrência de erros
        $conn->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);


        return $conn;
    }
}   