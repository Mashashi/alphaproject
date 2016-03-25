<?php
/** 
 * Editar um item outro.
 *
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

 
 if( !isset($_SESSION['user_pass']) ) session_start();
/**
 * Incluir as funções nativas dos outros itens.
 *
 */
 include_once(defined('IN_PHPAP')? "outros/outro_funcoes.php":"outro_funcoes.php");
 
 if ( isset( $_POST["titulo"] ) && isset( $_POST["etiqueta"] ) && isset( $_POST["sinopse"] ) && isset( $_POST["ano"] )
 && isset( $_POST["requesitavel"] )  && isset( $_POST["id_outro"] ) 
 && isset( $_POST["num_copi_alb"] )
 && $_SESSION['estat_carac'][7]){
	
	
	$bd = ligarBD();
	
	$id_outro = $_POST["id_outro"]; 
	
	$outr_car[0] = 
	clearGarbadge(rawurldecode($_POST["titulo"]),false,false);	
	
	$outr_car[1] = 
	clearGarbadge($_POST["etiqueta"], false, false);	
	
	$outr_car[2] = 
	clearGarbadge(rawurldecode($_POST["sinopse"]),true,true);
	
	$outr_car[3] = 
	clearGarbadge($_POST["ano"],false,false);
	
	$outr_car[4] = 
	clearGarbadge($_POST["requesitavel"],false,false);
	
	$outr_car[6] = 
	explode( "|", clearGarbadge(rawurldecode($_POST["num_copi_alb"]),false,false) );
	
	if( ! is_numeric($id_outro) ) die();
	
	$ver = trim( $outr_car[0] );
	
	if( empty( $ver ) ) 
			die ( 
			rawurlencode
("O título do álbum não pode estar em branco ou ser constituido somente por espaços em branco.") 
			);
		
		if( !strWordCount($outr_car[0]," ",40) )
			die ( 
			rawurlencode
("O título do álbum não pode ter mais de 40 caractéres consecutivos s/um espaço em branco.") 
			);
		
		
		
		/*if( empty( $outr_car[1] )  )
			die ( 
			rawurlencode
("A etiqueta do álbum não foi preenchida.") 
			);
		if(mysql_result($bd->submitQuery("Select Count(*) - (Select Count(*) From `outro` 
		Where `geral_id_geral` = '$id_outro' And `outro_etiqueta` = '$outr_car[1]') 
		From `outro` 
		Where `outro_etiqueta` = '$outr_car[1]'"), 0, 0) > 0)
			die ( 
			rawurlencode
("A etiqueta já esta atribuida a outro item.") 
			);
		
		if( strlen( $outr_car[3] ) != 4 || !is_numeric( $outr_car[3] ) || $outr_car[3] > date("Y")
		|| $outr_car[3] < 1870 ) 
			die ( 
			rawurlencode
("O ano do álbum não é válido.")
			);*/
			
	
	
	
	if( $outr_car[4] != true && $outr_car[4] != false ) die(); 
	
	$bd->submitQuery("Delete From `num_suport_outro` 
	Where `copi_outro_outro_geral_id_geral` = $id_outro");
	
	$bd->submitQuery("Delete From `copi_outro` 
	Where `outro_geral_id_geral` = $id_outro");
	
	
	
	if(	!empty( $outr_car[6] ) ){
		
		insertElementosOutros( $outr_car[6] , $id_outro );
	
	}
	
	if( $bd->submitQuery("UPDATE `outro` SET 
	`outro_sinopse` = '$outr_car[2]', 
	`outro_etiqueta` = '$outr_car[1]',
	`outro_nome` = '$outr_car[0]',
	`outro_ano` = '$outr_car[3]',
	`outro_requesitavel` = $outr_car[4]
	WHERE 
	`outro`.`geral_id_geral` = $id_outro
	") ) 
	echo rawurlencode("Dados do item $outr_car[0] actualizados com sucesso.");
	else
	echo rawurlencode("É impossível actualizar os dados do item $outr_car[0], a este momento.");
	
 } else if ( defined("IN_PHPAP") && $_SESSION['estat_carac'][8] && is_numeric($_GET['outro']) ) {
	
	/*Características do  outro*/
	$query = $bd->submitQuery("Select * From `outro` Where `geral_id_geral` = ".$_GET['outro']);
	
	echo "
	<div class=\"gestaopremain\" style=\"text-align:left;\">
 	
	   	<div id=\"gestaopremaintitle\">
		  <p>A editar outro</p>
		</div>";
	
	if( mysql_numrows($query) == 1){
	
	$elementos = printElementosOutro ( $_GET['outro'] );
	
	$checked =	mysql_result($query,0,5) ? "checked=\"checked\"" : "";

	echo "
	<table border=\"0\">
			
			<tr><td><div class=\"area\" style=\"width: auto;\">Título</div></td>
			<td><input class=\"forms\" maxlength=\"100\" type=\"text\" name=\"nom_outro\" 
			id=\"nom_outro\" value=\"".mysql_result($query,0,2)."\" />
			</td></tr>
			
			<tr><td>
			<div class=\"area\" style=\"width: auto;\">Etiqueta</div>
			</td><td><input class=\"forms\" maxlength=\"25\" type=\"text\" 
			name=\"eti_outro\" value=\"".mysql_result($query,0,6)."\" id=\"eti_outro\" /></td></tr>
			
			<tr><td><div class=\"area\" style=\"width: auto;\">Sinópse</div></td><td>
			<div id=\"toolbar\" style=\"
				background-image: URL('imagens/bg.gif');
				background-repeat: repeat-x;
				text-align:left;width:399px;\">
			".drawToolBar( "sin_outro", "toolbar_sin_outro", true )."</div>
			<textarea id=\"sin_outro\" name=\"sin_outro\" 
			style=\"font-family: verdana; font-size: 11px; width: 395px;\" rows=\"7\" 
			class=\"forms\">".reverseBase( mysql_result($query,0,3) )."</textarea><br />
			</td></tr>
			
			<tr><td><div class=\"area\" style=\"width: auto;\">Ano</div></td><td>
			<input class=\"forms\" type=\"text\" name=\"ano_outro\" 
			id=\"ano_outro\" value=\"".mysql_result($query,0,4)."\" /></td></tr>
			
			<tr><td>
			<div class=\"area\" style=\"width: auto;\">Requesitável</div></td><td>
			<input type=\"checkbox\" name=\"req_outro\"
			value=\"1\" id=\"req_outro\" $checked /></td></tr>
			
			<tr><td>
			<div class=\"area\" style=\"width: auto;\">Elemento</div>
			</td>
			<td>
			$elementos
			</td></tr>
			
			<tr><td>
			<input class=\"forms\" type=\"button\" value=\"Guardar alterações\" 
			name=\"upd_outro\" id=\"upd_outro\" />
			</td></tr>
			
			<tr><td>
			
			<form method=\"post\" action=\"?elem=2&amp;accao=8&amp;opcao=2\">
				<input type=\"submit\" value=\"Apagar item\" class=\"forms\" />
				<input type=\"hidden\" name=\"apg_outro\" id=\"apg_outro\" 
				value=\"".$_GET['outro']."\" />
				<input type=\"hidden\" name=\"flagedit\" id=\"flagedit\" value=\"1\" />
			</form>
			
			</td></tr>
			
			<tr><td>
			<a href=\"?elem=2&amp;accao=9&amp;outro=".$_GET['outro']."\">
			Ver em modo normal
			</a>
			</td></tr>
			
		</table>
		
		";				
	
	} else echo "Lamentamos mas esse item não existe.";
	
	echo "</div>";
					
 } else if( defined("IN_PHPAP") ) include_once ( "home.php" );