<?php session_start();  // iniciando a sessão
			
	include('config.php');
?>

<!DOCTYPE HTML>
<html lang="pt-br">
	<head>
		<meta charset="UTF-8">
		<title>Lista de alunos</title>
		<style>
			.btn-link{
  				border:none;
			    outline:none;
			    background:none;
			    cursor:pointer;
			    color:#0000EE;
			    padding:100 px;
			    text-decoration:underline;
			    font-size:inherit;
			}
		</style>
	</head>

	<body>

<?php
	// verificando se existe um usuário logado
	if (isset($_SESSION['logado']) && $_SESSION['logado'] == true){ 
		echo '<p>Usuário logado: <strong>' . $_SESSION['nome_aluno_logado'] . '</strong>';
	}
	else{
		$_SESSION['mensagem'] = "Você não efetuou login.";
		header('Location: index.php');
		die();
	}

	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='logout.php'>Efetuar logout</a></p>";

	if (isset($_SESSION['mensagem'])){
		echo '<h4 style="color: red">' . $_SESSION['mensagem'] . '</h4>';
		unset($_SESSION['mensagem']); 
	} 

	echo "<p>Selecione um dos alunos abaixo para avaliá-lo:</p>";

	// conexão ao banco de dados com PDO:
	$pdo = new PDO('mysql:host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPWD);

	// montando a consulta:
	$sql = 'CALL lista_alunos(' . $_SESSION['id_aluno_logado'] . ')';

	// preparando a consulta:
	$statement = $pdo->prepare($sql); 

	// executando a consulta:
	if (!$statement->execute()){
		$arr = $statement->errorInfo();
		$_SESSION['mensagem'] = 'Erro ao consultar o banco de dados:' 
		    . $arr[1] . ' ' . $arr[2] . ' ' . $arr[3];
		header('Location: index.php');
		die();
	}
	
	// fechando a conexão:
	$pdo = null;

	echo '
	    <form method="post" action="view-aluno.php" name="formulario">';

	while ($linha = $statement->fetch(PDO::FETCH_ASSOC)){

		echo ' 
			<button type="submit" name="aluno-avaliado" value="' . $linha['id_aluno'] . 
			'" class="btn-link">Grupo: ' . $linha['grupo'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $linha['nome'] . 
			'</button></br>
		';
	}
?>	
	</body>
</html>
