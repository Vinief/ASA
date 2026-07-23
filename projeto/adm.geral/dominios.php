<?php
Session_start();
if ($_SESSION['ativo'] != 's' and $_SESSION['acesso'] != 'v')
        header("Location: index.php");
?>
<HTML>
<HEAD>
<TITLE> DOMINIOS </TITLE>
</HEAD>
<BODY>
<H1>DOMINIOS</H1>
<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$bd = new mysqli("XXX.XXX.XXX.XXX", "USER", "PASSWORD", "DB");
if ($bd->connect_errno)
{
	die("Falha ao conectar ao MySQL: (" . $bd->connect_errno . ") " . $bd->connect_error);
}

$result = $bd->query("SELECT * FROM " . $_SESSION['autoridade'] . " where login='". $_SESSION['login'] ."'");

echo "<P>N&uacute;mero de linhas retornado pelo SGBD para a consulta: " . $result->num_rows . "<HR>\n";

echo("
	
	<TABLE BORDER=1>
	<TR><TH>Email</TH><TH>Usuarios</TH><TH>Senha</TH><TH>Atualizar</TH><TH>Apagar</TH></TR>\n
");

while($line =  $result->fetch_assoc())
{
	echo "<FORM ACTION=\"atualizar.php\" METHOD=\"GET\">" .
	"<TR><TD>\n" .
	$line['email']. 
	"<INPUT TYPE=\"HIDDEN\" VALUE=\"". $line['email'] . "\" name=\"email\" readonly >\n" .
	"</TD><TD>" . 
	"<INPUT TYPE=\"TEXT\" VALUE=\"" . $line['nome'] . "\" name=\"nome\">\n" .
	"</TD><TD>\n" . 
	"<INPUT TYPE=\"TEXT\" VALUE=\"" . $line['senha'] . "\" name=\"senha\">\n" . 
	"</TD><TD>\n" .
	"<INPUT TYPE=\"SUBMIT\" NAME=\"Botao\" VALUE=\"ATUALIZAR\">\n" . 
	"</TD><TD>\n" .
	"<INPUT TYPE=\"SUBMIT\" NAME=\"Botao\" VALUE=\"APAGAR\">\n" . 
	"</TD></TR>\n" .
	"</FORM>\n" ;

	echo $_SESSION['acesso'];
}

?>
<TR><TD COLSPAN="5" ALIGN="CENTER">
<button type="button" onclick="window.location.href='sair.php'">SAIR</button>
</TD></TR>
</TABLE>
<HR>
<FORM ACTION="inserir.php" METHOD="GET">
<TABLE BORDER=1>
<TR><TH>Senha</TH><TH>Ativo</TH><TH>Nome</TH><TH>Inserir</TH></TR>
<TR><TD>GERADO AUTOMATICAMENTE</TD>
<TD><INPUT TYPE="TEXT" VALUE="u" name="ativo" readonly></TD>
<TD><INPUT TYPE="TEXT" VALUE="" name="nome"></TD>
<TD><INPUT TYPE="SUBMIT" VALUE="INSERIR"></TD>
</TR>
</TABLE>
</FORM>

<h2>Trocar Senha</h2>
<form action="" method="post">
    <input type="password" name="senha_atual" placeholder="Senha Atual"><br><br>
    <input type="password" name="nova_senha" placeholder="Nova Senha"><br><br>
    <input type="password" name="confirmar_senha" placeholder="Confirmar Nova Senha"><br><br>
    <input type="submit" value="Alterar Senha">
</form>

</BODY>
</HTML>
