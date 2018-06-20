<?php session_start();  // iniciando a sessão

	include('config.php');

	// verificando se 'nome' existe e não está vazio:
	if (!isset($_POST['nome']) || empty($_POST['nome'])){ 
		$_SESSION['mensagem'] = "Informe um nome de usuário.";
		header('Location: novo-usuario.php');
		die();
	}
	// verificando se 'senha' existe e não está vazia:
	elseif (!isset($_POST['senha']) || empty($_POST['senha'])){ 
		$_SESSION['mensagem'] = "Senha inválida.";
		header('Location: novo-usuario.php');
		die();
	}
	// verificando se 'senhaaconfirmar' existe e não está vazia:
	elseif (!isset($_POST['senhaaconfirmar']) || empty($_POST['senhaaconfirmar'])){ 
		$_SESSION['mensagem'] = "Confirme a senha.";
		header('Location: novo-usuario.php');
		die();
	}

	// capturando as entradas do usuário:
	$nome = $_POST['nome'];
	$grupo = $_POST['grupo'];
	$login = $_POST['login'];
	$senha = $_POST['senha'];
	$senhaaconfirmar = $_POST['senhaaconfirmar'];

	// strcmp retorna zero se as duas strings forem iguais:
	if (strcmp($senha,$senhaaconfirmar)){
		$_SESSION['mensagem'] = "As senhas não conferem.";
		header('Location: novo-usuario.php');
		die();
	}

	// criptografando a senha:
	$senhacripto = md5($senha);

	// conexão ao banco de dados com PDO:
	$pdo = new PDO('mysql:host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPWD);

	// montando a consulta:
	$sql = "INSERT INTO aluno(nome, grupo, login, senha) 
	        VALUES (:nome, :grupo, :login, :senha)";

	// preparando a consulta:
	$statement = $pdo->prepare($sql); 
	$statement->bindParam(':nome', $nome, PDO::PARAM_STR);
	$statement->bindParam(':grupo', $grupo, PDO::PARAM_STR);
	$statement->bindParam(':login', $login, PDO::PARAM_STR);
	$statement->bindParam(':senha', $senhacripto, PDO::PARAM_STR);        

	// executando a consulta:
	if (!$statement->execute()){
		$arr = $statement->errorInfo();
		$_SESSION['mensagem'] = 'Erro ao tentar cadastrar registro:' . $arr[1] . ' ' . $arr[2] . ' ' . $arr[3];
		header('Location: novo-usuario.php');
		die();
	}
	
	// fechando a conexão:
	$pdo = null;

	// se não gerou erro o usuário foi cadastrado com sucesso:
	$_SESSION['mensagem'] = "Novo usuário cadastrado com sucesso.";
	header('Location: novo-usuario.php');
	die();

?>
