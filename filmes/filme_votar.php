<?php
/**
 * Por aqui passam todos os votos submetidos em relação aos filmes.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

 /**
   * Incluir as funções nativas dos filmes.
   */
 include_once("filme_funcoes.php");
 
 if(!isset($_SESSION['user_pass'])) session_start();
 
 validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
 
 if( isset( $_SESSION['user'] ) ){
		
		if( is_numeric( $_POST['voto'] ) && $_POST['voto'] < 5 && $_POST['voto'] > -1  
		&& isset( $_POST['id'] )){

			
			
			
			$id_filme = clearGarbadge( $_POST['id'], false, false );
			
			
			//Verificar se o filme em que se vai votar de facto existe na base de dados
			if( existeNaBd( "geral", "id_geral", $id_filme ) == 1 ){
				
				$bd = ligarBD();
				
				$query = $bd->submitQuery("Select Count(*) 
				From `controlo_votacao` Where `geral_id_geral` = $id_filme And 
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
				UPDATE `filme` SET `filme_classi` = (`filme_classi`+".$_POST['voto']."+1),
				`filme_classi_num_vot` = (`filme_classi_num_vot`+1) 
				WHERE geral_id_geral = $id_filme");
				
				$query = 
				$bd->submitQuery("Select `filme_classi`,`filme_classi_num_vot` From `filme` 
				Where `geral_id_geral` = $id_filme");
				
				/*Verifica se houve o retorno de mais de um registro
				faz a média entre a soma das classificações e o número 
				de classificações que foi feita*/
				if( mysql_numrows($query) == 1 )
					
					echo ( classiItem(mysql_result($query,0,0), mysql_result($query,0,1)) );
				
				} 
				
			} else {
				
				$query = 
				$bd->submitQuery("Select `filme_classi`,`filme_classi_num_vot` From `filme` 
				Where `geral_id_geral` = $id_filme");
				
				if( mysql_numrows($query) == 1 )
					echo ( classiItem(mysql_result($query,0,0), mysql_result($query,0,1)) );
					
				
			}
			
		} 
		
	 }
?>