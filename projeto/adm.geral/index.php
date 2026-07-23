<?php
Session_start();
$_SESSION['ativo']='f';
?>
<HTML>
<HEAD>
<TITLE>TESTE PHP</TITLE>
</head>
<BODY>
<DIV ALIGN="CENTER">
<H1>Forne&ccedil;a suas credenciais</H1> 

<TABLE BORDER="1">
<FORM ACTION="login.php" METHOD="POST">
<tr><th colspan="2">Digite seus dados</th></tr>
<TR>
<TH>EMAIL</TH>
<TD><INPUT TYPE="TEXT" NAME="email"></TD>
</TR>
<TR>
<TH>SENHA</TH>
<TD><INPUT TYPE="PASSWORD" NAME="senha"></TD>
</TR>
<TR>
<TD COLSPAN="2" ALIGN="CENTER"><INPUT TYPE="SUBMIT" NAME="SUBMETER"></TD>
</TR>
</FORM>
</TABLE>
</DIV>
</BODY>
</HTML>
