<? 
/**
 * Fazer o update a direito de autor e suporte.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */


	if( !defined( 'IN_PHPAP' ) ){
		
		if(!isset($_SESSION['user_pass'])) session_start();

		if( $_SESSION['estat_carac'][8] && isset($_POST['id']) && isset($_POST['contend']) ){
			
			header( 'Content-type: application/xml; charset="utf-8"', true );
			/**
			 * Incluir as funções nativas dos outros itens.
			 *
			 */
			include_once("outro_funcoes.php");
			
			$id = $_POST['id'];
			
			$contend = clearGarbadge( rawurldecode( $_POST['contend'] ), false, false);
			
			$bd = ligarBD();
			
			if( empty( $contend ) ){ die(); }
			
			$resposta = "<option>";
			
			switch($id){
				
				case 1: 
				$query = $bd->submitQuery("INSERT INTO `suport_outro` (
				`id_suport_outro` , `suport_outro_nome` ) VALUES ( NULL , '$contend' )");
				if($query)
				$resposta .= "<name>".rawurlencode("$contend")."</name><id>"
				.mysql_insert_id()."</id></option>";  
				else
				$resposta .= "<name></name></option>";
				break;
				
				case 2: 
				$query = $bd->submitQuery("INSERT INTO `direito_outro` (
				`id_direito_outro`,`direito_outro_outro_nome` ) VALUES (NULL , '$contend')");
				if($query)
				$resposta .= "<name>".rawurlencode("$contend")."</name><id>"
				.mysql_insert_id()."</id></option>"; 
				else
				$resposta .= "<name></name></option>";
				break;
				
				default: $resposta .= "<name></name></option>"; 
				break;
				
			}
			
			echo $resposta;
		
		} else if( $_SESSION['estat_carac'][8] && isset($_POST['flag']) && isset($_POST['id']) ){
			
			include_once("outro_funcoes.php");
			
			$flag = $_POST['flag'];
			
			$id = clearGarbadge($_POST['id'], false, false);
			
			$bd = ligarBD();
			
			$resposta = 0;
			 
			if( is_numeric($flag ) && is_numeric($id) && $id > 0 ){
			
				switch($flag){
				
					case 1: 
					$query = $bd->submitQuery("Delete From `suport_outro` 
					Where `id_suport_outro` = $id");
					if( mysql_affected_rows() > 0 ) $resposta = $id;
					break;
				
					case 2: 
					$query = $bd->submitQuery("Delete From `direito_outro` 
					Where `id_direito_outro` = $id");
					if( mysql_affected_rows() > 0 ) $resposta = $id; 
					break;
				
				}
			
			}
			
			echo $resposta;
			
		}

	}

?>