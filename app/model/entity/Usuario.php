<?php

namespace app\model\entity;

use app\model\Record;
use app\model\Transaction;

class Usuario extends Record
{
    const TABLENAME = 'usuario';
    const TABLE_PK  = 'idUsuario';

    // Método responsável por verificar se existe um usuário cadastrado
    public static function checarUsuario($email,$senha): Usuario|NULL
    {
        $sql = "SELECT * FROM ".self::TABLENAME." WHERE email = '{$email}' and senha = '{$senha}'";

        if($conn = Transaction::get())
        {
            if($result = $conn->query($sql))
            {
                //retorna os dados em forma de objeto
                $object = $result->fetchObject(get_called_class());
            }

            return $object ?? NULL;
        }
        
        throw new \Exception('Não há transação aberta!');
    }

}