<?php 
	session_start();

	require_once __DIR__.'/LangSession.php';
	require_once __DIR__.'/Idioma.php';

	try
	{
		if(!empty($_POST) && isset($_POST['idioma']))
		{
			LangSession::set($_POST['idioma']);

			header('location: idioma.php');
			exit;
		}

		$idioma =  (new Idioma(LangSession::get(),"idioma.ini")) -> obterArray();
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}

?>

<!DOCTYPE html>
<html lang="<?= $idioma['lang'] ?>">
<head>
	<meta charset="utf-8">
	<title><?= $idioma['titulo'] ?></title>
</head>
<body>
	<form action="idioma.php" method="POST">
		<input type="submit" name="idioma" value="pt-br">
		<input type="submit" name="idioma" value="en">
	</form>
	<hr>
	<h1><?= $idioma['titulo_principal'] ?></h1>
	<p><?= $idioma['paragrafo']; ?></p>
</body>
</html>