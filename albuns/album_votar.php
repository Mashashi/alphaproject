<?php
/**
 * Por aqui passam os votos submetidos ao elemento �lbum.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 */
			/**
 			 * Incluir as fun��es direccionadas para os �lbuns independetemente 
			 * do contexto em que a p�gina foi apresentada.
			 *  
 			 */
			include_once("album_funcoes.php");

 if( ! isset($_SESSION['user']) ) session_start();
 
 validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
  
 if( isset( $_SESSION['user'] ) ){
		
		if( is_numeric( $_POST['voto'] ) && $_POST['voto'] < 5 && $_POST['voto'] > -1 
		&& isset( $_POST['id'] )){
			
			
			//echo "sa".existeNaBd( "geral", "id_geral", $_POST['id'] ); 
			if( existeNaBd( "geral", "id_geral", $_POST['id'] ) == 1 ){
				
				//echo "hello";
				$bd = ligarBD();
				
				$query = $bd->submitQuery("Select Count(*) 
				From `controlo_votacao` Where `geral_id_geral` = ".$_POST['id']." And 
				`registo_id_registo` = ".$_SESSION['id_user']);
				
				if( mysql_result( $query,0 ) < 1 ){
				
				$bd->submitQuery("
				INSERT INTO `controlo_votacao` (
				`registo_estatuto_id_estatuto` ,
				`registo_id_registo` ,
				`geral_id_geral` 
				)
				VALUES (
					'".$_SESSION['id_estat']."', '".$_SESSION['id_user']."', '".$_POST['id']."'
				);
				");
				
				$bd->submitQuery("
				UPDATE `album` SET `album_classi` = (`album_classi`+".$_POST['voto']."+1),
				`album_classi_num_vot` = (`album_classi_num_vot`+1) 
				WHERE geral_id_geral = ".$_POST['id']);
				
				$query = 
				$bd->submitQuery("Select `album_classi`,`album_classi_num_vot` From `album` 
				Where `geral_id_geral` = ".$_POST['id']);
				
				
				if( mysql_numrows($query) == 1)
					//echo "ai  "
					//.mysql_result($query,0,0)." ".mysql_result($query,0,1)." ";-
					echo ( classiItem(mysql_result($query,0,0), mysql_result($query,0,1)) );
				
				} 
				
			} else {
				
				$query = 
				$bd->submitQuery("Select `album_classi`,`album_classi_num_vot` From `album` 
				Where `geral_id_geral` = ".$_POST['id']);
				
				
				if( mysql_numrows($query) == 1)
					echo ( classiItem(mysql_result($query,0,0), mysql_result($query,0,1)) );
				
				
			}
			
		} 
		
	 }

?>