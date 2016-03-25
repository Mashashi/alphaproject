<?php

/**
 * As configurçãos do síte.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
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
 * Opções backup.
 * 
 * -t faz com que não sejam realizadas as instruções de criação de tabelas.
 * Só as operações de inserção estão contempladas.
 *
 * Para obter mais informações pesquisar mysqldump 
 *
 * @var String
 */
$optbackup = "-t";

/**
 * De quantas em quantas horas se deve fazer o backup à bd.
 * 
 * @var integer
 */
$dothebackupin = 24;

/**
 * Se se encontra disponível um servidor SMTP para o envio de correio electrónico,
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
 * Número de posts por página. 
 * 
 * @var integer   
 */
$numpp = 5;

/**
 * Número de tópicos or página.
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
 * Número de dias a que o utilizador tem direito a fazer spam report para posts e tópicos.
 * 
 * @var integer    
 */
$atoripo = 3;

/**
 * Se utilizadores não registados estão autorizados a ver visualizar o síte.
 * 
 * @var boolean    
 */
$autorver = true;

/**
 * Se as frases apresentadas randomicamente estão activadas.
 * 
 * @var boolean    
 */
$frases = true;

/**
 * Tempo em minutos em que a inactividade do user leva a esse user ser dado 
 * como offline.
 * Nota: A sessão não expira. 
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
 * O email para qual os utilizadores deverão contactar o administrador
 * 
 * @var String    
 */
$emailadmin = "rafael_r.c@hotmail.com";

/**
 * Validar o dominio do email dos registados.
 * Ter em conta que esta acção atrasa em muito o sistema já que se baseia 
 * em pings (ICMP) para o domínio.
 *  
 * Exemplo: exemplo@hotmail.com verifica se www.hotmail.com existe.
 * <code>false</code> para desligado.
 * <code>true</code> para ligado.
 * 
 * Nota a expressão regular que valida por defeito o email é:
 * <code>^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$</code>
 *    
 * @var boolean    
 */
$emaildomain = false;

/**
 * Avatar por defeito
 * Ter em conta que esta acção atrasa em muito o sistema já que se baseia em 
 * pings de dominio.
 * Exemplo: exemplo@hotmail.com verifica se www.hotmail.com existe.
 * <code>false</code> para desligado.
 * <code>true</code> para ligado.
 * 
 * Nota as imagens são válidadas segundo a expressão regular:
 * <code>^imagens\/avatar\/.*\..{1,5}$</code>
 * Ter em conta então que este avatar se tem de localizar sempre em imagens/avatar/
 * 
 *   
 * @var boolean    
 */
$avatdefault = "1.gif";

/**
 * Tempo de refrescamento automatico da página 0 ou um número inferior 
 * significa desligado. Em minutos.
 *  
 * @var integer    
 */
$temprefre = 0;

/**
 * Se a neve está activada no inverno. 
 *  
 * @var bool    
 */
$snow_active = true;

/**
 * Texto de boas vindas o síte. 
 *  
 * @var String    
 */
$benvindo = "Temos o prazer de anunciar uma nova página
			na história da biblioteca da Eça de Queirós, o síte Alpha Project tem 
			como missão ser o teu melhor amigo no que toca na consulta e 
			requisição de filmes, álbuns, ou outros materiais didáticos.</p>
			<img src=\"imagens/extra.jpg\" alt=\"[Extra]\" />
			<p>Este não só é um espaço para te pores a par das novidades na 
			biblioteca Eça, mas também para trocares impressões dentro da 
			comunidade escolar, para isso sente-te
			à vontade na utilização do fórum.</p>
			<p><font color=\"red\">Por favor, afim de evitar problemas e 
			perguntas despropositadas consulta as FAQ 
			(as questões que são feitas com mais frequência).</font></p>
			<i>Para mais questões sobre a utilização devida do síte consulta as FAQ 
			ou <a href=\"mailto:$emailadmin\">contacta o administrador.</a></i>";

/**
 * As regras do síte.
 *  
 * @var String    
 */
$rules = "
Regras<hr />
<ol>
	<li>Se participativo.</li>
	<li>Faz críticas de uma forma construtiva.</li>
	<li>Respeita todos os utilizadores do síte.</li>
	<li>Não sobrecarregues o fórum com mensagens que inúteis.</li>
	<li>
	Se tiveres alguma dúvida dirige te as FAQ, utiliza a pesquisa, ou em último caso 
	contacta o administrador por PM ou email.
	</li>	
</ol>
<br />
<ul>
<li>
 <i>Nota: a infracação destas regras pode levar a suspensão na utilização do síte por tempo indeterminado</i>
</li>
</ul>
"; //Nao está a ser utilizado


/**
 * Todas as tags permitigas.
 * 
 * O uso de ? é desaconcelhável. 
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
 * Tags que serão listadas.
 * 
 * O uso de "?", é desaconcelhável, isto porque esta implementada a função join para 
 * unir as tags por "?" para possiblitar a pasagem deste array para javascript.
 * Exemplo:
 * Itálico?Link?Parágrafo
 * Esta string de seguida será convertida de novo para um array através da função 
 * explode de javascript 
 *    
 * @var String array    
 */
$tooltiptags = array(0 => "Itálico", 1 => "Link", 2 => "Parágrafo", 3 =>
    "Sublinhado", 4 => "Bold", 5 => "Codigo");

/**
 * Tags que aparceram escritas quando o utilizador seleccionar uma das tags de listagem.
 * 
 * O uso de "?" é desaconcelhável.  
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
 * Variáveis do ficheiro verlog.php na pasta bugs
 * 
 */
$user_log = "Admin"; //Utilizador log

/**
 * Pass log tem de estar de acordo com a função crypt com o parametro CRYPT_BLOWFISH.
 * Password de administração de código.
 *
 */
$pass_log = "0\$icHU7KFgyz."; //8qqb389giub<ºrlaalire9$83

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
 * Estas mensagens serão apresentadas aos utilizadores, ao lado esquerdo do banner. 
 * O primeiro índice indica se as mensagens estão activas ou não.
 *
 */
$mensagens_informacao = 
array(true,"<font color=\"red\">Se quiseres uma conta dirige-te às FAQ para mais esclarecimentos.</font>","<font color=\"red\">Para fazeres o login utiliza o <u>Utilizador</u>: Convidado & <u>Password</u>: 1234</font>","<font color=\"red\">Estamos sobre manutenção, seja paciente ;)</font>");


/**
 * Versão da plataforma alphaproject.
 *      
 */
define("VERSON_AP", "1.0");


/**
 * Número de dias que o utilizador tem o filme reservado.
 *     
 */
define("FILME_REQ", 5);

/**
 * Número de dias que o utilizador tem para devolver o filme.
 *      
 */
define("FILME_REQ_DEV", 5);

/**
 * Número de dias úteis que o utilizador tem o álbum reservado.
 *     
 */
define("ALBUM_REQ", 5);

/**
 * Número de dias úteis que o utilizador tem o álbum reservado.
 *    
 */
define("ALBUM_REQ_DEV", 5);


/**
 * Número de dias úteis que o utilizador tem o álbum reservado.
 *     
 */
define("OUTRO_REQ", 5);

/**
 * Número de dias úteis que o utilizador tem o álbum reservado.
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
#Apartir daqui aparecem váriáveis que ainda não estão a funcionar#
##################################################################
##################################################################








?>