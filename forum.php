<?php

/**
 * Valida as vari�veis $_GET['elem'],$_GET['area'] e $_GET['topico'].
 * E chama as fun��es que correspondem a configura��o do URL v�lidado.
 * 
 * Quando est�o definidas as vari�veis:
 * <ol>
 * 	<li>$_GET['elem'], $_GET['area'] e $_GET['topico'] chama 
 *  <code>listarAreaTopico()</code></li>
 *  <li>$_GET['elem'] e $_GET['area']</code> chama listarArea()</li>
 * 	<li>$_GET['elem']</code> chama listarAreasNome()</li> 
 * </ol>   
 * 
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 */

if( ! defined( 'IN_PHPAP' ) ) die();

/**
 * Incluir funcoes.php.
 *  
 */
include_once ( "funcoes_avan.php" );

	
	echo delPostOrTopic($_GET['delpostop']);


/*
 * 
 * Valida��o de vari�veis respons�veis pelo ger�nciamento das �reas do f�rum. 
 * 
 */  
 
$get = explode("/",$_SERVER['REQUEST_URI']);

if ( (is_numeric($_GET['elem']) && $_GET['elem'] > 0 && is_numeric($_GET['area']) &&
	$_GET['area'] > 0 && is_numeric($_GET['topico']) && $_GET['topico'] > 0) || isset($_GET['id_pesq']) )
{
	
	//Apagar um t�pico ou um post
	
	
	listarAreaTopico( $_GET['area'], $_GET['topico'], $_GET['id_pesq'] );

}
else
	if ( $_GET['elem'] == 8 && is_numeric($_GET['area']) &&  $_GET['area'] > 0  )
	{
		
		listarArea( $_GET['area'] );
		
	}
	else
		if ( $_GET['elem'] == 8 )
		{
			
			echo "<div class=\"local\">F�rum</div><div class=\"float-divider\"></div>";
			
			//Inser��o de uma nova �rea
			if( isset( $_POST["titulo"] ) && isset( $_POST["descricao"] ) )
			{ echo newArea($_POST["titulo"],$_POST["descricao"]); }

			//Apagar uma �rea
			if( isset( $_GET["delarea"] ) ){ echo delArea($_GET["delarea"]); }

			//Editar uma �rea
			if( isset( $_POST["editarea"] )
			&& isset( $_POST["edititulo"] )
 			&& isset( $_POST["editdescricao"] )
 			){ echo editArea($_POST["editarea"],$_POST["edititulo"],$_POST["editdescricao"]); }
			
			//Imprimir editar �rea
			if( isset( $_GET["editarea"] ) ){ echo printEditArea($_GET["editarea"]); }
			
			listarAreasNome();
			
		} else include_once ( "home.php" );
		

?>