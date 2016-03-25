<?php

/**
 * Ficheiro responsсvel pela ediчуo do conteњdo dos posts.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11К, 12К; I1 Eчa de Queirѓs
 * @version 1.0
 */

if( defined( 'IN_PHPAP' ) ) die();

session_start();
/**
 * Rquerir as funчѕes avanчadas
 *  
 */
require_once ( "funcoes_avan.php" );

validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );

if ( ( isset($_POST["tp"]) && ($_POST["content"]) && isset($_POST["id"]) ) )
{

	$bd = "";

	$tp = clearGarbadge( $_POST["tp"], false, false );

	if ( is_numeric($tp) )
	{
		
		if( $_SESSION['estat_carac'][1] &&  !$_SESSION['estat_carac'][0])
			$tp += 2;
		
		if( ( $_SESSION['estat_carac'][0] && $tp < 2 ) 
		|| ( $_SESSION['estat_carac'][1] && $tp > 1) ){
		
		$id = $_POST["id"];
		
		$content =  $tp % 2 == 0 ? 
		clearGarbadge(rawurldecode($_POST["content"]), false, false) :
		clearGarbadge(rawurldecode($_POST["content"]), true, true);

		$tittxt = $tp % 2 == 0 ? "post_titulo" : "post_texto";
		
		
		if($tp % 2 != 0 && substr($content,0,1) != "\n"){
			
			$content = "\n".$content;
			
		}
		
		if ( $tp % 2 == 0 ) 
			$id = substr( $id, strpos($id, "i") + 1 );
		else 
			$id = substr( $id, strpos($id, "e") + 1 );

		$bd = ligarBD();

		$bd = $bd->submitQuery( "Update `post` Set `$tittxt` = '$content' Where `id_post` = $id" );

		/*if ( ! $bd )
			$bd = "De momento nуo щ possэvel actualizar os dados!";
		else
			$bd = "";
		echo rawurlencode( "$bd" );*/
	}
	
	}
	
	

} else if ( isset($_POST["flag"]) && ( $_POST["flag"] == 1 || $_POST["flag"] == 0 ) 
&& isset($_POST["id"]) && $_SESSION['estat_carac'][0] ){
	
	$flag = $_POST["flag"];
	
	$id = clearGarbadge( $_POST["id"], false, false);
	 
	$id = substr( $id, strpos($id, "c") + 1 );
	
	if( is_numeric($id) && $id > 0 ){
	
		$bd = ligarBD();
		
		$bd = $bd->submitQuery( "Update `topico` Set `topico_pode_comentar` = '$flag' 
		Where `id_topico` = (Select `topico_id_topico` From `post` Where `id_post` = $id)" );
		
		if (!$bd)
			echo rawurlencode( "De momento nуo щ possэvel efectuar essa acчуo." );
			
	}
	
}

?>