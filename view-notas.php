<?php session_start();  // iniciando a sessão
			
	include('config.php');

	// verificando se existe um usuário logado
	if (isset($_SESSION['logado']) && $_SESSION['logado'] == true){ 
		echo '<h4>Seja bem vindo ' . $_SESSION['nome_aluno_logado'] . '!</h4>';
	}
	else{
		$_SESSION['mensagem'] = "Você não efetuou login.";
		header('Location: index.php');
		die();
	}

	echo "<p><a href='logout.php'>Efetuar logout</a></p>";

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
	    <form method="post" action="atualizar-notas.php" name="formulario">';

	while ($linha = $statement->fetch(PDO::FETCH_ASSOC)){

		echo '<p>' . $linha['grupo'] . '&nbsp;&nbsp;' . $linha['nome']
		    . '&nbsp;&nbsp;<input type="text" name="' . $linha['id_aluno']
		    . '" value="' . $linha['nota'] . '"> </p>';

	}

	echo '
	        <p><input type="submit" value="Atualizar notas"></p>
		</form>';

?>	
