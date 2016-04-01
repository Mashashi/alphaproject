<?php

/**
 * As configur��os do s�te.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 */

/**
 * Servidor onde se encontra a base de dados.
 * 127.0.0.1 ou localhost se a base de dados for local. 
 * 
 * @var String    
 */
$host = "localhost";

/**
 * Password para a base de dados. 
 * 
 * @var String    
 */
$pass_bd = "ZcKpqD59QJ5yep5b";

/**
 * Nome da base de dados. 
 * 
 * @var String    
 */
$db = "alpha_project";

/**
 * Nome de utilizador da base de dados. 
 * 
 * @var String    
 */
$user_bd = "aplogin";

/**
 * Porto da base de dados.
 * 
 * @var String
 */
$portdb = "3306";

/**
 * Op��es backup.
 * 
 * -t faz com que n�o sejam realizadas as instru��es de cria��o de tabelas.
 * S� as opera��es de inser��o est�o contempladas.
 *
 * Para obter mais informa��es pesquisar mysqldump 
 *
 * @var String
 */
$optbackup = "-t";

/**
 * De quantas em quantas horas se deve fazer o backup � bd.
 * 
 * @var integer
 */
$dothebackupin = 24;

/**
 * Se se encontra dispon�vel um servidor SMTP para o envio de correio electr�nico,
 * localmente.
 *  
 * @var boolean
 */
$availablesmtpserver = false;


/**
 * Permitir a um utilizador simples ver os itens. 
 *  
 * @var boolean
 */
$simple_per = true;


/**
 * N�mero de posts por p�gina. 
 * 
 * @var integer   
 */
$numpp = 5;

/**
 * N�mero de t�picos or p�gina.
 * 
 * @var integer    
 */
$numtp = 6;

/**
 * Se dois utilizadores podem fazer login.
 * 
 * @var integer    
 */
$utils_simul = true;

/**
 * N�mero de dias a que o utilizador tem direito a fazer spam report para posts e t�picos.
 * 
 * @var integer    
 */
$atoripo = 3;

/**
 * Se utilizadores n�o registados est�o autorizados a ver visualizar o s�te.
 * 
 * @var boolean    
 */
$autorver = true;

/**
 * Se as frases apresentadas randomicamente est�o activadas.
 * 
 * @var boolean    
 */
$frases = true;

/**
 * Tempo em minutos em que a inactividade do user leva a esse user ser dado 
 * como offline.
 * Nota: A sess�o n�o expira. 
 * 
 * @var integer    
 */
$exp = 5;

/**
 * Sistema do servidor 
 * 	false: unix  
 * 	true: win;
 * 
 * @var boolean   
 */
$sis = true;

/**
 * O email para qual os utilizadores dever�o contactar o administrador
 * 
 * @var String    
 */
$emailadmin = "rafael_r.c@hotmail.com";

/**
 * Validar o dominio do email dos registados.
 * Ter em conta que esta ac��o atrasa em muito o sistema j� que se baseia 
 * em pings (ICMP) para o dom�nio.
 *  
 * Exemplo: exemplo@hotmail.com verifica se www.hotmail.com existe.
 * <code>false</code> para desligado.
 * <code>true</code> para ligado.
 * 
 * Nota a express�o regular que valida por defeito o email �:
 * <code>^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$</code>
 *    
 * @var boolean    
 */
$emaildomain = false;

/**
 * Avatar por defeito
 * Ter em conta que esta ac��o atrasa em muito o sistema j� que se baseia em 
 * pings de dominio.
 * Exemplo: exemplo@hotmail.com verifica se www.hotmail.com existe.
 * <code>false</code> para desligado.
 * <code>true</code> para ligado.
 * 
 * Nota as imagens s�o v�lidadas segundo a express�o regular:
 * <code>^imagens\/avatar\/.*\..{1,5}$</code>
 * Ter em conta ent�o que este avatar se tem de localizar sempre em imagens/avatar/
 * 
 *   
 * @var boolean    
 */
$avatdefault = "1.gif";

/**
 * Tempo de refrescamento automatico da p�gina 0 ou um n�mero inferior 
 * significa desligado. Em minutos.
 *  
 * @var integer    
 */
$temprefre = 0;

/**
 * Se a neve est� activada no inverno. 
 *  
 * @var bool    
 */
$snow_active = true;

/**
 * Texto de boas vindas o s�te. 
 *  
 * @var String    
 */
$benvindo = "Temos o prazer de anunciar uma nova p�gina
			na hist�ria da biblioteca da E�a de Queir�s, o s�te Alpha Project tem 
			como miss�o ser o teu melhor amigo no que toca na consulta e 
			requisi��o de filmes, �lbuns, ou outros materiais did�ticos.</p>
			<img src=\"imagens/extra.jpg\" alt=\"[Extra]\" />
			<p>Este n�o s� � um espa�o para te pores a par das novidades na 
			biblioteca E�a, mas tamb�m para trocares impress�es dentro da 
			comunidade escolar, para isso sente-te
			� vontade na utiliza��o do f�rum.</p>
			<p><font color=\"red\">Por favor, afim de evitar problemas e 
			perguntas despropositadas consulta as FAQ 
			(as quest�es que s�o feitas com mais frequ�ncia).</font></p>
			<i>Para mais quest�es sobre a utiliza��o devida do s�te consulta as FAQ 
			ou <a href=\"mailto:$emailadmin\">contacta o administrador.</a></i>";

/**
 * As regras do s�te.
 *  
 * @var String    
 */
$rules = "
Regras<hr />
<ol>
	<li>Se participativo.</li>
	<li>Faz cr�ticas de uma forma construtiva.</li>
	<li>Respeita todos os utilizadores do s�te.</li>
	<li>N�o sobrecarregues o f�rum com mensagens que in�teis.</li>
	<li>
	Se tiveres alguma d�vida dirige te as FAQ, utiliza a pesquisa, ou em �ltimo caso 
	contacta o administrador por PM ou email.
	</li>	
</ol>
<br />
<ul>
<li>
 <i>Nota: a infraca��o destas regras pode levar a suspens�o na utiliza��o do s�te por tempo indeterminado</i>
</li>
</ul>
"; //Nao est� a ser utilizado


/**
 * Todas as tags permitigas.
 * 
 * O uso de ? � desaconcelh�vel. 
 *   
 * @var String array    
 */
$allowtags[0] = "<i></i>";
$allowtags[1] = "<a></a>";
$allowtags[2] = "<p></p>";
$allowtags[3] = "<u></u>";
$allowtags[4] = "<b></b>";
$allowtags[5] = "<code></code>";

/**
 * Tags que ser�o listadas.
 * 
 * O uso de "?", � desaconcelh�vel, isto porque esta implementada a fun��o join para 
 * unir as tags por "?" para possiblitar a pasagem deste array para javascript.
 * Exemplo:
 * It�lico?Link?Par�grafo
 * Esta string de seguida ser� convertida de novo para um array atrav�s da fun��o 
 * explode de javascript 
 *    
 * @var String array    
 */
$tooltiptags = array(0 => "It�lico", 1 => "Link", 2 => "Par�grafo", 3 =>
    "Sublinhado", 4 => "Bold", 5 => "Codigo");

/**
 * Tags que aparceram escritas quando o utilizador seleccionar uma das tags de listagem.
 * 
 * O uso de "?" � desaconcelh�vel.  
 *    
 * @var String array    
 */
$tagstoshow[0] = "<i></i>";
$tagstoshow[1] = 
"<a href=&quot;http://www.o_meu_site_aqui.com&quot;>O meu site aqui</a>";
$tagstoshow[2] = "<p></p>";
$tagstoshow[3] = "<u></u>";
$tagstoshow[4] = "<b></b>";
$tagstoshow[5] = "<code></code>";

/**
 * Impedir que o utilizador se ligue simultaneamente mais do que uma vez com o mesmo nick.
 * <code>false</code> Desactivado
 * <code>true</code> Activado
 *    
 * @var boolean    
 */
$canlogsimul = false; // Funciona mal


/**
 * Vari�veis do ficheiro verlog.php na pasta bugs
 * 
 */
$user_log = "Admin"; //Utilizador log

/**
 * Pass log tem de estar de acordo com a fun��o crypt com o parametro CRYPT_BLOWFISH.
 * Password de administra��o de c�digo.
 *
 */
$pass_log = "0\$icHU7KFgyz."; //8qqb389giub<�rlaalire9$83

/**
 * Tempo passado o qual o cookie autorizado expira
 * 
 */
$tempexp = 10;

/**
 * Guarda os nomes dos utilizadores que nao podem mudar a password.
 * 
 */
$utils_pass_block = array("Convidado");


/**
 * Estas mensagens ser�o apresentadas aos utilizadores, ao lado esquerdo do banner. 
 * O primeiro �ndice indica se as mensagens est�o activas ou n�o.
 *
 */
$mensagens_informacao = 
array(true,"<font color=\"red\">Se quiseres uma conta dirige-te �s FAQ para mais esclarecimentos.</font>","<font color=\"red\">Para fazeres o login utiliza o <u>Utilizador</u>: Convidado & <u>Password</u>: 1234</font>","<font color=\"red\">Estamos sobre manuten��o, seja paciente ;)</font>");


/**
 * Vers�o da plataforma alphaproject.
 *      
 */
define("VERSON_AP", "1.0");


/**
 * N�mero de dias que o utilizador tem o filme reservado.
 *     
 */
define("FILME_REQ", 5);

/**
 * N�mero de dias que o utilizador tem para devolver o filme.
 *      
 */
define("FILME_REQ_DEV", 5);

/**
 * N�mero de dias �teis que o utilizador tem o �lbum reservado.
 *     
 */
define("ALBUM_REQ", 5);

/**
 * N�mero de dias �teis que o utilizador tem o �lbum reservado.
 *    
 */
define("ALBUM_REQ_DEV", 5);


/**
 * N�mero de dias �teis que o utilizador tem o �lbum reservado.
 *     
 */
define("OUTRO_REQ", 5);

/**
 * N�mero de dias �teis que o utilizador tem o �lbum reservado.
 *     
 */
define("OUTRO_REQ_DEV", 5);

/**
 * Multa em euros, por cada dia que passe do tempo 
 * em que o utilizador tem o item da biblioteca.
 * 
 */
define("MULTA", 1);


##################################################################
##################################################################
#Apartir daqui aparecem v�ri�veis que ainda n�o est�o a funcionar#
##################################################################
##################################################################








?>