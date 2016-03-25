<?php
/** 
 * Editar um álbum.
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
 include_once(defined('IN_PHPAP')? "albuns/album_funcoes.php":"album_funcoes.php");
 
 if( !isset($_SESSION['user_pass']) ) session_start();
 
 validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
 
 if ( isset( $_POST["titulo"] ) 
 && isset( $_POST["etiqueta"] ) 
 && isset( $_POST["sinopse"] ) 
 && isset( $_POST["ano"] )
 && isset( $_POST["requesitavel"] )  
 && isset( $_POST["id_album"] ) 
 && $_SESSION['estat_carac'][7]){
	
	
	/*Aqui faz-se a edição propriamente dita pega-se nos dados, enviados e faz a 
	actualização dos mesmos*/
	
	
	
	$bd = ligarBD();
	
	
	
	$id_album = $_POST["id_album"]; 
	
	$alb_car[0] = 
	clearGarbadge(rawurldecode($_POST["titulo"]),false,false);	
	
	$alb_car[1] = 
	clearGarbadge($_POST["etiqueta"], false, false);	
	
	$alb_car[2] = 
	clearGarbadge(rawurldecode($_POST["sinopse"]),true,true);
	
	$alb_car[3] = 
	clearGarbadge($_POST["ano"],false,false);
	
	$alb_car[4] = 
	clearGarbadge($_POST["requesitavel"],false,false);
	
	$alb_car[5] = 
	clearGarbadge(rawurldecode($_POST["slogan"]),false,false);
	
	$alb_car[6] = 
	explode( "|", clearGarbadge(rawurldecode($_POST["num_copi_alb"]),false,false) );
	
	
	
	
	
	
	
	if( ! is_numeric($id_album) ) die();
	
	$ver = trim( $alb_car[0] );
	
	if( empty( $ver ) ) 
			die ( 
			rawurlencode
("O título do álbum não pode estar em branco ou ser constituido somente por espaços em branco.") 
			);
		
		if( !strWordCount($alb_car[0]," ",40) )
			die ( 
			rawurlencode
("O título do álbum não pode ter mais de 40 caractéres consecutivos s/um espaço em branco.") 
			);
		
		/*if( empty( $alb_car[1] ) )
			die ( 
			rawurlencode
("A etiqueta do álbum não foi preenchida.") 
			);
		
		
		if(mysql_result($bd->submitQuery("Select Count(*) - (Select Count(*) From `album` 
		Where `geral_id_geral` = $id_album And `album_etiqueta` like '$alb_car[1]') 
		From `album` 
		Where `album_etiqueta` like '$alb_car[1]'"), 0, 0) > 0)
			die ( 
			rawurlencode
("A etiqueta já esta atribuida a outro álbum.") 
			);
		
		if( strlen( $alb_car[3] ) != 4 || !is_numeric( $alb_car[3] ) || $alb_car[3] > date("Y")
		|| $alb_car[3] < 1870 ) 
			die ( 
			rawurlencode
("O ano do álbum não é válido.")
			);*/
			
	
	
	
	if( $alb_car[4] != true && $alb_car[4] != false ) die(); 
	
	$bd->submitQuery("Delete From `num_suport_album` 
	Where `copi_album_album_geral_id_geral` = $id_album");
	
	$bd->submitQuery("Delete From `copi_album` 
	Where `album_geral_id_geral` = $id_album");
	
	
	
	if(	!empty( $alb_car[6] ) ){
		
		insertElementosAlb( $alb_car[6] , $id_album );
	
	}
	
	if( $bd->submitQuery("UPDATE `album` SET 
	`album_sinopse` = '$alb_car[2]', 
	`album_etiqueta` = '$alb_car[1]',
	`album_nome` = '$alb_car[0]',
	`album_ano` = '$alb_car[3]',
	`album_requesitavel` = $alb_car[4]
	WHERE 
	`album`.`geral_id_geral` = $id_album
	") ) 
	echo rawurlencode("Dados do álbum $alb_car[0] actualizados com sucesso.");
	else
	echo rawurlencode("É impossível actualizar os dados do álbum $alb_car[0], a este momento.");
	
	
	
	
	
 } else if ( defined("IN_PHPAP") 
 && $_SESSION['estat_carac'][7] 
 && is_numeric($_GET['album']) ) {
	
	
	
	
	
	//Apresentar a interface de edição
	
	
	
	
	$bd = ligarBD();
	
	/*Características do  álbum*/
	$query = $bd->submitQuery("Select * From `album` Where `geral_id_geral` = ".$_GET['album']);
	
	echo "
	<div class=\"gestaopremain\" style=\"text-align:left;\">
 	
	   	<div id=\"gestaopremaintitle\">
		  <p>A editar álbum</p>
		</div>";
	
	if( mysql_numrows($query) == 1){
	
	$elementos = printElementosAlb ( $_GET['album'] );
	
	$checked =	mysql_result($query,0,5) ? "checked=\"checked\"" : "";
	
	echo "
	
	<table border=\"0\">
			
			<tr><td><div class=\"area\" style=\"width: auto;\">Título</div></td>
			<td><input class=\"forms\" maxlength=\"100\" type=\"text\" name=\"nom_alb\" 
			id=\"nom_alb\" value=\"".mysql_result($query,0,2)."\" />
			</td></tr>
			
			<tr><td>
			<div class=\"area\" style=\"width: auto;\">Etiqueta</div>
			</td><td><input class=\"forms\" maxlength=\"25\" type=\"text\" 
			name=\"eti_alb\" value=\"".mysql_result($query,0,1)."\" id=\"eti_alb\" /></td></tr>
			
			<tr><td><div class=\"area\" style=\"width: auto;\">Sinópse</div></td><td>
			<div id=\"toolbar\" style=\"
				background-image: URL('imagens/bg.gif');
				background-repeat: repeat-x;
				text-align:left;width:399px;\">
			".drawToolBar( "sin_alb", "toolbar_sin_alb", true )."</div>
			<textarea id=\"sin_alb\" name=\"sin_alb\" 
			style=\"font-family: verdana; font-size: 11px; width: 395px;\" rows=\"7\" 
			class=\"forms\">".reverseBase( mysql_result($query,0,3) )."</textarea><br />
			</td></tr>
			
			<tr><td><div class=\"area\" style=\"width: auto;\">Ano</div></td><td>
			<input class=\"forms\" type=\"text\" name=\"ano_alb\" 
			id=\"ano_alb\" value=\"".mysql_result($query,0,4)."\" /></td></tr>
			
			<tr><td>
			<div class=\"area\" style=\"width: auto;\">Requesitável</div></td><td>
			<input type=\"checkbox\" name=\"req_alb\"
			value=\"1\" id=\"req_alb\" $checked /></td></tr>
			
			<tr><td>
			<div class=\"area\" style=\"width: auto;\">Elemento</div>
			</td>
			<td>
			$elementos
			</td></tr>
			
			<tr><td>
			<input class=\"forms\" type=\"button\" value=\"Guardar alterações\" name=\"upd_alb\" 
			id=\"upd_alb\" />
			</td></tr>
			
			<tr><td>
			<form method=\"post\" action=\"?elem=2&amp;accao=6&amp;opcao=2\">
				<input type=\"submit\" value=\"Apagar álbum\" class=\"forms\" />
				<input type=\"hidden\" name=\"apg_alb\" id=\"apg_alb\" 
				value=\"".$_GET['album']."\" />
				<input type=\"hidden\" name=\"flagedit\" id=\"flagedit\" value=\"1\" />
			</form>
			</td></tr>
			
			<tr><td>
			<a href=\"?elem=2&amp;accao=7&amp;album=".$_GET['album']."\">
			Ver em modo normal
			</a>
			</td></tr>
			
		</table>
		
		";				
	
	} else echo "Lamentamos mas esse álbum não existe.";
	
	echo "</div>";
					
 } else if( defined("IN_PHPAP") ) include_once ( "home.php" );
 
?>