<?php
Session_start();
if ((isset($_GET['login'])) or (isset($_GET['nome']))){
	$arquivos = array('index.php', 'login.php', 'usuarios.php', 'dominios.php', 'inserir.php', 'atualizar.php', 'trocar_senha.php');
	$nome=$_GET['nome'];
	$login=$_GET['login'];
	$ativo=$_GET['ativo'];
	//$senha = 1234;
	$senha= "1F(" .rand(100000, 999999). ")";

}
else
	die('Erro na passagem de par&acirc;metros');

if ( $_SESSION['ativo'] == 'm' and substr_count($login, '@') == 0){
	$nome = 'root';
	$email = $nome . '@' . $login;
	$dir = '/projeto/adm.geral/' . $login;
	mkdir($dir . '/logs', 0755, true);
	$log = fopen("/projeto/adm.geral/$login/logs/erros.log", "w");
	fclose($log);
	foreach($arquivos as $arquivo){
		$origem= $arquivo;
		copy($origem, "$dir/$arquivo");
		chmod("$dir/$arquivo", 0755);
		}

	}
elseif ( $_SESSION['ativo'] == 's' and substr_count($nome, '@') == 0){
	$login = $_SESSION['login'];
	$email = $nome .'@'. $login;
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$bd = new mysqli("XXX.XXX.XXX.XXX", "USER", "PASSWORD", "DB");
if ($bd->connect_errno)
{
	die("Falha ao conectar ao MySQL: (" . $bd->connect_errno . ") " . $bd->connect_error);
}

if (substr_count($email, '@') == 1){
	$result =  $bd->query("SELECT MAX(uid) AS uid FROM banco_geral");
	$line =  $result->fetch_assoc();
	$uid = $line['uid'] + 1;
	$bd->query("INSERT INTO banco_geral (nome, login, senha, uid, gid, ativo, dir, shell, email, acesso) VALUES ('$nome', '$login', '$senha', $uid, $uid, '$ativo', '$dir', '/bin/bash', '$email', 'p')");
	if( $_SESSION["ativo"]=='f'){
		header("location: index.php");
	}
	elseif( $_SESSION["ativo"]=='u'){
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
};

";
		$apache_texto = "
<VirtualHost *:80>
ServerName adm.geral
DocumentRoot /projeto/adm.geral
ErrorLog /projeto/adm.geral/logs/erros.log
<Directory \"/projeto/adm.geral\">
AllowOverride All
Order Allow,deny
Allow from all
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
ErrorLog /projeto/adm.geral/$login/logs/erros.log
<Directory \"/projeto/adm.geral/$login\">
AllowOverride All
Order Allow,deny
Allow from all
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
		header("Location: http://" .$_SESSION['login']. "/mestre.php");	
		exec('/root/exeroot > /dev/null 2>&1 &');
		
		}
}
else{
	if( $_SESSION["ativo"]=='f'){
		header("location: index.php");
	}
	elseif( $_SESSION["ativo"]=='u'){
		header("Location: http://" .$_SESSION['login']. "/usuario.php");
	}
	elseif( $_SESSION["ativo"]=='s'){
		header("Location: http://" .$_SESSION['login']. "/dominios.php");
	}
	elseif( $_SESSION["ativo"]=='m'){
		header("Location: http://" .$_SESSION['login']. "/mestre.php");
	}

}
?>
