<?php session_start();  // iniciando a sessão

	include('config.php');

	// verificando se existe um usuário logado
	if (!isset($_SESSION['logado']) || $_SESSION['logado'] == false){ 
		$_SESSION['mensagem'] = "Você não efetuou login.";
		header('Location: index.php');
		die();
	}

	// conexão ao banco de dados com PDO:
	$pdo = new PDO('mysql:host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPWD);

	foreach($_POST as $key => $value) {

		if (empty($value)) $value = 'NULL';

		$sql = 'CALL atualizar_nota(' . $_SESSION['id_aluno_logado'] . ', ' . $key . ', ' . $value . ')';

		// var_dump($sql); die();

		$statement = $pdo->prepare($sql); 
		if (!$statement->execute()){
			$arr = $statement->errorInfo();
			$_SESSION['mensagem'] = 'Erro ao atualizar o banco de dados:' 
		    	. $arr[1] . ' ' . $arr[2] . ' ' . $arr[3];
			header('Location: index.php');
			die();
		}
	}

	// fechando a conexão:
	$pdo = null;

?>
