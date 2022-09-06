<?php 

function dd($conteudo):void
{
	echo '<pre>';
	var_dump($conteudo);
	echo '</pre>';
}

try {
	$conn = new PDO('mysql:host=localhost;dbname=estudo','weslley','123');

	//#########################################################################

	//Abrindo Transação
	$conn->beginTransaction();
	
	//Método exec() -> retorna quantidade de linhas que foram afetadas (INSERT,UPDATE.DELETE)
	$resultado = $conn->exec("UPDATE cliente SET nome='Fran',email='fran@teste.com' WHERE id = 1");

	if($resultado)
	{
		echo 'Dados alterados com sucesso!';
	}
	else {
		echo 'Nenhum dado foi alterado!';
	}

	//#########################################################################

	$consulta = $conn->query('SELECT * FROM cliente');

	echo '<pre>';
	//var_dump(get_class_methods($consulta));
	//var_dump($consulta->fetchAll(PDO::FETCH_OBJ));
	echo '</pre>';

	$conn->commit();

	//#########################################################################

} catch (Exception $e) {
	$conn->rollback();
	echo $e->getMessage();
}


$arr = [
	"nome" => "Francisco",
	"sobrenome" => "Silva",
	"email" => "fran@teste.com"
];

dd($arr);