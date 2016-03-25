<? 
/**
 * Fazer o update aos elementos gênero, suporte e som.
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

	if(!defined( 'IN_PHPAP' )){
		
		if(!isset($_SESSION['user_pass'])) session_start();
		
		validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
		
		if( $_SESSION['estat_carac'][6] && isset($_POST['id']) && isset($_POST['contend']) ){
			
			header( 'Content-type: application/xml; charset="utf-8"', true );
			
			$id = $_POST['id'];
			
			$contend = clearGarbadge(rawurldecode( $_POST['contend'] ), false, false);
			
			$bd = ligarBD();
			
			if( empty($contend) ){ die(); }
			
			$resposta = "<option>";
			
			switch($id){
				//Insere um novo gênero de filme
				case 1: 
				$query = $bd->submitQuery("INSERT INTO `genero_filme` (
				`id_genero_filme` , `genero_filme_nome` ) VALUES ( NULL , '$contend' )");
				if($query)
				$resposta .= "<name>".rawurlencode("$contend")."</name><id>"
				.mysql_insert_id()."</id></option>";  
				else
				$resposta .= "<name></name></option>";
				break;
				
				case 2: 
				//Inserir um tipo filme
				$query = $bd->submitQuery("INSERT INTO `tipo_som_filme` (
				`id_tipo_som_filme`,`tipo_som_filme_nome` )VALUES (NULL , '$contend')");
				if($query)
				$resposta .= "<name>".rawurlencode("$contend")."</name><id>"
				.mysql_insert_id()."</id></option>"; 
				else
				$resposta .= "<name></name></option>";
				break;
				
				case 3: 
				//Inserir um novo suporte de filme
				$contend = trim($contend, "|");
				
				$query = $bd->submitQuery("INSERT INTO `suport_filme` (
				`id_suport_filme` ,`suport_filme_nome` )VALUES ( NULL , '$contend' )");
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
		
		} else if( $_SESSION['estat_carac'][6] && isset($_POST['flag']) && isset($_POST['id']) ){
			
			$flag = $_POST['flag'];
			
			$id = clearGarbadge($_POST['id'], false, false);
			
			$bd = ligarBD();
			
			$resposta = 0;
			 
			if( is_numeric($flag ) && is_numeric($id) && $id > 0 ){
			
				switch($flag){
				
					case 1: 
					//Apagar um gênero de filme
					$query = $bd->submitQuery("Delete From `genero_filme` 
					Where `id_genero_filme` = $id");
					if( mysql_affected_rows() > 0 ) $resposta = $id;
					break;
				
					case 2: 
					//Apagar um tipo de som
					$query = $bd->submitQuery("Delete From `tipo_som_filme` 
					Where `id_tipo_som_filme` = $id");
					if( mysql_affected_rows() > 0 ) $resposta = $id; 
					break;
				
					case 3:
					//Apagar um tipo de suporte dos filmes
					$query = $bd->submitQuery("Delete From `suport_filme` 
					Where `id_suport_filme` = $id");
					if( mysql_affected_rows() > 0 ) $resposta = $id; 
					break;
				
				}
			
			}
			
			echo $resposta;
			
		}

	}

?>