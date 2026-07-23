<?php
Session_start();
if ($_SESSION['ativo'] != 'm'){
        header("Location: index.php");
}
?>
<HTML>
<HEAD>
<TITLE> TESTE PHP </TITLE>
</HEAD>
<BODY>
<H1>TESTE DO PHP</H1>
<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$bd = new mysqli("XXX.XXX.XXX.XXX", "USER", "PASSWORD", "DB");
if ($bd->connect_errno)
{
	die("Falha ao conectar ao MySQL: (" . $bd->connect_errno . ") " . $bd->connect_error);
}

$result = $bd->query("SELECT * FROM " . $_SESSION['autoridade']);

echo "<P>N&uacute;mero de linhas retornado pelo SGBD para a consulta: " . $result->num_rows . "<HR>\n";

echo("
	
	<TABLE BORDER=1>
	<TR><TH>Email</TH><TH>Dominio</TH><TH>Senha</TH><TH>Atualizar</TH><TH>Apagar</TH></TR>\n
");

while($line =  $result->fetch_assoc())
{
	echo "<FORM ACTION=\"atualizar.php\" METHOD=\"GET\">" .
	"<TR><TD>\n" . 
	$line['email']. 
	"<INPUT TYPE=\"HIDDEN\" VALUE=\"". $line['email'] . "\" name=\"email\">\n" . 
	"</TD><TD>" . 
	"<INPUT TYPE=\"TEXT\" VALUE=\"" . $line['login'] . "\" name=\"login\">\n" .
	"</TD><TD>\n" . 
	"<INPUT TYPE=\"TEXT\" VALUE=\"" . $line['senha'] . "\" name=\"senha\">\n" . 
	"</TD><TD>\n" .
	"<INPUT TYPE=\"SUBMIT\" NAME=\"Botao\" VALUE=\"ATUALIZAR\">\n" . 
	"</TD><TD>\n" .
	"<INPUT TYPE=\"SUBMIT\" NAME=\"Botao\" VALUE=\"APAGAR\">\n" . 
	"</TD></TR>\n" .
	"</FORM>\n" ;
}

?>
<TR><TD COLSPAN="5" ALIGN="CENTER">
<button type="button" onclick="window.location.href='sair.php'">SAIR</button>
</TD></TR>
</TABLE>
<HR>
<TABLE BORDER=1>
<TR><TH>senha</TH><TH>ativo</TH><TH>Dominio</TH></TR>
<FORM ACTION="inserir.php" METHOD="GET">
<TR><TD>GERADO AUTOMATICAMENTE</TD>
<TD><INPUT TYPE="TEXT" VALUE="s" name="ativo" readonly></TD>
<TD><INPUT TYPE="TEXT" VALUE="" name="login"></TD>
<TD><INPUT TYPE="SUBMIT" VALUE="INSERIR"></TD>
</TR>
</FORM>
</TABLE>
<h2>Trocar Senha</h2>

<form action="trocar_senha.php" method="get">
    <input type="password" name="senha_atual" placeholder="Senha Atual"><br><br>

    <input type="password" name="nova_senha" placeholder="Nova Senha"><br><br>

    <input type="password" name="confirmar_senha" placeholder="Confirmar Nova Senha"><br><br>

    <input type="submit" value="Alterar Senha">
</form>
</BODY>
</HTML>
