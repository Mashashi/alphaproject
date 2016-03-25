<?php

/**
 * Fazer a submiзгo de respeito prestado pelos utilizadores uns aos outros.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca
 * @copyright Copyright (c) 2008, 2009; 11є, 12є; I1 Eзa de Queirуs
 * @version 1.0
 */
 if( !$_SESSION['estat_nome'] ) session_start();
 
 include_once ("funcoes.php");
 
 validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
 
 if( isset( $_SESSION['estat_nome'] ) ) {
 
 if( isset( $_POST['tipo_respeito'] ) && !defined( 'IN_PHPAP' ) && isset( $_POST['id_post'] ) && $_POST['id_post'] > 0 ){
		
		$bd = ligarBD();
		
		$tipo_respeito = clearGarbadge( $_POST['tipo_respeito'], false, false );
		
		$id_post = clearGarbadge( $_POST['id_post'], false, false );
		
		if( mysql_result($bd->submitQuery("Select Count(*) From `post` Where `registo_id_registo` = ".$_SESSION['id_user']." And `id_post` = $id_post"),0,0) == 0 ){
		
		if( mysql_result($bd->submitQuery("Select Count(*) From `controlo_respeito` Where `registo_id_registo` = ".$_SESSION['id_user']." And `post_id_post` = $id_post"),0,0) == 0 ){
			
			$query_post = $bd->submitQuery("Select `registo_id_registo`,`topico_area_id_area`,`topico_id_topico`,`registo_estatuto_id_estatuto`  From `post` Where `id_post` = $id_post");
			
			if( mysql_numrows($query_post) == 1 ){
				
				$id_topico = mysql_result($query_post,0,2);
				$id_area = mysql_result($query_post,0,1);
				$id_registo = mysql_result($query_post,0,0);
				$id_estatuto = mysql_result($query_post,0,3);
				
				/*echo "Insert Into `controlo_respeito` 
				Values (".$_SESSION['id_user'].",".$_SESSION['id_estat'].",$id_post,$id_topico,$id_area,$id_registo,$id_estatuto,) ";*/
				
				if( $tipo_respeito == "thumbs_up" )
					$tipo_respeito = 1;
				else if($tipo_respeito == "thumbs_down")
					$tipo_respeito = 0;
				else die();
				
				if( $bd->submitQuery(
				"Insert Into `controlo_respeito` 
				Values (".$_SESSION['id_user'].",".$_SESSION['id_estat'].",$id_post,$id_topico,$id_area,$id_registo,$id_estatuto,$tipo_respeito);") )
					echo rawurlencode("A sua classificaзгo foi submetida com sucesso.");
				
			}
		
		} else {
		
			echo rawurlencode("Nгo pode classificar duas vezes o mesmo post.");
		
		}
		
		} else {
		
			echo rawurlencode("Nгo podes classificar os teus prуprios posts.");
		
		}
		
	}	
 
 
 } else {
 
	echo rawurlencode("Para opinar sobre os comentбrios faзa login.");
	
 }
 
 ?>