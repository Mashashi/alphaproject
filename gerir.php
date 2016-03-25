<?php

/**
 * Em gerir.php podemos encontrar um menu simples que divide por áreas 
 * as possiblidades de gestão do síte.
 * 
 *  
 * Para tal temos então 5 grandes áreas subdivididas conforme as necessidades:<br />
 * <ol>
 * 	<li>Gestão do Fórum</li>
 * 	<li>Gestão dos Filmes</li>
 * 	<li>Gestão dos Álbuns</li>
 * 	<li>Gestão de Outros itens</li>
 * 	<li>Gestão de Geral</li>   
 * </ol>
 *   
 * 
 * Na gestão do fórum podemos encontrar:
 *  <ol>
 * 		<li>Gestão de áreas</li>
 * 		<li>Gestão de tópicos</li>
 * 		<li>Gestão das posts</li>
 * 		<li>Gestão das spam</li> 	
 *  </ol> 
 *   
 * Na gestão geral podemos encontrar:
 *  <ol>
 * 		<li>Gestão de estatutos</li>
 * 		<li>Gestão de utiliadores</li>
 * 		<li>Gestão das FAQ</li>
 * 		<li>Gestão das frases</li> 	
 *  </ol> 
 *    
 * Para uma organização o mais simples possível, foram para o efeito criadas
 * três váriáveis de sessão definidas na função validarUser($user,$pass) presente 
 * no ficheiro funcoes.php, a várialvel de $_SESSION['id_estat'] contém o id do 
 * estatuto, $_SESSION['estat_nome'], contém o nome do estatuto e 
 * $_SESSION['estat_carac'] armazena sob a forma de um array todas
 * as permições do estatuto. 
 * 
 * Verificações feitas:
 * <ol>
 * 	<li>Se o utilizador tem uma sessão iniciada</li>
 * 	<li>Se a <code>$_GET['accao']</code> esta inicializado</li>  
 *  <li>Se a <code>$_GET['accao']</code> é nomérico</li>  
 *  <li>Se a <code>$_GET['accao']</code> esta entre 1 e 14</li>  
 * </ol>
 * 
 * <b>Nota:</b><i>Quando a validação dos dados falha é incloido o ficheiro home.php</i>
 * 
 * <b>Nota:</b> Para gerir o spam a permição gerir tópicos e posts deverá estar activa.
 *         
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
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
 * Faz o control da impressão do nome da área de gestão.
 * 
 * @var boolean  
 */
$prim = true;


/**
 * printPermicao() 
 *  
 * Função que verifica as variáveis a baixo passadas e imprime o subtópico de gestão.
 *   
 * @param String $tit Titulo da área de gestão.
 * @param boolean $flag Váriavel booleana que define se o tópico de gestão será mostrado.
 * @param String $accao Descrição do que o utilizador pode fazer, 
 * 					 com o prefixo <i>"Podes"</i> por defeito.
 * @param String $link Link para o qual dá o tópico de gestão.
 * 
 * @global String Se o título da área de gestão já foi imprimido.
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
	/*else echo "<div id=\"gestaopremainareaprevint\" > Não podes $accao</div>";*/

}


/**
 * printTabelaPermicoes()
 *  
 * Função que imprime a tabela de gestão do fórum.
 * 
 * @uses printPermicao()
 * 
 * @global boolean Controla a impressão do titulo da área de gestão
 *    
 * @return void   
 */
function printTabelaPermicoes()
{

	global $prim;

	echo "<div class=\"gestaopremain\">
 	
	   	<div id=\"gestaopremaintitle\">
		   Permissões do estatuto " . $_SESSION['estat_nome'] . "
		</div>
		
		<table valign=\"top\">
		
		<tr>
		
		<td>";

	printPermicao( "Fórum", $_SESSION['estat_carac'][2], "gerir áreas.", 
	"elem=8" );

	printPermicao( "Fórum", $_SESSION['estat_carac'][0], "gerir tópicos.", "elem=2#" );

	printPermicao( "Fórum", $_SESSION['estat_carac'][1], "gerir posts.", "elem=2#" );

	printPermicao( "Fórum", 
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

	printPermicao( "Álbuns",  $_SESSION['estat_carac'][7], "gerir álbuns.", "elem=2&amp;accao=6" );

	printPermicao( "Álbuns",  $simple_per?$_SESSION['estat_carac'][4]:true, "ver álbuns.", 
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
	"ver o registro das requesições.", 
	"elem=2&amp;accao=2" );
	
	echo "</td></div></tr></table><center><a href=\"report.php\">Relatório</a></center></div>";


}




if ( isset($_SESSION['user']) )
{
	
	

	if ( isset($_GET['accao']) && is_numeric($_GET['accao']) && $_GET['accao'] > 0 && $_GET['accao'] < 18 )
	{

		switch ( $_GET['accao'] )
		{

			case 1:	if ( $_SESSION['estat_carac'][10] ){
						//editar o utilizador no menu de administração
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
		//alguma área de gestão
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