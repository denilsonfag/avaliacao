<?php session_start();  // iniciando a sessão
			
	include('config.php');
?>

<!DOCTYPE HTML>
<html lang="pt-br">
	<head>
		<meta charset="UTF-8">
		<title>Lista de alunos</title>
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

	// atualizando o aluno a ser avaliado na sessão:
	$_SESSION['id_aluno_avaliado'] = $_POST['aluno-avaliado'];

	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='logout.php'>Efetuar logout</a></p>";

	echo "<p>Defina ou atualize a nota do aluno selecionado:</p>";

	// conexão ao banco de dados com PDO:
	$pdo = new PDO('mysql:host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPWD);

	// montando a consulta:
	$sql = 'CALL dados_aluno(' . $_SESSION['id_aluno_logado'] . ',' . $_SESSION['id_aluno_avaliado'] . ')';

	// preparando a consulta:
	$statement = $pdo->prepare($sql); 

	// executando a consulta:
	if (!$statement->execute()){
		$arr = $statement->errorInfo();
		$_SESSION['mensagem'] = 'Erro ao consultar o banco de dados: ' 
		    . $arr[1] . ' ' . $arr[2] . ' ' . $arr[3];
		header('Location: view-lista-alunos.php');
		die();
	}
	
	// fechando a conexão:
	$pdo = null;

	echo '
	    <form method="post" action="atualizar-nota.php" name="formulario">';

	$linha = $statement->fetch(PDO::FETCH_ASSOC);

	echo '<p> Grupo: ' . $linha['grupo'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $linha['nome']
	    . '&nbsp;&nbsp;<input type="text" pattern="\d*" name="nota" value="'. $linha['nota'] .'"> </p>';


	echo '
	        <p><input type="submit" value="Atualizar nota"></p>
		</form>';

?>	
	</body>
</html>
