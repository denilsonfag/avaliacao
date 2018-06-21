<?php session_start();  // iniciando a sessão

	include('config.php');

	// verificando se existe um usuário logado
	if (!isset($_SESSION['logado']) || $_SESSION['logado'] == false){ 
		$_SESSION['mensagem'] = "Você não efetuou login.";
		header('Location: index.php');
		die();
	}

	if (empty($_POST['nota'])) {
		$_SESSION['mensagem'] = 'Nota inválida: deve ser um valor inteiro entre 1 e 10.';
		header('Location: view-lista-alunos.php');
		die();
	}
	else{

		// conexão ao banco de dados com PDO:
		$pdo = new PDO('mysql:host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPWD);


		$sql = 'CALL atualizar_nota(' . $_SESSION['id_aluno_logado'] . ',' . $_SESSION['id_aluno_avaliado'] . ',' . $_POST['nota'] . ')';

		$statement = $pdo->prepare($sql); 
		if (!$statement->execute()){
			$arr = $statement->errorInfo();
			$_SESSION['mensagem'] = 'Erro ao atualizar o banco de dados: ' 
		    	. $arr[1] . ' ' . $arr[2] . ' ' . $arr[3];
			header('Location: view-lista-alunos.php');
			die();
		}

		// fechando a conexão:
		$pdo = null;

		$_SESSION['mensagem'] = 'Nota atualizada com sucesso.';
		header('Location: view-lista-alunos.php');
		die();
	}

?>
