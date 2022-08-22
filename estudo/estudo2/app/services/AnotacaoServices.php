<?php 
    namespace app\services;

    use lib\database\Db;
    use app\model\Anotacao as AnotacaoModel;

    class AnotacaoServices
    {
        public function listar()
        {
            try
			{
				$conn = Db::getConnection();

				$anotacao = new AnotacaoModel($conn);

				//Adicionando anotações para ser acessado na view->session.php
				return $anotacao->getAll();

			} catch(\Exception $e) {
				echo $e->getMessage();
			}
        }
    }