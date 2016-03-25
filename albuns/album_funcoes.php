<?php
/**
 * Funções nativas dos álbuns.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

/**
 * Incluir as funções direccionadas para os álbuns independetemente do contexto
 * em que a página foi apresentada.
 *  
 */

 
include_once(defined('IN_PHPAP') ? "funcoes_avan.php":"../funcoes_avan.php");

/**
 * listarSupAlbum()
 *  
 * Listar os suportes de um álbum, com base no ID de requesição ou ID do álbum.
 * 
 * @param integer $id_sel 
 *  ID da caixa select onde se vai seleccionar o tipo de suporte
 * @param integer $id_alb_req 
 *  ID da requesição ou ID do álbum conforme $flag
 * @param boolean $flag 
 *  A true é inserido o ID da requesição a false o ID da requesição
 * @param boolean $falg_user 
 * 	A true todos os suportes ou a false se vão ser listados os suportes de 
 *  álbuns disponíveis
 *      
 * @return String
 */
function listarSupAlbum($id_sel, $id_alb_req, $flag, $falg_user){
		
		$bd = ligarBD();
		
		$suporte = "<select id=\"$id_sel\" class=\"forms\">";
		
		if($id_alb_req > 0 && !$flag){
				
				$query = 
				$bd->submitQuery("Select `id_suport_album`,`suport_album_nome`
				From `suport_album`,`copi_album` Where `album_geral_id_geral` = $id_alb_req
				And `suport_album_id_suport_album` = `id_suport_album`
				Order By `suport_album_nome`");
				
			
		} else if($id_alb_req > 0){

			$query = $bd->submitQuery("
			Select `id_suport_album`,`suport_album_nome` 
			From `suport_album`,`copi_album`,`requesicao` 
			Where `suport_album_id_suport_album` = `id_suport_album` 
			And `id_requesicao` = $id_alb_req 
			And `album_geral_id_geral` = 
			(Select `geral_id_geral` From `requesicao` Where `id_requesicao` = $id_alb_req )
			Order By `suport_album_nome`");
			
			
			if($flag){
			
				$id_sup = 
				$bd->submitQuery("Select `requesicao_id_suporte`
				From `requesicao` Where `id_requesicao` = $id_alb_req");
				
				if(mysql_num_rows($id_sup) == 1) $id_sup = mysql_result($id_sup,0,0);
				else $id_sup = 0;
			
			}
			
		} else {
			
				$query = $bd->submitQuery("Select * From `suport_album` 
				Order By `suport_album_nome` ");
			
		}
		
		
		for($i = 0; $i < mysql_num_rows($query); $i++){
	
			if($i == 0 && $flag){
			
				while( $row = mysql_fetch_array($query) ){
				
					if( $row[0]  == $id_sup ){
						
						$suporte .= "<option value=\"$row[0]\">$row[1]</option>";
						
						break;
					
					}
				
				}
				
				$suporte .= "<option value=\"0\"></option>";	
				
			}
			
			if( $id_sup != mysql_result($query, $i, 0) ){	
				
				if($falg_user) {
					
					if( 
					getAlbunsDisSup( $id_alb_req, mysql_result($query, $i, 0)) > 0  
					){
					
						$suporte .= "<option value=\"".mysql_result($query, $i, 0)."\">"
						.mysql_result($query, $i, 1)."</option>";
					
					}
					 
				} else {
					
					$suporte .= "<option value=\"".mysql_result($query, $i, 0)."\">"
						.mysql_result($query, $i, 1)."</option>";
					
				}
				
			}
		
		}
	
		$suporte .= "</select>";
		
		mysql_free_result($query);
		
		return $suporte;
		
}



/**
 * getAlbunsDisSup()
 * 
 * Retorna os álbuns disponíveis em um suporte.
 *    
 * @param integer $id_album ID geral do item
 * @param integer $id_sup ID do suporte
 *    
 * @return mixed
 */
function getAlbunsDisSup($id_album, $id_sup){
	
	$bd = ligarBD();
	
	$id_album = clearGarbadge($id_album, false, false);
	
	$id_sup = clearGarbadge($id_sup, false, false);
	
	$bd = $bd->submitQuery( 
	"Select If(`copi_album_totais` >
	( Select Count(*) From `requesicao` Where `geral_id_geral` = '$id_album' And ( `requesicao_dat_min`>= '"
	.date("Ymd", mktime(0, 0, 0, date("m"), date("d")-ALBUM_REQ, date("Y") )).
	"' Or `requesicao_dia_levantado` <> '00000000') 
	And `requesicao_id_suporte` = '$id_sup' ), 
	(`copi_album_totais` - 
	( Select Count(*) From `requesicao` Where `geral_id_geral` = '$id_album' 
	And (`requesicao_dat_min` >= '"
	.date("Ymd", mktime(0, 0, 0, date("m"), date("d")-ALBUM_REQ, date("Y") )).
	"' Or `requesicao_dia_levantado` <> '00000000') 
	And `requesicao_id_suporte` = '$id_sup' )), 0) As 'disponiveis'
	FROM `copi_album` Where `album_geral_id_geral` = '$id_album' 
	And `suport_album_id_suport_album` = '$id_sup'");
	
	if(mysql_num_rows($bd) > 0)
		$bd = mysql_result($bd, 0,0);
	else
		$bd = null;
	
	return $bd;
	
}



/**
 * printElementosAlb()
 * 
 * Imprime a interface simplemente para mostar ou introduzir cópias.
 *    
 * @param integer $id_album 
 *  ID geral do álbum caso seja inferior a um, nenhum tipo de cópia será 
 *  carregado na interface.
 * 
 * @return String
 */
function printElementosAlb( $id_album ){
	
	$suporte = listarSupAlbum("sup_fil", 0, false, false);
	
	$sufix = "
	<br />
	
	<p>
	Número de cópias neste suporte:<br />
	<input type=\"text\" class=\"forms\" name=\"num_cop_fil\" id=\"num_cop_fil\" />
	</p>
	
	<p>			
	Elementos do suporte: <br />
	<select name=\"char_sup_film\" id=\"char_sup_film\">
		<option value=\"A\">A</option>
		<option value=\"B\">B</option>
		<option value=\"C\">C</option>
		<option value=\"D\">D</option>
		<option value=\"E\">E</option>
		<option value=\"F\">F</option>
		<option value=\"G\">G</option>
		<option value=\"H\">H</option>
		<option value=\"I\">I</option>
		<option value=\"J\">J</option>
		<option value=\"K\">K</option>
		<option value=\"L\">L</option>
		<option value=\"M\">M</option>
		<option value=\"N\">N</option>
		<option value=\"O\">O</option>
		<option value=\"P\">P</option>
		<option value=\"Q\">Q</option>
		<option value=\"R\">R</option>
		<option value=\"S\">S</option>
		<option value=\"T\">T</option>
		<option value=\"U\">U</option>
		<option value=\"V\">V</option>
		<option value=\"W\">W</option>
		<option value=\"X\">X</option>
		<option value=\"Y\">Y</option>
		<option value=\"Z\">Z</option>
	</select>
	
	<br />
	</p>
			
	<p>
	Suporte:<br />	
	$suporte	
	<br />
	<input type=\"button\" class=\"forms\" name=\"new_sup_alb\" 
	value=\"Inserir  novo suporte\" id=\"new_sup_alb\" />	
	<input type=\"button\" class=\"forms\" name=\"del_sup_alb\" 
	value=\"Apagar suporte\"  id=\"del_sup_alb\" />	
	</p>
			
	<input type=\"button\" class=\"forms\" value=\"Adicionar número de cópias\" 
	name=\"new_sup_sub\" id=\"new_sup_sub\" />
			
	<input type=\"button\" class=\"forms\" name=\"del_sup_sub\" 
	value=\"Remover número de cópias\"  id=\"del_sup_sub\" />";
		
	if( $id_album > 0 ){
		
		$bd = ligarBD();
		
		$query = "Select Count(*) From `album` Where `geral_id_geral` = $id_album";
		
		$temp = "";
		
		$temp_2 = "";
		
		if( mysql_result( $bd->submitQuery($query) , 0 ) == 1 ){
			
			$query =
			"SELECT `copi_album_totais`,`char_num_suport_album`,
			`suport_album_id_suport_album` 
			FROM `copi_album` Join `num_suport_album`
			ON `copi_album_album_geral_id_geral` = `album_geral_id_geral` And
			`suport_album_id_suport_album` = `copi_album_suport_album_id_suport_album`
			Where `album_geral_id_geral` = $id_album";
			
			$query = $bd->submitQuery($query);
			
			while($row = mysql_fetch_row($query)){
				
				$query_2 = "Select `suport_album_nome` From `suport_album` 
				Where `id_suport_album` = $row[2]";
				
				$query_2 = $bd->submitQuery($query_2);
				
				$query_2 = 
				mysql_numrows($query_2) != 1 ? 
				"Suporte não definido" : 
				mysql_result($query_2,0);
				
				$temp .= 
				"<option value=\"$row[0]|$row[1]|$row[2]\">$row[0]|$row[1]|$query_2</option>";
				
				if( !empty($temp_2) ) $temp_2 .= "|";
				
				$temp_2 .= "$row[0]|$row[1]|$row[2]";
				
			}
			
			
			
			
			
			$elementos = "
			<input type=\"hidden\" name=\"data_filme_copi\" value=\"$temp_2\" 
			id=\"data_filme_copi\" />
			Elemento:<br /><select name=\"copi_mos_film\" id=\"copi_mos_film\" size=\"5\">";

			$elementos .= $temp;
			
			$elementos .= "</select>$sufix";
			
			
		}
		
	} else {
		
		$elementos = "
		<input type=\"hidden\" name=\"data_filme_copi\" id=\"data_filme_copi\" />
		Elemento:<br />
		<select name=\"copi_mos_film\" id=\"copi_mos_film\" size=\"5\"></select>
		$sufix";
		
	}
	
	return $elementos;
	
}

/**
 * tipoGenListAlb()
 * 
 * Listar os gêneros disponíveis dos álbuns.
 *    
 * @return String
 */
function tipoGenListAlb(){
	
	$bd = ligarBD();
	
	$genero = "<select id=\"gen_alb\"><option value=\"0\"></option>";
	
	$query = $bd->submitQuery("Select * From `trilha_genero_album` Order By 
	`trilha_genero_album_album_nome`");
	
	for($i = 0; $i < mysql_num_rows($query); $i++){
		
		$genero .= "<option value=\"".mysql_result($query,$i,0)."\">"
		.mysql_result($query,$i,1)."</option>";
		
		
	}
	
	$genero .= "</select>";
	
	mysql_free_result($query);
	
	return $genero;
	
}


/**
 * insertElementosAlb()
 * 
 * Insere elementos de um álbum isto é para um álbum vão existir X número de cópias,
 * com um certo suporte, e com um dado número de suportes para uma cópia.
 *    
 * @param array $elems Nesta variável tem de constar de 3 em 3 registros por esta
 *  ordem (Número de cópias totais), (Char identificativo do número de suportes),
 *  (O id do suporte do álbum)  
 * @param integer $id_geral ID geral do álbum
 *   
 * @return void
 */
function insertElementosAlb( $elems , $id_geral ){
	
	$bd = ligarBD();
	
	//print_r($elems);
	$suporte_ids[0] = array();
	
	for($i = 0; $i < count( $elems ) ; $i+=3){
					
		if( 
		empty($elems[$i+1]) || $elems[$i+2] < 1 || $elems[$i] < 0 
		) $i = count( $elems );
		
		for( $y = 0;$y < count( $suporte_ids ) ;$y++ ){
			
			if($suporte_ids[$y] == $elems[$i+2]) $i = count( $elems );
			
		}
		
		array_push( $suporte_ids, $elems[$i+2]);
		
		if( $i < count( $elems ) ){		
				
		$bd->submitQuery("INSERT INTO `copi_album` (
			`album_geral_id_geral` ,
			`suport_album_id_suport_album` ,
			`copi_album_totais` 
			)
			VALUES (
			'$id_geral', '".$elems[$i+2]."', '".$elems[$i]."');");		
		
		$bd->submitQuery("
			INSERT INTO `num_suport_album` (
			`char_num_suport_album` ,
			`copi_album_album_geral_id_geral` ,
			`copi_album_suport_album_id_suport_album` 
			)
			VALUES (
			'".$elems[$i+1]."', '$id_geral', '".$elems[$i+2]."')");
			
			
		}	
							
	}
	
}


/**
 * listTrilGenAlb()
 * 
 * Listar os gêneros musicais em base de dados, caso $id_alb_track seja inferior a 
 * 1, caso contrário apenas lista o gênero do ID da trilha correspondente.
 *     
 * @param integer $id_alb_track ID da trilha do álbum
 * @param String $id_sel ID do select
 *   
 * @return String 
 */
function listTrilGenAlb( $id_alb_track, $id_sel ){
	
	$bd = ligarBD();
	
	if($id_alb_track > 0)
		$query = "SELECT `id_trilha_genero_album`,
		`trilha_genero_album_album_nome` FROM `trilha_genero_album`,`trilha_album`
		Where `id_trilha` = $id_alb_track
		And `id_trilha_genero_album` = `trilha_genero_album_id_trilha_genero_album`";
	else
		$query = "SELECT * FROM `trilha_genero_album`";
		
	$query = $bd->submitQuery($query);
	
	$buffer = 
	"<select class=\"alb_tril_gen_update\" id=\"$id_sel\" name=\"$id_sel\">";
	
	while( $row = mysql_fetch_array($query) ){
		
		$buffer .= "<option value=\"$row[0]\">$row[1]</option>";
		
	}
	
	$buffer .= "</select>";
	
	mysql_free_result($query);
	
	return $buffer;
	
}



/**
 * albInserirTrilh()
 * 
 * Inserir uma trilha a um álbum, retorna true caso a inserção tenha sido feita com
 * sucesso, caso contrário, retorna false.
 *    
 * @param integer $id_geral ID geral do álbum
 * @param String array $name_tri_alb Nome da trilha do álbum
 * @param String array $acerc_tri_alb Acerca desta trilha
 * @param integer array $sel_alb_track_gen ID do gênero musical da trilha
 * @param String array $time_tri_alb Tempo que a trilha tem de duração
 *       
 * @return boolean
 */
function albInserirTrilh($id_geral, $name_tri_alb, 
$acerc_tri_alb, $sel_alb_track_gen, $time_tri_alb ){

	$bd = ligarBD();
	
	$flag = true;
	
	
	
	$query = "INSERT INTO `trilha_album` (
	`id_trilha` ,
	`album_geral_id_geral` ,
	`trilha_genero_album_id_trilha_genero_album` ,
	`trilha_album_nome` ,
	`trilha_album_duracao` ,
	`trilha_album_acerca` 
	)
	VALUES ";
	
	$max_count = max(array(count($name_tri_alb),count($acerc_tri_alb)
	,count($sel_alb_track_gen),count($time_tri_alb)));
		
	if( count($name_tri_alb) != $max_count || count($acerc_tri_alb) != $max_count 
	|| count($sel_alb_track_gen) != $max_count || count($time_tri_alb) != $max_count )
	$flag = false;
	
	if($flag){
		
		$flag = false;
			
		for($i = 0; $i < $max_count; $i++){
			// Se o nome da música estiver por preencher ou o tempo não for válido
			// o resto do for não é executado
			if( empty($name_tri_alb[$i]) || !validTime((array) $time_tri_alb[$i]) ) 				
			continue;
			
			//Não permitir que um álbum ultrapasse as 24 horas impostas de áudio
			
	if( getNumDaysAlb($id_geral, $time_tri_alb[$i]) >= 1){
			
	echo (rawurlencode(
	"Não é permitido que a duração de áudio de um álbum seja superior ou igual a 24h."
	."\nA trilha de nome $name_tri_alb[$i] será ignorada.\n"
	));
			continue;	
	
	}
			
			if(!$flag) $flag = true;
			
			$name_tri_alb[$i] = clearGarbadge(
			rawurldecode($name_tri_alb[$i]), false, false);
			$acerc_tri_alb[$i] = clearGarbadge(
			rawurldecode($acerc_tri_alb[$i]), false, false);
			$sel_alb_track_gen[$i] = clearGarbadge(
			rawurldecode($sel_alb_track_gen[$i]), false, false);
			$time_tri_alb[$i] = clearGarbadge(
			rawurldecode($time_tri_alb[$i]), false, false);
			
			$query .= "(NULL , '$id_geral', '$sel_alb_track_gen[$i]'
			, '$name_tri_alb[$i]', '$time_tri_alb[$i]', '$acerc_tri_alb[$i]'),";
			
		}	
		
		$query {strlen($query)-1} = ";";
		
		if($flag){
			
			if( !$bd->submitQuery($query) ) $flag = false;
			
		}
		
	}
	
	return $flag;
	
}




/**
 * albUltrapasaLimite()
 * 
 * Determina o número de dias resultante da do tempo de áudio de um álbum quando 
 * se lhe adiciona um certo tempo.
 * 
 * Se tempo for ignorado apenas conta o número de dias de áudio de um álbum sem
 * adicionar um certo tempo 
 * 
 * @uses validTime() 
 *       
 * @param integer $id_elem_alb ID geral do álbum
 * @param float $limite Limite de tempo em horas
 * @param String $tempo Tempo a adicionar no formato HH:MM:SS
 *    
 * @return double
 */
function getNumDaysAlb($id_elem_alb, $tempo){			
			
			if(!validTime((array) $tempo)){
				
				$tempo = "00:00:00";
				
			}
			
			$bd = ligarBD();
			
			$duracao = "SELECT sum(HOUR(`trilha_album_duracao`))
			,sum(MINUTE(`trilha_album_duracao`)), sum(SECOND(`trilha_album_duracao`)) 
			FROM `trilha_album` Where `album_geral_id_geral` = $id_elem_alb";
			
			$duracao = $bd->submitQuery( $duracao );
			
			$tempo = explode(":", $tempo);
			
			//print_r($tempo);
			
			$dur = mktime( mysql_result($duracao,0,0)+$tempo[0], 
			mysql_result($duracao,0,1)+$tempo[1],
			mysql_result($duracao,0,2)+$tempo[2]);
			
			$dur = ( $dur-mktime(null,null,null,date("m"),date("d"),date("Y")) );
			
			//Determinar os dias na data
			$dur = ($dur/60/60/24);
				
			mysql_freeresult($duracao);
	
	
	return $dur;
}


/**
 * validTime()
 * 
 * Valida um conjunto de horas no formato HH:MM:SS, retorna true se forem válidas 
 * e false caso contrário.
 *    
 * @param array $time Uma hora no formato HH:MM:SS
 *   
 * @return boolean
 */
function validTime(array $time){
	
	$flag = true;
	
	$aux_time = "";
	
	for($i = 0; $i < count($time); $i++){
		$time[$i] = rawurldecode($time[$i]);
			
		if( !preg_match("/^[0-9]{2}:[0-5][0-9]:[0-5][0-9]$/", $time[$i]) ) 
			$flag = false;
		
		$aux_time = explode(":", $time[$i]);
		
		if( !is_numeric((integer) $aux_time[0]) || !is_numeric((integer) $aux_time[1]) 
		|| !is_numeric((integer) $aux_time[2]) ) $flag = false;
		
		if( $aux_time[2] > 59 || $aux_time[2] <0 || $aux_time[1] > 59 
		|| $aux_time[1] < 0 
		|| $aux_time[0] > 99 || $aux_time[0] < 0 ) $flag = false;
		
		//echo implode(":", array($aux_time[0],$aux_time[1],$aux_time[2]) );
		
		if( empty($aux_time[0]) && empty($aux_time[1]) && empty($aux_time[2]) )
		$flag = true;
		
		if(!$flag) break;
		
	}
	
	return $flag;
	
}



/**
 * apgAlbum()
 * 
 * Apagar um álbum com base no seu ID geral.
 *  
 * @param integer $id ID geral do álbum
 *   
 * @return void
 */
function apgAlbum( $id ){
	
	$bd = ligarBD();
	
	$id = clearGarbadge( $id, false, false);
		
		if( is_numeric( $id ) &&  $id > 0 ){
			
			$bd->submitQuery("Delete From `geral` Where `id_geral` = $id And `id_elemento` = 2");
			
			if(mysql_affected_rows() > 0){
				
				$bd->submitQuery(
				"Delete From `album` Where `geral_id_geral` = $id");
					
				$bd->submitQuery("Delete From `trilha_album`
				Where `album_geral_id_geral` = $id");
			
				$bd->submitQuery("Delete From `num_suport_album`
				Where `copi_album_album_geral_id_geral` = $id");
			
				$bd->submitQuery("Delete From `copi_album`
				Where `album_geral_id_geral` = $id");
					
				$bd->submitQuery( "Delete From `requesicao`
				Where `geral_id_geral` = $id" );
				
			}
			
		}
	
}



/**
 * getCharNumAlbum()
 * 
 * Obtém o char identificativo do número de suportes de um álbum em um dado suporte.
 *   
 * @param integer $id_alb ID geral do álbum
 * @param integer $id_sup ID do suporte
 *    
 * @return char
 */
function getCharNumAlbum( $id_alb, $id_sup){

	$bd = ligarBD();
	
	$id_alb = clearGarbadge($id_alb, false, false);
	
	$id_sup = clearGarbadge($id_sup, false, false);
	
	$bd = $bd->submitQuery( 
	"SELECT `char_num_suport_album` FROM `num_suport_album` Where
	`copi_album_album_geral_id_geral` = $id_alb And 
	`copi_album_suport_album_id_suport_album` = $id_sup" );
	
	if(mysql_numrows($bd) == 1){
		
		$bd = mysql_result($bd,0,0);
		
	} 
	
	/*elseif ( mysql_numrows($bd) > 1) {$bd = "Inconsistencia de dados";}*/
	
	else {
		
		$bd = "";
		
	}
	
	return $bd;

}


/**
 * tempoAlbum()
 * 
 * Soma o tempo de todas as trilhas de um álbum.
 * 
 * @todo No caso de o CD ter mais de 24 horas de música o mecanismo já não se 
 * comporta como o previsto. 
 *     
 * @param integer $id_alb ID geral do álbum
 * @param String $timestamp  timestamp formato de retorno da hora, para mais detalhes
 *  ver a função nativa do php date(). 
 *    
 * @return String
 */
function tempoAlbum( $id_alb, $timestamp){

	$bd = ligarBD();
	
	$duracao = $bd->submitQuery("SELECT sum(HOUR(`trilha_album_duracao`))
			,sum(MINUTE(`trilha_album_duracao`)), sum(SECOND(`trilha_album_duracao`)) 
			FROM `trilha_album` Where `album_geral_id_geral` = $id_alb");
			
	$dur = date( $timestamp, mktime( mysql_result($duracao,0,0), 
	mysql_result($duracao,0,1), mysql_result($duracao,0,2)) );
	
	mysql_free_result($duracao);
			
	return $dur;

}



/**
 * listarTrilhasAlbum()
 *  
 * Lista todas as trilhas de um álbum.
 *   
 * @param integer $id_alb ID do álbum
 * @param boolean $permissOrd Flag que indica se o utilizador tem permissões para 
 *  ordenar as faixas 
 *    
 * @return void
 */
function listarTrilhasAlbum( $id_alb, $permissOrd){
	
	if( is_numeric($id_alb) ){
		
	$bd = ligarBD();
	
	$id_alb = clearGarbadge($id_alb, false, false);
	
	$query = $bd->submitQuery(" 
	SELECT 
	`id_trilha`,`album_geral_id_geral`
	,`trilha_genero_album_album_nome`
	,`trilha_album_nome`
	,`trilha_album_duracao`
	,`trilha_album_acerca`
	,`trilha_album_ordem`
	FROM `trilha_album` Left Join `trilha_genero_album` On
	`id_trilha_genero_album`= `trilha_genero_album_id_trilha_genero_album`
	Where `album_geral_id_geral` = $id_alb 
	Order By `trilha_album_ordem`");
	
	
	echo "<div  style=\"border: 1px solid grey;margin-right:2px;\">";
	
	if($permissOrd) {
		
	echo "
	<table width=\"585\" boder=\"4\">	
			<tr><td width=\"146\">Nome</td>
				<td width=\"146\">Duração</td>
				<td width=\"146\">Gênero</td>
				<td width=\"146\">Acerca</td>
			</tr>
			<tr>
				<td width=\"146\">
					<input type=\"text\" class=\"forms\" name=\"name_new_trilh\"
					id=\"name_new_trilh\" />
				</td>
				<td width=\"146\">
					<input type=\"text\" class=\"forms\" name=\"time_tri_alb\" 
					id=\"time_tri_alb\" />
				</td>
				<td width=\"146\">".listTrilGenAlb( 0, "ref_gen_upda" )."</td>
				<td width=\"146\">
					<textarea class=\"forms\" style=\"width:146px;\" 
					name=\"acerc_tri_alb\" id=\"acerc_tri_alb\"></textarea>
				</td>
			</tr>
		</table>
		
		<table>
		<tr>
				<td>
				<input type=\"button\" style=\"width:50;\" 
				value=\"Novo gênero musical\" 
				id=\"new_trilh_alb_gen\" class=\"forms\" />
				</td>
				<td>
				<input type=\"button\" style=\"width:50;\" 
				value=\"Apagar gênero musical\" 
				id=\"del_trilh_alb_gen\" 
				class=\"forms\" />
				</td>
				<td>
				<input type=\"button\" value=\"Inserir música\" 
				id=\"ins_trilh_edit\" 
				class=\"forms\" />
				</td>
		</tr>
		</table>";
	}
	
	echo "<table width=\"585\" boder=\"4\">	
			<tr>";
				echo $permissOrd?"<td width=\"50\">Marca</td>":"";
				echo "<td width=\"146\">Nome</td>
				<td width=\"146\">Duração</td>
				<td width=\"146\">Gênero</td>
				<td width=\"146\">Acerca</td>
			</tr>
		</table>";
	
	echo $permissOrd?"
	<form name=\"apg_trilh_fo\" id=\"apg_trilh_fo\" method=\"post\">":"";
	
	if($permissOrd)
		echo "<table id=\"tracks_alb\" width=\"585\">";
	else
		echo "<table id=\"tracks_alb_user\"  width=\"585\">";
	for($i = 0; $i < mysql_num_rows($query); $i++){
		
		echo "<tr id=\"".mysql_result($query,$i,0)."#".mysql_result($query, $i, 6)."\">";
		
		echo $permissOrd?"<td width=\"50\"><input type=\"checkbox\" 
		value=\"".mysql_result($query,$i,0)."\" name=\"apg_trlh_a[$i]\"
		id=\"apg_trlh_a[$i]\" class=\"overchecktrilh\"/></td>":"";
		
		
		echo "<!--<td width=\"146\">".mysql_result($query,$i,0)."</td>-->
		<td width=\"146\">".mysql_result($query,$i,3)."</td>
		<td width=\"146\">".mysql_result($query,$i,4)."</td>
		<td width=\"146\">".mysql_result($query,$i,2)."</td>
		<td width=\"146\">".mysql_result($query,$i,5)."</td>
		</tr>";
		
	}
	
	echo "</table>";
	
	echo $permissOrd?"</form>":"";
	
	echo $permissOrd?
	"<input type=\"button\" class=\"forms\" value=\"Apagar seleccionadas\" 
	id=\"apg_trilhas_alb\" style=\"margin: 3px;\" />":"";
	
	echo "</div>";
	
	mysql_free_result($query);
	
	}

}
?>