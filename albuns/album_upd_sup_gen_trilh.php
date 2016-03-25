<?php
/**
 * Fazer o update dos elmentos suporte, gênero e trilha
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

			/**
 			 * Incluir as funções direccionadas para os álbuns independetemente 
			 * do contexto em que a página foi apresentada.
			 *  
 			 */
			include_once("album_funcoes.php");
			


if(!defined( 'IN_PHPAP' )){
		
	if( !isset($_SESSION['id_user']) ) session_start();
	
	validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
	
	if( $_SESSION['estat_carac'][7] && isset($_POST['id']) && isset($_POST['contend']) ){
			
			$resposta = "";
			
			if($id != 3 && $id != 4){
				
				$resposta = "<option>";
				header( 'Content-type: application/xml; charset="utf-8"', true );
				
			}
			
			$id = $_POST['id'];
			
			$contend = clearGarbadge(rawurldecode( $_POST['contend'] ), false, false);
			
			$bd = ligarBD();
			
			if( empty($contend) ){ die(); }
			
			
			switch($id){
				
				
				case 1:
				// Inserir um suporte de álbum
				$contend = trim($contend, "|");
				$query = $bd->submitQuery("INSERT INTO `suport_album` (
				`id_suport_album` , `suport_album_nome` ) VALUES ( NULL , '$contend' )");
				if($query)
				$resposta .= "<name>".rawurlencode("$contend")."</name><id>"
				.mysql_insert_id()."</id></option>";  
				else
				$resposta .= "<name></name></option>";
				break;
				
				
				
				case 2: 
				// Inserir o g^nero de uma trilha
				$query = $bd->submitQuery("INSERT INTO `trilha_genero_album` (
				`id_trilha_genero_album`,`trilha_genero_album_album_nome` )VALUES 
				(NULL , '$contend')");
				if($query)
				$resposta .= "<name>".rawurlencode("$contend")."</name><id>"
				.mysql_insert_id()."</id></option>"; 
				else
				$resposta .= "<name></name></option>";
				break;
				
				
				
				
				case 3:
				
				// Ordernar as trilhas
				$id_album = $_POST['id_album'];
				
				if( mysql_result($bd->submitQuery("
				Select Count(*) From `geral` Join `trilha_album` On
				`album_geral_id_geral` = `id_geral` Where
				`id_geral` = $id_album
				"),0,0) == 1 ) die();
				
				
				
				$antes = $bd->submitQuery("Select `id_trilha` From `trilha_album`
		 		Where `album_geral_id_geral` = $id_album Order By `trilha_album_ordem`");
		 		
				
		 		
				$posicoes = explode(" ", 
				clearGarbadge( rawurldecode($_POST['contend']), false, false) );
				
				$ids = explode(" ", 
				clearGarbadge( rawurldecode($_POST['ids']), false, false) );
				
				//Asseguramo-nos de que o array tem o mesmo 
				//número de indexs da soma dos da tabela
				if( mysql_numrows($antes) != count($posicoes) 
				|| count($posicoes) != count($ids)  ) die();
				
				$temp = array();
					 
				for($e = 0; $e < mysql_numrows($antes); $e++ ){	
					
					$temp[$e] = mysql_result($antes,$e);
					//Verificar se os valores existem ainda que por uma ordem diferente
					if( array_search ( $temp[$e], $posicoes ) < 0 )	die();
						
				}
				
				for($i = 0; $i < count($posicoes);$i++){
					
					if($$posicoes[$i] == $temp[$i]) continue;
						
					$bd->submitQuery("Update `trilha_album` Set `trilha_album_ordem` = ($i+1)
					Where `id_trilha` = $ids[$i]");
					
				}
				
				break;
				
				
				
				case 4: 
				// Inserir uma nova trilha em modo de edição
				$nome_trilh = clearGarbadge(
				rawurldecode($_POST['contend']), false,false);
				
				$tempo_trilh = rawurldecode( $_POST['time_tri'] );
				
				$id_alb = clearGarbadge( $_POST['id_alb'], false, false );
				
				$acerca = clearGarbadge( ( $_POST['acerca'] ), false, false );
				
				$gen_trilh = $_POST['gen_trilh'];
				
				if( !validTime((array) $tempo_trilh) ) 
					die(rawurlencode("O tempo da trilha é inválido."));
				
				if( 
				!is_numeric ($id_alb) || empty($id_alb) || $id_alb < 1
				|| 
				!is_numeric($gen_trilh) || empty($gen_trilh) || $gen_trilh < 1
				) die();
				
				
				
				if( !existeNaBd("geral", "id_geral", $id_alb)) die();
				
				$max_trilh = mysql_result($bd->submitQuery("
					Select max(`trilha_album_ordem`) From `trilha_album` Where 
					`album_geral_id_geral` = $id_alb
					
				"),0,0);
			
	if( getNumDaysAlb($id_alb, $tempo_trilh) >= 1){
			
	die(rawurlencode(
	"Não é permitido que a duração de áudio de um álbum seja superior ou igual a 24h."
	));
					
		}
				
			
			
				
				$bd->submitQuery("
					INSERT INTO `trilha_album` (
						`id_trilha` ,
						`album_geral_id_geral` ,
						`trilha_genero_album_id_trilha_genero_album` ,
						`trilha_album_nome` ,
						`trilha_album_duracao` ,
						`trilha_album_acerca`,
						`trilha_album_ordem`
						)
						VALUES (
						null, '$id_alb', '$gen_trilh', '$nome_trilh',
						'$tempo_trilh', '$acerca',
						$max_trilh+1
						);
					");
				
				if( mysql_affected_rows() < 0)
					$resposta .= "Não foi possível atender ao seu pedido";
				else 
					echo mysql_insert_id()."#".($max_trilh+1);
				
				break;
				
				default: $resposta .= "<name></name></option>"; 
				
			}
			
			echo $resposta;
			
	} else if( $_SESSION['estat_carac'][7] && isset($_POST['flag']) && isset($_POST['id']) ){
			
			$flag = $_POST['flag'];
			
			$id = clearGarbadge($_POST['id'], false, false);
			
			$bd = ligarBD();
			
			$resposta = "";
			 
			if( is_numeric( $flag ) && is_numeric($id) && $id > 0 ){
			
				switch($flag){
					
					case 1: 
					// Apagar um tipo de suporte dos álbuns
					$query = $bd->submitQuery("Delete From `suport_album` 
					Where `id_suport_album` = $id");
					if( mysql_affected_rows() > 0 ) $resposta = $id;
					break;
					
					case 2: 
					// Apagar um gênero de trilha
					$query = $bd->submitQuery("Delete From `trilha_genero_album` 
					Where `id_trilha_genero_album` = $id");
					if( mysql_affected_rows() > 0 ) $resposta = $id; 
					break;
				
					case 3: 
					//Apagar um série de trilhas de um álbum seleccionadas
					$apg_trlh_a = $_POST['apg_trlh_a'];
					
					$query = "Delete From `trilha_album` Where `id_trilha` IN(";
					
					foreach($apg_trlh_a as $trilh_apg){
						
						if( !is_numeric( $trilh_apg ) ) die();
							
						$query .= "$trilh_apg, ";
						
					}
					
					$query{strlen($query)-2} = ")";
					
					$bd->submitQuery($query);
					
					break;
				
				}
			
			}
			
			echo $resposta;
		
	}
		
		
}

/**
 * maxMinArray()
 * 
 * Achar o mínimo ou máximo de um array.
 * 
 * @deprecated O uso desta função é desaconselhável 
 * 
 * @param String $haystack
 * @param String $flag
 * @param String $which     
 *  
 * @return bool
 */
function maxMinArray( array $haystack, $flag, $which){
	
	$val = $haystack[0];
	
	for($i = 1; $i < count($haystack); $i++ ){
		
		if($flag){
			
			if($haystack[$i] > $val) $val = $haystack[$i];
			
		} else {
			
			if( $haystack[$i] < $val ) $val = $haystack[$i];
			
		}
		
		
	}
	
	return $val;
}


/**
 * trocarId()
 * 
 * Troca $id_1 por $id_2.
 * 
 * @deprecated O uso desta função é desaconselhável 
 * 
 * @param String $tabela
 * @param String $campo
 * @param String $id_1      
 * @param String $id_2
 *  
 * @return bool
 */
function trocarId($tabela, $campo, $id_1, $id_2){
	
	$success = false;
	
	$tabela = clearGarbadge($tabela, true, true);
	
	$campo = clearGarbadge($campo, true, true);
	
	if( is_numeric($id_1) && is_numeric($id_2) && $id_1 != $id_2){
		
		$bd = ligarBD();
		
			$bd->submitQuery("UPDATE `$tabela` SET `$campo` = 0
		 	Where `id_trilha` = $id_1");
		 
			if( mysql_affected_rows() == 1){
			 		
				$bd->submitQuery("UPDATE `$tabela` SET `$campo` = $id_1
		 		Where `id_trilha` = $id_2");
				
				if( mysql_affected_rows() == 1){
						
					$bd->submitQuery("UPDATE `$tabela` SET `$campo` = $id_2
		 			Where `id_trilha` = 0");
					
					if( mysql_affected_rows() == 1){ $success = true; }	
					
				}
				
			} 
			
	}
	
	return $success;
}
?>