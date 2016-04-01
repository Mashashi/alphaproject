<?php

/**
 * Em gerir.php podemos encontrar um menu simples que divide por �reas 
 * as possiblidades de gest�o do s�te.
 * 
 *  
 * Para tal temos ent�o 5 grandes �reas subdivididas conforme as necessidades:<br />
 * <ol>
 * 	<li>Gest�o do F�rum</li>
 * 	<li>Gest�o dos Filmes</li>
 * 	<li>Gest�o dos �lbuns</li>
 * 	<li>Gest�o de Outros itens</li>
 * 	<li>Gest�o de Geral</li>   
 * </ol>
 *   
 * 
 * Na gest�o do f�rum podemos encontrar:
 *  <ol>
 * 		<li>Gest�o de �reas</li>
 * 		<li>Gest�o de t�picos</li>
 * 		<li>Gest�o das posts</li>
 * 		<li>Gest�o das spam</li> 	
 *  </ol> 
 *   
 * Na gest�o geral podemos encontrar:
 *  <ol>
 * 		<li>Gest�o de estatutos</li>
 * 		<li>Gest�o de utiliadores</li>
 * 		<li>Gest�o das FAQ</li>
 * 		<li>Gest�o das frases</li> 	
 *  </ol> 
 *    
 * Para uma organiza��o o mais simples poss�vel, foram para o efeito criadas
 * tr�s v�ri�veis de sess�o definidas na fun��o validarUser($user,$pass) presente 
 * no ficheiro funcoes.php, a v�rialvel de $_SESSION['id_estat'] cont�m o id do 
 * estatuto, $_SESSION['estat_nome'], cont�m o nome do estatuto e 
 * $_SESSION['estat_carac'] armazena sob a forma de um array todas
 * as permi��es do estatuto. 
 * 
 * Verifica��es feitas:
 * <ol>
 * 	<li>Se o utilizador tem uma sess�o iniciada</li>
 * 	<li>Se a <code>$_GET['accao']</code> esta inicializado</li>  
 *  <li>Se a <code>$_GET['accao']</code> � nom�rico</li>  
 *  <li>Se a <code>$_GET['accao']</code> esta entre 1 e 14</li>  
 * </ol>
 * 
 * <b>Nota:</b><i>Quando a valida��o dos dados falha � incloido o ficheiro home.php</i>
 * 
 * <b>Nota:</b> Para gerir o spam a permi��o gerir t�picos e posts dever� estar activa.
 *         
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 */

/**
 * Incluir funcoes_avan.php.
 *  
 */
include_once ( "funcoes_avan.php" );

if ( ! isset($_SESSION['user']) )
	session_start();

/**
 * Faz o control da impress�o do nome da �rea de gest�o.
 * 
 * @var boolean  
 */
$prim = true;


/**
 * printPermicao() 
 *  
 * Fun��o que verifica as vari�veis a baixo passadas e imprime o subt�pico de gest�o.
 *   
 * @param String $tit Titulo da �rea de gest�o.
 * @param boolean $flag V�riavel booleana que define se o t�pico de gest�o ser� mostrado.
 * @param String $accao Descri��o do que o utilizador pode fazer, 
 * 					 com o prefixo <i>"Podes"</i> por defeito.
 * @param String $link Link para o qual d� o t�pico de gest�o.
 * 
 * @global String Se o t�tulo da �rea de gest�o j� foi imprimido.
 * 
 * @return void   
 */
function printPermicao( $tit, $flag, $accao, $link )
{

	global $prim;

	if ( $flag == 1 )
	{

		if ( $prim )
		{

			echo "<div id=\"gestaopremainarea\"><div id=\"gestaopremainareanome\">$tit</div>";
			$prim = false;

		}
		echo "<a href=\"".$_SERVER["PHP_SELF"]."?$link\"><div id=\"gestaopremainareaprevit\" > 
						Podes $accao 
					</div></a>";

	}
	/*else echo "<div id=\"gestaopremainareaprevint\" > N�o podes $accao</div>";*/

}


/**
 * printTabelaPermicoes()
 *  
 * Fun��o que imprime a tabela de gest�o do f�rum.
 * 
 * @uses printPermicao()
 * 
 * @global boolean Controla a impress�o do titulo da �rea de gest�o
 *    
 * @return void   
 */
function printTabelaPermicoes()
{

	global $prim;

	echo "<div class=\"gestaopremain\">
 	
	   	<div id=\"gestaopremaintitle\">
		   Permiss�es do estatuto " . $_SESSION['estat_nome'] . "
		</div>
		
		<table valign=\"top\">
		
		<tr>
		
		<td>";

	printPermicao( "F�rum", $_SESSION['estat_carac'][2], "gerir �reas.", 
	"elem=8" );

	printPermicao( "F�rum", $_SESSION['estat_carac'][0], "gerir t�picos.", "elem=2#" );

	printPermicao( "F�rum", $_SESSION['estat_carac'][1], "gerir posts.", "elem=2#" );

	printPermicao( "F�rum", 
	$_SESSION['estat_carac'][1] || $_SESSION['estat_carac'][0], 
	"gerir spam.", "elem=2&amp;accao=14" );


	echo "</td>";

	if ( ! $prim )
		echo "</div>";

	$prim = true;

	echo "<td>";

	printPermicao( "Filmes", $_SESSION['estat_carac'][6], "gerir filmes.", "elem=2&amp;accao=4" );

	printPermicao( "Filmes", $simple_per?$_SESSION['estat_carac'][3]:true, "ver filmes.", 
	"elem=2&amp;accao=5" );

	$prim = true;

	echo "</td>";

	if ( ! $prim )
		echo "</div>";

	$prim = true;

	echo "<td>";

	printPermicao( "�lbuns",  $_SESSION['estat_carac'][7], "gerir �lbuns.", "elem=2&amp;accao=6" );

	printPermicao( "�lbuns",  $simple_per?$_SESSION['estat_carac'][4]:true, "ver �lbuns.", 
	"elem=2&amp;accao=7" );

	$prim = true;

	echo "</td>";

	if ( ! $prim )
		echo "</div>";

	$prim = true;

	echo "<td>";

	printPermicao( "Outros", $_SESSION['estat_carac'][8], "gerir outros itens.", 
	"elem=2&amp;accao=8" );

	printPermicao( "Outros",  $simple_per?$_SESSION['estat_carac'][5]:true, "ver outros itens.",
		"elem=2&amp;accao=9" );
	$prim = true;

	echo "</td>";

	if ( ! $prim )
		echo "</div>";

	$prim = true;

	echo "<td>";

	printPermicao( "Geral", $_SESSION['estat_carac'][11], "gerir os estatutos.", 
	"elem=2&amp;accao=12" );

	printPermicao( "Geral", $_SESSION['estat_carac'][10], "gerir os utilizadores.",
	"elem=2&amp;accao=10" );

	printPermicao( "Geral", $_SESSION['estat_carac'][9], "gerir as FAQ.", 
	"elem=2&amp;accao=11" );

	printPermicao( "Geral", $_SESSION['estat_carac'][12], "gerir frases.", 
	"elem=2&amp;accao=13" );
	
	printPermicao( "Geral", 
	($_SESSION['estat_carac'][6] || $_SESSION['estat_carac'][7] || $_SESSION['estat_carac'][8]), 
	"ver o registro das requesi��es.", 
	"elem=2&amp;accao=2" );
	
	echo "</td></div></tr></table><center><a href=\"report.php\">Relat�rio</a></center></div>";


}




if ( isset($_SESSION['user']) )
{
	
	

	if ( isset($_GET['accao']) && is_numeric($_GET['accao']) && $_GET['accao'] > 0 && $_GET['accao'] < 18 )
	{

		switch ( $_GET['accao'] )
		{

			case 1:	if ( $_SESSION['estat_carac'][10] ){
						//editar o utilizador no menu de administra��o
						editVisUtil( $_GET['upidedit'] );
						break;
					}	
			
			case 2: 
				if ( 
				($_SESSION['estat_carac'][6] || $_SESSION['estat_carac'][7] || $_SESSION['estat_carac'][8]) 
				){
				
					include_once("requesicao_log.php");
					break;
				}
			break;
				
			
			case 4:
				include_once ("filmes/filme_prin.php");
				break;
			case 5:
				include_once("filmes/filme_ver.php");
				break;
			case 6:
				include_once ( "albuns/album_prin.php" );
				break;
			case 7:
				include_once ( "albuns/album_ver.php" );
				break;
			case 8:
				include_once ( "outros/outro_prin.php" );
				break;
			case 9:
				include_once ( "outros/outro_ver.php" );
				break;

			case 10:
				if ( $_SESSION['estat_carac'][10] )
				{
					
					if( ! defined( 'IN_PHPAP' ) ) die();
					
					printGerirRegistos();
					
					include_once ( "inserirutil.php" );
					
					break;
					
				}
					
				

			case 11:
				
				if ( $_SESSION['estat_carac'][9] ){
					
					if( ! defined( 'IN_PHPAP' ) ) die();
					
					printGerirFaq();
					
					break;
					
				}
				
					
					
				

			case 12:
				
				if ( $_SESSION['estat_carac'][11] ){
					
					if( ! defined( 'IN_PHPAP' ) ) die();
					
					printGerirEstatuto();
					
					break;
					
				}
					
				

			case 13:
				if ( $_SESSION['estat_carac'][12] ){
					
					if( ! defined( 'IN_PHPAP' ) ) die();
					
					printGerirFrases();
					
					break;
					
				}
					
				
			
			case 14:
				if ( $_SESSION['estat_carac'][0] || $_SESSION['estat_carac'][1] ){
					
					if( ! defined( 'IN_PHPAP' ) ) die();
					
					include_once ( "gerir_spam.php" );
					
					break;
					
				}
					
				
			
			case 15: include_once ( "filmes/filme_editar.php" );
					 break;
			
			case 16: include_once ( "albuns/album_editar.php" );
					 break;
					 
			case 17: include_once ( "outros/outro_editar.php" );
					 break;
					 
			default: include_once ( "home.php" );

		}
		
		
		
		//array_search(1,$_SESSION['estat_carac'])>-1 Ver se o utilizador tem no estatuto
		//alguma �rea de gest�o
	}
	else{
		
		/*if ( array_search(1, $_SESSION['estat_carac']) > -1 )
		{*/
			
			if( ! defined( 'IN_PHPAP' ) ) die();
			
			printTabelaPermicoes();
			
		
		/*}
		else
		{
			include_once ( "home.php" );
		}*/
		
	}
	
}
else
	include_once ( "home.php" );


?>