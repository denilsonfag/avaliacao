<?php
	session_start();  // todos os arquivos que manipulam a sessão devem ter esta linha, mesmo que seja apenas para destruir a sessão
	session_destroy();
    header("Location:index.php");
?>
