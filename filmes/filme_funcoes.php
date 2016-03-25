<?php
/**
 * Funções relativas aos filmes  
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

/**
 *  
 */
include_once(defined('IN_PHPAP') ? "funcoes_avan.php":"../funcoes_avan.php");




/**
 * 
 * @param 
 *   
 * @return 
 */
function printListSuports($id_sel, $id_fil_req, $flag, $falg_user){
		
		$bd = ligarBD();
		
		$suporte = "<select id=\"$id_sel\" class=\"forms\">";
		
		if($id_fil_req > 0 && !$flag){
			
			//getFilmesDisSup( $id_fil_req, );
			
				$query = 
				$bd->submitQuery("Select `id_suport_filme`,`suport_filme_nome`
				From `suport_filme`,`copi_filme` Where `filme_geral_id_geral` = $id_fil_req
				And `suport_filme_id_suport_filme` = `id_suport_filme`
				Order By `suport_filme_nome`");
				
			
		} else if($id_fil_req > 0){

			$query = $bd->submitQuery("
			Select `id_suport_filme`,`suport_filme_nome` 
			From `suport_filme`,`copi_filme`,`requesicao` 
			Where `suport_filme_id_suport_filme` = `id_suport_filme` 
			And `id_requesicao` = $id_fil_req 
			And `filme_geral_id_geral` = 
			(Select `geral_id_geral` From `requesicao` Where `id_requesicao` = $id_fil_req )
			Order By `suport_filme_nome`");
			
			if($flag){
			
				$id_sup = 
				$bd->submitQuery("Select `requesicao_id_suporte`
				From `requesicao` Where `id_requesicao` = '$id_fil_req'");
				
				if(mysql_num_rows($id_sup) == 1) $id_sup = mysql_result($id_sup,0,0);
				else $id_sup = 0;
			
			}
			
		} else {
			
				$query = $bd->submitQuery("Select * From `suport_filme` Order By `suport_filme_nome` ");
			
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
					getFilmesDisSup( $id_fil_req, mysql_result($query, $i, 0)) > 0  
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
 * 
 * @param 
 *   
 * @return 
 */
function tipoSomListEdit( $id_tip_som ){
	
	$bd = ligarBD();
	
	$queryy = $bd->submitQuery("Select * From `tipo_som_filme` Order By `tipo_som_filme_nome`");
	
	$tipo_som_t = "<select id=\"tip_som_fil\">";
	
	for($i = 0; $i < mysql_num_rows($queryy); $i++){
		
		if($i == 0){
		
			while( $row = mysql_fetch_array($queryy) ){
			
				if( $row[0]  == $id_tip_som ){
					
					$tipo_som_t .= "<option value=\"$row[0]\">$row[1]</option>";
				
					break;
				
				}
			
			}
			
			$tipo_som_t .= "<option value=\"0\"></option>";	
			
		} 
			
		if( $id_tip_som != mysql_result($queryy,$i,0) ){
			 	
			$tipo_som_t .= "<option value=\"".mysql_result($queryy,$i,0)."\">"
			.mysql_result($queryy,$i,1)."</option>";
		
		}
				
	}
	
	$tipo_som_t .= "</select>";
	
	mysql_free_result($queryy);
	
	return $tipo_som_t;
	
}




/**
 * 
 * @param 
 *   
 * @return 
 */
/*function tipSomList(){
	
	$bd = ligarBD();
	
	$query = $bd->submitQuery("Select * From 
	`tipo_som_filme` Order By `tipo_som_filme_nome`");
	
	$tipo_som_t = "<select id=\"tip_som_fil\"><option value=\"0\"></option>";
	
	for($i = 0; $i < mysql_num_rows($query); $i++){
		
		$tipo_som_t .= "<option value=\"".mysql_result($query,$i,0)."\">"
		.mysql_result($query,$i,1)."</option>";
		
	}
	
	$tipo_som_t .= "</select>";
	
	mysql_free_result($query);
	
	return $tipo_som_t;
	
}*/




/**
 * 
 * @param 
 *   
 * @return 
 */
function tipoGenListEdit( $id_tip_gen ){
	
	$bd = ligarBD();
	
	$queryy = $bd->submitQuery("Select * From `genero_filme` Order By `genero_filme_nome`");
	
	$genero = "<select id=\"gen_fil\">";
	
	for($i = 0; $i < mysql_num_rows($queryy); $i++){
	
		if($i == 0){
			
			
		while( $row = mysql_fetch_array($queryy) ){
			
			if( $row[0] == $id_tip_gen ){
				
				$genero .= "<option value=\"$row[0]\">$row[1]</option>";
				
				break;
				
			}
			
		}
		
			$genero .= "<option value=\"0\"></option>";
			
		} 
			
		if( $row[0] != mysql_result($queryy,$i,0) ){
			
			$genero .= "<option value=\"".mysql_result($queryy,$i,0)."\">"
			.mysql_result($queryy,$i,1)."</option>";
			
		}	
		
	}
	
	$genero .= "</select>";
	
	mysql_free_result($queryy);
	
	return $genero;
	
}

/*function tipoGenList(){
	
	$bd = ligarBD();
	
	$genero = "<select id=\"gen_fil\"><option value=\"0\"></option>";
	
	$query = $bd->submitQuery("Select * From `genero_filme` Order By `genero_filme_nome`");
	
	for($i = 0; $i < mysql_num_rows($query); $i++){
		
		$genero .= "<option value=\"".mysql_result($query,$i,0)."\">"
		.mysql_result($query,$i,1)."</option>";
		
		
	}
	
	$genero .= "</select>";
	
	mysql_free_result($query);
	
	return $genero;
	
}*/




/**
 * 
 * @param 
 *   
 * @return 
 */
function relListEdit( $id_filme ){
	
	$rels = "<select multiple=\"multiple\" id=\"rel_fil\" size=\"5\">";
	
	if(is_numeric($id_filme)){
		
		$bd = ligarBD();
	
		$queryy = $bd->submitQuery("Select `realizador_filme_nome` 
		From `realizador_filme` 
		Where `filme_geral_id_geral` = ".$id_filme." Order By `realizador_filme_nome` Asc");
		
		while( $row = mysql_fetch_array($queryy) ){
		
			$rels .= "<option value=\"$row[0]\">$row[0]</option>";
		
		}
	
		mysql_free_result($queryy);
	
	}
	
	$rels .= "</select>";
	
	return $rels;
	
}




/**
 * 
 * @param 
 *   
 * @return 
 */
function printElementos( $id_filme ){
	
	$suporte = printListSuports("sup_fil", 0, false, false);
	
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
	<input type=\"button\" class=\"forms\" name=\"new_sup\" 
	value=\"Inserir  novo suporte\" id=\"new_sup\" />	
	<input type=\"button\" class=\"forms\" name=\"del_sup\" 
	value=\"Apagar suporte\"  id=\"del_sup\" />	
	</p>
			
	<input type=\"button\" class=\"forms\" value=\"Adicionar número de cópias\" 
	name=\"new_sup_sub\" id=\"new_sup_sub\" />
			
	<input type=\"button\" class=\"forms\" name=\"del_sup_sub\" 
	value=\"Remover número de cópias\"  id=\"del_sup_sub\" />";
		
	if( $id_filme > 0 ){
		
		$bd = ligarBD();
		
		$query = "Select Count(*) From `filme` Where `geral_id_geral` = $id_filme";
		
		$temp = "";
		
		$temp_2 = "";
		
		if( mysql_result( $bd->submitQuery($query) , 0 ) == 1 ){
			
			$query =
			"SELECT `copi_filme_totais`,`char_num_suport_filme`,
			`suport_filme_id_suport_filme` 
			FROM `copi_filme` join `num_suport_filme`
			ON `copi_filme_filme_geral_id_geral` = `filme_geral_id_geral` And
			`suport_filme_id_suport_filme` = `copi_filme_suport_filme_id_suport_filme`
			Where `filme_geral_id_geral` = $id_filme";
			
			$query = $bd->submitQuery($query);
			
			while($row = mysql_fetch_row($query)){
				
				$query_2 = "Select `suport_filme_nome` From `suport_filme` 
				Where `id_suport_filme` = $row[2]";
				
				$query_2 = $bd->submitQuery($query_2);
				
				$query_2 = mysql_numrows($query_2) != 1 ? 
				"Suporte não definido" : mysql_result($query_2,0);
				
				$temp .= 
				"<option value=\"$row[0]|$row[1]|$row[2]\">$row[0]|$row[1]|$query_2</option>";
				
				if( !empty($temp_2) ) $temp_2 .= "|";
				
				$temp_2 .= "$row[0]|$row[1]|$row[2]";
				
			}
			
			
			
			
			
			$elementos = "
			<input type=\"hidden\" name=\"data_filme_copi\" value=\"$temp_2\" 
			id=\"data_filme_copi\" />
			Elemento:<br /><select name=\"copi_mos_film\" id=\"copi_mos_film\" size=\"5\">";
			
			/*SELECT Distinct `copi_filme_totais`,`char_num_suport_filme`,`suport_filme_nome`,				   `id_suport_filme` FROM 				   
			`copi_filme`,`num_suport_filme`,`suport_filme`
			WHERE `copi_filme_filme_geral_id_geral` = `filme_geral_id_geral` And 								`filme_geral_id_geral`
			= $id_filme*/

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
 * 
 * @param 
 *   
 * @return 
 */
function insertElementos( $elems , $id_geral, $gen_fil, $id_som ){
	
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
		
		$bd->submitQuery("
			INSERT INTO `copi_filme` (
			`filme_genero_filme_filme_id_genero_filme` ,
			`filme_geral_id_geral` ,
			`filme_tipo_som_filme_id_tipo_som_filme` ,
			`suport_filme_id_suport_filme` ,
			`copi_filme_totais` 
			)
			VALUES (
			'$gen_fil', '$id_geral', '$id_som', '".$elems[$i+2]."'
			, '".$elems[$i]."')");
				
					
		$bd->submitQuery("
			INSERT INTO `num_suport_filme` (
			`char_num_suport_filme` ,
			`copi_filme_filme_geral_id_geral` ,
			`copi_filme_filme_genero_filme_filme_id_genero_filme` ,
			`copi_filme_filme_tipo_som_filme_id_tipo_som_filme` ,
			`copi_filme_suport_filme_id_suport_filme` 
			)
			VALUES (
			'".$elems[$i+1]."', '$id_geral', '$gen_fil', '$id_som', '".$elems[$i+2]."')");
		}	
							
	}
	
}



/**
 * 
 * @param 
 *   
 * @return 
 */
function apgFilme( $id ){
	
	$bd = ligarBD();
	
	$id = clearGarbadge( $id, false, false);
			
		if( is_numeric( $id ) &&  $id > 0 ){
			
				$bd->submitQuery("Delete From `geral` Where `id_geral` = '$id' And
				`id_elemento` = '1'");
				
				if(mysql_affected_rows() > 0){
				
				$bd->submitQuery("Delete From `filme` Where `geral_id_geral` = '$id'");
				
				$bd->submitQuery("Delete From `realizador_filme`
				Where `filme_geral_id_geral` = '$id'");
				
				$bd->submitQuery("Delete From `num_suport_filme`
				Where `copi_filme_filme_geral_id_geral` = '$id'");
				
				$bd->submitQuery("Delete From `copi_filme`
				Where `filme_geral_id_geral` = '$id'");
				
				$bd->submitQuery("Delete From `requesicao`
				Where `geral_id_geral` = '$id'");
				
			}
			
		}
	
}



/**
 * 
 * @param 
 *   
 * @return 
 */
function insereRel($rels, $id_geral, $tip_som, $gen_fil){
	
	if( is_numeric($id_geral) && is_numeric($tip_som) && is_numeric($gen_fil) ){
		
		$bd = ligarBD();
		
		for($i = 0; $i < count($rels) ; $i++){
		
			if( ! $bd->submitQuery("INSERT INTO `realizador_filme` (
			`id_realizador`,
			`filme_genero_filme_filme_id_genero_filme`,
			`filme_geral_id_geral`,
			`filme_tipo_som_filme_id_tipo_som_filme`,
			`realizador_filme_nome`
			)
			VALUES (
				NULL , '$gen_fil', '$id_geral', '$tip_som', '"
				.rawurldecode( clearGarbadge($rels[$i], false, false) )."'
			);
			") ) break;
	
	 	}

	}
	
}



/**
 * 
 * @param 
 *   
 * @return 
 */
function getFilmesDisSup($id_filme, $id_sup){
	
	$bd = ligarBD();
	
	$bd = $bd->submitQuery( 
	"
	Select If(`copi_filme_totais` >
	( Select Count(*) From `requesicao` Where `geral_id_geral` = '$id_filme' And (					  `requesicao_dat_min`>= '"
	.date("Ymd", mktime(0, 0, 0, date("m"), date("d")-FILME_REQ, date("Y") )).
	"' Or `requesicao_dia_levantado` <> '00000000') 
	And `requesicao_id_suporte` = '$id_sup' ), 
	(`copi_filme_totais` - 
	( Select Count(*) From `requesicao` Where `geral_id_geral` = '$id_filme' 
	And (`requesicao_dat_min` >= '"
	.date("Ymd", mktime(0, 0, 0, date("m"), date("d")-FILME_REQ, date("Y") )).
	"' Or `requesicao_dia_levantado` <> '00000000') 
	And `requesicao_id_suporte` = '$id_sup' )), 0) As 'disponiveis'
	FROM `copi_filme` Where `filme_geral_id_geral` = '$id_filme' 
	And `suport_filme_id_suport_filme` = '$id_sup'
	");
	
	/*echo "
	SELECT IF(`copi_filme_totais` >
	( Select Count(*) From `requesicao` Where `geral_id_geral` = '$id_filme' And (					 `requesicao_dat_min` 
	>= '"
	.date("Ymd", mktime(0, 0, 0, date("m"), date("d")-FILME_REQ, date("Y") )).
	"' Or `requesicao_dia_levantado` <> '00000000') 
	And `requesicao_id_suporte` = '$id_sup' ), 
	(`copi_filme_totais` - 
	( Select Count(*) From `requesicao` Where `geral_id_geral` = '$id_filme' 
	And (`requesicao_dat_min` >= '"
	.date("Ymd", mktime(0, 0, 0, date("m"), date("d")-FILME_REQ, date("Y") )).
	"' Or `requesicao_dia_levantado` <> '00000000') 
	And `requesicao_id_suporte` = '$id_sup' )), 0) As 'disponiveis'
	FROM `copi_filme` Where `filme_geral_id_geral` = '$id_filme' 
	And `suport_filme_id_suport_filme` = '$id_sup'
	";*/
	
	if(mysql_num_rows($bd) > 0)
		$bd = mysql_result($bd, 0,0);
	else
		$bd = null;
	
	return $bd;
	
}



/**
 * 
 * @param 
 *   
 * @return 
 */
function getCharNumFilme( $id_filme, $id_sup){

	$bd = ligarBD();
	
	$id_filme = clearGarbadge($id_filme, false, false);
	
	$id_sup = clearGarbadge($id_sup, false, false);
	
	$bd = $bd->submitQuery( 
	"SELECT `char_num_suport_filme` FROM `num_suport_filme` Where
	`copi_filme_filme_geral_id_geral` = '$id_filme' And 
	`copi_filme_suport_filme_id_suport_filme` = '$id_sup'" );
	
	if(mysql_numrows($bd) == 1){
		
		$bd = mysql_result($bd,0,0);
		
	} elseif ( mysql_numrows($bd) > 1) {
		
		$bd = "Inconsistencia de dados";
		 
	} else {
		
		$bd = "";
		
	}
	
	return $bd;

}



/**
 * 
 * @param 
 *   
 * @return 
 */
function supReq($id_req){

	$bd = ligarBD();

	$bd = $bd->submitQuery("Select `suport_filme_nome` From `suport_filme` 
	Where `id_suport_filme` = (Select `requesicao_id_suporte` From `requesicao` Where `id_requesicao` = '$id_req')");
	
	if(mysql_num_rows($bd) > 0)
		$bd = mysql_result($bd, 0,0);
	else
		$bd = null;
	
	return $bd;
	
}


/**
 * 
 * @param 
 *   
 * @return 
 */
function giveIdRelated ( $id, array $keywords ){
	
	$query = "";
	
	if( existeNaBd("filme", "geral_id_geral", $id) == 1){
		
		$bd = ligarBD();
		
		$filme_query = $bd->submitQuery("Select * From `filme` Where `geral_id_geral` = $id");
		
		$filme_genero = mysql_result($filme_query, "genero_filme_filme_id_genero_filme", 1);
		
		/*$filme_genero = mysql_result($filme, 0, 0);
		$filme_genero = mysql_result($filme, 0, 0);
		$filme_genero = mysql_result($filme, 0, 0);*/
		
		$query = "Select * From `filme` Where ";
		
		if(count( $keywords ) > 0) $query .= "`filme_sinopse` ";
		
		for($i = 0; $i < count( $keywords );$i++){
		
			$keywords[$i] = clearGarbadge( $keywords[$i], false, false );
			
			$query .= " Like \"%$keywords[$i]%\" Or "; 
		
		}
		
		if(count( $keywords ) > 0){
		
			$query = substr( $query, 0, strlen($query )-4 );
			
			$query .= " And ";
		
		}
		
		$query .= "`genero_filme_filme_id_genero_filme` = $filme_genero And `geral_id_geral` <> $id ORDER BY RAND() Limit 0,7";
		
		$query = $bd->submitQuery( $query );
		
		
	}
	
	return $query;
	
}
?>