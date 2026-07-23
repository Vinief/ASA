<?php
Session_start();
if ( $_SESSION['acesso'] == 'p'){
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$bd = new mysqli("XXX.XXX.XXX.XXX", "USER", "PASSWORD", "DB");
	if ($bd->connect_errno){
		die("Falha ao conectar ao MySQL: (" . $bd->connect_errno . ") " . $bd->connect_error);
		}
	echo $email;
	$result = $bd->query("SELECT senha FROM banco_geral WHERE email='". $_SESSION['email'] ."'");
	$line = $result->fetch_assoc();
	$senha = $line['senha'];
	echo "essa eh sua senha: $senha";
	}	
	

elseif( $_SESSION['ativo'] != 'u' ){
	header('Location : index.php');
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Trocar Senha</title>
</head>
<body>

<h2>Trocar Senha</h2>

<form action="trocar_senha.php" method="post">

    <input type="password" name="senha_atual" placeholder="Senha Atual"><br><br>

    <input type="password" name="nova_senha" placeholder="Nova Senha"><br><br>

    <input type="password" name="confirmar_senha" placeholder="Confirmar Nova Senha"><br><br>

    <input type="submit" value="Alterar Senha">
</form>

</body>
</html>

