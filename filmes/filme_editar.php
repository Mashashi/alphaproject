<?php
/**
 * Editar um filme.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

 defined('IN_PHPAP') ? include_once("filmes/filme_funcoes.php"):include_once("filme_funcoes.php");
 
 if(!isset($_SESSION['user_pass'])) session_start();
 
 validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
 
 if ( isset( $_POST["titulo"] ) && isset( $_POST["etiqueta"] ) && isset( $_POST["sinopse"] ) && isset( $_POST["ano"] ) && isset( $_POST["duracao"] ) && isset( $_POST["requesitavel"] ) 
&& isset( $_POST["slogan"] ) && isset( $_POST["tipo_som"] )  
&& isset( $_POST["gen_filme"] ) && isset( $_POST["class_film"] ) && isset( $_POST["id_filme"] ) 
&& $_SESSION['estat_carac'][6]){
	
	$id_film = $_POST["id_filme"]; 
	
	if( ! is_numeric($id_film) ) die();
	
	include_once("filme_funcoes.php");
	
	$fil_car[8] = 
	clearGarbadge(rawurldecode($_POST["titulo"]),false,false);	
	
	$fil_car[6] = 
	clearGarbadge($_POST["etiqueta"], false, false);	
	
	$fil_car[1] = 
	clearGarbadge(rawurldecode($_POST["sinopse"]),true,true);
	
	$fil_car[2] = 
	clearGarbadge($_POST["ano"],false,false);
	
	$fil_car[3] = 
	substr( clearGarbadge($_POST["duracao"],false,false),0,strlen($fil_car[3])-3);
	
	$fil_car[7] = 
	clearGarbadge($_POST["requesitavel"],false,false);
	
	$fil_car[0] = 
	clearGarbadge(rawurldecode($_POST["slogan"]),false,false);
	
	$fil_car[4] = 
	clearGarbadge($_POST["tipo_som"],false,false);
	
	$fil_car[9] = 
	clearGarbadge($_POST["gen_filme"],false,false);
	
	$fil_car[5] = 
	clearGarbadge($_POST["class_film"],false,false);
	
	$fil_car[10] = 
	explode( "|", clearGarbadge(rawurldecode($_POST["num_copi_film"]),false,false) );
	
	$ver = trim( $fil_car[8] );
	
	if( empty( $ver ) ) 
			die ( 
			rawurlencode
("O título do filme não pode estar em branco ou ser constituido somente por espaços em branco.") 
			);
		
		if( !strWordCount($fil_car[8]," ",40) )
			die ( 
			rawurlencode
("O título do filme não pode ter mais de 40 caractéres consecutivos s/um espaço em branco.") 
			);
		
		
		
		
		
		/*if( empty( $fil_car[6] ) )
			die ( 
			rawurlencode
("A etiqueta do filme não foi preenchida.") 
			);
			
		$bd = mysql_result($bd->submitQuery("Select Count(*) - (Select Count(*) From `filme` 
		Where `geral_id_geral` = $id_film And `filme_etiqueta` like '$fil_car[6]') 
		From `filme` 
		Where `filme_etiqueta` like '$fil_car[6]'"), 0, 0);

	
		if($bd > 0)
			die ( 
			rawurlencode
("A etiqueta já esta atribuida a outro filme.") 
			);
			
		if( strlen( $fil_car[2] ) != 4 || !is_numeric( $fil_car[2] ) || $fil_car[2] > date(Y)
		|| $fil_car[2] < 1893 ) //O cinema foi inventado em 1895 pelos irmãos Lumier ;)
			die ( 
			rawurlencode
("O ano do filme não é válido.")
			);
		
		if( $fil_car[3] < 0 || $fil_car[3] > 9999 || !is_numeric($fil_car[3]) ) 
			die (rawurlencode
("A duração do filme não foi preenchida.")
			);*/
		
		$bd = ligarBD();
		
		if( ($fil_car[5]>10) || ($fil_car[5]< 0)){
			
			die( rawurlencode("Classificação IMDB inválida") );
				
		}
	
	if( $fil_car[7] != true && $fil_car[7] != false ) die(); 
	
	$bd = ligarBD();
	
	$rels = $_POST['realizadores'];
	
	$bd->submitQuery("Delete From `realizador_filme` Where `filme_geral_id_geral` = $id_film");
	
	insereRel($rels, $id_film, $fil_car[4], $fil_car[9]);
	
	$bd->submitQuery("Delete From `num_suport_filme` 
	Where `copi_filme_filme_geral_id_geral` = $id_film");
	
	$bd->submitQuery("Delete From `copi_filme` 
	Where `filme_geral_id_geral` = $id_film");
	
	//print_r($rels);
	
	if(	!empty( $fil_car[10] ) )
		insertElementos( $fil_car[10] , $id_film, $fil_car[9], $fil_car[4] );
	
	if( $bd->submitQuery("UPDATE `filme` SET 
	`filme_sinopse` = '$fil_car[1]', 
	`filme_etiqueta` = '$fil_car[6]',
	`filme_nome` = '$fil_car[8]',
	`filme_slogan` = '$fil_car[0]',
	`filme_ano` = '$fil_car[2]',
	`filme_duracao` = '$fil_car[3]',
	`filme_imdb` = '$fil_car[5]',
	`filme_requesitavel` = $fil_car[7],
	`tipo_som_filme_id_tipo_som_filme` = '$fil_car[4]',
	`genero_filme_filme_id_genero_filme` = '$fil_car[9]'
	WHERE 
	`filme`.`geral_id_geral` = $id_film
	") ) 
	echo rawurlencode("Dados do filme $fil_car[8] actualizados com sucesso.");
	else
	echo rawurlencode("É impossível actualizar os dados do filme $fil_car[8], a este momento.");
	
 } else if ( defined("IN_PHPAP") && $_SESSION['estat_carac'][6] && $_GET['filme'] 
 && is_numeric($_GET['filme']) ) {
	
	/*Características do filme*/
	$query = $bd->submitQuery("Select * From `filme` Where `geral_id_geral` = ".$_GET['filme']);
	
	echo "
	<div class=\"gestaopremain\" style=\"text-align:left;\">
 	
	   	<div id=\"gestaopremaintitle\">
		  <p>A editar filme</p>
		</div>";
	
	if( mysql_numrows($query) == 1){
		
		
		
	
	$tipo_som_t = tipoSomListEdit( mysql_result($query,0,2) );
	
	$genero = tipoGenListEdit( mysql_result($query,0,1) );
	
	$rels = relListEdit( $_GET['filme'] );
	
	$suporte = printListSuports("sup_fil",0, false, false);
	
	$elementos = printElementos( $_GET['filme'] );
	
	 
	 
	 
	$dur = mysql_result($query,0,8);
	
	while( strlen($dur) < 4 ) $dur = "0".$dur; $dur .= "min";
	
	$checked =	mysql_result($query,0,10) ? "checked=\"checked\"" : "";
	
	$classi_imdb = mysql_result( $query, 0, 9);
	
	if( $classi_imdb != 0){
		
		if( preg_match("/\./", $classi_imdb)  )
			$classi_imdb = "0".$classi_imdb;
		else if($classi_imdb != 10)
			$classi_imdb = "0".$classi_imdb.".0";
		
	}
	
	
	
				
	echo "
	
	<table border=\"0\">
			
			<tr><td><div class=\"area\" style=\"width: auto;\">Título</div></td>
			<td><input class=\"forms\" maxlength=\"70\" type=\"text\" name=\"nom_fil\" 
			id=\"nom_fil\" value=\"".mysql_result($query,0,4)."\" />
			</td></tr>
			
			<tr><td>
			<div class=\"area\" style=\"width: auto;\">Etiqueta</div>
			</td><td><input class=\"forms\" maxlength=\"25\" type=\"text\" 
			name=\"eti_fil\" value=\"".mysql_result($query,0,3)."\" id=\"eti_fil\" /></td></tr>
			
			<tr><td><div class=\"area\" style=\"width: auto;\">Sinópse</div></td><td>
			<div id=\"toolbar\" style=\"
				background-image: URL('imagens/bg.gif');
				background-repeat: repeat-x;
				text-align:left;width:399px;\">
			".drawToolBar( "sin_fil", "toolbar_sin_fil", true )."</div>
			<textarea id=\"sin_fil\" name=\"sin_fil\" 
			style=\"font-family: verdana; font-size: 11px; width: 395px;\" rows=\"7\" 
			class=\"forms\">".reverseBase( mysql_result($query,0,6) )."</textarea><br />
			</td></tr>
			
			<tr><td><div class=\"area\" style=\"width: auto;\">Ano</div></td><td>
			<input class=\"forms\" type=\"text\" name=\"ano_fil\" 
			id=\"ano_fil\" value=\"".mysql_result($query,0,7)."\" /></td></tr>
			
			<tr><td><div class=\"area\" style=\"width: auto;\">Duração</div></td><td>
			<input class=\"forms\" type=\"text\" name=\"dur_fil\" 
			id=\"dur_fil\" value=\"$dur\" /></td></tr>
			
			<tr><td>
			<div class=\"area\" style=\"width: auto;\">Requesitável</div></td><td>
			<input type=\"checkbox\" name=\"req_fil\"
			value=\"1\" id=\"req_fil\" $checked /></td></tr>
			
			<tr><td><div class=\"area\" style=\"width: auto;\">Slogan</div></td><td>
			<input class=\"forms\" maxlength=\"150\" type=\"text\" 
			name=\"slo_fil\" id=\"slo_fil\" value=\"".mysql_result($query,0,5)."\" /></td></tr>
			
			<tr><td><div class=\"area\" style=\"width: auto;\">Tipo de som</div></td>
			<td>$tipo_som_t
			<input class=\"forms\" type=\"button\" value=\"Novo tipo de som\" id=\"new_som\" />
			<input class=\"forms\" type=\"button\" value=\"Apagar tipo de som\" id=\"apg_som\" />
			</td></tr>
			
			<tr><td><div class=\"area\" style=\"width: auto;\">Realizador</div></td><td>
			$rels
			<input class=\"forms\" type=\"button\" value=\"Adicionar realizador\" id=\"new_rel\" />			   <input class=\"forms\" type=\"button\" value=\"Apagar realizador\" id=\"apg_rel\" />
			</td></tr>
			
			<tr><td><div class=\"area\" style=\"width: auto;\">Género de filme</div>
			</td><td>$genero
			<input class=\"forms\" type=\"button\" value=\"Novo gênero\" id=\"new_gen\" />
			<input class=\"forms\" type=\"button\" value=\"Apagar gênero\" id=\"apg_gen\" />
			</td></tr>
			
			<tr><td><div class=\"area\" style=\"width: auto;\">Classificação IMDB</div></td><td>
			<input class=\"forms\"  value=\"$classi_imdb\" type=\"text\" 
			name=\"class_imdb_fil\" id=\"class_imdb_fil\" />
			</td></tr>
			
			<tr><td>
			<div class=\"area\" style=\"width: auto;\">Elemento</div>
			</td>
			<td>
			$elementos
			</td></tr>
			
			<tr><td>
			<input class=\"forms\" type=\"button\" value=\"Guardar alterações\" name=\"upd_fil\" 
			id=\"upd_fil\" />
			</td></tr>
			
			<tr><td>
			<form method=\"post\" action=\"?elem=2&amp;accao=4&amp;opcao=2\">
				<input type=\"submit\" value=\"Apagar filme\" class=\"forms\" />
				<input type=\"hidden\" name=\"apg_fil\" id=\"apg_fil\" 
				value=\"".$_GET['filme']."\" />
				<input type=\"hidden\" name=\"flagedit\" id=\"flagedit\" 
				value=\"1\" />
			</form>
			</td></tr>
			
			<tr><td>
			<a href=\"?elem=2&amp;accao=5&amp;filme=".$_GET['filme']."\">
			Ver em modo normal
			</a>
			</td></tr>
			
		</table>
		
		";				
	
	} else echo "Lamentamos mas esse filme não existe.";
	
	echo "</div>";
					
 } else if( defined("IN_PHPAP") ) include_once ( "home.php" );
 
?>