<?php
/**
 * Votar no item do tipo outro.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */


if( ! isset($_SESSION['user']) ) session_start();
 
 if( isset( $_SESSION['user'] ) ){
		
		if( is_numeric( $_POST['voto'] ) && $_POST['voto'] < 5 && $_POST['voto'] > -1 
		&& isset( $_POST['id'] )){
			/**
			 * Incluir as funções nativas dos outros itens.
			 *
			 */
			include_once("outro_funcoes.php");
			
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
				UPDATE `outro` SET `outro_classi` = (`outro_classi`+".$_POST['voto']."+1),
				`outro_classi_num_vot` = (`outro_classi_num_vot`+1) 
				WHERE geral_id_geral = ".$_POST['id']);
				
				$query = 
				$bd->submitQuery("Select `outro_classi`,`outro_classi_num_vot` From `outro` 
				Where `geral_id_geral` = ".$_POST['id']);
				
				
				if( mysql_numrows($query) == 1)
					//echo "ai  "
					//.mysql_result($query,0,0)." ".mysql_result($query,0,1)." ";-
					echo ( classiItem(mysql_result($query,0,0), mysql_result($query,0,1)) );
				
				} 
				
			} else {
				
				$query = 
				$bd->submitQuery("Select `outro_classi`,`outro_classi_num_vot` From `outro` 
				Where `geral_id_geral` = ".$_POST['id']);
				
				
				if( mysql_numrows($query) == 1)
					echo ( classiItem(mysql_result($query,0,0), mysql_result($query,0,1)) );
				
				
			}
			
		} 
		
	 }

?>