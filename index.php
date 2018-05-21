<?php session_start();  // iniciando a sessão ?>

<!DOCTYPE HTML>
<html lang="pt-br">
	<head>
		<meta charset="UTF-8">
		<title>Login</title>
		<style>
			.block{
				margin: auto;
				width:270px;
				border: 1px solid;
			}
			.line{
				text-align: center;
			}
		</style>
	</head>

	<body>

		<div class="block">
			<p class="line"> Página de Login </p>

			<form method="post" action="login.php" name="formulario" onsubmit="return validarEntradas();">
				<p class="line">Login <input type="text" name="login"> </p>
				<p class="line">Senha <input type="password" name="senha"> </p>
				<p class="line"><input type="submit" value="Efetuar login"></p>
			</form>

			<?php
				if (isset($_SESSION['mensagem'])){
					echo '<h4 style="color: red; text-align: center;">' . $_SESSION['mensagem'] . '</h4>';
					unset($_SESSION['mensagem']); 
				} 
			?>

			<p class="line"><a href="novo-usuario.php">Cadastrar novo usuário</a></p>

		</div>

		<script>

			function validarEntradas(){
		 
				if(document.formulario.login.value==""){
					alert( "Informe um login de usuário." );
					document.formulario.login.focus();
					return false;
				}

				if(document.formulario.senha.value==""){
					alert( "Senha inválida." );
					document.formulario.senha.focus();
					return false;
				}
		 
		 		return true;
			}

		</script>

	</body>
</html>
