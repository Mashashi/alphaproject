<?php

/**
 * Valida as variáveis $_GET['elem'],$_GET['area'] e $_GET['topico'].
 * E chama as funções que correspondem a configuração do URL válidado.
 * 
 * Quando estão definidas as variáveis:
 * <ol>
 * 	<li>$_GET['elem'], $_GET['area'] e $_GET['topico'] chama 
 *  <code>listarAreaTopico()</code></li>
 *  <li>$_GET['elem'] e $_GET['area']</code> chama listarArea()</li>
 * 	<li>$_GET['elem']</code> chama listarAreasNome()</li> 
 * </ol>   
 * 
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
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
 * Validação de variáveis responsáveis pelo gerênciamento das áreas do fórum. 
 * 
 */  
 
$get = explode("/",$_SERVER['REQUEST_URI']);

if ( (is_numeric($_GET['elem']) && $_GET['elem'] > 0 && is_numeric($_GET['area']) &&
	$_GET['area'] > 0 && is_numeric($_GET['topico']) && $_GET['topico'] > 0) || isset($_GET['id_pesq']) )
{
	
	//Apagar um tópico ou um post
	
	
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
			
			echo "<div class=\"local\">Fórum</div><div class=\"float-divider\"></div>";
			
			//Inserção de uma nova área
			if( isset( $_POST["titulo"] ) && isset( $_POST["descricao"] ) )
			{ echo newArea($_POST["titulo"],$_POST["descricao"]); }

			//Apagar uma área
			if( isset( $_GET["delarea"] ) ){ echo delArea($_GET["delarea"]); }

			//Editar uma área
			if( isset( $_POST["editarea"] )
			&& isset( $_POST["edititulo"] )
 			&& isset( $_POST["editdescricao"] )
 			){ echo editArea($_POST["editarea"],$_POST["edititulo"],$_POST["editdescricao"]); }
			
			//Imprimir editar área
			if( isset( $_GET["editarea"] ) ){ echo printEditArea($_GET["editarea"]); }
			
			listarAreasNome();
			
		} else include_once ( "home.php" );
		

?>