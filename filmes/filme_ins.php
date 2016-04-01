<?
/**
 * Inserir um filme na base de dados.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 */


if(!isset($_SESSION['user_pass'])) session_start();

/**
 * Incluir as fun��es nativas dos filmes.
 */
include_once(defined('IN_PHPAP') ? "filmes/filme_funcoes.php":"filme_funcoes.php");

validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
	
if( $_SESSION['estat_carac'][6] ){

/*O utilizador s� pode aceder a este ficheiro se tiver sess�o iniciada e dentro da sess�o
se tiver a flag acimaa 1*/

	
	$bd = ligarBD();
	
if ( isset( $_POST["nom_fil"] ) && isset( $_POST["eti_fil"] ) && isset( $_POST["sin_fil"] ) && isset( $_POST["ano_fil"] ) && isset( $_POST["dur_fil"] ) && isset( $_POST["req_fil"] ) 
&& isset( $_POST["slo_fil"] ) && isset( $_POST["tip_som_fil"] ) && isset( $_POST["gen_fil"] ) 
&& isset( $_POST["class_imdb_fil"] ) && isset($_POST["num_copi_film"]) ){
		
		
		/*Se as vari�veis acima estiverem difinidas ser� feita uma tentaiva de 
		inser��o de filme*/
		$fil_car[8] = 
		clearGarbadge(rawurldecode($_POST["nom_fil"]),false,false);	
		
		$fil_car[6] = 
		clearGarbadge($_POST["eti_fil"],false,false);	
		
		$fil_car[1] = 
		clearGarbadge(rawurldecode($_POST["sin_fil"]),true,true);
		
		$fil_car[2] = 
		clearGarbadge($_POST["ano_fil"],false,false);
		
		$fil_car[3] = 
		substr( clearGarbadge($_POST["dur_fil"],false,false),0,strlen($fil_car[3])-3);
		
		$fil_car[7] = 
		clearGarbadge($_POST["req_fil"],false,false);
		
		$fil_car[0] = 
		clearGarbadge(rawurldecode($_POST["slo_fil"]),false,false);
		
		$fil_car[4] = 
		clearGarbadge($_POST["tip_som_fil"],false,false);
		
		$fil_car[9] = 
		clearGarbadge($_POST["gen_fil"],false,false);
		
		$fil_car[5] = 
		clearGarbadge($_POST["class_imdb_fil"],false,false);
		
		$fil_car[10] = 
		explode( "|", clearGarbadge(rawurldecode($_POST["num_copi_film"]),false,false) );
		
		//die(rawurlencode($fil_car[8]) );
		
		$ver = trim( $fil_car[8] );
		
		if( empty( $ver ) ) 
			die ( 
			rawurlencode
("O t�tulo do filme n�o pode estar em branco ou ser constituido somente por espa�os em branco.") 
			);
		
		if( !strWordCount($fil_car[8]," ",40) )
			die ( 
			rawurlencode
("O t�tulo do filme n�o pode ter mais de 40 caract�res consecutivos s/um espa�o em branco.") 
			);
		
		
		
		/*if( empty( $fil_car[6] )  )
			die ( 
			rawurlencode
("A etiqueta do filme n�o foi preenchida.") 
			);
		
		if(existeNaBd("filme", "filme_etiqueta", $fil_car[6]) > 0)
			die ( 
			rawurlencode
("A etiqueta j� est� atribuida a outro filme.")
			);
			
		
		
		
		if( strlen( $fil_car[2] ) != 4 || !is_numeric( $fil_car[2] ) || $fil_car[2] > date("Y")
		|| $fil_car[2] < 1893 ) //O cinema foi inventado em 1895 pelos irm�os Lumier ;)
			die ( 
			rawurlencode
("O ano do filme n�o � v�lido.")
			);
		
		if( $fil_car[3] < 0 || $fil_car[3] > 9999 || !is_numeric($fil_car[3]) ) 
			die (rawurlencode
("A dura��o do filme n�o foi preenchida.")
			);*/
		
		
		
		if( ($fil_car[5]>10) || ($fil_car[5]< 0))
			die( rawurlencode("Classifica��o IMDB inv�lida") );
		
		if( $fil_car[7] != true && $fil_car[7] != false ) die(); 
		
		if( $bd->submitQuery("Insert Into `geral` (`id_geral`,`id_elemento`) VALUES (NULL, 1)") ){
			
			$id_geral = mysql_insert_id();
			
			if( $bd->submitQuery("
				INSERT INTO `filme` (
				`geral_id_geral` ,
				`genero_filme_filme_id_genero_filme` ,
				`tipo_som_filme_id_tipo_som_filme` ,
				`filme_etiqueta` ,
				`filme_nome` ,
				`filme_slogan` ,
				`filme_sinopse` ,
				`filme_ano` ,
				`filme_duracao` ,
				`filme_imdb` ,
				`filme_requesitavel` ,
				`filme_classi` ,
				`filme_classi_num_vot` 
				)
				VALUES (
				'$id_geral' , '$fil_car[9]', '$fil_car[4]', '$fil_car[6]'
				, '$fil_car[8]'
				, '$fil_car[0]', '$fil_car[1]', '$fil_car[2]', '$fil_car[3]', '$fil_car[5]'
				, $fil_car[7], '0', '0'
				);
				") ){
				
				insertElementos( $fil_car[10] , $id_geral, $fil_car[9], $fil_car[4] );
				
				echo rawurlencode ("Filme $fil_car[8] inserido com sucesso.");	
				
				} else{
			
				echo 
				rawurlencode(
				"N�o � poss�vel de momento adicionar $fil_car[8] � lista de filmes."
				);
			
				$bd->submitQuery("Delete From `geral` Where `id_geral` = ".mysql_insert_id());
			
			}
				
		}
		
		$rels = $_POST['rel_fil'];
		
		insereRel($rels, $id_geral, $fil_car[4], $fil_car[9]);
		
	 	/*for($i = 0; $i < count($rels) ; $i++){
		
			if( ! $bd->submitQuery("INSERT INTO `realizador_filme` (
			`id_realizador` ,
			`filme_genero_filme_filme_id_genero_filme` ,
			`filme_geral_id_geral` ,
			`filme_tipo_som_filme_id_tipo_som_filme` ,
			`realizador_filme_nome`
			)
			VALUES (
				NULL , '$fil_car[9]', '$id_geral', '$fil_car[4]', '"
				.clearGarbadge($rels[$i], false, false)."'
			);
			") ) break;
	 	}*/
		
	
	
}else if (defined( 'IN_PHPAP' )){
	
	/*Se estiver definido no index.php � imprimida 
	a interface de inser��o de um novo filme*/
	
	$tipo_som_t = tipoSomListEdit( 0 );
	
	$genero = tipoGenListEdit( 0 );
	
	$elementos = printElementos( 0 );
	
	echo "
			<form name=\"ins_filme\" id=\"ins_filme\">
			
			<p><font color=\"brown\">Elementos de preenchimento obrigat�rio...</font></p>
			
			<p><label for=\"nom_fil\">
			T�tulo:<br /> 
			<input class=\"forms\" maxlength=\"70\" type=\"text\" name=\"nom_fil\" 
			id=\"nom_fil\" />
			</label></p>
				
			<p><label for=\"eti_fil\">
			Etiqueta:<br /> 
			<input class=\"forms\" maxlength=\"25\" type=\"text\" name=\"eti_fil\" 
			id=\"eti_fil\" />
			</label></p>	
			
			
			<div id=\"toolbar\" style=\"
				background-image: URL('imagens/bg.gif');
				background-repeat: repeat-x;
				text-align:left;width:419px;\">
			".drawToolBar( "sin_fil", "toolbar_sin_fil", true)."</div>
			<textarea id=\"sin_fil\" name=\"sin_fil\" 
			style=\"font-family: verdana; font-size: 11px; width: 417px;\" rows=\"7\" 
			class=\"forms\">A sua sin�pse aqui...</textarea>
			
			
			<p><label for=\"ano_fil\">
			Ano:<br /> 
			<input class=\"forms\" type=\"text\" name=\"ano_fil\" id=\"ano_fil\" />
			</label></p>
			
			<p><label for=\"dur_fil\">
			Dura��o:<br /> 
			<input class=\"forms\" type=\"text\" name=\"dur_fil\" id=\"dur_fil\" />
			</label></p>
			
			<p><font color=\"brown\">Elementos de preenchimento n�o obrigat�rio...</font></p>
			
			<p><label for=\"req_fil\">
			Requesit�vel:
			<input type=\"checkbox\" name=\"req_fil\" value=\"1\" id=\"req_fil\" />
			</label></p>
			
			<p><label for=\"slo_fil\">
			Slogan:<br /> 
			<input class=\"forms\" maxlength=\"150\" type=\"text\" name=\"slo_fil\" 
			id=\"slo_fil\" />
			</label></p>
			
			<p>
			Tipo de som:<br />
			$tipo_som_t
			<br />
			<input class=\"forms\" type=\"button\" value=\"Novo tipo de som\" id=\"new_som\" />
			<input class=\"forms\" type=\"button\" value=\"Apagar tipo de som\" id=\"apg_som\" />
			<br />
			</p>
			
			<p>
			Realizador:<br />
			<select multiple=\"multiple\" id=\"rel_fil\" size=\"5\"></select>
			<br />
			<input class=\"forms\" type=\"button\" value=\"Adicionar realizador\" 
			id=\"new_rel\" />			  
			<input class=\"forms\" type=\"button\" value=\"Apagar realizador\" id=\"apg_rel\" />
			<br />
			</p>
			
			<p>
			G�nero de filme:<br />
			$genero
			<br /><div id=\"\"></div>	
			<input class=\"forms\" type=\"button\" value=\"Novo g�nero\" id=\"new_gen\" />	
			<input class=\"forms\" type=\"button\" value=\"Apagar g�nero\" id=\"apg_gen\" />
			<br />	
			</p>
			
			
			<div class=\"gestaopremain\" style=\"text-align:left;\">
			<p>
			$elementos
			<br />
			</div>
			
			<p>
			<label for=\"class_imdb_fil\">
			Classifica��o IMDB:<br />
			<input class=\"forms\"  type=\"text\" name=\"class_imdb_fil\" 
			id=\"class_imdb_fil\" />
			</label>
			</p>
			
			<input class=\"forms\" type=\"button\" value=\"Inserir filme\" 
			name=\"ins_fil\" id=\"ins_fil\" />
			
			<input class=\"forms\" type=\"reset\" value=\"Limpar\" 
			name=\"ins_fil\" id=\"ins_fil\" />
			
			</form>
		 ";
	
}

}

?>