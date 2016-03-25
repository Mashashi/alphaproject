<?php
/**
 * Inserir um álbum.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */
	
		
	
	
	if( !isset($_SESSION['user_pass']) ) session_start();
	
	
	
	/**
 	 * Incluir as funções direccionadas para os álbuns independetemente do contexto
 	 * em que a página foi apresentada.   
  	 */
	include_once( "album_funcoes.php" );
	
	
	validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
	
		
	if( $_SESSION['estat_carac'][7] ) {	
	
	if( isset($_POST['nom_alb']) && isset($_POST['eti_alb']) 
	&& isset($_POST['sin_alb']) && isset($_POST['ano_alb']) 
	&& isset($_POST['num_copi_alb']) && isset($_POST['req_alb']) ){
		
		
		
		$bd = ligarBD();
		
		$nom_alb = clearGarbadge(rawurldecode($_POST['nom_alb']), false, false);
		$eti_alb = clearGarbadge($_POST['eti_alb'], false, false);
		$sin_alb = clearGarbadge(rawurldecode($_POST['sin_alb']), true, true);
		$ano_alb = clearGarbadge($_POST['ano_alb'], false, false);
		$num_copi_alb = clearGarbadge(rawurldecode($_POST['num_copi_alb']), false, false);
		$num_copi_alb = explode("|", $num_copi_alb);
		$req_alb = clearGarbadge($_POST['req_alb'], false, false);
		
		$nom_alb_test = ltrim($nom_alb);
		
		
		
		if( empty($nom_alb_test) ){
			
			die( 
			rawurlencode(
		"O título do álbum não pode estar por preencher ou conter somente espaços em branco.") 
			);
			
		}
		
		if( !strWordCount($nom_alb," ",40) ){
			
			die ( 
			rawurlencode
("O título do álbum não pode ter mais do que 40 caractéres consecutivos s/um espaço em branco.")
			);
		
		}
		
		/*if( empty( $eti_alb ) ){
			
			die( 
			rawurlencode(
			"Preencha devidamente a etiqueta.") 
			);	
			
		}
		
		if(mysql_result(
		$bd->submitQuery("Select Count(*) From `album` Where `album_etiqueta` Like '$eti_alb' ") 
		,0,0) > 0){
			
			die( 
			rawurlencode(
			"Essa etiqueta já está a ser utilizada por outro álbum.") 
			);	
			
		}
		
		if( strlen( $ano_alb ) != 4 || !is_numeric( $ano_alb ) || $ano_alb > date("Y")
		|| $ano_alb < 1000 ) //
			die ( 
			rawurlencode
("O ano do álbum não é válido.")
			);*/
		
		
		if( !strWordCount($sin_alb," ",40) ){
			
			die ( 
			rawurlencode
("A sinopese do álbum não pode ter mais do que 40 caractéres consecutivos s/um espaço em branco.")
			);
		
		}
		
		
		
		
		if( !is_bool((bool) $req_alb) ) die();
		
		//echo $req_alb;
		/*print_r($_POST['name_tri_alb']);
		print_r($_POST['acerc_tri_alb']);
		print_r($_POST['sel_alb_track_gen']);
		print_r($_POST['time_tri_alb']);*/
		
		$name_tri_alb = $_POST['name_tri_alb'];
		$acerc_tri_alb = $_POST['acerc_tri_alb'];
		$sel_alb_track_gen = $_POST['sel_alb_track_gen'];
		$time_tri_alb = $_POST['time_tri_alb'];
		
		
		if( !validTime( (array) $time_tri_alb ) )
			die ( 
			rawurlencode
("Um dos tempos das trilhas não é válido.")
			);
		
		$query = $bd->submitQuery(
		"INSERT INTO `geral` (
		`id_geral`,
		`id_elemento`
		)
		VALUES (NULL , '2');"
		);
		
		$geral_id = mysql_insert_id();
		
		if($query) {
		
		
		
		$query = $bd->submitQuery(
		"INSERT INTO `album` (
		`geral_id_geral` ,
		`album_etiqueta` ,
		`album_nome` ,
		`album_sinopse` ,
		`album_ano` ,
		`album_requesitavel` ,
		`album_classi` ,
		`album_classi_num_vot`
		)
		VALUES (
		'$geral_id', '$eti_alb', '$nom_alb', '$sin_alb'
		, '$ano_alb', $req_alb, '0', '0'
		);"
		);
		
			if($query){
			
				insertElementosAlb( $num_copi_alb, $geral_id );
				
				albInserirTrilh( $geral_id, $name_tri_alb, 
				$acerc_tri_alb, $sel_alb_track_gen, $time_tri_alb);
				
				echo rawurlencode("O álbum $nom_alb foi introduzido com sucesso.");
			
			} else {
			
				$bd->submitQuery("Delete From `geral` Where `id_geral` = ".mysql_insert_id());
				
				echo rawurlencode("De momento não é possível introduzir o álbum $nom_alb.");
			
			}
		
		} else {
			
			echo rawurlencode("De momento não é possível introduzir o álbum $nom_alb.");
			
		}
		
		//mysql_freeresult( $query ); 	
		
	} else {
		
	echo "
	<form method=\"post\" name=\"album_ins\" id=\"album_ins\">
	<p><font color=\"brown\">Elementos de preenchimento obrigatório...</font></p>
	
	<label for=\"nome_alb\">
	Título álbum:<br />
	<input type=\"text\" class=\"forms\" maxlength=\"100\" name=\"nome_alb\" id=\"nome_alb\" />
	</label>
	
	<p><label for=\"etiq_alb\">Etiqueta:<br />
	<input type=\"text\" class=\"forms\" name=\"etiq_alb\" maxlength=\"10\" id=\"etiq_alb\" />
	</label></p>
	
	<div id=\"toolbar\" style=\"
	background-image: URL('imagens/bg.gif');
	background-repeat: repeat-x;
	text-align:left;width:419px;\">
	".drawToolBar( "sin_alb", "toolbar_sin_alb", true)."</div>
	<textarea id=\"sin_alb\" name=\"sin_alb\" 
	style=\"font-family: verdana; font-size: 11px; width: 417px;\" rows=\"7\" 
	class=\"forms\">A sua sinópse aqui...</textarea>
	
	<p><label for=\"ano_alb\">
	Ano:<br /> 
	<input class=\"forms\" type=\"text\" name=\"ano_alb\" id=\"ano_alb\" />
	</label></p>
			
	<!--<label for=\"dur_alb\">
	Duração:<br /> 
	<input class=\"forms\" type=\"text\" name=\"dur_alb\" id=\"dur_alb\" />
	</label>-->
	
	<p><font color=\"brown\">Elementos de preenchimento não obrigatório...</font></p>
	
	<label for=\"req_alb\">
	Requesitável:
	<input type=\"checkbox\" name=\"req_alb\" value=\"1\" id=\"req_alb\" />
	</label>
	
	<div class=\"gestaopremain\" style=\"text-align:left;\"><br />"
	.printElementosAlb( 0 )."</div>
	
	<br />
	
	<div class=\"gestaopremain\" style=\"text-align:left;\"><br />
	<table width=\"577\" id=\"trilh_alb_table\">
	<tr><td>Nome</td><td>Duração</td><td>Acerca</td><td>Gênero</td></tr>
	
	<!--<form id=\"tracks_alb\" name=\"tracks_alb\">-->
	<tr>
	<td><input type=\"text\" class=\"forms\" name=\"name_tri_alb[0]\" maxlength=\"100\" /></td>
	<td><input type=\"text\" class=\"forms\" id=\"time_tri_alb\" name=\"time_tri_alb[0]\" /></td>
	<td><textarea class=\"forms\" style=\"width:140px;\" name=\"acerc_tri_alb[0]\"></textarea>
	</td>
	<td>".listTrilGenAlb( 0, "sel_alb_track_gen[0]" )."</td>
	</tr>
	<!--</form>-->
	
	</table>
	<input type=\"button\" value=\"Nova trilha\" id=\"new_trilh_alb\" class=\"forms\" />
	<input type=\"button\" value=\"Novo gênero músical\" id=\"new_trilh_alb_gen\" 
	class=\"forms\" />
	<input type=\"button\" value=\"Apagar gênero músical\" id=\"del_trilh_alb_gen\" 
	class=\"forms\" />".listTrilGenAlb( 0, "ref_gen_upda" )."
	<br />
	Nota: Se algum do nome da trilha ficar por preencher essa entrada não será válida.
	</div>
	
	
	
	<p>
	<input class=\"forms\" type=\"button\" name=\"ins_new_alb\" id=\"ins_new_alb\" 
	value=\"Inserir álbum\" />
	<input class=\"forms\" type=\"reset\" value=\"Limpar\" />
	</form>
	</p>
	";
		
	}
	
	} else include_once(defined('IN_PHPAP') ? "home.php":"../home.php");

?>