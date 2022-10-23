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
        $sql = "SELECT * FROM ".self::TABLENAME." WHERE email = '{$email}'";

        if($conn = Transaction::get())
        {
            if($result = $conn->query($sql))
            {
                //retorna os dados em forma de objeto
                $object = $result->fetchObject(get_called_class());
            }

            if($object)
            {
                if(password_verify($senha,$object->senha))
                {
                    return $object;
                }
                else{
                    return NULL;
                }
            }

            return NULL;
        }
        
        throw new \Exception('Não há transação aberta!');
    }

}