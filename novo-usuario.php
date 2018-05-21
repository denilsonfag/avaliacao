<?php session_start();  // iniciando a sessão ?>

<!DOCTYPE HTML>
<html lang="pt-br">
	<head>
		<meta charset="UTF-8">
		<title>Novo usuário</title>
		<style>
			.block{
				margin: auto;
				width:350px;
				border: 1px solid;
			}
			.line{
				text-align: center;
			}
		</style>
	</head>

	<body>

		<div class="block">
			<p class="line"> Novo usuário </p>

			<form method="post" action="cadastrar-usuario.php">
				<p class="line">Nome <input type="text" name="nome"> </p>
				<p class="line">Grupo <input type="text" name="grupo"> </p>
				<p class="line">Login <input type="text" name="login"> </p>
				<p class="line">Senha <input type="password" name="senha"> </p>
				<p class="line">Confirmar senha <input type="password" name="senhaaconfirmar"> </p>
				<p class="line"><input type="submit" value="Cadastrar"></p>
			</form>

			<?php
				if (isset($_SESSION['mensagem'])){
					echo '<h4 style="color: red; text-align: center;">' . $_SESSION['mensagem'] . '</h4>';
					unset($_SESSION['mensagem']); 
				} 
			?>

			<p class="line"><a href="index.php">Voltar</a></p>

		</div>

	</body>
</html>
