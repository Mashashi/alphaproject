<?php

/**
 * Funções básicas de funcionamento do fórum, maioritariamente.
 * 
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

/**
 * Incluir a classe para fazer a variável de acesso a base de dados bd.php.
 *  	
 */
require_once ( "bd.php" );

/**
 * Incluir o ficheiro de configuração config.php.
 *  
 */
require_once ( "config.php" );

/**
 * listarCamposTabela()
 
 * Retorna o conteúdo de uma tabela com os números de campo desejados e com as opções
 * exprimidas por $adi, em um array. Ex: 
 * <pre>
 * ---------------------|tabela|---------------------
 * |campo1|campo2|campo3|campo4|campo5|campo6|campo7|
 * |ai    |lol   |cool  |dude  |dred  |girl  |mel   |
 * |vv    |aa    |xx    |cc    |bb    |ll    |kk    |
 * |seven |six   |one	|two   |three |four  |five  |  
 * --------------------------------------------------
 * </pre> 
 * <code>
 * //Função
 * $array = listarCamposTabela(array("campo1","campo2","campo3"),"tabela",
 *  "Where `campo1` In('ai','vv')");
 * print_r($array); 
 * //Retorno
 *  Array(
 *  		//Primeiro registo
 * 		[0] => "ai"
 * 		[1] => "lol"
 * 		[2] => "cool"
 * 		//Segundo registo
 * 		[3] => "vv"
 * 		[4] => "aa"
 * 		[5] => "xx"   
 *   )
 *  </code>  
 * 
 *   
 * @param mixed array $campos
 * @param String $tabela 
 * 
 * @return mixed array
 */
function listarCamposTabela( array $campos, $tabela, $adi ){

	$bd = ligarBD();

	$comando = "Select ";

	$array_result = "";

	$index = 0;

	foreach ( $campos as $campo )
	{

		$comando .= "$campo,";

	}

	$comando{strlen( $comando ) - 1} = " ";

	$comando .= " From `$tabela` " . $adi;
	//echo $comando;
	
	$exec = $bd->submitQuery( $comando );
	
	while ( $row = mysql_fetch_array($exec) )
	{

		for ( $i = 0; $i < count($row) - (count($row) / 2); $i++ )
			$array_result[$index++] = $row[$i];

	}


	return $array_result;

}





/**
 * fazerErro()
 * 
 * Imprime uma mensgem de erro.
 *    
 * @param String $msg
 *  
 * @return void
 */
function fazerErro( $msg ){

	global $emailadmin;

	echo "<div class=\"float-divider\"></div>
			<font face=\"verdana\">
					Ooooops...<br />
				<font color=\"red\" size=\"1\">
					<img src=\"imagens/alertmod.gif\" title=\"Erro\" alt=\"[:(]\" /> 
					$msg
				</font>
			</font>
					<br />
					Para mais esclarecimentos
					<br /> 
					<a href=\"mailto:$emailadmin\">contacta o administrador.</a>";

}





/**
 * listarAreaTopico()
 * 
 * Esta função esta encarregada de imprimir todo o conteúdo de um determinado tópico.
 *   
 * @uses pustAsSpam()
 * @uses listarCamposTabela()
 * @uses seeIfCanSpam()
 * @uses drawSubmitPost() 
 * 
 * @param integer $area  
 * @param integer $topico 
 *      
 * @return void
 */
function listarAreaTopico( $area, $topico, $id_pesq ){

	global $autorver;
	
	$bd = ligarBD();
	
	
	
	/**/
	if ( is_numeric( $id_pesq ) ){
			
			$query =
			$bd->submitQuery("Select `topico_area_id_area`, `topico_id_topico` 
			From `post` Where `id_post` = $id_pesq");
			
			if(mysql_numrows($query) == 1){
				
				$area = mysql_result($query,0,0);
				
				$topico = mysql_result($query,0,1);
			
			}
			
	} 
	
	/**/
	if ( (($autorver) || (isset($_SESSION['user']))) )
	{
		
		if( (((is_numeric($area)) 
		&& ($area > 0)) && ((isset($topico)) && (is_numeric($topico)) 
		&& ($topico > 0)) ) ){
		
		
			
		/*Meter mensagem como spam se $_GET['spam'] estiver inicializado*/
		if ( isset($_GET['spam']) )
			pustAsSpam( $_GET['spam'] );
		/*Meter mensagem como spam*/
		
		global $avatdefault;
		global $numpp;
		
		$query = 
		$bd->submitQuery( "Select `area_nome` From `area` Where `id_area` = $area" );
		
		if ( mysql_num_rows($query) > 0 ){
			
			$buffer .= "
			<div class=\"local\"><a href=\"$phpself?elem=8\">Fórum</a> » ";
			
			$buffer .= "<a href=\"".$_SERVER["PHP_SELF"]."?elem=8&amp;area=$area\">
			" . mysql_result( $query, 0 ) . "
			</a>";
			
			$query = $bd->submitQuery( "Select `post_titulo` From `post` Where 
			`topico_id_topico` = '$topico' 
			And `post_prin` = 1 And `post_activo` = 1" );

			if ( mysql_num_rows($query) > 0 ){
					
					$sticky = 
					mysql_result( $bd->submitQuery("Select `topico_sticky` From `topico` Where `id_topico` = $topico"),0,0 );
					
					$tit = mysql_result( $query, 0 );
					
					echo  "$buffer »";
					
					if($sticky){
					
						echo  " <img src=\"imagens/sticky.gif\" alt=\"[Sticky]\" title=\"Sticky\" />";
					
					}
					
					echo  " $tit </div>";
				
				} else echo $buffer . " » <i>Inexistente</i></div>";

			if (is_numeric($_GET['pagi']) && is_numeric($_GET['pagf']) 
			&& $_GET['pagf'] > 0 && is_valid($_GET['pagi'], $numpp) ){
				
				$pagi = $_GET['pagi'];
				$pagf = $_GET['pagf'];
				
			} else {
				
				$pagi = 0;	
				$pagf = $numpp;

			}	
			
			if( is_numeric( $id_pesq ) ){
			
				$countIdPostMenor = 
				mysql_result( $bd->submitQuery("Select Count(*) From `post` Where `id_post` < $id_pesq"),0,0 );
			
				$pagi = $pagf * floor( ( $countIdPostMenor / $pagf) );
			
				if($pagi < 0){ $pagi = 0; }
				
			}
			
		
			
			
				
			//Listagem da informação refenrente aos posts do tópico.
			$arrayposts = array();
			$arrayposts[0] = "`registo_id_registo`";
			$arrayposts[1] = "`post_titulo`";
			$arrayposts[2] = "DATE_FORMAT(`post_data_hora`, '%d-%m%-%Y %H:%i:%s')";
			$arrayposts[3] = "`post_texto`";
			$arrayposts[4] = "`id_post`";
			$adiposts = "Where `topico_id_topico` = $topico And `post_activo` = 1 
			Order By `post_prin` Desc, `id_post` Asc Limit $pagi,$pagf";
			
			$arrayposts = listarCamposTabela( $arrayposts, "post", $adiposts );
			


			
			
			
			
			
			if ( count($arrayposts) > 4 )
			{
			
				$msgalert = "";
				
				for ( $i = 4; $i < sizeof($arrayposts); $i += 5 )
				{

				$query_respeito = 
					$bd->submitQuery( 
						
						"SELECT 
						((SELECT Count( * ) 
						FROM `controlo_respeito` 
						WHERE `controlo_respeito_tipo` = 1 And `post_id_post` = ".$arrayposts[$i].") - 
						(SELECT Count( * ) 
						FROM `controlo_respeito` 
						WHERE `controlo_respeito_tipo` = 0 And `post_id_post` = ".$arrayposts[$i]."))" 
						
					);
					
					
					
					if($i == 4) $topictitulo = $arrayposts[$i-3];
					
					//Dados do utilizador que colocou a mensagem
					$arrayutil = "";
					$arrayutil[0] = "DATE_FORMAT(`registo_data`, '%d-%m-%Y')";
					$arrayutil[1] = "registo_avatar";
					$arrayutil[2] = "registo_ass";
					$arrayutil[3] = "DATE_FORMAT(`registo_online`, '%d%m%Y%H%i%s')";
					$arrayutil[4] = "estatuto_id_estatuto";
					$arrayutil[5] = "registo_nick";
					
					$adiutil = "Where `id_registo` = " . $arrayposts[$i - 4];
					$arrayutil = listarCamposTabela( $arrayutil, "registo", $adiutil );

					$querynump = $bd->submitQuery( "Select count(*) 
					From `post` Where `registo_id_registo` = " .
						$arrayposts[$i - 4] );

					if( !empty($arrayutil[4]) ){
						
						$queryestat = $bd->submitQuery( "Select `estatuto_nome` 
						From `estatuto` Where `id_estatuto` = $arrayutil[4]" );

						if ( mysql_numrows($queryestat) == 1 )
							$queryestat = 
							"<div class=\"geralbottomleftitens\">Estatuto: " 
							. mysql_result( $queryestat,0 ) . "</div>";
						else
							$queryestat = "";
						
					} else
						$queryestat = 
						"<div class=\"geralbottomleftitens\">Estatuto: Indefinido</div>";
					
					
					if ( $arrayutil[1] == null || trim($arrayutil[1]) == "" )
					$arrayutil[1] = "
					<img src=\"imagens/avatar/$avatdefault\" 
					title=\"Avatar\" alt=\"Avatar\"/>";
					else
						$arrayutil[1] = "<img src=\"$arrayutil[1]\" title=\"Avatar\" 
						alt=\"Avatar\"/>";
						
					$imagemestado = "imagens/msn1.gif";
					$alt = "[Offline]";
					if ( $arrayutil[3] > date("dmYHis") )
					{
						$imagemestado = "imagens/msn2.gif";
						$alt = "[Online]";
					}

					if ( $arrayutil[2] == null ) $arrayutil[2] = "";
					
					if( empty($arrayutil[5]) )$arrayutil[5] = "Autor banido";
					
					if( empty($arrayutil[0]) )$arrayutil[0] = "Indefinido";
					
					
					
					
					
					
					echo "
				<div class=\"geral\"><div class=\"geraltop\">
					<div class=\"geraltopleft\">" . $arrayposts[$i - 2] . "</div>
					<div class=\"geraltopright\">#" . ( round(($i / 5), 0) + ($pagi) ) .
						"</div>
				</div>
					<div class=\"geralbottom\">
						<div class=\"geralbottomleft\">
							<div id=\"nick$i\" class=\"geralbottomleftitens\">Nick: <a href=\"?elem=10&amp;perfil=". $arrayposts[$i - 4] ."\">" 
							. $arrayutil[5] . "</a>
							</div>
							<div class=\"geralbottomleftitens\" style=\"height: 100px;\">
							Classificação: ".mysql_result($query_respeito,0)."<br />
							<img src=\"imagens/thumbs_down.bmp\" class=\"thumbs thumbs_down\" style=\"cursor: hand;\" id=\"thumbs_down\" alt=\"[Péssimo comentário]\" title=\"Péssimo comentário\" />
							<img src=\"imagens/thumbs_up.bmp\" class=\"thumbs thumbs_up\" style=\"cursor: hand;\" id=\"thumbs_up\" alt=\"[Bom comentário]\" title=\"Bom comentário\" />
							<div class=\"info_thumbs\"></div>
							<input type=\"hidden\" value=\"$arrayposts[$i]\" class=\"hide_thumbs\" />
							</div>
							
							<div class=\"geralbottomleftitens\">Estado: 
							<img src=\"$imagemestado\" alt=\"$alt\" />
							</div>
							$queryestat
							<div class=\"geralbottomleftitens\">
							
							<div class=\"avatarcontent\">$arrayutil[1]</div>
							</div>
							<div class=\"geralbottomleftitens\">Registado a:<br />" 
							. $arrayutil[0] . "
							</div>
							<div class=\"geralbottomleftitens\">Nº Posts: " 
							. mysql_result( $querynump, 0 ) . "
							</div>
						</div>
						
						<div class=\"head geralbottomright\">
							<img src=\"imagens/topico.gif\" alt=\"[Post:]\" />";

					if ( (round(($i / 5), 0) + ($pagi)) == 1 ){
						echo "Me:";
						
						if( $_SESSION['estat_carac'][0] )
							$msgalert = "Apagar este tópico implica apagar todas as respostas se existentes.\\nDeseja continuar?";
						
					}else {
						if( $_SESSION['estat_carac'][1] )
							$msgalert = "Deseja realmente apagar este post?";
						echo "Re:";
					
					}
					$class = $i == 4 ? "editaraquitopico" : "editaraquipost";
					
					echo "<br /><strong><strong id=\"pti$arrayposts[$i]\" class=\"$class\">" 
					. $arrayposts[$i - 3] . "</strong></strong>
							<hr />
						</div>
						<div class=\"postbody geralbottomright\">
							<div id=\"pte$arrayposts[$i]\" class=\"$class\">" 
							. nl2br( $arrayposts[$i - 1] ) . "</div>
						<hr />
						<div class=\"assi\">$arrayutil[2]</div>
						</div>
						
						<div class=\"footer geralbottomright\">
						 
						</div>
						<div class=\"geralbottomright\" style=\"text-align: right;\">
						<div style=\"float: right\">";

					if ( $i == 4 ) {
						
						if($_SESSION['estat_carac'][0]){
							
							$check = "";
							
							if( ! mysql_result(
							$bd->submitQuery( "Select `topico_pode_comentar` 
							From `topico` Where `id_topico` = (Select `topico_id_topico` 
							From `post` Where `id_post` = $arrayposts[4])" ),0,0) ) 
							$check = "checked=\"checked\"";
							
							echo "<img src=\"imagens/trancado.png\" alt=\"[Trancado?]\" 
						title=\"Trancado?\" /> <input id=\"tranc$arrayposts[$i]\" $check 
						type=\"checkbox\" class=\"trancadotop\" title=\"Trancado?\" />";
						
						}
						
						echo "<a href=\"javascript:window.print();\">
					<img src=\"imagens/imprimir.png\" border=\"0\" alt=\"Imprimir\" 
					title=\"Imprimir página\" /></a> ";
					
					}
					
					echo "
				<a href=\"#top\"><img src=\"imagens/top.png\" border=\"0\" 
				alt=\"Topo\" title=\"Ir para o topo\" /></a> ";

					$querycoment = $bd->submitQuery( "Select `topico_pode_comentar` From `topico` Where `id_topico` = $topico" );

					if ( isset($_SESSION['user']) )
					{

						if ( mysql_result($querycoment, 0) )
							echo "<a href=\"javascript:quote('pte$arrayposts[$i]','nick$i')\">
					<img src=\"imagens/citar.png\" border=\"0\" alt=\"[Citar]\" 
						title=\"Citar $arrayutil[5]\"/></a> ";


						if ( seeIfCanSpam($arrayposts[$i]) )
						{

							echo "
						<a href=\"#\" 
						onclick=\"
javascript:confirmSpam('?elem=8&amp;area=$area&amp;topico=$topico&amp;spam=$arrayposts[$i]','Estas prestes a reportar uma mensagem como spam! Para obter mais ajuda sobre este assunto consulta a FAQ. Desejas continuar?','Spam submetido com sucesso');\">
						<img src=\"imagens/spam.png\" border=\"0\" alt=\"[Spam]\" 
							title=\"Reportar Spam\"/>
						</a>";
					
					}
					
					
					$manipostopic = "
									<a href=\"javascript:confirmOp('"
									.$_SERVER["PHP_SELF"]."?elem="
									.$_GET['elem']."&amp;area=".$_GET['area']
									."&amp;topico="
									.$_GET['topico']
									."&amp;delpostop=$arrayposts[$i]','$msgalert','');\">
									<img src=\"imagens/excluir.png\" alt=\"[Excluir]\" 
									border=\"0\" 
									title=\"Excluir\" />
									</a>
									";
					
					if( $i == 4 ) {
							
						if($_SESSION['estat_carac'][0]) echo $manipostopic;
					
					} else {
						
						if($_SESSION['estat_carac'][1]) echo $manipostopic;	
						
					}
					
					
					
					
					}

					echo "</div></div>
						
					</div>
					
					</div>
					
					<div class=\"float-divider\"></div>
					
					";


				}

				echo "<div id=\"preview\"></div>";

				if ( isset($_SESSION['user']) )
				{

					if ( mysql_result($querycoment, 0) )
						drawSubmitPost( $area, $topico , $topictitulo );

					else
						echo "<font color=\"brown\">Tópico fechado.</font>";

				}
				else
					echo "<font color=\"brown\">Para participar no fórum é preciso fazer login.</font>";
				echo listarPagsArea( $area, $topico, $pagi, $numpp );

				$bd->submitQuery( "Update `topico` Set `topico_visto` = (`topico_visto`+1) Where `id_topico` = $topico" );

	
			}
			else
			{

				fazerErro( "O tópico desejado não esta disponível." );

			}

		}
		else
		{
			echo "<div class=\"local\">
			<a href=\"$phpself?elem=8\">Fórum</a> » Inexistente » Inexistente </div>";
			
			fazerErro( "A área com o tópico desejado não esta disponível." );

		}
		
		} else {
			
			echo "<div class=\"local\">
			<a href=\"$phpself?elem=8\">Fórum</a> » Inexistente » Inexistente </div>";
			
			fazerErro( "A área com o tópico desejado não esta disponível." );
		
		}
	}
	else
	{

		fazerErro( "Para ver a página por completo é preciso estar registado." );

	}


}





/**
 * listarAreasNome()
 * 
 * Lista todas as áreas pelo nome, mais a sua descrição.
 * 
 * Para acontecimentos não esperados é imprimido através da função fazerErro() uma 
 * das seguntes frases:
 * 
 * <ol>
 * 	<li>De momento não existem áreas predefinidas!</li> 
 * 	<li>Para ver a página por completo é preciso estar registado.</li>  
 * </ol>
 * 
 * @uses listarCamposTabela()
 * @uses fazerErro()
 *       
 * @return String
 */
 function listarAreasNome(){
	
	global $autorver;

	if ( ($autorver) || (isset($_SESSION['user'])) )
	{
		
		$id = 0;
		
		$fields = "";

		$fields[0] = "id_area";

		$fields[1] = "area_nome";

		$fields[2] = "area_descricao";

		$fields = listarCamposTabela( $fields, "area", "Order By `$fields[1]` Asc" );

		$buffer = "<div style=\"width:600px;float:left;\" class=\"basic\">";
		
		if ( sizeof($fields) - 1 > 0 )
		{

			

			$cont = 0;
			
			$msgalert = "Ao prosseguir com esta acção estará a apagar a área desejada e consequentemente todos os tópicos e respostas.\\nDeseja continuar?";
			
			foreach ( $fields as $key => $out )
			{

				if ( $cont == 0 )
				{
					
					$buffer .= "<a class=\"toogle\" href=\"?elem=8&amp;area=$out\">";

					$id = $out;
				
				}
				else
					if ( $cont == 1 )
					{

						$buffer .= "$out</a><div style=\"padding:3px;\">";
						
					}
					else
					{

						$buffer .= "$out";
  				
  				
  				if($_SESSION['estat_carac'][2]){
					
					$buffer .= "
					<p></p><a href=\"".$_SERVER['PHP_SELF']."?elem=8&amp;editarea=$id\">
					<img src=\"imagens/editar.png\" alt=\"[Renomear]\" border=\"0\" 
					title=\"Editar\" /> 
					</a>
					<a href=\"javascript:confirmOp('".$_SERVER['PHP_SELF']
					."?elem=8&amp;delarea=$id','$msgalert','');\">
					<img src=\"imagens/excluir.png\" 
					alt=\"[Excluir]\" border=\"0\" 
					title=\"Excluir\" />
					</a>
					";
					
				}
 				
				$buffer .= "</div>";

					}

					$cont++;

				if ( $cont > 2 ) $cont = 0;
				
			} //Fim do foreach
			
			
			
		}	else	{

			fazerErro( "De momento não existem áreas predefinidas!" );
			
		}
		
		if( $_SESSION['estat_carac'][2] ){
			
			
	
	
	//
			
			
			$buffer .= "
			<a href=\"#\" class=\"toogle\">Nova área</a><div style=\"padding-left:11%;\">
			
			<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."?elem=8\">
				
				<label for=\"titulo\">
				Nome:<br />
				<input name=\"titulo\" maxlength=\"255\" type=\"text\" class=\"forms\" />
				</label>
				
				<label for=\"descricao\">
				<br />Descrição:<br />
				
				<div id=\"toolbar\" style=\"
				background-image: URL('imagens/bg.gif');
				background-repeat: repeat-x;
				text-align:left;width:140px;\">
				" . drawToolBar( "descricaoo$id2", "toolbarhtml$id", false ) . "</div>
					
					<textarea name=\"descricao\" id=\"descricaoo$id2\" 
					class=\"font11 forms\" style=\"width: 138px;\"></textarea>
				</label>
				
				<p><input type=\"submit\" value=\"Criar\" class=\"forms\" /></p>
				
			</form>
			
			</div>
			";
				
				
			}
			
			
			$id = rand(0,99999).rand(0,99999).rand(0,99999);
			
			$id2 = rand(0,99999).rand(0,99999).rand(0,99999);
			
			$checked = array();
 
 			switch($_GET['rapipor']){
	
				case 2: $checked[1] = "checked=\"checked\""; break;
	
				case 3: $checked[2] = "checked=\"checked\""; break;
	
				default: $checked[0] = "checked=\"checked\""; break;
	
 			}

			echo "<div class=\"float-divider\"></div><div class=\"area\"><b id=\"startpesquisar\" style=\"cursor: pointer;\">";
 
			echo "
			<!--<script type=\"text/javascript\">
			if (navigator.appName == 'Microsoft Internet Explorer' && navigator.appVersion == 7.0 )
				document.write('<a href=\"#\">Pesquisa</a>');
			else
				document.write('Pesquisa');
			</script>-->
			Pesquisar
			";
 
			echo "</b></div>
			<div class=\"descricao\" style=\"width: 178px;\">Pesquisa rápida pelo síte.</div><br />
			<div class=\"pesquisar\">";
	
 
			echo "<div class=\"local\" style=\"font-size: 11px;\">Pesquisa rápida</div>
	
			<form method=\"get\" name=\"psqrapgeral\" action=\"index.php?elem=12\">
	
			<p>
			<label for=\"rapitext\">
			Valor da pesquisa:<br />
			<input type=\"text\" id=\"rapitext\" name=\"rapitext\" value=\"".$_GET['rapitext']."\" 
			class=\"forms\" />
			</label>
			</p>

			<p><label for=\"subpsqra\">
			<input type=\"submit\" value=\"Pesquisar\" id=\"subpsqra\" class=\"forms\" />
			</label></p>

			Pesquisar por:
			<table>
			<tr>
				<td>Tópicos (Títulos)</td>
				<td><input type=\"radio\" name=\"rapipor\" value=\"1\" $checked[0] /></td>
			</tr>
			<tr>
				<td>Respostas (Corpo da mensagem)</td>
				<td><input type=\"radio\" name=\"rapipor\" $checked[1] value=\"2\" /></td>
			</tr>
			
			<tr>
				<td>Nomes de utilizadores</td>
				<td><input type=\"radio\" name=\"rapipor\" $checked[2] value=\"3\" /></td>
			</tr>
			
			</table>
			<input type=\"hidden\" name=\"elem\" value=\"12\" />
			</form>";
			
			define( 'INCLUDE_PHPAP_PESRAP' , true );
			
			
			echo "<br /></div>";
			
			
			
			
			
			echo $buffer."</div>";
			 
			
			

	}
	else
	{

		fazerErro( "Para ver a página por completo é preciso estar registado." );

	}

 }
/*function listarAreasNome(){
	
	global $autorver;

	if ( ($autorver) || (isset($_SESSION['user'])) )
	{
		
		$id = 0;
		
		$fields = "";

		$fields[0] = "id_area";

		$fields[1] = "area_nome";

		$fields[2] = "area_descricao";

		$fields = listarCamposTabela( $fields, "area", "Order By `$fields[1]` Asc" );

		$buffer = "<div style=\"width:182px;float:left;\" class=\"basic\">";
		
		if ( sizeof($fields) - 1 > 0 )
		{

			

			$cont = 0;
			
			$msgalert = "Ao prosseguir com esta acção estará a apagar a área desejada e consequentemente todos os tópicos e respostas.\\nDeseja continuar?";
			
			foreach ( $fields as $key => $out )
			{

				if ( $cont == 0 )
				{
					
					$buffer .= "<a class=\"toogle\" href=\"?elem=8&amp;area=$out\">";

					$id = $out;
				
				}
				else
					if ( $cont == 1 )
					{

						$buffer .= "$out</a><div style=\"padding:3px;\">";
						
					}
					else
					{

						$buffer .= "$out";
  				
  				
  				if($_SESSION['estat_carac'][2]){
					
					$buffer .= "
					<p></p><a href=\"".$_SERVER['PHP_SELF']."?elem=8&amp;editarea=$id\">
					<img src=\"imagens/editar.png\" alt=\"[Renomear]\" border=\"0\" 
					title=\"Editar\" /> 
					</a>
					<a href=\"javascript:confirmOp('".$_SERVER['PHP_SELF']
					."?elem=8&amp;delarea=$id','$msgalert','');\">
					<img src=\"imagens/excluir.png\" 
					alt=\"[Excluir]\" border=\"0\" 
					title=\"Excluir\" />
					</a>
					";
					
				}
 				
				$buffer .= "</div>";

					}

					$cont++;

				if ( $cont > 2 ) $cont = 0;
				
			} //Fim do foreach
			
			
			
		}	else	{

			fazerErro( "De momento não existem áreas predefinidas!" );
			
		}
		
		if( $_SESSION['estat_carac'][2] ){
			
			$id = rand(0,99999).rand(0,99999).rand(0,99999);
			
			$id2 = rand(0,99999).rand(0,99999).rand(0,99999);
			
			$buffer .= "
			<a href=\"#\" class=\"toogle\">Nova área</a><div style=\"padding-left:11%;\">
			
			<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."?elem=8\">
				
				<label for=\"titulo\">
				Nome:<br />
				<input name=\"titulo\" maxlength=\"255\" type=\"text\" class=\"forms\" />
				</label>
				
				<label for=\"descricao\">
				Descrição:<br />
				
				<div id=\"toolbar\" style=\"
				background-image: URL('imagens/bg.gif');
				background-repeat: repeat-x;
				text-align:left;width:140px;\">
				" . drawToolBar( "descricaoo$id2", "toolbarhtml$id", false ) . "</div>
					
					<textarea name=\"descricao\" id=\"descricaoo$id2\" 
					class=\"font11 forms\" style=\"width: 138px;\"></textarea>
				</label>
				
				<p><input type=\"submit\" value=\"Criar\" class=\"forms\" /></p>
				
			</form>
			
			</div>
			";
				
				
			}
			
			echo $buffer."</div>";
			
		

	}
	else
	{

		fazerErro( "Para ver a página por completo é preciso estar registado." );

	}

}*/





/**
 * listarArea()
 * 
 * Esta função lista todo o conteúdo de uma área.
 * Para para acontecimentos inesperados, chama a função fazer erro e imprime uma 
 * das mensagens:
 *
 * <ol>
 * 	<li>Por enquanto não existem tópicos nesta área</li>
 * 	<li>Esta área não existe</li>
 * 	<li>Para ver a página por completo é preciso estar registado</li>  
 * </ol>   
 * 
 * @uses fazerErro()
 * @uses clearGarbadge()  
 *  
 * @param integer $area 
 * 
 * @global integer Número de tópicos por página
 * @global String Tags permitidas
 * @global String O título das tags permitidas
 * @global boolean Se um utilizador não registrado pode ver o fórum
 *      	
 * @return void
 */
function listarArea( $area ){

	global $autorver;

	if ( (($autorver) || (isset($_SESSION['user']))) && (isset($area) && is_numeric
		($area) && $area > 0) )
	{

		global $numtp;
		global $tooltiptags;
		global $tagstoshow;

		$buffer = "";

		$bd = ligarBD();

		$query = 
		$bd->submitQuery( "Select `area_nome` From `area` Where `id_area` = $area" );

		echo "
		<script type=\"text/javascript\"> 
		var tt = '";

		if ( count($tooltiptags) > 0 )
			echo join( '?', $tooltiptags );

		echo "';
		tt = explode('?',tt);
		
		var ts = '";

		if ( count($tagstoshow) > 0 )
			echo join( '?', $tagstoshow );

		echo "';
		ts = explode('?',ts);
		</script>";

		$msg = "<div class=\"titulotopico\" id=\"topicnew\"  
				style=\"font-size: 13px;max-width: 100%;border: 0px;\">
				<a href=\"javascript:novoTopico('$area',tt,ts,".$_SESSION['estat_carac'][0].");\">
				<img border=\"0\" src=\"imagens/new.png\" alt=\"[-->]\">
				</a></div><div class=\"float-divider\"></div>";

		if ( mysql_num_rows($query) == 1 )
		{

			$areatit = mysql_result( $query,0,0 );

		}
		else
		{

			$areatit = "<i>Inexistente</i>";
			$msg = "";

		}
		
		echo "<div class=\"local\">
			<a href=\"$phpself?elem=8\">Fórum</a> » 
			$areatit</div><div class=\"float-divider\"></div>
			";

		if ( isset($_SESSION['user']) )
			echo $msg;

		if ( mysql_num_rows($query) > 0 )
		{


			if ( is_numeric($_GET['pagi']) && is_numeric($_GET['pagf']) 
			&& is_valid($_GET['pagi'],$numtp) && $_GET['pagf'] > 0 ){ 
				
				$pagi = $_GET['pagi'];
				$pagf = $_GET['pagf'];
				
			}
			else{
				
				$pagi = 0;
				$pagf = $numtp;
			
			}	
			//Reparar no promenor `id_topico` = `topico_id_topico` sem isto será retornado
			//o cross join das duas tabelas, também conhecido como produto cartesiano.
			$query = $bd->submitQuery( "
			
			(SELECT `id_topico`,`topico_visto`,`topico_sticky` FROM `topico` 
			Where `area_id_area` = $area And `topico_sticky` = 1)
			Union 
			(Select `id_topico`,`topico_visto`,`topico_sticky`
			From `topico`,`post` 
			Where `id_topico` = `topico_id_topico`
			And  `area_id_area` = $area 
			And `post_activo` = 1
			And `post_prin` = 1
			Order By  `id_topico` Desc Limit $pagi,$pagf)
			
			 " );
			//
			if ( mysql_num_rows($query) > 0 )
			{

			echo "
			<div class=\"topico\">
				
				<div class=\"titulotopico\">
					Tópico
				</div>
				
				<div class=\"prome\">
					Iniciado por:
				</div>
				
				<div class=\"prome\">
					Respostas:
				</div>
				
				<div class=\"prome\">
					Visto:
				</div>
				<br />
				
			</div>
			";


				$fla = "FFFFFF";
				$ind = true;
				$respo = "";

				while ( $row = mysql_fetch_array($query) )
				{


					$link = 0;

					for ( $i = 0; $i < count($row) - (count($row) / 2); $i++ )
					{

						$link++;

						if ( $link == 1 )
						{

							if ( $ind )
							{
								$fla = "E0E0DF";
								$ind = false;
							}
							else
							{
								$fla = "F0F0EF";
								$ind = true;
							}


							$respo = $bd->submitQuery( "Select count(*)
					From `post` Where `topico_id_topico` = '$row[$i]' And `post_prin` = 0 
					And `post_activo` = 1" );


							$querycom = $bd->submitQuery( "Select `registo_nick`
					From `registo` Where `id_registo` = 
					(Select `registo_id_registo` From `post` Where `post_prin` = 1 
					And `topico_id_topico` = $row[$i])" );

							$nomeutil = "";

							if ( mysql_affected_rows() == 1 )
							{

								$nomeutil = mysql_result( $querycom, 0 );

							}
							else
							{

								$nomeutil = "Autor banido";

							}

							if ( strlen($nomeutil) > 10 )
							{

								$nomeutil = substr( $nomeutil, 0, 10 ) . "...";

							}

							if ( mysql_affected_rows() != 1 )
							{

								$nomeutil = "<em><b>" . $nomeutil . "</b></em>";

							}
							$querytitl = $bd->submitQuery( "Select `post_titulo` From `post` Where `topico_id_topico` = $row[$i] And `post_prin` = 1" );

							if ( mysql_num_rows($querytitl) == 1 )
								$titulo = mysql_result( $querytitl, 0 );
							else
								$titulo = "<b><em>Inexistente</em></b>";

							if ( strlen(strip_tags($titulo)) > 24 )
								$titulo = substr( $titulo, 0, 24 ) . "...";
							
							$id_topico =$row[$i];
							
							
							
							$buffer = "<div class=\"topico topico_mov_area\" 
							style=\"background-color: #$fla;\">
							
							<div style=\"float:left;\" >
							<img class=\"icon_thread\" alt=\"[Tópico]\" src=\"
							";
							
							if($row[2] == 1){
							
								$buffer .= "imagens/sticky.png\" title=\"Sticky\" alt=\"[Sticky]\"";
							
							} else {
							
								$buffer .= "imagens/post.png\" title=\"Post\" alt=\"[Post]\"";
							
							}
							
							$buffer .= " /></div><div class=\"titulotopico\">
							<a href=\"?elem=8&amp;area=$area&amp;topico=$row[$i]\">" 
							. $titulo . "</a><br />";
							
							
							
							
							
						$buffer .= "</div>";
						
						
						} else
							if ( $link == 2 )
							{


								echo $buffer."
				<div class=\"prome\">$nomeutil</div>
				<div class=\"prome\" style=\"margin-right: 1px;\">
				" . mysql_result( $respo, 0 ) . "
				</div>
				<div class=\"prome\" style=\"margin-right: 1px;\">
				$row[$i]
				</div>
				";
				
				if($_SESSION['estat_carac'][0]){
					
					
					echo listAreasMov($area)
					."<input type=\"hidden\" value=\"$id_topico\" class=\"mov_top_id\" />
					Sticky<input type=\"checkbox\" value=\"1\" ";
					
					if($row[2]) echo " checked=\"$row[2]\" ";
					
					echo " class=\"sticky_change\" >";
				}
				echo "<div class=\"float-divider\"></div></div>";

							}

					}


				}

				echo listarPagsArea( $area, null, $pagi, $numtp );


			}
			else
			{

				fazerErro( "Por enquanto não existem tópicos nesta área." );

			}

		}
		else
		{

			fazerErro( "Esta área não existe." );

		}

	

	}
	else
	{

		fazerErro( "Para ver a página por completo é preciso estar registado." );

	}

}





/**
 * listAreasMov()
 * 
 * Listar o menu para movimentar um tópico.
 * 
 * @uses fazerErro()
 *   
 * @param int $doNotListId
 *  
 * @return void
 */
function listAreasMov($doNotListId){
	
	$area = "";
	
	$bd_area = ligarBD();
	
	$bd_area = $bd_area->submitQuery("Select `id_area`, `area_nome` From `area`");
	
	$retorno = "
	<br />Mover para: <form><select class=\"forms movtopto_area\">";
	
	$first = false;
	
	for($i = 0; $i < mysql_numrows($bd_area); $i++){
		
		if( $doNotListId != mysql_result($bd_area, $i, 0) ){
			
			if($first == false) $first = mysql_result($bd_area, $i, 1);
		
			$retorno .= "<option value=\"".mysql_result($bd_area, $i, 0)."\">".mysql_result($bd_area, $i, 1)."</option>";
		
		}
	}
	$bd_area = mysql_fetch_array($bd_area);
	$retorno .= "</select> <input class=\"movtopto_area_submit forms\" type=\"button\" value=\"Mover\"></form>";
	
	return $first!=false?$retorno:"";
	
}





/**
 * drawSubmitPost()
 * 
 * Nesta função é desenhada a caixa para o utilizador poder introduzir as suas mensagens.
 * Válido apenas para posts.
 * O equivalente desta função para tópicos é em javascript, <code>novoTopico(area)</code>.   
 *   
 * @uses fazerErro()
 *   
 * @param integer $area
 * @param integer $topico  
 *  
 * @return void
 */
function drawSubmitPost( $area, $topico, $titulotopic ){

	global $autorver;

	if ( (($autorver) || (isset($_SESSION['user']))) && (isset($area) && is_numeric
		($area) && $area > 0) && (isset($topico) && is_numeric($topico) && $topico > 0) )
	{

		echo "
	<form name=\"submitpost\" id=\"submitpost\">
	
	<div class=\"geral\">
	<div class=\"geraltop\">
	<div class=\"geraltopleftpost\">
		<img src=\"imagens/reply.png\" alt=\"[Responder]\">
	</div>
	<div class=\"geraltopleftpost\" style=\"padding: 0px;border: 0px;margin-top: 2px;\">
	<div class=\"geraltoppostinle\" style=\"height: 18px;\">
		Assunto
	</div>
	
	<div id=\"geraltoppostinri\">
		<input class=\"assuntostyle forms\" 
		id=\"assunto\"
		type=\"text\" 
		maxlength=\"80\" 
		name=\"assunto\"
		value= \"$titulotopic\" />
	
		<input
		type=\"hidden\" 
		maxlength=\"1\" 
		value=\"1\"
		name=\"coment\" />
		
		<input
		type=\"hidden\"
		value=\"$topico\"
		name=\"topico\" id=\"topico\" />
		
		<input
		type=\"hidden\"  
		value=\"$area\"
		name=\"area\" id=\"area\" />
		
	</div>
	</div>
	</div>
	
	<div class=\"geraltop\">
	<div class=\"geraltoppostinle\" id=\"statpost\" style=\"
	background-image: URL('imagens/bg.gif');
	background-repeat: repeat-x;
	text-align:left;\">
		" . drawToolBar( "texto", "selecttoolb", true ) . "
	</div>
	
	<div id=\"geraltoppostinri\" style=\"width: 421px;\">
		<textarea id=\"texto\"   style=\"font-family: verdana; 
		font-size: 11px; width: 417px;\" rows=\"7\" 
		name=\"texto\" class=\"forms\"></textarea>
	</div>
	</div>
	
  <div class=\"geraltop\" style=\"text-align: center;\">
  <input type=\"button\" class=\"forms\" id=\"subpost\" name=\"submeter\" value=\"Submeter\" />
  
  
  <input type=\"button\" class=\"forms\" id=\"prepost\"
  value=\"Previsualizar\" onclick=\"javascript:preVisualizar('" . $_SESSION['user'] . "','#num','" . $_SESSION['estat_nome'] .
			"',document.submitpost.texto.value,document.submitpost.assunto.value,'" 
			. $_SESSION['assi'] .
			"','" . $_SESSION['data_registo'] . "','" . ( $_SESSION['num_posts'] + 1 ) .
			"','" . $_SESSION['avatar'] . "');\" />
	
	
	
	
	</div>
	</div>
	
	</form>
	";

	}
	else
	{

		fazerErro( "Para ver a página por completo é preciso estar registado." );

	}

}





/**
 * drawToolBar()
 * 
 * Função que desenha a barra de ferramentas.
 *    
 * @param integer $responseid Id de reposta para o elemento DOM
 * @param integer $id define um id e nome para o <select></select>
 *  
 * @return void
 */
function drawToolBar( $responseid, $id, $printyou_inter ){
	
	global $tooltiptags;
	global $tagstoshow;

	$resposta = "";

	if ( empty($id) )
		$id = rand( 0, 100 ) . rand( 0, 100 ) . rand( 0, 100 );

	$resposta = "
	<img src=\"imagens/dots.png\" alt=\"[:]\" />&nbsp;<select id=\"$id\" name=\"$id\" onChange=\"javascript:addTextToolBar('$id','$responseid');\" class=\"heighttags\"><option value=\"\">Tags permitidas</option>";

	for ( $i = 0; $i < sizeof($tagstoshow); $i++ )
	{
		$tip = $tooltiptags[$i];

		$resposta .= "<option value=\"$tagstoshow[$i]\">" . $tip . "</option>";

	}

	$resposta .= "</select>";
	
	if($printyou_inter){
		
		$id = rand( 0, 100 ) . rand( 0, 100 ) . rand( 0, 100 );
	
		$resposta .= "<br />
		<img style=\"cursor:pointer;\" 
		class=\"newvidyou\" 
		border=\"0\"
		id=\"$id\"
		alt=\"Introduzir video do youtube\" 
		title=\"Introduzir video do youtube\" 
		src=\"imagens/youtube_2.png\" />
	
		<input type=\"hidden\" value=\"$responseid\" id=\"rox$id\" />";
	
	}
	
	return $resposta;
	
}





/**
 * onLine()
 * 
 * ID do utilizador, se estiver ligado irá perlonagar o tempo da sua sessão e dispultar
 * a acção que leva ao update dos utilizadores offline ou online, senão irá apenas fazer 
 * esta última.
 * 
 * @param integer $user 
 * 
 * @global integer Tempo passado o qual o user é dado offline
 * 
 * @return void
 */
function onLine( $user, $estat, $key ){
	
	
	
	if( validarKey( $user, $estat , $key ) ) {
	
	global $exp;

	$bd = ligarBD();
	
	$bd->submitQuery( "Delete From `session_control` Where `registo_id_registo` In (Select `id_registo` From `registo` Where `registo_online` is null) " );
	
	if ( (isset($user) && is_numeric($user) && $user > 0) )
	{
	
		$query = $bd->submitQuery( "Select DATE_FORMAT(`registo_online`, '%Y%m%d%H%i%s') 
			From `registo` Where `id_registo` = $user" );

		if ( mysql_numrows($query) == 1 )
		{

			if ( mysql_result($query, 0, 0) < date("YmdHis") || mysql_result($query, 0, 0) == null )
			{

				if ( isset($_SESSION['id_user']) )
				{

					$tempo = date( "YmdHis", mktime(date("H"), (date("i") + ($exp)), date("s")) );
					$bd->submitQuery( "Update `registo` Set `registo_online` = $tempo 
					Where `id_registo` = " . $_SESSION['id_user'] );

				}
				else
				{

					session_destroy();
					header( "location: index.php?act=logout" );

				}
				
			}
			else
			{

				$tempo = date( "YmdHis", mktime(date("H"), (date("i") + ($exp)), date("s")) );

				$_SESSION['timelefttt'] = $exp;

				$bd->submitQuery( "Update `registo` Set `registo_online` = $tempo 
				Where `id_registo` = $user" );

			}


		}
		else
		{
			
			//Impedir que um utilizador apagado fique online
			header( "location: index.php?act=logout" );
		
		}
		
		
		$query = $bd->submitQuery( "Select DATE_FORMAT(`registo_is_activo`, '%Y%m%d') 
		From `registo` Where `id_registo` = $user" );
		
		if( mysql_numrows($query) == 1 ){
				
			if( mysql_result($query,0,0) >= date("Ymd") ){
				
				//Impedir que um utilizador bloqueado fique online
				header( "location: index.php?act=logout" ); 
			
			}
			
		}
		
	}
	
	$tempo = date( "YmdHis" );
	
	$bd->submitQuery( "Update `registo` Set `registo_online` = null 
	Where `registo_online` < $tempo " );
	
	
	
	}
	
	
	
}





/**
 * clearGarbadge()
 * 
 * Função que faz a filtragem de informação passada através de $str e que por
 * $allowtags permite ou não confrome true ou false.
 * 
 * Funções que poderiam ser adicioandas: 
 * 	1.htmlspecialchars()
 *  Adicionar uma maneira de limitar a altura da tag object para os videos do youtube.com
 *   
 * @param String $str Texto a filtrar
 * @param boolean $allowtags Se permite a inserção de tags 
 * @param boolean $allowyoutube Se permite a inserção de vídeos do youtube
 *   
 * @return String
 */
function clearGarbadge( $str, $allowtag, $allowyoutube ){

	global $allowtags;
	
	$str = str_replace( "'", "´", $str );
    
	//$str = htmlspecialchars($str, ENT_QUOTES);
	//$str = preg_replace("/<.*\s\s*.*>/i", "", $str);
	
	//$str = preg_replace("/class|style/i", "", $str);
	
	if ( $allowtag ) $str = strip_tags( $str, join("", $allowtags) );
	else $str = strip_tags( $str );
	
	if($allowyoutube){
		
		$matches = preg_split("#\[youtube\]http://(?:www\.)?youtube.com/watch\?v=([0-9A-Za-z-_]{11})[^[]*\[/youtube\]#is", $str,-1,PREG_SPLIT_DELIM_CAPTURE);

		$final = array();
 
		for($i = 0; $i < count($matches) ;$i++) 
			if($i%2!=0) array_push($final,$matches[$i]);
		
		for($i = 0; $i < count($matches) ;$i++)
			$str = preg_replace("#\[youtube\]http://(?:www\.)?youtube.com/watch\?v=([0-9A-Za-z-_]{11})[^[]*\[/youtube\]#is", printBaseYou($final[$i]) , $str, 1);
	
	}
	
	//$str = preg_replace("#\[youtube\].*\[/youtube\]#is", "" , $str);
	
	return $str;

}





/**
 * printBaseYou()
 * 
 * A variavel $id será o id do video do youtube de notar que este código é xhtml válido. 
 * 
 * @uses clearGarbadge() 
 *  
 * @param String $id ID do vídeo do youtube
 *  
 * @return void
 */
function printBaseYou($id){


return "<object data=\"http://www.youtube.com/v/$id&hl=en&fs=1&rel=0&color1=0xe1600f&color2=0xfebd01\" type=\"application/x-shockwave-flash\" width=\"419\" height=\"350\">
<param name=\"quality\" value=\"high\" />
<param name=\"allowFullScreen\" value=\"true\" />
<param name=\"movie\" value=\"http://www.youtube.com/v/$id&hl=en&fs=1&rel=0&color1=0xe1600f&color2=0xfebd01\" />
<img border=\"0\" src=\"/img/video.png\" alt=\"Vídeo (Objeto multimídia)\" /></object><!--[youtubecopy]$id\[/youtubecopy]-->";

}

/**
 * reverseBaseYou()
 * 
 * Localizar o código html correspondente, dentro deste o id do clip e 
 * colocá-lo entre a tag [youtube][/youtube]. 
 * 
 * @uses clearGarbadge() 
 *  
 * @param String $id
 *  
 * @return void
 */
function reverseBase($str){
	
	//preg_match_all	
	$matches = array();
	
	//Case insensentive
	$matches = preg_split("#<!--\[youtubecopy\]([0-9A-Za-z-_]{11}[^[]*)\[/youtubecopy\]-->#is",$str,-1,PREG_SPLIT_DELIM_CAPTURE);
	
	//echo "<code>".print_r( $matches )."</code>";
	
	
	$final = array();
 
	for($i = 0; $i < count($matches) ;$i++) 
		if($i%2!=0) array_push( $final,$matches[$i] );
		
	for($i = 0; $i < count($final) ;$i++){
			
			$str = preg_replace(
			 "#<object.*[^[]*object>#",
			 "[youtube]http://www.youtube.com/watch?v=$final[$i][/youtube]",
			 $str,
			 1);
			
	}
	
	return $str;

}



/**
 * validarUser()
 * 
 * Função responsável pela inicialização de váriáveis de sessão. 
 * 
 * @uses clearGarbadge() 
 *  
 * @param String $user
 * @param String $pass  
 *  
 * @return void
 */
function validarUser( $user, $pass )
{
	
	//echo sprintf("%s, %s",$user, $pass);
	
	if ( (isset($user)) && (isset($pass)) )
	{

		$user = clearGarbadge( rawurldecode($user), false, false);

		$pass = clearGarbadge( rawurldecode($pass), false, false);
		
		$index = 0;

		if ( empty($user) )
			die();


		if ( empty($pass) )
			die();

		global $avatdefault;
		global $exp;
		
		$basedados = ligarBD();

		$is = $basedados->submitQuery( "Select 
	   `registo_pass`,`estatuto_id_estatuto`,`registo_sha1`,DATE_FORMAT(`registo_data_ultima`, '%d-%m%-%Y'),`id_registo`,`registo_avatar`,`registo_ass`,DATE_FORMAT(`registo_data`, '%d-%m%-%Y'), DATE_FORMAT(`registo_data_nas`, '%d-%m%-%Y'),`registo_homepage`,`registo_mail` From `registo` Where `registo_nick` Like '$user'" );

	
		if ( mysql_num_rows($is) > 0 )
		{
			
			$array_result = "";
			$index = 0;

			while ( $row = mysql_fetch_array($is) )
			{

				for ( $i = 0; $i < count($row) - (count($row) / 2); $i++ )
					$array_result[$index++] = $row[$i];

			}

			
			$pass_user_bd = $array_result[0];

			if ( $array_result[2] == 1 )
				$pass = sha1( $pass );
			
			
			
			if ( ($pass_user_bd) == ($pass) )
			{
			
				$data = date( "Y-n-j" );
				
				//Sha ligado ou não
				$_SESSION['sha_act'] = $array_result[2];
				//Password do utilizador
				$_SESSION['user_pass'] = $pass_user_bd;
				//Homepage
				$_SESSION['home_pag'] = $array_result[9];
				//Data de nascimento
				$_SESSION['data_nas'] = $array_result[8];
				//Nome de utilizador
				$_SESSION['user'] = $user;
				//ID do utilizador
				$_SESSION['id_user'] = $array_result[4];
				//Chave de sessão
				$_SESSION['key_session'] = gerarKeySessao( $_SESSION['id_user'] );
				//ID do esatuto
				$_SESSION['id_estat'] = $array_result[1];
				//Data da úlrima visita
				$_SESSION['ultvisi'] = $array_result[3];
				
				$query = $basedados->submitQuery( "Select `estatuto_nome`
					,`estatuto_gerir_topi`
					,`estatuto_gerir_post`
					,`estatuto_gerir_area`
					,`estatuto_req_filme`
					,`estatuto_req_album`
					,`estatuto_req_outro`
					,`estatuto_gerir_filme`
					,`estatuto_gerir_album`
					,`estatuto_gerir_outro`
					,`estatuto_gerir_faq`
					,`estatuto_gerir_registo` 
					,`estatuto_gerir_estatuto`
					,`estatuto_gerir_frases`
					From `estatuto` WHERE 
					`id_estatuto` = $array_result[1]" );
				
				
				if ( mysql_num_rows($query) == 1 )
				{
					
					//Nome do estatuto
					$_SESSION['estat_nome']
					= mysql_result( $query, 0, 'estatuto_nome' );

					/*Variáveis que controlam as permições*/
					$_SESSION['estat_carac'][0]
					= mysql_result( $query, 0, 'estatuto_gerir_topi' );

					$_SESSION['estat_carac'][1]
					= mysql_result( $query, 0, 'estatuto_gerir_post' );

					$_SESSION['estat_carac'][2]
					= mysql_result( $query, 0, 'estatuto_gerir_area' );

					$_SESSION['estat_carac'][3]
					= mysql_result( $query, 0, 'estatuto_req_filme' );

					$_SESSION['estat_carac'][4]
					= mysql_result( $query, 0, 'estatuto_req_album' );

					$_SESSION['estat_carac'][5]
					= mysql_result( $query, 0, 'estatuto_req_outro' );

					$_SESSION['estat_carac'][6]
					= mysql_result( $query, 0, 'estatuto_gerir_filme' );

					$_SESSION['estat_carac'][7]
					= mysql_result( $query, 0, 'estatuto_gerir_album' );

					$_SESSION['estat_carac'][8]
					= mysql_result( $query, 0, 'estatuto_gerir_outro' );

					$_SESSION['estat_carac'][9]
					= mysql_result( $query, 0, 'estatuto_gerir_faq' );

					$_SESSION['estat_carac'][10]
					= mysql_result( $query, 0, 'estatuto_gerir_registo' );

					$_SESSION['estat_carac'][11]
					= mysql_result( $query, 0, 'estatuto_gerir_estatuto' );

					$_SESSION['estat_carac'][12]
					= mysql_result( $query, 0, 'estatuto_gerir_frases' );
					/*Variáveis que controlam as permições*/

				}

				//Avatar do utilizador
				if ( $array_result[5] == null )
					$_SESSION['avatar'] = "imagens/avatar/$avatdefault";
				else
					$_SESSION['avatar'] = $array_result[5];

				//Assinatura códificada com rawurlencode()
				$_SESSION['assi'] = rawurlencode( $array_result[6] );

				//Data de registo
				$_SESSION['data_registo'] = $array_result[7];

				//E-mail
				$_SESSION['e_mail'] = $array_result[10];

				$query = $basedados->submitQuery( "Select count(*) From `post` WHERE 
					`registo_id_registo` = $array_result[4]" );

				$_SESSION['num_posts'] = mysql_result( $query, 0 );

			}
			
			
			
			$tempo = date( "YmdHis", mktime(date("H"), (date("i") + ($exp)), date("s")) );

			$basedados->submitQuery( "Update `registo` Set `registo_online` = $tempo 
				Where `id_registo` = " . $_SESSION['id_user'] );
			
			
			
		}

		

	}

}


/**
 * seeIfCanSpam()
 * 
 * Função encarregada de verificar se o utilizador pode reportar como spam certa 
 * menssagem, através do ID desse mensagem. 
 * 
 * @uses clearGarbadge() 
 * 
 * @global boolean Número de dias que a mensagem se encontra habilitada para ser reportada como spam
 * 
 * @param integer $num
 *   
 * @return boolean
 */
function seeIfCanSpam( $num ){

	global $atoripo;

	$num = clearGarbadge( $num, false, false);

	$flag = false;

	if ( $atoripo > 0 )
	{

		$bd = ligarBD();

		$queryano = 
		"Select DATE_FORMAT(`post_data_hora`, '%Y') From `post` Where `id_post` = $num";

		$querymes = 
		"Select DATE_FORMAT(`post_data_hora`, '%m') From `post` Where `id_post` = $num";

		$querydia = 
		"Select DATE_FORMAT(`post_data_hora`, '%d') From `post` Where `id_post` = $num";

		$queryano = $bd->submitQuery( $queryano );
		$querymes = $bd->submitQuery( $querymes );
		$querydia = $bd->submitQuery( $querydia );


		if ( mysql_num_rows($queryano) == 1 && mysql_num_rows($querymes) == 1 &&
			mysql_num_rows($querydia) == 1 && isset($_SESSION['user']) )
		{

			$queryano = mysql_result( $queryano, 0 );
			$querymes = mysql_result( $querymes, 0 );
			$querydia = mysql_result( $querydia, 0 );

			$query = date( "Ymd", mktime(0, 0, 0, $querymes, $querydia + $atoripo, $queryano) );

			if ( $query > date("Ymd") )
			{

				$flag = true;

			}

		}

	}

	return $flag;
}


/**
 * listarPagsArea()
 * 
 * Listar tópicos e mensagens por página. <br /> 
 * Para fazer o controle desta estrutura utilizam-se as variáveis $_GET['pagi']
 * e $_GET['pagf'] que representam correspondentemente o tópico inicial e final.
 *   
 * <p>As variáveis:
 * <ol>
 * 	<li>$area: A área a ser dividida</li>
 * 	<li>$topico: O tópico a ser dividido</li>
 * 	<li>$num: Corresponde a <code>$_GET['pagi']</code></li>
 * 	<li>$lim: Corresponde a <code>$_GET['pagf']</code></li>   
 * </ol>
 * </p>
 * 
 * @uses fazerStringPerso()
 * @uses fazerStringPags()
 * 
 * @param integer $area ID da área
 * @param integer $topico ID do tópico
 * @param integer $num Número de página actual
 * @param integer $lim Limite de elementos por página
 *   
 * @return String
 */
function listarPagsArea( $area, $topico, $num, $lim ){

	$bd = ligarBD();
	
	$resultado = ""; //Resultado da função
	$ini = 0; //Primeira página listada
	$cont = 0; //Número de ciclos do for

	if ( isset($topico) )
	{
		
		$query = $bd->submitQuery( "Select Count(*) From `post` Where 
				`topico_area_id_area` = $area 
				And `topico_id_topico` = $topico 
				And `post_activo` = 1" );
				
	}
	else
	{
		
		
		$query = $bd->submitQuery( "Select Count(*) From `post` Where 
				`topico_area_id_area` = $area 
				And `post_activo` = 1
				And `post_prin` = 1" );

	}
	
	$query = (ceil( mysql_result( $query, 0 )/ $lim)-1); //Número de páginas por posts
	
	
	if ( $query > 0 )
	{
		//$num Número da página em que se encontra
		//Impedir a excepção divisão por zero
		$num > 0 ? $num /= $lim : $num = 0;

		$ini = $num - 3;

		if ( $ini < 0 ) $ini = 0;

		$resultado .= "<div class=\"listpags\"><div class=\"pags\">$num de " . $query . "</div>";
		//Inicio do contentor

		for ( $i = $ini; $i <= $query; $i++ )
		{

			if ( $cont > 6 ) break;

			if ( $i != 0 && $num - 3 > 0 && $cont == 0 )
			{

				$resultado .= 
				isset($topico) 
				? fazerStringPerso( "«", $area, $topico, 0, $lim, "Ir para o início" )
				: fazerStringPerso( "«", $area, null, 0, $lim, "Ir para o início" );

			}

			if ( $i == $ini && $num != 0 )
			{
				
				$resultado .=
				isset($topico) 
				?fazerStringPerso( "&lt;", $area, $topico, (($num * $lim) - $lim),
					$lim, "Ir para a folha de posts anterior" ) 
				: fazerStringPerso( "&lt;", $area, null, (($num * $lim) - $lim), 
					$lim,  "Ir para a folha de posts anterior" );

			}

			if ( $i != $num )
			{

				$resultado .= 
				isset($topico)
				? fazerStringPags( $i, $area, $topico, $lim, true ) 
				: fazerStringPags( $i, $area, null, $lim, true );

			}
			else
			{

				$resultado .= 
				isset($topico) 
				? fazerStringPags( $i, $area, $topico, $lim, false ) 
				: fazerStringPags( $i, $area, null, $lim, false );
				
			}

			if ( (($i == $query ) || ($i == $ini + 6)) && $num != $query )
			{

				$resultado .= isset($topico) 
				? fazerStringPerso( "&gt;", $area, $topico, (($num * $lim) + $lim),
				$lim, "Avançar para a próxima folha de posts" )
				: fazerStringPerso( "&gt;", $area, null, (($num * $lim) + $lim), $lim,
				"Avançar para a próxima folha de posts" );

			}

			if ( $i != $query && $i == $ini + 4 )
			{
				$resultado .= 
				isset($topico)
				? fazerStringPerso( "»", $area, $topico, ($query * $lim), 
					$lim, "Ir para o fim" )
				: fazerStringPerso( "»", $area, null, ($query * $lim), 
					$lim, "Ir para o fim" );
					
			}

			$cont++;

		}
		
		$resultado .= "</div>";
		
	}
	
	return $resultado;

}


/**
 * fazerStringPags()
 * 
 * Função responsável poela impressão da interface de localização nas páfinas listadas
 * para o tópico ou mensagem.
 * 
 * @param integer $numpag 
 * @param integer $area 
 * @param integer $topico 
 * @param integer $limite 
 * @param boolean $act 
 *      
 * @return String
 */
function fazerStringPags( $numpag, $area, $topico, $limite, $act )
{

	return 
	 
	(! $act) 
	? " <div class=\"pags\" style=\"background-color: #FFA599;\" 
		title=\"Folha de posts actual\">$numpag</div> "
	: isset($topico) 
	? " <a href=\"?elem=8&amp;area=$area&amp;topico=$topico&amp;pagi=" . ( $limite *
			$numpag ) . "&amp;pagf=$limite\"><div class=\"pags\" 
			title=\"Folha de posts nº $numpag\">$numpag</div></a>"
	: " <a href=\"?elem=8&amp;area=$area&amp;pagi=" . ( $limite * $numpag ) .
			"&amp;pagf=$limite\" class=\"pags\" title=\"Folha de posts nº $numpag\">$numpag</a>";
			
}


/**
 * fazerStringPerso()
 * 
 * Fazer a impressão dos caractéres <code> &lt; &gt; » « </code> e númeração 
 * responsáveis pelo navegamento sem interagir directamente com as variáveis
 * que se encontra no URL.
 *  
 * @param String $char 
 * @param integer $area 
 * @param integer $topico 
 * @param integer $ini 
 * @param String $tooltip 
 *      
 * @return String
 */
function fazerStringPerso( $char, $area, $topico, $ini, $final, $tooltip ){

	return 
	isset($topico)
	? "<a href=\"?elem=8&amp;area=$area&amp;topico=$topico&amp;pagi=$ini&amp;pagf=$final\" 			   class=\"pags\" title=\"$tooltip\">$char</a>"
	: "<a href=\"?elem=8&amp;area=$area&amp;pagi=$ini&amp;pagf=$final\" 
			title=\"$tooltip\" class=\"pags\">$char</a>";
			
}


/**
 * is_valid()
 * 
 * Confirma se o resto da divisão de $num por $gen é 0. Se 
 * $gen for 0 ou output será false.
 *  
 * @param integer $num 
 * @param integer $gen 
 *  
 * @return boolean
 */
function is_valid( $num, $gen ){
	return $gen == 0 ? false : $num % $gen == 0 ? true : false;
}


/**
 * pustAsSpam()
 *  
 * Por como spam. Para o efeito os campos `post_activo` na tabela
 * `post` das mensagens correspondentes ao tópico são postos a 0.
 * Isto também é valido para uma mensagem isolada.
 * 
 * @uses existeNaBd() 
 * @uses seeIfCanSpam()
 * @uses ePostPrin() 
 * 
 * @param integer $id_mensagem  
 *   
 * @return void
 */
function pustAsSpam( $id_mensagem )
{

	if ( isset($id_mensagem) && is_numeric($id_mensagem) && $id_mensagem > 0 )
	{

		if ( existeNaBd("post", "id_post", $id_mensagem) == 1 )
		{

			if ( ePostPrin($id_mensagem) && seeIfCanSpam($id_mensagem) )
			{

				$bd = ligarBD();

				$bd->submitQuery( "
					Update `post` Set `post_activo` = 0 Where `topico_id_topico` = 
					(Select `topico_id_topico` From (Select * From `post`) As temp
					Where `id_post` = $id_mensagem)
					" );
					
			}
			else
				if ( seeIfCanSpam($id_mensagem) )
				{

					$bd = ligarBD();

					$bd->submitQuery( "
						Update `post` Set `post_activo` = 0 Where `id_post` = $id_mensagem
						" );
			
				}

		}

	}

}


/**
 * existeNaBd()
 * 
 * Retorna o número de ocorrências de um valor num campo de uma tabela.
 *  
 * @uses clearGarbadge()
 * 
 * @param String $tabela
 * @param String $campo 
 * @param String $valor 
 *    
 * @return integer
 */
function existeNaBd( $tabela, $campo, $valor ){

	//if ( isset($tabela) && isset($campo) && isset($valor) && $valor > 0 ){

		$tabela = clearGarbadge( $tabela, false, false);
		$campo = clearGarbadge( $campo, false, false);
		$valor = clearGarbadge( $valor, false, false);

		$bd = ligarBD();
		
		$query = $bd->submitQuery( "Select `$campo` From `$tabela` Where `$campo` Like '$valor'" );

	//}

	return mysql_num_rows($query);

}

/**
 * ePostPrin()
 * 
 * Ver se uma mensagem esta definida como tópico atavés do seu ID. 
 * 
 * @param integer $id_post 
 *  
 * @return boolean
 */
function ePostPrin( $id_post ){

	$flag = false;

	if ( isset($id_post) && is_numeric($id_post) && $id_post > 0 )
	{

		$bd = ligarBD();

		$query = $bd->submitQuery( "
		Select `post_prin` From `post` Where `id_post` = $id_post
		" );

		if ( mysql_num_rows($query) > 0 )
		{

			if ( mysql_result($query, 0) == true )
				$flag = true;

		}

		

	}

	return $flag;

}




/**
 * ligarBD()
 * 
 * Criar uma ligação válida a bd segundo as configurações da página. 
 *  
 * @return bd
 */
function ligarBD(){
	
	global $user_bd;
	global $pass_bd;
	global $db;
			
	$bd = new bd();

	$bd->setLigar( $host, $user_bd, $pass_bd, $db );
	
	return $bd;
	
}


/**
 * alertMsgJs()
 * 
 * Imprimir um alert em js com uma mensagem personalizável. 
 * 
 * @param String $msg
 *  
 * @return String
 */
function alertMsgJs($msg){
	
	return "
		<script type=\"text/javascript\">
		alert(
		\"".str_replace("\"","''",$msg)."\"	 
		);</script>
		";

}

/**
 * seeNomElem()
 * 
 * Obter uma descrição do elemento segundo 1 = filme, 2 = álbuns ou 3 = outros.  
 * 
 * @param integer $id ID do elemento
 * @param boolean $flag a true para uma imagem, a false para o nome. 
 *  
 * @return String
 */
function seeNomElem($id, $flag){
	
	switch($id){
		
		case 1: $id = $flag?
		"<img src=\"imagens/filme.png\" border=\"0\" title=\"Filme\" alt=\"[Filme]\" />":"Filme";
		break;
		
		case 2: 
		$id = $flag?
		"<img src=\"imagens/album.png\" border=\"0\" title=\"Álbum\" alt=\"[Álbum]\" />":"Álbum";
		break;
		
		case 3: $id = $flag?
		"<img src=\"imagens/outro.png\" border=\"0\" title=\"Outro\" alt=\"[Outro]\" />":"Outro";
		break;
		
		default: $id = $flag?
		"<img src=\"imagens/alertmod.gif\" border=\"0\" title=\"Sem correspondência\" 
		alt=\"[Sem correspondência]\" />"
		:"Sem correspondência";	
		
	}
		
	return $id;
	
}

/**
 * difDays()
 * 
 * Faz a contagem dos dias decorridos da data $data_ini até a data actual.
 * Usada para por exmplo calcular a multa de uma requesição.   
 * 
 * @param String $data_ini Data no formato DD/MM/AAAA
 * @param char $explode_by O caracter que separa os dias de meses e anos. 
 *  
 * @return integer
 */
function difDays($data_ini, $explode_by){
	
	$data_ini = explode($explode_by, $data_ini);
	$data_ini = mktime(0, 0, 0, $data_ini[1], $data_ini[2], $data_ini[0]);
	$data_actual = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
	$data_ini = floor( ( ($data_ini - $data_actual) /86400 ) * (-1) );
	//Diferença entre segundos como 24 horas têm 86400
	
	return $data_ini;
	
}

/**
 * getCampoFromRegistoWhereId()
 *
 * Obter um campo da tabela registo através do id do registo.
 * 
 * @param integer $id_user ID do user
 * @param String $campo Campo do qual se quer retirar os dados
 *  
 * @return mixed
 */
function getCampoFromRegistoWhereId( $id_user, $campo ){
	
	$bd = ligarBD();
	
	$retorno = null;
	
	$id_user = clearGarbadge($id_user, false, false);
	
	$campo = clearGarbadge($campo, false, false);
	
	$query = $bd->submitQuery("Select `$campo` From `registo` Where 
	`id_registo` = $id_user");
	
	if(mysql_numrows( $query ) == 1)
		$retorno = mysql_result($query,0,0);
	else
		$retorno = null;
	
	return $retorno;
	
}

/**
 * commonInfo()
 *
 * Fazer sondagens da base de dados.
 *
 * @return String
 */
function commonInfo(){

$bd = "";

$bd = ligarBD();
	
$buffer .= "Contamos com: <br /><br /> ";
//contagem de utilizadores
$aux = mysql_result( $bd->submitQuery("Select Count(*) From `registo`"), 0 );
if($aux == 0) $buffer .= "Nenhum utilizador...<br />";
else if($aux == 1) $buffer .= "1 utilizador...<br />";
else $buffer .= "$aux utilizadores...<br />";

$tempo = date( "YmdHis" );
//Ver quantos utilizadores estão online
$aux =  mysql_result( 
			$bd->submitQuery("Select Count(*) From `registo` Where `registo_online` > $tempo"), 0 );
		
if($aux == 0) $buffer .= "Nenhum utilizador online...<br />";
else if($aux == 1) $buffer .= "1 utilizador online...<br />";
else $buffer .= "$aux utilizadores online...<br />";


//Ver quantas áreas existem no fórum
$aux = mysql_result( $bd->submitQuery("Select Count(*) From `area`"),0 );

if($aux == 0) $buffer .= "Nenhuma área...<br />";
else if($aux == 1) $buffer .= "1 área...<br />";
else $buffer .= "$aux áreas...<br />";

//Contagem de tópicos activos
$aux = mysql_result( $bd->submitQuery("Select Count(*) From `post` Where `post_activo` = 1 And `post_prin` = 1"),
	0 );
if($aux == 0) $buffer .= "Nenhum tópico...<br />";
else if($aux == 1) $buffer .= "1 tópico...<br />";
else $buffer .= "$aux tópicos...<br />";

//Contar os posts que não tem a flag de tópico
$aux = mysql_result( $bd->submitQuery("Select Count(*) From `post` Where `post_activo` = 1 And `post_prin` = 0"),
	0, 0 );

if($aux == 0) $buffer .= "Nenhuma resposta...<br />";
else if($aux == 1) $buffer .= "1 resposta...<br />";
else $buffer .= "$aux respostas...<br />";

$aux = mysql_result( $bd->submitQuery("Select Count(*) From `filme`"),0, 0 );

if($aux == 0) $buffer .= "Nenhum filme...<br />";
else if($aux == 1) $buffer .= "1 filme...<br />";
else $buffer .= "$aux filmes...<br />";

$aux = mysql_result( $bd->submitQuery("Select Count(*) From `album`"),0, 0 );

if($aux == 0) $buffer .= "Nenhum álbum...<br />";
else if($aux == 1) $buffer .= "1 álbum...<br />";
else $buffer .= "$aux álbuns...<br />";

$aux = mysql_result( $bd->submitQuery("Select Count(*) From `outro`"),0, 0 );

if($aux == 0) $buffer .= "Nenhum item inclassificável...<br />";
else if($aux == 1) $buffer .= "1 item inclassificável...<br />";
else $buffer .= "$aux itens inclassificáveis...<br />";

$buffer .= "<br />...no síte da biblioteca.";

return $buffer;

}



/**
 * Obter o o mês escrito do algarimo correspondente.
 * 
 * @param $mes byte Número do mês
 *  
 * @global Email do admin
 *
 * @return String
 */
function nameMes($mes){
	
	switch($mes){
		
		case 1: $mes = "Janeiro";
				break;
		case 2: $mes = "Fevereiro";
				break;
		case 3: $mes = "Março";
				break;
		case 4: $mes = "Abril";
				break;
		case 5: $mes = "Maio";
				break;
		case 6: $mes = "Junho";
				break;
		case 7: $mes = "Julho";
				break;
		case 8: $mes = "Agosto";
				break;
		case 9: $mes = "Setembro";
				break;
		case 10: $mes = "Outubro";
				break;
		case 11: $mes = "Novembro";
				break;
		case 12: $mes = "Dezembro";
				break;
	}
	
	return "$mes";
}


/**
 * mensagensInfomacao()
 *
 * Fazer a impressão das mensagens de informação
 * 
 * @param array $msg As mensagens de informação
 *
 * @return String
 */
function mensagensInfomacao($msg){

	
	if($msg[0]){
		
		for($i = 1;$i < count($msg); $i++){
		
			echo $msg[$i]."<br />";
		
		}

		
	}

}



/**
 * gerarKeySessao()
 *
 * Criar uma chave única para validar a sessão de um utilizador
 * 
 * @global boolean $utils_simul
 *
 * @param array $id_util
 *
 * @return mixed Retorna a chave que foi atribuída ao utilizador se tudo tiver corrido como o previsto ou 0 se algo tiver corrido mal.
 */
function gerarKeySessao( $id_util ){
	
	global $utils_simul;
	
	$chave = 0;
	
	if( is_numeric($id_util) && $id_util >0 ){
		
		$key = sha1( rand(0,9999)."|".date("Ymdsu")."|".rand(0,9999) );
		
		$bd = ligarBD();
		
		if(	existeNaBd( "registo","id_registo",$id_util ) ){
		
			$query_estatuto = $bd->submitQuery("Select `estatuto_id_estatuto` From `registo` Where `id_registo` = $id_util");
			
			if(mysql_numrows($query_estatuto) == 1){
				
				$id_estatuto = mysql_result($query_estatuto,0,0);
				
				$query_key = $bd->submitQuery("Select `id_session_control` From `session_control` Where `registo_id_registo` = $id_util ");
				
				if(mysql_num_rows($query_key) == 0){
				
					$bd->submitQuery("Insert Into `session_control` (`id_session_control`,`registo_id_registo`,`registo_estatuto_id_estatuto`) Values('$key', $id_util, ".mysql_result($query_estatuto,0,0).")");
					
					$chave = $key;
					
				} else if(mysql_num_rows($query_key) == 1 && $utils_simul){
				
					$chave = mysql_result( $query_key,0,0 );
				
				}
				
			}
			
		}
	
	}
	return $chave;
	
}


/**
 * validarKey()
 *
 * Validar a key do utilizador.
 * 
 * @param array $id_util
 * @param array $key
 * 
 * @return mixed Retorna verdadeiro se a chave existir e falso se não existir.
 */
function validarKey( $id_util, $id_estat , $key ){
	
	$chave = false;
	
	if( is_numeric($id_util) && $id_util > 0 && is_numeric($id_estat) && $id_estat > 0 ){
		
		$key = clearGarbadge( $key, false, false );
		
		$bd = ligarBD();
		
		if(	existeNaBd( "registo", "id_registo", $id_util ) ){
			
			if(	existeNaBd( "estatuto", "id_estatuto", $id_estat ) ){
			
				if( mysql_result($bd->submitQuery("Select Count(*) From `session_control` Where `registo_id_registo` = $id_util And `registo_estatuto_id_estatuto` = $id_estat And `id_session_control` = '$key'"),0,0) == 1 ){
				
					if(mysql_result($bd->submitQuery("Select Count(*) From `session_control` Where `registo_id_registo` = $id_util "),0,0) == 1){
					
						$query = $bd->submitQuery("Select * From `estatuto` Where `id_estatuto` = $id_estat ");
						
						if(
						($_SESSION['estat_nome'] == mysql_result( $query, 0, 'estatuto_nome' )) &&
						($_SESSION['estat_carac'][0] == mysql_result( $query, 0, 'estatuto_gerir_topi' )) &&
						($_SESSION['estat_carac'][1] == mysql_result( $query, 0, 'estatuto_gerir_post' )) &&
						($_SESSION['estat_carac'][2] == mysql_result( $query, 0, 'estatuto_gerir_area' )) &&
						($_SESSION['estat_carac'][3] == mysql_result( $query, 0, 'estatuto_req_filme' )) &&
						($_SESSION['estat_carac'][4] == mysql_result( $query, 0, 'estatuto_req_album' )) &&
						($_SESSION['estat_carac'][5] == mysql_result( $query, 0, 'estatuto_req_outro' )) &&
						($_SESSION['estat_carac'][6] == mysql_result( $query, 0, 'estatuto_gerir_filme' )) &&
						($_SESSION['estat_carac'][7] == mysql_result( $query, 0, 'estatuto_gerir_album' )) &&
						($_SESSION['estat_carac'][8] == mysql_result( $query, 0, 'estatuto_gerir_outro' )) &&
						($_SESSION['estat_carac'][9] == mysql_result( $query, 0, 'estatuto_gerir_faq' )) &&
						($_SESSION['estat_carac'][10] == mysql_result( $query, 0, 'estatuto_gerir_registo' )) &&
						($_SESSION['estat_carac'][11] == mysql_result( $query, 0, 'estatuto_gerir_estatuto' )) &&
						($_SESSION['estat_carac'][12] == mysql_result( $query, 0, 'estatuto_gerir_frases' ))
						){
						
							$chave = true;
						
						}
						
					
					}
				
				}
			
			}
			
		}
	
	}
	return $chave;
	
}
?>