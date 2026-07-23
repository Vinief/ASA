<?php
Session_start();
/*if( $_SESSION["ativo"]=='f'){
	header("Location: index.php");
}*/
if( $_SESSION["ativo"]=='u'){
	header("Location: http://" .$_SESSION['login']. "/usuarios.php");
	
}
elseif( $_SESSION["ativo"]=='s'){
	header("Location: http://" .$_SESSION['login']. "/dominios.php");
}
elseif( $_SESSION["ativo"]=='m'){
	header("Location: http://" .$_SESSION['login']. "/mestre.php");
}
elseif ($_SESSION["ativo"] == NULL){
	header('Location: index.php');
}

if (isset($_POST['email']) and isset($_POST['senha'])){	
	$email = $_POST['email'];
	$senha = $_POST['senha'];
	$parte = explode("@", $email);
	$_SESSION['login'] = $parte[1];
}
else{
    die('Erro na passagem de par&acirc;metros');
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$bd = new mysqli("XXX.XXX.XXX.XXX", "USER", "PASSWORD", "DB");
if ($bd->connect_errno)
{
	die("Falha ao conectar ao MySQL: (" . $bd->connect_errno . ") " . $bd->connect_error);
}

$result = $bd->query("SELECT ativo, email, acesso from banco_geral where email='$email' and senha='$senha'");
if ($bd->errno)
{
	die("Erro na execucao do SQL: $sql ($bd->errno) $bd->error");
};

$line = $result->fetch_assoc();


if ($line['ativo'] == 'm'){
	$_SESSION["ativo"] = 'm';
	$_SESSION['email'] = $line['email'];
	$_SESSION['autoridade'] = 'dominios';
	header("Location: http://" .$_SESSION['login']. "/mestre.php");
    
	}
elseif ($line['ativo']== 's'){
	$_SESSION["ativo"] = 's';
	$_SESSION['email'] = $line['email'];
	$_SESSION['autoridade'] = 'usuarios';
	echo $_SESSION['login'] . $line['ativo'] . $line['acesso'];
	if( $line['acesso'] == 'p'){
		$_SESSION['acesso'] = $line['acesso'];
		header("Location: http://" .$_SESSION['login']. "/usuarios.php");
	}
	
	else{	
		header("Location: http://" .$_SESSION['login']. "/dominios.php");
	}	
}

elseif ($line['ativo'] == 'u'){
	$_SESSION["ativo"] = 'u';
	$_SESSION['email'] = $line['email'];
	$_SESSION['acesso'] = $line['acesso'];
	header("Location: http://" .$_SESSION['login']. "/usuarios.php");
}
else{
	header("Location: index.php");
    }
?>
