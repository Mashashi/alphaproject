<?php
/**
 * Funções nativas dos elementos do tipo outros.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

/**
 * Incluir o ficheiro de funções nativas dos ao outros. 
 */
include_once(defined('IN_PHPAP') ? "funcoes_avan.php":"../funcoes_avan.php");

/**
 * apgOutro() 
 *
 * Apaga um item outro com base no seu ID 
 *
 * @param integer $id ID do item outro a apagar
 *   
 * @return void
 */
function apgOutro( $id ){
	
	$bd = ligarBD();
	
	$id = clearGarbadge( $id, false, false);
			
		if( is_numeric( $id ) &&  $id > 0 ){
			
			$bd->submitQuery("Delete From `geral` Where `id_geral` = '$id' And
			`id_elemento` = 3");
		
			if(mysql_affected_rows() > 0){
				
				$bd->submitQuery("Delete From `outro` Where `geral_id_geral` = $id");
			
				$bd->submitQuery("Delete From `num_suport_outro`
				Where `copi_outro_outro_geral_id_geral` = '$id'");
			
				$bd->submitQuery("Delete From `copi_outro`
				Where `outro_geral_id_geral` = '$id'");
					
				$bd->submitQuery("Delete From `requesicao`
				Where `geral_id_geral` = '$id'");
				
			}
			
		}
	
}




/**
 * listarSupOutro() 
 * 
 * 
 *
 * @uses getOutrosDisSup()
 *
 * @param integer $id_sel
 * @param integer $id_outr_req
 * @param boolean $flag
 * @param boolean $falg_user
 * 
 * @return String
 */
function listarSupOutro($id_sel, $id_outr_req, $flag, $falg_user){
		
		$bd = ligarBD();
		
		$suporte = "<select id=\"$id_sel\" class=\"forms\">";
		
		if($id_outr_req > 0 && !$flag){
				
				
					
				$query = 
				$bd->submitQuery("Select `id_suport_outro`,`suport_outro_nome`
				From `suport_outro`,`copi_outro` Where `outro_geral_id_geral` = $id_outr_req
				And `suport_outro_id_suport_outro` = `id_suport_outro`
				Order By `suport_outro_nome`");
				
				
				
			
		} else if($id_outr_req > 0){

			$query = $bd->submitQuery("
			Select `id_suport_outro`,`suport_outro_nome` 
			From `suport_outro`,`copi_outro`,`requesicao` 
			Where `suport_outro_id_suport_outro` = `id_suport_outro` 
			And `id_requesicao` = $id_outr_req 
			And `outro_geral_id_geral` = 
			(Select `geral_id_geral` From `requesicao` Where `id_requesicao` = $id_outr_req )
			Order By `suport_outro_nome`");
			
			if($flag){
			
				$id_sup = 
				$bd->submitQuery("Select `requesicao_id_suporte`
				From `requesicao` Where `id_requesicao` = $id_outr_req");
				
				if(mysql_num_rows($id_sup) == 1) $id_sup = mysql_result($id_sup,0,0);
				else $id_sup = 0;
			
			}
			
		} else {
			
				$query = $bd->submitQuery("Select * From `suport_outro` 
				Order By `suport_outro_nome`");
			
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
					getOutrosDisSup( $id_outr_req, mysql_result($query, $i, 0)) > 0  
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
 * printElementosOutro() 
 *
 *
 * @use listarSupOutro()
 *
 * @param integer $id_outro
 *  
 * @return String
 */
function printElementosOutro( $id_outro ){
	
	$suporte = listarSupOutro("sup_fil", 0, false, false);
	
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
	<input type=\"button\" class=\"forms\" name=\"new_sup_outro\" 
	value=\"Inserir  novo suporte\" id=\"new_sup_outro\" />	
	<input type=\"button\" class=\"forms\" name=\"del_sup_outro\" 
	value=\"Apagar suporte\"  id=\"del_sup_outro\" />	
	</p>
			
	<input type=\"button\" class=\"forms\" value=\"Adicionar número de cópias\" 
	name=\"new_sup_sub\" id=\"new_sup_sub\" />
			
	<input type=\"button\" class=\"forms\" name=\"del_sup_sub\" 
	value=\"Remover número de cópias\"  id=\"del_sup_sub\" />";
		
	if( $id_outro > 0 ){
		
		$bd = ligarBD();
		
		$query = "Select Count(*) From `outro` Where `geral_id_geral` = $id_outro";
		
		$temp = "";
		
		$temp_2 = "";
		
		if( mysql_result( $bd->submitQuery($query) , 0 ) == 1 ){
			
			$query =
			"SELECT `copi_outro_totais`,`char_num_suport_outro`,
			`suport_outro_id_suport_outro` 
			FROM `copi_outro` Join `num_suport_outro`
			ON `copi_outro_outro_geral_id_geral` = `outro_geral_id_geral` And
			`suport_outro_id_suport_outro` = `copi_outro_suport_outro_id_suport_outro`
			Where `outro_geral_id_geral` = $id_outro";
			
			$query = $bd->submitQuery($query);
			
			while($row = mysql_fetch_row($query)){
				
				$query_2 = "Select `suport_outro_nome` From `suport_outro` 
				Where `id_suport_outro` = $row[2]";
				
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
 * dirListOutros() 
 *
 * @param String $id_sel_nom
 * @param integer $id_outro
 * @param String $more_query
 * 
 * @return String
 */
function dirListOutros( $id_sel_nom, $id_outro, $more_query ){
	
	$bd = ligarBD();
	
	$buffer = "";
	
	$dir = "";
	
	if($id_outro > 0){
		
		$query = $bd->submitQuery("Select `direito_outro_outro_id_direito_outro` 
		From `outro` Where `geral_id_geral` = $id_outro ");
		
		if( mysql_numrows( $query ) == 1 ){
			
			$dir = mysql_result($query,0,0) ;
			
			$query = $bd->submitQuery("Select * From `direito_outro` Order By `direito_outro_outro_nome`");
			
			for($i = 0; $i < mysql_numrows($query); $i++){
				
				if(mysql_result($query,$i,0) == $dir){
					
					$dir = "<option value=\"".mysql_result($query,$i,0)."\">".mysql_result($query,$i,1)."</option>";
				
				}
				$buffer .= "<option value=\"".mysql_result($query,$i,0)."\">".mysql_result($query,$i,1)."</option>";
			
			}	
		
		} else $id_outro = 0;
		
	} 
	
	if ($id_outro < 1) {
	
		$query = $bd->submitQuery("Select * From `direito_outro` Order By `direito_outro_outro_nome`");
	
		for($i = 0; $i < mysql_numrows($query); $i++){
		
			$buffer .= "<option value=\"".mysql_result($query,$i,0)."\">".mysql_result($query,$i,1)."</option>";
		
		}
		
	}
	
	$buffer = "<label for=\"$id_sel_nom\"><select id=\"$id_sel_nom\" name=\"$id_sel_nom\">$dir
	<option value=\"0\"> </option>$buffer";
	
	$buffer .= "</select></label><br />
	<input type=\"button\" class=\"forms\" name=\"inser_dir_aut\" id=\"inser_dir_aut\" value=\"Inserir novo direito\" />
	<input type=\"button\" class=\"forms\" name=\"apg_dir_aut\" id=\"apg_dir_aut\" value=\"Apagar direito\" />";
			
	return $buffer;
	
}



/**
 * insertElementosOutros() 
 *
 * @param array $elems
 * @param integer $id_geral
 *
 * @return void
 */
function insertElementosOutros( $elems , $id_geral ){
	
	$bd = ligarBD();
	
	$suporte_ids[0] = array();
	
	$direito_outro = $bd->submitQuery("Select `direito_outro_outro_id_direito_outro`
	From `outro` Where `geral_id_geral` = $id_geral");
	
	if( mysql_numrows($direito_outro) == 1){
	
	$direito_outro = mysql_result($direito_outro,0,0);
	
	//echo $direito_outro;
	
	for($i = 0; $i < count( $elems ) ; $i+=3){
					
		if( 
		empty($elems[$i+1]) || $elems[$i+2] < 1 || $elems[$i] < 0 
		) $i = count( $elems );
		
		for( $y = 0;$y < count( $suporte_ids ) ;$y++ ){
			
			if($suporte_ids[$y] == $elems[$i+2]) $i = count( $elems );
			
		}
		
		array_push( $suporte_ids, $elems[$i+2] );
		
		if( $i < count( $elems ) ){		
		
		
		
		$bd->submitQuery("INSERT INTO `copi_outro` (
			`outro_geral_id_geral` ,
			`outro_direito_outro_outro_id_direito_outro`,
			`suport_outro_id_suport_outro` ,
			`copi_outro_totais` 
			)
			VALUES (
			'$id_geral', '$direito_outro', '".$elems[$i+2]."', '".$elems[$i]."');");		
			
		$bd->submitQuery("
			INSERT INTO `num_suport_outro` (
			`char_num_suport_outro` ,
			`copi_outro_outro_geral_id_geral` ,
			`copi_outro_suport_outro_id_suport_outro`,
			`copi_outro_outro_direito_outro_outro_id_direito_outro`
			)
			VALUES (
			'".$elems[$i+1]."', '$id_geral', '".$elems[$i+2]."', '$direito_outro')");
			
			
		}	
							
	}
	
	}
}


/**
 * getOutrosDisSup() 
 *
 * @param integer $id_outro
 * @param integer $id_sup
 * 
 * @return mixed
 */
function getOutrosDisSup($id_outro, $id_sup){
	
	$bd = ligarBD();
	
	$id_outro = clearGarbadge($id_outro, false, false);
	
	$id_sup = clearGarbadge($id_sup, false, false);
	
	$bd = $bd->submitQuery( 
	"Select If(`copi_outro_totais` >
	( Select Count(*) From `requesicao` Where `geral_id_geral` = '$id_outro' And ( `requesicao_dat_min`>= '"
	.date("Ymd", mktime(0, 0, 0, date("m"), date("d")-OUTRO_REQ, date("Y") )).
	"' Or `requesicao_dia_levantado` <> '00000000') 
	And `requesicao_id_suporte` = '$id_sup' ), 
	(`copi_outro_totais` - 
	( Select Count(*) From `requesicao` Where `geral_id_geral` = '$id_outro' 
	And (`requesicao_dat_min` >= '"
	.date("Ymd", mktime(0, 0, 0, date("m"), date("d")-OUTRO_REQ, date("Y") )).
	"' Or `requesicao_dia_levantado` <> '00000000') 
	And `requesicao_id_suporte` = '$id_sup' )), 0) As 'disponiveis'
	FROM `copi_outro` Where `outro_geral_id_geral` = '$id_outro' 
	And `suport_outro_id_suport_outro` = '$id_sup'");
	
	if(mysql_num_rows($bd) > 0)
		$bd = mysql_result($bd, 0,0);
	else
		$bd = null;
	
	return $bd;
	
}


/**
 * getCharNumOutro()
 * 
 * 
 * 
 * @param integer $id_outr ID do geral do outro 
 * @param integer $id_sup
 * 
 * @return mixed
 */
function getCharNumOutro( $id_outr, $id_sup){

	$bd = ligarBD();
	
	$id_outr = clearGarbadge($id_outr, false, false);
	
	$id_sup = clearGarbadge($id_sup, false, false);
	
	$bd = $bd->submitQuery( 
	"SELECT `char_num_suport_outro` FROM `num_suport_outro` Where
	`copi_outro_outro_geral_id_geral` = '$id_outr' And 
	`copi_outro_suport_outro_id_suport_outro` = '$id_sup'" );
	
	if(mysql_numrows($bd) == 1){
		
		$bd = mysql_result($bd,0,0);
		
	} elseif ( mysql_numrows($bd) > 1) {
		
		$bd = "Inconsistencia de dados";
		 
	} else {
		
		$bd = "";
		
	}
	
	return $bd;

}
?>