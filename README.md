Neste projeto realizaremos a integração dos servidores virtuais já implementados na disciplina (DNS - Bind, HTTP - Apache, FTP - Proftp, SMTP - Postfix, POP3 ou IMAP - Dovecot), acrescentando uma interface WEB em PHP, ou outra linguagem de sua preferência, para a criação de domínios virtuais e seus usuários.

O projeto consistirá de uma interface gráfica WEB acessada por três tipos de usuários:

A) Usuário administrador.

Usuário que poderá realizar as seguintes funções:

A.1) Criar domínio virtual;
A.2) Remover domínio virtual;
A.3) Trocar a própria senha e a senha de qualquer administrador de domínio virtual;

B) Usuários administradores dos domínios virtuais:

B.1) Criar e-mail (usuário) no seu domínio virtual;
B.2) Remover e-mail (usuário) do seu domínio virtual;
B.3) Trocar a própria senha e a senha de qualquer usuário do seu domínio virtual;

C) Usuários dos domínios virtuais:

C.1) Trocar a própria senha;

Instruções gerais:

a) O sistema comportará um ou mais usuários administradores.
b) O(s) usuário(s) administrador(es) deverá(ão) ter sua(s) credencial(is) armazenada(s) em banco de dados, preferencialmente com senha(s) criptografada(s);
c) Uma vez autenticado um usuário administrador, o sistema deverá informar os domínios já configurados, com a opção de removê-los (mediante confirmação);
d) Também deverá apresentar a opção para criar um novo domínio, desde que não já tenha sido configurado no sistema;
e) Todas as informações sobre domínios e usuários devem ser armazenadas em banco de dados;
f) Uma vez acrescentado ou removido um domínio, deverá ser executado um script (escrito em qualquer linguagem) executado com permissão de "root" para recarregar as configurações do Apache e do BIND;
g) Os arquivos de configuração do Apache e do BIND deverão fazer referência a arquivos de inclusão do projeto (httpd.conf.projeto e named.conf.projeto) que serão integralmente regerados a cada acréscimo ou remoção de domínios virtuais;
h) Os arquivos de inclusão da configuração do BIND e do Apache deverão estar em diretórios onde o usuário do Apache possua permissão para gerá-los;
i) Após a geração dos arquivos de inclusão, o script de recarga da configuração dos servidores (com permissão de root) deverá ser chamado;
j) Os usuários dos domínios virtuais serão identificados pelos seus endereços de correio;
k) Todo domínio virtual, ao ser criado (ex.: domínio.criado), terá o usuário administrador, identificado como "root@domínio.criado", responsável pela sua administração;
l) A senha do administrador do domínio virtual será gerada de forma aleatório pelo sistema;
m) O administrador do domínio virtual acessará o sistema para criar e remover e-mails (usuários) no seu domínio. As senhas dos e-mails serão aleatoriamente geradas pelo sistema e apresentadas ao administrador do domínio virtual;
n) O administrador do domínio virtual também poderá acessar o sistema via FTP para transferir os arquivos do site do seu domínio;
o) No servidor ftp, o administrador do domínio virtual se identificará com o seu e-mail e senha, e terá acesso apenas à pasta e subpastas utilizadas pelo Apache para hospedar o conteúdo do site vinculado ao domínio administrado. O administrador do domínio virtual não terá acesso às pastas de outros domínios, ou a pastas do sistema. A pasta utilizada pelo Apache para hospedar o domínio em questão será a pasta raiz apresentada pelo FTP ao administrador do domínio virtual;
p) Os demais usuários virtuais do domínio não terão acesso ao FTP, podendo apenas usar o sistema para enviar e receber correios;
q) O sistema conterá funcionalidades de troca de senha para todos os usuários (administradores do sistema, administradores de domínios virtuais, e usuários destes domínios);
r) A troca de senha será obrigatória no primeiro acesso dos administradores de domínios virtuais e usuários dos domínios;
s) Integre os bancos de dados do servidor FTP virtual e do serviço de mensagens. Faça com que consultem a mesma base de usuários e domínios;
t) Para cada domínio virtual criado, deverá ser configurado o BIND e o APACHE para responder por ele. O raiz do site www.DOMÍNIO.CRIADO deve ser a pasta base do FTP do usuário administrador deste domínio. O usuário só verá a pasta do seu site quando logar via FTP (não poderá ter acesso a pastas de outros domínios);
u) Crie uma subpasta "adm", abaixo da pasta base de cada domínio virtual, onde somente os usuários deste domínio, mediante autenticação do APACHE, usando o banco de dados dos demais serviços (FTP e mensagens), terão acesso.


___ CHECKLIST DA CORREÇÃO ___

ADM GERAL:
Login Adm
Troca senha Adm
Cria domínio
Exclui domínio
Configura BD
Cria includes
Reinicia serviços
Cria Adm do domínio
Apresenta senha do adm do domínio
Cria pastas e conteúdo default no servidor virtual


ADM DOMÍNIOS:
Login Adm domínio
Troca senha adm do domínio
Cria usuário domínio
Apresenta a senha de usuário
Remove usuário do domínio
Inclui no BD
Atualiza usuários do domínio com acesso à pasta adm

USUÁRIOS:
Login usuário
Troca senha do usuário

SERVIÇOS:

DNS configurado corretamente
Apache configurado e Site no Ar
Adm do site restrito aos usuários do domínio
Email para usuário do domínio - envio ok
Email para usuário do domínio - leitura ok
FTP configurado corretamente
Somente Adm do domínio com acesso ao FTP
