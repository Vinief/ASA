<?php
Session_start();
if ((isset($_POST['senha_atual'])) and (isset($_POST['confirmar_senha'])) and (isset($_POST['nova_senha']))){
	$senha_nova	=$_POST['nova_senha'];
	$senha_atual	=$_POST['senha_atual'];
	$senha_conf	=$_POST['confirmar_senha'];
	$email		=$_SESSION['email'];
}

else
	
	die('Erro na passagem de par&acirc;metros');

if ($senha_nova == $senha_conf){
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$bd = new mysqli("XXX.XXX.XXX.XXX", "USER", "PASSWORD", "DB");
	if ($bd->connect_errno){
		die("Falha ao conectar ao MySQL: (" . $bd->connect_errno . ") " . $bd->connect_error);
	}
	
	$_SESSION['acesso'] = 'v';
	$bd->query("UPDATE banco_geral SET senha = '$senha_nova', acesso = 'v' WHERE email='$email' AND senha='$senha_atual'");
	if ($bd->errno){
		die("Erro na execucao do SQL: $sql ($bd->errno) $bd->error");
	};
	}



echo "$senha_nova, $senha_atual, $senha_conf, $email,";

if ($_SESSION['ativo'] == 'm' and $_SESSION['acesso'] == 'v'){
	header("Location: http://" .$_SESSION['login']. "/mestre.php");
    
	}
elseif ($_SESSION['ativo'] == 's' and  $_SESSION['acesso'] == 'v'){
	header("Location: http://" .$_SESSION['login'] . "/dominios.php");
    
	}
elseif ($_SESSION['ativo'] == 'u' and $_SESSION['acesso'] == 'v'){
	header("Location: http://" .$_SESSION['login']. "/usuarios.php");
    
	}

else{
	header("Location: index.php");
}

?>
