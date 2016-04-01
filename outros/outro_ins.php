<?php
/**
 * Inserir um elemento outro.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 */

	
	if( !isset($_SESSION['user_pass']) ) session_start();
	/**
	 * Incluir as fun��es nativas dos outros.
	 *
	 */
	include_once("outro_funcoes.php");
	
	
	
	if( $_SESSION['estat_carac'][8] ) {	
	
	//Se o utilizador tiver sess�o iniciada e tiver permiss�es para gerir os outros itens
	
	if( isset($_POST['nom_outro']) && isset($_POST['eti_outro']) 
	&& isset($_POST['sin_outro']) && isset($_POST['ano_outro']) 
	&& isset($_POST['num_copi_outro']) && isset($_POST['req_outro']) 
	&& isset($_POST['dir_sel']) ){
		
		$bd = ligarBD();
		
		$nom_outro = clearGarbadge(rawurldecode($_POST['nom_outro']), false, false);
		$eti_outro = clearGarbadge($_POST['eti_outro'], false, false);
		$sin_outro = clearGarbadge(rawurldecode($_POST['sin_outro']), true, true);
		$ano_outro = clearGarbadge($_POST['ano_outro'], false, false);
		$num_copi_outro = clearGarbadge(rawurldecode($_POST['num_copi_outro']), false, false);
		$num_copi_outro = explode("|", $num_copi_outro);
		$req_outro = clearGarbadge($_POST['req_outro'], false, false);
		$dir_sel = clearGarbadge($_POST['dir_sel'], false, false);
		
		$nom_outro_test = ltrim($nom_outro);
		
		if( empty($nom_outro_test) ){
			
			die( 
			rawurlencode(
		"O t�tulo do outro item n�o pode estar por preencher ou conter somente espa�os em branco.") 
			);
			
		}
		
		if( !strWordCount($nom_outro," ",40) ){
			
			die ( 
			rawurlencode
("O t�tulo do outro item n�o pode ter mais do que 40 caract�res consecutivos s/um espa�o em branco.")
			);
		
		}
		
		/*if( empty($eti_outro) ){
			
			die( 
			rawurlencode(
			"Preencha devidamente a etiqueta.") 
			);	
			
		}
		
		//Verificar se j� existe um item outro com este id
		if(mysql_result(
		$bd->submitQuery("Select Count(*) From `outro` Where `outro_etiqueta` Like '$eti_outro'") 
		,0,0) > 0){
			
			die( 
			rawurlencode(
			"Essa etiqueta j� est� a ser utilizada por outro item.") 
			);	
			
		}
		
		if( strlen( $ano_outro ) != 4 || !is_numeric( $ano_outro ) || $ano_outro > date("Y") )
			die ( 
			rawurlencode
("O ano do outro item n�o � v�lido.")
			);
		*/
		
		if( !strWordCount($sin_outro," ",40) ){
			
			die ( 
			rawurlencode
("A sinopese do outro item n�o pode ter mais do que 40 caract�res consecutivos s/um espa�o em branco.")
			);
		
		}
		
		
		
		
		if( !is_bool( (bool) $req_outro) ) die();
		
		
		if( !is_numeric($dir_sel) ) die();
		
		$query = $bd->submitQuery(
		"INSERT INTO `geral` (
		`id_geral`,
		`id_elemento`
		)
		VALUES (NULL , '3');"
		);
		
		$geral_id = mysql_insert_id();
		
		if($query) {
		
			
		$query = $bd->submitQuery(
		"INSERT INTO `outro` (
		`geral_id_geral` ,
		`direito_outro_outro_id_direito_outro`,
		`outro_etiqueta` ,
		`outro_nome` ,
		`outro_sinopse` ,
		`outro_ano` ,
		`outro_requesitavel` ,
		`outro_classi` ,
		`outro_classi_num_vot`
		)
		VALUES (
		'$geral_id', '$dir_sel', '$eti_outro', '$nom_outro', '$sin_outro'
		, '$ano_outro', $req_outro, '0', '0'
		);"
		);
		
		if($query){
			
			insertElementosOutros( $num_copi_outro, $geral_id );
			
			echo rawurlencode("O outro item \"$nom_outro\" foi introduzido com sucesso.");
			
		} else {
			
			$bd->submitQuery("Delete From `geral` Where `id_geral` = ".mysql_insert_id());
			
			echo rawurlencode("De momento n�o � poss�vel introduzir o outro item $nom_outro.");
			
		}
		
		} else {
			
			echo rawurlencode("De momento n�o � poss�vel introduzir o outro item $nom_outro.");
			
		}
		
		//mysql_freeresult( $query ); 	
		
	} else {
	

	//Aqui � imprimida a interface de inser��o de outros itens
	echo "
	<form method=\"post\" name=\"outro_ins\" id=\"outro_ins\">
	<p><font color=\"brown\">Elementos de preenchimento obrigat�rio...</font></p>
	
	<label for=\"nome_outro\">
	T�tulo item outro:<br />
	<input type=\"text\" class=\"forms\" maxlength=\"100\" name=\"nome_outro\" id=\"nome_outro\" />
	</label>
	
	<p><label for=\"etiq_outro\">Etiqueta:<br />
	<input type=\"text\" class=\"forms\" name=\"etiq_outro\" maxlength=\"25\" id=\"etiq_outro\" />
	</label></p>
	
	<div id=\"toolbar\" style=\"
	background-image: URL('imagens/bg.gif');
	background-repeat: repeat-x;
	text-align:left;width:419px;\">
	".drawToolBar( "sin_outro", "toolbar_sin_outro", true)."</div>
	<textarea id=\"sin_outro\" name=\"sin_outro\" 
	style=\"font-family: verdana; font-size: 11px; width: 417px;\" rows=\"7\" 
	class=\"forms\">A sua sin�pse aqui...</textarea>
	
	<p><label for=\"ano_outro\">
	Ano:<br /> 
	<input class=\"forms\" type=\"text\" name=\"ano_outro\" id=\"ano_outro\" />
	</label></p>
	
	<p><font color=\"brown\">Elementos de preenchimento n�o obrigat�rio...</font></p>
	
	".dirListOutros( "dir_sel", 0, "" )."
	
	<p><label for=\"req_outro\">
	Requesit�vel:
	<input type=\"checkbox\" name=\"req_outro\" value=\"1\" id=\"req_outro\" />
	</label></p>
	
	<div class=\"gestaopremain\" style=\"text-align:left;\"><br />"
	.printElementosOutro( 0 )."</div>
	
	<br />
	
	<p>
	<input class=\"forms\" type=\"button\" name=\"ins_new_outr\" id=\"ins_new_outr\" 
	value=\"Inserir outro item\" />
	<input class=\"forms\" type=\"reset\" value=\"Limpar\" />
	</form>
	</p>
	";
	
	}
	
	} else include_once(defined('IN_PHPAP') ? "home.php":"../home.php");

?>