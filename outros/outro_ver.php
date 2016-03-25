<?
/**
 * Ficha técnica do item tipo outro.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

 
 if( defined('IN_PHPAP') && $_SESSION['user'] ){
	
	echo "
	<div class=\"local\"><a href=\"?elem=2\">".$_SESSION['estat_nome']."</a> 
	» ";
	echo isset($_GET['outro'])?"Visualizar características do outro item":"Pesquisar outro item por nome";
	
	echo "</div><div class=\"gestaopremain\">";
	
	
	
	if( isset( $_GET['outro'] ) && !empty( $_GET['outro'] ) && is_numeric( $_GET['outro'] ) ){
		/**
		 * Incluir as funções nativas dos outros itens.
		 *
		 */
		include_once("outros/outro_funcoes.php");
	
		$id = clearGarbadge( $_GET['outro'], false, false);
		
		$query_outr = $bd->submitQuery("Select * From `outro` Where `geral_id_geral` = $id");
	
		if( mysql_num_rows( $query_outr ) > 0){
			
			$outro_carac = "";
			
			if( strlen( mysql_result($query_outr,0,6) ) > 0 )
				$outro_carac = "Etiqueta: ".mysql_result($query_outr,0,6);
			
			
			if(mysql_result($query_outr, 0, 4) > 0)
				$outro_carac .= "\nAno: ".mysql_result($query_outr, 0, 4);
			
			$val = classiItem( mysql_result($query_outr,0,7), mysql_result($query_outr,0,8) );	
			
			$outro_carac .= "\nRequesitável: ".(mysql_result($query_outr,0,5)?"Sim":"Não");
			
			
			
			$help = $bd->submitQuery("Select `direito_outro_outro_nome` From `direito_outro` 
			Where `id_direito_outro` = ".mysql_result($query_outr,0,1));
			
			if(mysql_numrows($help) == 1)
				$outro_carac .= "\nDireito: ".mysql_result($help,0,0);
			
			$help = $bd->submitQuery("Select `suport_outro_id_suport_outro`
			,`copi_outro_totais` From `copi_outro` Where 
			`outro_geral_id_geral` = $id");
			
			if( mysql_numrows($help) > 1 )
				$outro_carac .= "\nSuportes disponíveis: ";//.mysql_result($help,0);
			else if( mysql_numrows($help) == 1)
				$outro_carac .= "\nSuporte disponível: ";//.mysql_result($help,0);
			
			$flag_req = true;
			
			for($i = 0; $i < mysql_numrows($help) ;$i++){			
				
				$id_sup_outr = mysql_result( $help, $i, 0 );
				
				$copi_tot = mysql_result( $help, $i, 1 );
				
				$help2 = $bd->submitQuery( 
				"Select `suport_outro_nome`
				From `suport_outro` Where 
				`id_suport_outro` = $id_sup_outr");
				
				
				$char_outro = getCharNumOutro( $id, $id_sup_outr );
				
				
				mysql_numrows( $help2 ) == 0 ? $id_sup = 0:
				$id_sup = mysql_result( $help2, 0 );
				
				
				$copi_dis_reque = 
				getOutrosDisSup($id, $id_sup_outr );
				
				if($copi_dis_reque < 0 ) 
					$copi_dis_reque = 0;
				else
					$flag_req = true;
					
				if( mysql_numrows( $help2 ) == 0) 
					$help2 = "|Suporte não definido|"; 
				else 
					$help2 = mysql_result( $help2, 0 );
				
				$outro_carac .= "\n $help2, cópias $copi_tot, cópias disponíveis "." "
				."$copi_dis_reque, elementos $char_outro";
				
			}
			
			
			
			$outro_sinopse = mysql_result($query_outr, 0, 3);
			
			$outro_nome = rawurlencode( utf8_encode( mysql_result($query_outr,0,2) ) );
			
			$outro_carac = rawurlencode( utf8_encode($outro_carac) );
					
			if($_SESSION['estat_carac'][6]) echo "
			<a href=\"".$_SERVER['PHP_SELF']."?elem=2&amp;accao=17&amp;outro=$id\">
			<img src=\"imagens/editar.png\" alt=\"[Editar]\" title=\"Editar álbum\"
			border=\"0\" />
			</a>
			<br />
			<a href=\"?modpsqreq=1&amp;psqreqtext=".mysql_result($query_outr, 0, 8)
			."&elem=2&amp;accao=8&amp;opcao=3\">
			Ver todas as requesições deste item</a>		
			";
		
			echo " <object width=\"587\" height=\"200\">
					<embed 
					src=\"flash/type_2.swf?descricao=$outro_carac&amp;titulo=$outro_nome\" 
					type=\"application/x-shockwave-flash\" wmode=\"transparent\"
					width=\"587\" height=\"310\"></embed>
					</object>
					<div style=\"text-align:left;float:left;width:587px;\">
					".nl2br($outro_sinopse)."<br />";
					
					
					
					//listarTrilhasAlbum( $id, $_SESSION['estat_carac'][7] );
					
					
					echo "<div id=\"debugArea\"></div>";
					
					
					
					$queryc = $bd->submitQuery("Select Count(*) 
					From `controlo_votacao` Where `geral_id_geral` = 
					$id And 
					`registo_id_registo` = ".$_SESSION['id_user']);
					
					$queryctot = $bd->submitQuery("Select Count(*) 
					From `controlo_votacao` Where `geral_id_geral` = 
					$id");
					
					
					
					
					
					/*Início da votação*/
					echo "<br />
					
					<input type=\"hidden\" id=\"album_id_ord\" value=\"$id\" />
					
					<div class=\"filmopconta\"
					style=\"background-image:URL('imagens/outro.jpg');height:300px;\">
					
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
							
						echo "<img src=\"imagens/star.png\" class=\"votestaroutr\" />";
							
					} 
						
					for($i = 0; $i < 5-$val; $i++){
							
						echo "<img src=\"imagens/star2.png\" class=\"votestaroutr\" />";
							
					}
					
					echo "<br /><b id=\"text_pos_vot\"><br /></b>
						
					<input type=\"hidden\" id=\"outro_classi_out\" value=\"$val\" />
						
					<input type=\"hidden\" id=\"outro_id_out\" value=\"$id\" />";
					
						
					} else {
						
						echo "<div>$querycc</div>";
						
						for($i = 0; $i < $val;$i++){
							
							echo "<img src=\"imagens/star.png\" class=\"votestaroutr\" />";
							
						} 
						
						for($i = 0; $i < 5-$val; $i++){
							
							echo "<img src=\"imagens/star2.png\" class=\"votestaroutr\" />";
							
						}
						echo "<input type=\"hidden\" id=\"album_id_alb\" value=\"\" />
					
						<input type=\"hidden\" id=\"album_classi_alb\" value=\"$val\" />
					
						<b id=\"text_pos_vot\">Obrigado pelo voto!</b>";
					
						
							
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
					
					
					if( mysql_numrows($help) > 0 && mysql_result($query_outr,0,5) && $flag_req
					&& $_SESSION['estat_carac'][5]){
						
						echo "<div style=\"padding:3px;width:124px;float:right;\" 
							class=\"votestars\" id=\"requesicaoform\">
						
							Data de levantamento:
							
							<input class=\"forms\" size=\"5\" type=\"text\" 
							name=\"dat_min_lev_req\" id=\"dat_min_lev_req\" /> "
							.date("Y")."<br />
							".listarSupOutro("sup_outr", $id, false, true)."
							<ul>
							<li>Podes requesitar no máximo até.
							".
							date("d/m/Y",
							mktime(0,0,0,date("m"),date("d")+OUTRO_REQ,date("y")))
							."</li>
							<li>E terá de devolve-lo num prazo de ".OUTRO_REQ_DEV
							." dias depois de levantado.</li>
							</ul>
							<hr />
						
							<input class=\"forms\" type=\"button\" value=\"Requesitar\" 
							name=\"req_button_outr\" id=\"req_button_outr\" />
					
							<b id=\"statusreq\"></b></div>";
							
						} else {
						
							echo  "<div style=\"padding:3px;width:124px;float:right;\" 
							class=\"votestars\" id=\"requesicaoform\">
							De momento as requesições para este item encontram-se 
							desactivadas.
							</div>";
						
						}
						
					} else {
						
						echo "<div style=\"padding:3px;width:124px;float:right;\" 
							class=\"votestars\" id=\"requesicaoform\">";
							
						$query = $bd->submitQuery("Select 
						`requesicao_dat_min`,`requesicao_dia_levantado`,
						`requesicao_id_suporte` From `requesicao` 
						Where `registo_id_registo` = ".$_SESSION['id_user']."
						And `geral_id_geral` = $id");
						
						$req_dev = explode("-", mysql_result($query, 0, 1) ); 
						
						$req_max = explode("-", mysql_result($query, 0, 0) );
						
						//Prazo máximo para de volver o filme
						$req_max_dev = "00000000";
						
						if(join("",$req_dev) != "00000000"){
						
							$req_max_dev = date("Ymd", mktime(0, 0, 0, $req_dev[1], 
							$req_dev[2]+OUTRO_REQ_DEV, $req_dev[0] ) );
						
						}
						
						//Prazo máximo para levantar o filme
						$req_max_lev = 
						date("Ymd", mktime(0, 0, 0, $req_max[1], 
						$req_max[2]+OUTRO_REQ, $req_max[0] ) );
						
						/*if(!$flag_req){
						
							echo "A sua requesição foi concelada porque o item 
							pretendido não se encontra disponível";
						
						}else*/
						if( $req_max_lev < date("Ymd") && join("",$req_dev) == "00000000" ){
							
							echo "A sua requesição expirou x|";
							
						} else if( $req_max_dev < date("Ymd") 
						&& $req_max_dev != "00000000" ){
							
							$req_max_dev = 
							date("Y-m-d", mktime(0, 0, 0, $req_dev[1], 
							$req_dev[2]+OUTRO_REQ_DEV, $req_dev[0] ) );
							
							echo "<p>O prazo para a entrega do item outro 
							\"<b>".date("d/m/Y", mktime(0, 0, 0, $req_dev[1], 
							$req_dev[2]+OUTRO_REQ_DEV, $req_dev[0] ) )
							."</b>\" expirou tem a pagar: <font color=\"brown\">"
							.( (difDays($req_max_dev, "-") * MULTA) )." €</font>.</p>";
							
						} else if( join("",$req_dev) == "00000000") {
							
							$sup = mysql_result($query, 0, 2);
							
							$sup = $bd->submitQuery("
							Select `suport_outro_nome` From `suport_outro`
							Where `id_suport_outro` = '$sup'
							");
							
							if(mysql_numrows($sup) > 0){
								
								$sup = mysql_result($sup, 0, 0);
								$sup = "<b>$sup</b>";
							
								echo "
								<p>Tem até <br />\"<b>".
								date("d/m/Y", mktime(0, 0, 0, $req_max[1], 
								$req_max[2]+OUTRO_REQ, $req_max[0] ) )
								.
								"
								</b>\" para levantar o item que requesitou em:<br /> $sup.</p>";
							
							} else {
								
								echo 
								"A sua requesição foi cancelada porque o suporte pretendido 
								não se encontra disponível.";
								
							}
							
							
							
						} else {
							
							echo "<p>Tem até <br />\"<b>".
							date("d/m/Y", mktime(0, 0, 0, $req_dev[1], 
							$req_dev[2]+OUTRO_REQ_DEV, $req_dev[0] ) )
							."</b>\" para entregar o item outro.<p/>";
							
						}
						
						echo "</div>";
							
						mysql_freeresult( $query );
						
					}
					/*Final da requesição*/
					
					
					
					echo "</div>";
					
					
					echo "</div>";
					
					mysql_freeresult( $query_outr );
					
					
					
		} else {
			
			echo "Lamentamos mas o item pretendido não se encontra disponível.";
			
		}	
	
	} else {
	
		echo "<div style=\"margin: 15px;\">
		<form method=\"get\" action=\"?elem=2&amp;accao=9\">
			<label for=\"psqoutro\">
			<input class=\"forms\" type=\"text\" name=\"psqoutro\" maxlenght=\"100\" />
			</label>
			<input class=\"forms\" type=\"submit\" value=\"Go\" />
			
			<input class=\"forms\" type=\"hidden\" name=\"accao\" value=\"9\" />
			<input class=\"forms\" type=\"hidden\" name=\"elem\" value=\"2\" />
		</form>
		</div>";
	
		if( isset( $_GET['psqoutro'] ) ){
			
			$pagi = clearGarbadge( $_GET['pagi'], false, false);

			$pagf = clearGarbadge( $_GET['pagf'], false, false);

			if ( ! is_numeric($pagi) || ! is_numeric($pagf) || $pagi < 0 || $pagf < 0 )
			{

				$pagi = 0;
		
				$pagf = 5;
		
			}
			
			$adi = " Limit $pagi,$pagf";
			
			include_once("outros/outro_funcoes.php");
		
			include_once("pesquisas/pesq_rap.php");
		
			$psqoutro = new pesq_rap( "Outro", $host , $user_bd , $pass_bd, $db);
		
			$results = 
			$psqoutro->psqOutro( clearGarbadge( $_GET['psqoutro'], false, false), $adi );
			
			$cont = 
			$psqoutro->psqOutro( clearGarbadge( $_GET['psqoutro'], false, false), "" );
			
			echo "<table border=\"0\" style=\"text-align:left;width: 585px;\">
			<tr><td>
			<div class=\"area\" style=\"text-align:center;width: auto;\">
			";
			
			
			
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
				<a href=\"?elem=2&amp;accao=9&amp;outro="
				.current($results)."\">".next($results)."</a>
				</td></tr>";
				
				$i++;
				
				next($results);
				
			}
			
			echo "</table>";
			
			
			
			
			//Número de páginas totais
			$query_count_spam = floor( (mysql_numrows(
			$bd->submitQuery( 
			preg_replace("/Limit [0-9][0-9]*,[0-9][0-9]*$/","",$psqoutro->getLastQuery()) )
			)) / 6 );
			
			if($query_count_spam > 0){
			
			//Divisão do spam por páginas
			echo "<div class=\"listpags\" style=\"float: left;\">";
				
			//Página actual
			$pag_actual = floor ( $pagi / $pagf );

	if ( $pag_actual > 0 )
		echo "<div class=\"pags\" style=\"margin-left: 0px;\">
		<a href=\"?elem=2&amp;accao=9&amp;pagi=" . ( $pagi - 5 ) 
		. "&amp;pagf=5&amp;psqoutro=".$_GET['psqoutro']."\"
		 title=\"Recuar para página anterior\">&lt;</a></div>";

	echo "<div class=\"pags\" style=\"margin-left: 0px;\">
	$pag_actual de $query_count_spam</div>";

	if ( $query_count_spam > 0 && $pag_actual < $query_count_spam )
		echo "<div class=\"pags\" style=\"margin-left: 0px;\">
		<a href=\"?elem=2&amp;accao=9&amp;pagi=" . ( $pagi +  5 ) 
		. "&amp;pagf=5&amp;psqoutro=".$_GET['psqoutro']."\" 
		title=\"Avançar para a próxima página\">&gt;</a></div>";

	echo "</div>";
			
			
		}
	
	}
 }
	
	echo "</div>";
		
 }
 
?>