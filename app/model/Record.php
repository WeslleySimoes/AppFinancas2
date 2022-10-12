<?php 
namespace app\model;

abstract class Record
{
    protected array $data; //Contém os dados do objeto

    public function __construct($id = null){
        if($id) //se o ID for Informado
        { 
            //Carrega o objeto Correspondente
            $object = $this->load($id);
            
            if($object)
            {
                $this->fromArray($object->toArray());
            }
        }
    }

    // public function __clone()
    // {
    //     unset($this->data[constant('self::TABLE_PK')]);
    // }

    public function __set($prop,$value)
    {
        if(method_exists($this,'set_'.$prop))
        {
        //executa o método set_<propriedade>
        call_user_func(array($this,'set_'.$prop),$value);
        } 
        else
        {
            if($value == null)
            {
                unset($this->data[$prop]);        
            }
            else{
                $this->data[$prop] = $value; //Atribui o valor da propriedade
            }
        }
    }

    public function __get($prop)
    {
        if(method_exists($this,'get_'.$prop))
        {
        //executa o método set_<propriedade>
        return call_user_func(array($this,'get_'.$prop));
        } 
        else
        {
            if(isset($this->data[$prop]))
            {
                return $this->data[$prop];
            }
        }
    }

    public function __isset($prop)
    {
        return isset($this->data[$prop]);
    }

    //Responsável por atribuir um array para a propriedade $data;
    public function fromArray($data)
    {
        $this->data = $data;
    }

    //Responsável por retorna a propriedade array $data;
    public function toArray()
    {
        return $this->data;
    }

    //Retorna o noma da tabela do banco que estamos manipulando, o nome desta tabela está armazenado na constantes TABLENAME de cada classe
    public function getEntity()
    {
        //Retorna o nome da classe atual
        $class = get_class($this);

        //Retorna o valor contido na constante TABLENAME
        return constant("{$class}::TABLENAME");
    }

    //Retorna o nome da coluna da chave Primária
    public function getPK()
    {
        //Retorna o nome da classe atual
        $class = get_class($this);

        //Retorna o valor contido na constante TABLENAME
        return constant("{$class}::TABLE_PK");
    }

    public function store()
    {   
        $prepared = $this->prepare($this->data);

        //Verificar se a classe contem o atributo id e se ele contém valorm ou se ele existe no banco de dados
        if(empty($this->data[$this->getPK()]) || !$this->load($this->data[$this->getPK()]))    
        {
            //Incrementa o ID
            if(empty($this->data[$this->getPK()]))
            {
                $this->data[$this->getPK()] = $this->getLast() + 1;
                $prepared[$this->getPK()] = $this->data[$this->getPK()];
            }

            //Cria uma instrução de insert
            $sql = " INSERT INTO {$this->getEntity()} (".implode(',',array_keys($prepared)).") VALUES (".implode(',',array_values($prepared)).') ';
        }
        else
        {
            //Monta uma string de UPDATE
            $sql = "UPDATE {$this->getEntity()} ";

            //Monta os pares: coluna = valor, ...
            if($prepared)
            {
                foreach($prepared as $column => $value)
                {
                    if($column !== $this->getPK())
                    {
                        $set[] = "{$column} = {$value}";
                    }
                }
            }

            $sql .= " SET ".implode(',',$set);
            $sql .= " WHERE {$this->getPK()} = ".(int)$this->data[$this->getPK()];
        }

        //obtém transação ativa
        if($conn = Transaction::get())
        {
            $result = $conn->exec($sql);

            return $result;
        }
        else
        {
            throw new \Exception('Não há transação ativa!');
        }
    }

    public function load($id)
    {
        //Monta instrução de SELECT
        $sql  = "SELECT * FROM {$this->getEntity()} ";
        $sql .= " WHERE {$this->getPK()}=".(int) $id;
        
        //Obtém transação ativa
        if($conn = Transaction::get())
        {
            $result = $conn->query($sql);

            //se retornou algum dado
            if($result)
            {
                //retorna os dados em forma de objeto
                $object = $result->fetchObject(get_class($this));

            }
            return $object;
        }
        else
        {
            throw new \Exception('Não há transação ativa!');
        }
    }

    public static function find($id)
    {
        $classname = get_called_class();
        $ar = new $classname;

        return $ar->load($id);

    }

    public function delete()
    {
        if(!empty($this->data[$this->getPK()]))
        {
            $sql = "DELETE FROM {$this->getEntity()} WHERE {$this->getPK()}={$this->data[$this->getPK()]}";

            if($conn = Transaction::get())
            {
                $result = $conn->exec($sql);

                return $result;
            }
            else{
                throw new \Exception('Não há transação ativa!');
            }
        }

        return null;
    }

    public function getLast()
    {
        if($conn = Transaction::get())
        {
            $sql = "SELECT max({$this->getPK()}) FROM {$this->getEntity()}";

            $result = $conn->query($sql);

            //Retorna os dados do banco
            $row = $result->fetch();

            return $row[0];
        }
        else {
            throw new \Exception("Não há transação ativa!");
        }
    }

    public function prepare($data)
    {
        $prepered = array();

        foreach($data as $key => $value)
        {
            //Verifica se variavel é do tipo escalar(integer,string,float,boolean)
            if(is_scalar($value)){
                $prepared[$key] = $this->escape($value);
            }
        }

        return $prepared;
    }

    public function escape($value)
    {
        if(is_string($value) && !empty($value))
        {
            //adiciona \ em aspas
            $value = addslashes($value);

            return "'$value'";
        }
        else if (is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        }
        else if($value !== '')
        {
            return $value;
        }
        else {
            return 'null';
        }
    }

    public static function findBy($culunaValor,$classe = true)
    {

        $classeAtual = get_called_class();

        $sql = "SELECT * FROM ".constant("{$classeAtual}::TABLENAME")." WHERE {$culunaValor}";

 

        if($conn = Transaction::get())
        {
            if($result = $conn->query($sql))
            {
                //retorna os dados em forma de objeto
                if($classe)
                {
                    $object = $result->fetchAll(\PDO::FETCH_CLASS,get_called_class());
                }
                else{
                    $object = $result->fetchAll(\PDO::FETCH_ASSOC);
                }
            }

            return $object ?? NULL;
        }
        
        throw new \Exception('Não há transação aberta!');
    }


    public static function loadAll($id)
    {
        $classe = get_called_class();
    
        $sql = 'SELECT * FROM '.constant("{$classe}::TABLENAME")." WHERE  id_usuario = {$id}";

        if($conn = Transaction::get())
        {
            $result = $conn->query($sql);

            return $result->fetchAll(\PDO::FETCH_CLASS,$classe);
        }
        else{
            throw new \Exception('Não há transação ativa!');
        }
    }

    public function removeProp($propName)
    {
        unset($this->data[$propName]);
    }
}