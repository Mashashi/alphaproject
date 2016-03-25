<?
/**
 * Ver a ficha técnica de um filme.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

 /**
  * Se estiver definido no index.php (isto vê-se verificando se a constante IN_PHPAP se
  * encontra definida) e o utilizador tiver uma sessão inicia.
  *
  */
 if( defined('IN_PHPAP') ){
 
 /**
  * Incluir as funções nativas dos filmes.
  */
include_once("filmes/filme_funcoes.php");
 
 validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
 
 if( isset($_SESSION['user']) ){
 
	echo "
	<div class=\"local\"><a href=\"?elem=2\">".$_SESSION['estat_nome']."</a> 
	» ";
	echo isset($_GET['filme'])?"Visualizar características do filme":"Pesquisar filme por nome";
	
	echo "</div><div class=\"gestaopremain\">";
	
	
	
	if( isset( $_GET['filme'] ) && !empty( $_GET['filme'] ) && is_numeric( $_GET['filme'] )){
	
		$id = clearGarbadge( $_GET['filme'], false, false);
	
		$query_fil = $bd->submitQuery("Select * From `filme` Where `geral_id_geral` = $id");
	
		if( mysql_num_rows( $query_fil ) > 0){
			
			$filme_carac = "";
			
			if( strlen( mysql_result($query_fil,0,3) ) > 0 )
				$filme_carac = "Etiqueta: ".mysql_result($query_fil,0,3);
			
			$des = mysql_result($query_fil,0,5);
			
			if( !empty( $des ) )
			
			$filme_carac .= "\nDescrição: ".$des;
			
			if(mysql_result($query_fil,0,7) > 0 )
				$filme_carac .= "\nAno: ".mysql_result($query_fil,0,7);
			
			if(mysql_result($query_fil,0,8) > 0){
			
				$filme_carac .= "\nDuração: ".mysql_result($query_fil,0,8)."min";
			
			}
			
			if( mysql_result($query_fil,0,9) != 0)
			$filme_carac .= "\nClassificação IMDB: ".mysql_result($query_fil,0,9);
			
			$val = classiItem( mysql_result($query_fil,0,11), mysql_result($query_fil,0,12) );	
			
			//$filme_carac .= "\nClassificação alphaproject: ".$val;
			
			$filme_carac .= "\nRequesitável: "
			.(mysql_result($query_fil,0,10)?"Sim":"Não");
			
			$help = $bd->submitQuery( 
			"Select `genero_filme_nome` From `genero_filme` Where `id_genero_filme` = "
			.mysql_result($query_fil,0,1 ) );
			
			$genero = $help;
			
			if( mysql_num_rows($help) == 1 )
			
				$filme_carac .= "\nGénero: ".mysql_result($help,0);
			
			
			
			
			$help = $bd->submitQuery( 
				"Select `realizador_filme_nome` From `realizador_filme` Where 
				`filme_geral_id_geral` = "
				.mysql_result($query_fil,0,0 ) );
			
			if( mysql_num_rows($help) > 1 ) $filme_carac .= "\nRealizadores: ";	
			else if( mysql_num_rows($help) == 1 ) $filme_carac .= "\nRealizador: ";
				
			while( $row = mysql_fetch_array($help) ){
			
				$filme_carac .= $row[0].", ";
			
			}
			
			if( mysql_num_rows($help) > 0 )
				$filme_carac = substr($filme_carac, 0, strlen($filme_carac)-2);
			
			$help = $bd->submitQuery( 
				"Select `tipo_som_filme_nome` From `tipo_som_filme` Where 
				`id_tipo_som_filme` = ".mysql_result($query_fil,0,2 ) );
				
			if( mysql_numrows( $help ) == 1 )	
				$filme_carac .= "\nTipo de som: ".mysql_result($help,0);
			
			
			$help = $bd->submitQuery("Select `suport_filme_id_suport_filme`
			,`copi_filme_totais` From `copi_filme` Where 
			`filme_geral_id_geral` = $id");
			
			if( mysql_numrows($help) > 1 )
				$filme_carac .= "\nSuportes disponíveis: ";//.mysql_result($help,0);
			else if( mysql_numrows($help) == 1)
				$filme_carac .= "\nSuporte disponível: ";//.mysql_result($help,0);
			
			
			$copi_dis_reque = 0;
			
			//Vai estar a true quando há um suporte com 1 ou mais cópias disponíveis.
			$flag_req = false;
			
			for($i = 0; $i < mysql_numrows($help) ;$i++){			
				
				$id_sup_alb = mysql_result( $help, $i, 0 );
				
				$copi_tot = mysql_result( $help, $i, 1 );
				
				
				$help2 = $bd->submitQuery( 
				"Select `suport_filme_nome`
				From `suport_filme` Where 
				`id_suport_filme` = $id_sup_alb" );
				
				$char_filme = getCharNumFilme( $id, $id_sup_alb );
				
				mysql_numrows( $help2 ) == 0 ? $id_sup = 0:
				$id_sup = mysql_result( $help2, 0 );
				
				$copi_dis_reque = getFilmesDisSup($id, $id_sup_alb );
				
				if($copi_dis_reque <= 0 ) 
					$copi_dis_reque = 0;
				else
					$flag_req = true;
					
				if( mysql_numrows( $help2 ) == 0) 
					$help2 = "|Suporte não definido|"; 
				else 
					$help2 = mysql_result( $help2, 0 );
				
				$filme_carac .= "\n $help2, cópias $copi_tot, cópias disponíveis"." "
				."$copi_dis_reque, elementos $char_filme";
				
			}
			
			$filme_carac = rawurlencode( utf8_encode($filme_carac) );
			
			$filme_sinopse = mysql_result($query_fil, 0, 6);
			
			$filme_nome = rawurlencode( utf8_encode( mysql_result($query_fil,0,4)  ) );
			
					
			if($_SESSION['estat_carac'][6]) echo "
			<a href=\"".$_SERVER['PHP_SELF']."?elem=2&amp;accao=15&amp;filme=$id\">
			<img src=\"imagens/editar.png\" alt=\"[Editar]\" title=\"Editar filme\"
			border=\"0\" />
			</a>
			<br />
			<a href=\"?modpsqreq=1&amp;psqreqtext=".mysql_result($query_fil,0,3)
			."&amp;elem=2&amp;accao=4&amp;opcao=3\">
			Ver todas as requesições deste filme</a>		
			";
		
			echo " <object width=\"587\" height=\"310\">
					<embed 
					src=\"flash/type_2.swf?descricao=$filme_carac&amp;titulo=$filme_nome\" 
					type=\"application/x-shockwave-flash\" wmode=\"transparent\"
					width=\"587\" height=\"310\"></embed>
					</object>
					<div style=\"text-align:left;float:left;width:587px;\">
					".nl2br($filme_sinopse)."<br />";
					
					
					
					
					
					
					
					
					
					
					
					
					$queryc = $bd->submitQuery("Select Count(*) 
					From `controlo_votacao` Where `geral_id_geral` = 
					$id And 
					`registo_id_registo` = ".$_SESSION['id_user']);
					
					$queryctot = $bd->submitQuery("Select Count(*) 
					From `controlo_votacao` Where `geral_id_geral` = 
					$id");
					
					
					
					
					
					
					
					
					
					
					
					
					
					/*Início da votação*/
					echo "<br />
					
					<div class=\"filmopconta\" style=\"height:400px;\">
					
					<div class=\"votestars\">";	
					
					if ( mysql_result($queryctot, 0, 0) == 0 )
						$querycc = "S/Classificação";	
					 else if ( mysql_result($queryctot, 0, 0) == 1 )
						$querycc = "Classificado 1 vez";
					 else 
						$querycc = "Classificado ".mysql_result($queryctot, 0, 0)." vezes";
					
					
					if( mysql_result($queryc,0) < 1 ){
					
					echo "<div>$querycc</div>";
					
					for($i = 0; $i < $val;$i++){
							
						echo "<img src=\"imagens/star.png\" class=\"votestar\" />";
							
					} 
						
					for($i = 0; $i < 5-$val; $i++){
							
						echo "<img src=\"imagens/star2.png\" class=\"votestar\" />";
							
					}
					
					echo "<input type=\"hidden\" id=\"film_id_fil\" value=\"$id\" />
					
					<input type=\"hidden\" id=\"film_classi_fil\" value=\"$val\" />
					
					<b id=\"text_pos_vot\"><br /><br /></b>";
						
					} else {
						
						echo "<div>$querycc</div>";
						
						for($i = 0; $i < $val;$i++){
							
							echo "<img src=\"imagens/star.png\" class=\"votestar\" />";
							
						} 
						
						for($i = 0; $i < 5-$val; $i++){
							
							echo "<img src=\"imagens/star2.png\" class=\"votestar\" />";
							
						}
						
						echo "<br /><b id=\"text_pos_vot\">Obrigado pelo voto!</b>
						
						<input type=\"hidden\" id=\"film_id_fil\" value=\"\" />
						
						<input type=\"hidden\" id=\"film_classi_fil\" value=\"$val\" />";
							
					} 
					
					echo "</div>";
					/*Final da votação*/
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					/*Início da requesição*/
					//Vamos-nos certificar que o utilizador já não fez uma requesição
					if( mysql_result($bd->submitQuery(
					"Select Count(*) From `requesicao` 
					Where `geral_id_geral` = $id 
					And `registo_id_registo` = ".$_SESSION['id_user'])
					,0,0) < 1 ){
					
						/*$flag = false;
						if(count( $copi_dis_reque) > 0 )
							$helper = array_values($copi_dis_reque);
						for($i = 0; $i < count($helper);$i++){
							if( $helper[$i] > 0 ){
								$flag = true; break;
							}
						}*/
					
					if( mysql_numrows($help) > 0 && 
					mysql_result($query_fil,0,10) && $flag_req && $_SESSION['estat_carac'][3]){
						
						echo "<div style=\"padding:3px;width:124px;float:right;\" 
							class=\"votestars\" id=\"requesicaoform\">
						
							Data de levantamento:
							
							<input class=\"forms\" size=\"5\" type=\"text\" 
							name=\"dat_min_lev_req\" id=\"dat_min_lev_req\" /> "
							.date("Y")."<br />
							".printListSuports("sup_fil", $id, false, true)."
							<ul>
							<li>Podes requesitar no máximo até.
							".
							date("d/m/Y",
							mktime(0,0,0,date("m"),date("d")+FILME_REQ,date("y")))
							."</li>
							<li>E terá de devolve-lo num prazo de ".FILME_REQ_DEV
							." dias depois de levantado.</li>
							</ul>
							<hr />
						
							<input type=\"hidden\" value=\"$id\" 
							name=\"id_film_h\" id=\"id_film_h\" />
					
							<input class=\"forms\" type=\"button\" value=\"Requesitar\" 
							name=\"req_button\" id=\"req_button\" />
							<b id=\"statusreq\"></b>
							</div>";
							
						} else {
						
							echo  "<div style=\"padding:3px;width:124px;float:right;\" 
							class=\"votestars\" id=\"requesicaoform\">
							De momento as requesições para este filme encontram-se 
							desactivadas.
							</div>";
						
						}
						
					} else {
						
						echo "<div style=\"padding:3px;width:124px;float:right;\" 
							class=\"votestars\" id=\"requesicaoform\">";
							
						$query = $bd->submitQuery("Select 
						`requesicao_dat_min`,`requesicao_dia_levantado`,
						`requesicao_id_suporte` From `requesicao` 
						Where `registo_id_registo` = '".$_SESSION['id_user']."'
						And `geral_id_geral` = '".$id."'");
						
						$req_dev = explode("-", mysql_result($query, 0, 1) ); 
						
						$req_max = explode("-", mysql_result($query, 0, 0) );
						
						//Prazo máximo para de volver o filme
						$req_max_dev = "00000000";
						if(join("",$req_dev) != "00000000"){
						
							$req_max_dev = date("Ymd", mktime(0, 0, 0, $req_dev[1], 
							$req_dev[2]+FILME_REQ_DEV, $req_dev[0] ) );
						
						}
						
						//Prazo máximo para levantar o filme
						$req_max_lev = 
						date("Ymd", mktime(0, 0, 0, $req_max[1], 
						$req_max[2]+FILME_REQ, $req_max[0] ) );
						
						/*if( !$flag_req ){
							echo "A sua requesição foi concelada porque o item 
							pretendido não se encontra disponível";
						}else*/
						
						if( $req_max_lev < date("Ymd") && join("",$req_dev) == "00000000" ){
							
							echo "A sua requesição expirou x|";
							
						} else if( $req_max_dev < date("Ymd") 
						&& $req_max_dev != "00000000" ){
							
							$req_max_dev = 
							date("Y-m-d", mktime(0, 0, 0, $req_dev[1], 
							$req_dev[2]+FILME_REQ_DEV, $req_dev[0] ) );
							
							echo "<p>O prazo para a entrega do filme 
							\"<b>".date("d/m/Y", mktime(0, 0, 0, $req_dev[1], 
							$req_dev[2]+FILME_REQ_DEV, $req_dev[0] ) )
							."</b>\" expirou tem a pagar: <font color=\"brown\">"
							.( (difDays($req_max_dev, "-") * MULTA) )." €</font>.</p>";
							
						} else if( join("",$req_dev) == "00000000") {
							
							$sup = mysql_result($query, 0, 2);
							
							$sup = $bd->submitQuery("
							Select `suport_filme_nome` From `suport_filme`
							Where `id_suport_filme` = $sup
							");
							
							if( mysql_numrows($sup) > 0 ){
								
								$sup = mysql_result($sup, 0, 0);
								$sup = "<b>".$sup."</b>";
							
								echo "
								<p>Tem até <br />\"<b>".
								date("d/m/Y", mktime(0, 0, 0, $req_max[1], 
								$req_max[2]+FILME_REQ, $req_max[0] ) )
								.
								"</b>\" para levantar o filme que requesitou em:<br /> $sup.</p>";
							
							} else {
								
								echo 
								"A sua requesição foi cancelada porque o suporte pretendido 
								não se encontra disponível.";
								
							}
							
							
							
						} else {
							
							echo "<p>Tem até <br />\"<b>".
							date("d/m/Y", mktime(0, 0, 0, $req_dev[1], 
							$req_dev[2]+FILME_REQ_DEV, $req_dev[0] ) )
							."</b>\" para entregar o filme.<p/>";
							
						}
						
						echo "</div>";
							
						mysql_freeresult( $query );
						
					}
					/*Final da requesição*/
					
					
					
					if( mysql_numrows($genero) == 1 ){
						
						$query_related = giveIdRelated ( $id, array() );
						
						if( mysql_numrows( $query_related ) >0  ){
						
						echo "<div><b>Outros filmes de ".mysql_result( $genero, 0 )."</b><br /><ol>";
						
						
						
						for($i = 0; $i < mysql_numrows( $query_related ); $i++ ){
						
							echo "<li><em><a href=\"?elem=2&amp;accao=5&amp;filme=".mysql_result($query_related,$i,0)."\">".mysql_result( $query_related, $i, 4 )."</a></em></li>";
						
						}
					
						echo "</ol></div>";
						
						}
						
					}
					
					echo "</div>";
					
					echo "</div>";
					
					mysql_freeresult( $query_fil );
					
					
					
		} else {
			
			echo "Lamentamos mas o filme pretendido não se encontra disponível.";
			
		}	
	
	} else {
		
		/**
		 * Aqui são feitas as pesquisas dos filmes.
		 *
		 */
		
		
		echo "
		<!--Pesquisar filme<hr />-->
		<div style=\"margin: 15px;\">
		<form method=\"get\" action=\"?elem=2&amp;accao=5\">
			<label for=\"psqfilme\">
			<input class=\"forms\" type=\"text\" name=\"psqfilme\" maxlenght=\"100\" />
			</label>
			<input class=\"forms\" type=\"submit\" value=\"Go\" />
			
			<input class=\"forms\" type=\"hidden\" name=\"accao\" value=\"5\" />
			<input class=\"forms\" type=\"hidden\" name=\"elem\" value=\"2\" />
		</form>
		</div>
		";
	
	
		//Trecho de código que lida com os parâmetros de pesquisa enviados
		if( isset( $_GET['psqfilme'] ) ){
			
			$pagi = clearGarbadge( $_GET['pagi'], false, false);

			$pagf = clearGarbadge( $_GET['pagf'], false, false);

			if ( ! is_numeric($pagi) || ! is_numeric($pagf) || $pagi < 0 || $pagf < 0 )
			{

				$pagi = 0;
		
				$pagf = 5;
		
			}
			
			$adi = " Limit $pagi,$pagf";
			
			include_once("filmes/filme_funcoes.php");
		
			include_once("pesquisas/pesq_rap.php");
		
			$psqfilme = new pesq_rap(  "Filme", $host , $user_bd , $pass_bd, $db);
		
			$results = 
			$psqfilme->psqFilme( clearGarbadge( $_GET['psqfilme'], false, false), $adi );
			
			$cont = 
			$psqfilme->psqFilme( clearGarbadge( $_GET['psqfilme'], false, false), "" );
			
			echo "<table border=\"0\" style=\"text-align:left;width: 585px;\">
			<tr><td>
			<div class=\"area\" style=\"text-align:center;width: auto;\">
			";
			
			
			//Imprimir os resultados
			if(count($cont)==0) {
				
				echo "Nenhum resultado";
				
			} else if ((count($cont)/2)==1) {
				
				echo "1 resultado";
				
			} else {
				
				echo (count($cont)/2)." resultados";
				
			}
			
			echo "</div></td></tr>";
			
			$i = 0;
			
			while ( current($results) ){
				
				if($i%2==0) $fla = "E0E0DF";
				else $fla = "FFFFFF";
					
				echo "<tr style=\"background-color: #$fla;\"><td>
				<a href=\"?elem=2&amp;accao=5&amp;filme="
				.current($results)."\">".next($results)."</a>
				</td></tr>";
				
				$i++;
				
				next($results);
				
			}
			
			echo "</table>";
			
			
			//Divisão do spam por páginas
			
			
			//Número de páginas totais
			$query_count_spam = floor( (mysql_numrows(
			$bd->submitQuery( 
			preg_replace("/Limit [0-9][0-9]*,[0-9][0-9]*$/","",$psqfilme->getLastQuery()) )
			)) / 6 );
			
	if($query_count_spam){
	
			echo "<div class=\"listpags\" style=\"float: left;\">";
			
			//Página actual
			$pag_actual = floor ( $pagi / $pagf );

		if ( $pag_actual > 0 )
			echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a href=\"?elem=2&amp;accao=5&amp;pagi=" . ( $pagi - 5 ) . "&amp;pagf=5&amp;psqfilme=".$_GET['psqfilme']."\" title=\"Recuar para página anterior\">&lt;</a></div>";

		echo "<div class=\"pags\" style=\"margin-left: 0px;\">$pag_actual de $query_count_spam</div>";

		if ( $query_count_spam > 0 && $pag_actual < $query_count_spam )
			echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a href=\"?elem=2&amp;accao=5&amp;pagi=" . ( $pagi +  5 ) . "&amp;pagf=5&amp;psqfilme=".$_GET['psqfilme']."\" title=\"Avançar para a próxima página\">&gt;</a></div>";

		echo "</div>";
		
	}
			
		}
	
	}
	
	echo "<!--<p style=\"float: left;\"><a href =\"".$_SERVER["PHP_SELF"]
	."?elem=2&amp;accao=4\">Regressar</a></p>-->
	</div>";
		
 }
 
}
 
?>