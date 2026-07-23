<?php
Session_start();
if ((isset($_GET['nome']) and isset($_GET['senha'])) or (isset($_GET['login']) and isset($_GET['senha']))){	
	$arquivos = array('index.php','login.php','dominios.php','usuarios.php', 'atualizar.php', 'inserir.php', 'trocar_senha.php');
	$login=$_GET['login'];//nome do dominio |depos do @
	$nome =$_GET['nome']; //nome do usuario	|antes do @
	$email=$_GET['email'];
	$senha=$_GET['senha'];
	$botao=$_GET['Botao'];
	}
else
	die('Erro na passagem de par&acirc;metros');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$bd = new mysqli("XXX.XXX.XXX.XXX", "USER", "PASSWORD", "DB");
if ($bd->connect_errno){
	die("Falha ao conectar ao MySQL: (" . $bd->connect_errno . ") " . $bd->connect_error);
}

if ( $_SESSION['ativo'] == 'm' and substr($email, '@') == 0){
	$parte = explode("@", $email);
	$login_antigo = $parte[1];
	$nome = 'root';
	$email_novo = $nome.'@'.$login;
	$dir = '/projeto/adm.geral/' . $login;
	$result = $bd->query("SELECT dir FROM banco_geral WHERE email='$email'");
	$line = $result->fetch_assoc();
	foreach($arquivos as $arquivo){
		unlink($line['dir']."/$arquivo");
	unlink("/var/named/$login_antigo.zone");
	
	unlink($line['dir'].'/logs'."/erros.log");
	rmdir($line['dir']. '/logs');
	rmdir($line['dir']);
	
}
}
	

elseif ( $_SESSION['ativo'] == 's' and substr($email, '@') == 0){
	$login = $_SESSION['login'];
	$email_novo = $nome. '@' .$_SESSION['login'];
}
if ($botao == 'ATUALIZAR' and $_SESSION['ativo'] == 'm'){	
	
	
	$bd->query("UPDATE banco_geral SET email='$email_novo', senha='$senha', nome='$nome', login='$login', dir='$dir', shell='/bin/bash' WHERE email='$email'");
	$bd->query("UPDATE usuarios SET email = REPLACE(email, '$login_antigo', '$login'), login = REPLACE(login, '$login_antigo', '$login'), dir   = REPLACE(dir, '$login_antigo', '$login') WHERE email LIKE '%$login_antigo'");


	}
elseif ($botao == 'APAGAR' and $_SESSION['ativo'] == 'm'){
	$bd->query("DELETE FROM banco_geral WHERE login ='$login_antigo'");
	$bd->query("UPDATE usuarios SET email = REPLACE(email, '$login_antigo', '$login'), login = REPLACE(login, '$login_antigo', '$login'), dir   = REPLACE(dir, '$login_antigo', '$login') WHERE email LIKE '%$login_antigo'");


}

if ($botao == 'ATUALIZAR' and $_SESSION['ativo'] == 's'){	
	
	
	$bd->query("UPDATE banco_geral SET email='$email_novo', senha='$senha', nome='$nome', login='$login', dir='$dir', shell='/bin/bash' WHERE email='$email'");


	}
elseif ($botao == 'APAGAR' and $_SESSION['ativo'] == 's'){
	$bd->query("DELETE FROM ". $_SESSION['autoridade'] ." WHERE email='$email'");


}


if( $_SESSION["ativo"]=='u'){
	header("Location: http://" .$_SESSION['login']. "/usuarios.php");
}
elseif( $_SESSION["ativo"]=='s'){
	header("Location: http://" .$_SESSION['login']. "/dominios.php");
}
elseif( $_SESSION["ativo"]=='m'){
	$result = $bd->query("SELECT * FROM " . $_SESSION['autoridade']);
	$zona = fopen("/etc/named.conf.projeto", "w");
	$apache = fopen("/etc/httpd/conf/httpd.conf.projeto", "w");
	$zona_conf = fopen("/var/named/adm.geral.zone", "w");
	$zona_texto = "
zone \"adm.geral\"{
  type master;
  file \"/var/named/adm.geral.zone\";
  allow-query{any;};
};";
		$apache_texto = "
<VirtualHost *:80>
ServerName adm.geral
DocumentRoot /projeto/adm.geral
<Directory \"/projeto/adm.geral\">
AllowOverride All
Require all granted
</Directory>
</VirtualHost>
";
	 	$zona_conf_texto ="
\$TTL 10
\$ORIGIN adm.geral.

@	IN  SOA  @ root (
	2025052600 ;serial
	120	; Refresh
	60	; Retry
	300	; Expire
	10	; Minimum
)

	IN A	192.168.102.132
	IN NS	@

	IN MX 0 @
";
	
		fwrite($zona, $zona_texto);
		fwrite($apache, $apache_texto);
		fwrite($zona_conf, $zona_conf_texto);
		fclose($zona_conf);
		while($line =  $result->fetch_assoc()){
			$dir = $line['dir'];
			mkdir($dir, 0755, true);
			foreach($arquivos as $arquivo){
				$origem= $arquivo;
				copy($origem, "$dir/$arquivo");
				chmod("$dir/$arquivo", 0755);
			}
			$login = $line['login'];
			$zona_conf = fopen("/var/named/$login.zone", "w");
			$zona_texto = "
zone \"$login\"{
  type master;
  file \"/var/named/$login.zone\";
  allow-query{any;};
};";
			$apache_texto = "
<VirtualHost *:80>
ServerName $login
DocumentRoot /projeto/adm.geral/$login
<Directory \"/projeto/adm.geral/$login\">
AllowOverride All
Require all granted
</Directory>
</VirtualHost>
";
			$zona_conf_texto ="
\$TTL 10
\$ORIGIN $login.

@	IN  SOA  @ root (
	2025052600 ;serial
	120	; Refresh
	60	; Retry
	300	; Expire
	10	; Minimum
)

	IN A	192.168.102.132
	IN NS	@

	IN MX 0 @
";

			fwrite($zona, $zona_texto);
			fwrite($apache, $apache_texto);
			fwrite($zona_conf, $zona_conf_texto);
			fclose($zona_conf);
		}

		fclose($zona);
		fclose($apache);
		exec('/root/exeroot > /dev/null 2>&1 &');
		header("Location: http://" .$_SESSION['login']. "/mestre.php");
}
elseif ($_SESSION["ativo"] == NULL){
        header('Location: index.php');

}
?>
