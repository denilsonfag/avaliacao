<?php session_start();  // iniciando a sessão

	include('config.php');

	// verificando se 'login' existe e não está vazio:
	if (!isset($_POST['login']) || empty($_POST['login'])){ 
		$_SESSION['mensagem'] = "Informe um login de usuário.";
		header('Location: index.php');
		die();
	}
	// verificando se 'senha' existe e não está vazia:
	elseif (!isset($_POST['senha']) || empty($_POST['senha'])){ 
		$_SESSION['mensagem'] = "Senha inválida.";
		header('Location: index.php');
		die();
	}

	// capturando as entradas do usuário:
	$login = $_POST['login'];
	$senha = $_POST['senha'];
	
	// conexão ao banco de dados com PDO:
	$pdo = new PDO('mysql:host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPWD);

	// montando a consulta:
	$sql = "SELECT id_aluno, nome, login, senha FROM aluno WHERE login= :login";

	// preparando a consulta:
	$statement = $pdo->prepare($sql); 
	$statement->bindParam(':login', $login, PDO::PARAM_STR);

	// executando a consulta:
	if (!$statement->execute()){
		$arr = $statement->errorInfo();
		$_SESSION['mensagem'] = 'Erro ao consultar o banco de dados:' . $arr[1] . ' ' . $arr[2] . ' ' . $arr[3];
		header('Location: index.php');
		die();
	}
	
	$linha = $statement->fetch(PDO::FETCH_ASSOC); // capturando a primeira linha do resultado da consulta

	// fechando a conexão:
	$pdo = null;

	if (!$linha){  // se $linha for false, não foi encontrado nenhum usuário com este login
		$_SESSION['mensagem'] = "Usuário não cadastrado.";
		header('Location: index.php');
		die();
	}

	// criptografando a senha para comparar:
	$senhacripto = md5($senha);

	if (strcmp($senhacripto, $linha['senha'])){
		$_SESSION['mensagem'] = "A senha não confere.";
		header('Location: index.php');
		die();
	}

	// se chegou até aqui, tudo certo: logar o usuário
	$_SESSION['id_aluno_logado'] = $linha['id_aluno'];
	$_SESSION['nome_aluno_logado'] = $linha['nome'];
	$_SESSION['logado'] = true; 
	header('Location: view-notas.php');
	die();

?>	
