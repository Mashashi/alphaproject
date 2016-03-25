<?
/**
 * Ver a ficha técnica do álbum.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

		/**
 		 * Incluir as funções direccionadas para os álbuns 
		 * independetemente do contexto em que a página foi apresentada.   
 		 */
		include_once("albuns/album_funcoes.php");
 
 validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
 
 if( defined('IN_PHPAP') && $_SESSION['user'] ){
	
	echo "
	<div class=\"local\"><a href=\"?elem=2\">".$_SESSION['estat_nome']."</a> 
	» ";
	echo isset($_GET['album'])?"Visualizar características do álbum":"Pesquisar álbum por nome";
	
	echo "</div><div class=\"gestaopremain\">";
	
	
	
	if( isset( $_GET['album'] ) && !empty( $_GET['album'] ) && is_numeric( $_GET['album'] ) ){
	
		$id = clearGarbadge( $_GET['album'], false, false);
		
		$query_alb = $bd->submitQuery("Select * From `album` Where `geral_id_geral` = $id");
	
		if( mysql_num_rows( $query_alb ) > 0){
			
			$album_carac = "";
			
			if( strlen( mysql_result($query_alb,0,1) ) > 0 )
				$album_carac = "Etiqueta: ".mysql_result($query_alb,0,1);
			
			/*$des = mysql_result($query_fil,0,5);
			if( !empty( $des ) )
			$filme_carac .= "\nDescrição: ".$des;*/
			
			if( mysql_result($query_alb, 0, 4) > 0)
				$album_carac .= "\nAno: ".mysql_result($query_alb, 0, 4);
			
			$dur = tempoAlbum( $id , "H:i:s" );
			
			if( $dur != "00:00:00" ) $album_carac .= "\nDuração: $dur";
			
			$val = 
			classiItem( mysql_result($query_alb,0,6), mysql_result($query_alb,0,7) );	
			
			//$filme_carac .= "\nClassificação alphaproject: ".$val;
			
			$album_carac .= 
			"\nRequesitável: ".(mysql_result($query_alb,0,5)?"Sim":"Não");
			
			
			$help = $bd->submitQuery("Select `suport_album_id_suport_album`
			,`copi_album_totais` From `copi_album` Where 
			`album_geral_id_geral` = $id");
			
			if( mysql_numrows($help) > 1 )
				$album_carac .= "\nSuportes disponíveis: ";//.mysql_result($help,0);
			else if( mysql_numrows($help) == 1)
				$album_carac .= "\nSuporte disponível: ";//.mysql_result($help,0);
			
			$flag_req = true;
			
			for($i = 0; $i < mysql_numrows($help) ;$i++){			
				
				$id_sup_alb = mysql_result( $help, $i, 0 );
				
				$copi_tot = mysql_result( $help, $i, 1 );
				
				$help2 = $bd->submitQuery( 
				"Select `suport_album_nome`
				From `suport_album` Where 
				`id_suport_album` = $id_sup_alb");
				
				
				$char_album = getCharNumAlbum( $id, $id_sup_alb );
				
				
				mysql_numrows( $help2 ) == 0 ? $id_sup = 0:
				$id_sup = mysql_result( $help2, 0 );
				
				
				$copi_dis_reque = 
				getAlbunsDisSup($id, $id_sup_alb );
				
				if($copi_dis_reque < 0 ) 
					$copi_dis_reque = 0;
				else
					$flag_req = true;
					
				if( mysql_numrows( $help2 ) == 0) 
					$help2 = "|Suporte não definido|"; 
				else 
					$help2 = mysql_result( $help2, 0 );
				
				$album_carac .= "\n $help2, cópias $copi_tot, cópias disponíveis "." "
				."$copi_dis_reque, elementos $char_album";
				
			}
			
			
			
			$album_sinopse = mysql_result($query_alb, 0, 3);
			
			$album_nome = rawurlencode( utf8_encode(mysql_result($query_alb,0,2)));
			
			$album_carac = rawurlencode( utf8_encode($album_carac) );
					
			if($_SESSION['estat_carac'][6]) echo "
			<a href=\"".$_SERVER['PHP_SELF']."?elem=2&amp;accao=16&amp;album=$id\">
			<img src=\"imagens/editar.png\" alt=\"[Editar]\" title=\"Editar álbum\"
			border=\"0\" />
			</a>
			<br />
			<a href=\"?modpsqreq=1&amp;psqreqtext=".mysql_result($query_alb, 0, 1)
			."&elem=2&amp;accao=6&amp;opcao=3\">
			Ver todas as requesições deste álbum</a>		
			";
		
			echo " <object width=\"587\" height=\"200\">
					<embed 
					src=\"flash/type_2.swf?descricao=$album_carac&amp;titulo=$album_nome\" 
					type=\"application/x-shockwave-flash\" wmode=\"transparent\"
					width=\"587\" height=\"310\"></embed>
					</object>
					<div style=\"text-align:left;float:left;width:587px;\">
					".nl2br($album_sinopse)."<br />";
					
					
					
					listarTrilhasAlbum( $id, $_SESSION['estat_carac'][7] );
					
					
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
					style=\"background-image:URL('imagens/albuns.jpg');height:300px;\">
					
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
							
						echo "<img src=\"imagens/star.png\" class=\"votestaralb\" />";
							
					} 
						
					for($i = 0; $i < 5-$val; $i++){
							
						echo "<img src=\"imagens/star2.png\" class=\"votestaralb\" />";
							
					}
					
					echo "<br /><b id=\"text_pos_vot\"><br /></b>
						
					<input type=\"hidden\" id=\"album_classi_alb\" value=\"$val\" />
						
					<input type=\"hidden\" id=\"album_id_alb\" value=\"$id\" />";
					
						
					} else {
						
						echo "<div>$querycc</div>";
						
						for($i = 0; $i < $val;$i++){
							
							echo "<img src=\"imagens/star.png\" class=\"votestaralb\" />";
							
						} 
						
						for($i = 0; $i < 5-$val; $i++){
							
							echo "<img src=\"imagens/star2.png\" class=\"votestaralb\" />";
							
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
					
					
					if( mysql_numrows($help) > 0 && mysql_result($query_alb,0,5) && $flag_req && 
					$_SESSION['estat_carac'][4]){
						
						echo "<div style=\"padding:3px;width:124px;float:right;\" 
							class=\"votestars\" id=\"requesicaoform\">
						
							Data de levantamento:
							
							<input class=\"forms\" size=\"5\" type=\"text\" 
							name=\"dat_min_lev_req\" id=\"dat_min_lev_req\" /> "
							.date("Y")."<br />
							".listarSupAlbum("sup_alb", $id, false, true)."
							<ul>
							<li>Podes requesitar no máximo até.
							".
							date("d/m/Y",
							mktime(0,0,0,date("m"),date("d")+ALBUM_REQ,date("y")))
							."</li>
							<li>E terá de devolve-lo num prazo de ".ALBUM_REQ_DEV
							." dias depois de levantado.</li>
							</ul>
							<hr />
						
							<input class=\"forms\" type=\"button\" value=\"Requesitar\" 
							name=\"req_button\" id=\"req_button_alb\" />
					
							<b id=\"statusreq\"></b></div>";
							
						} else {
						
							echo  "<div style=\"padding:3px;width:124px;float:right;\" 
							class=\"votestars\" id=\"requesicaoform\">
							De momento as requesições para este álbum encontram-se 
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
						And `geral_id_geral` = '$id'");
						
						$req_dev = explode("-", mysql_result($query, 0, 1) ); 
						
						$req_max = explode("-", mysql_result($query, 0, 0) );
						
						//Prazo máximo para devolver o filme
						$req_max_dev = "00000000";
						
						if(join("",$req_dev) != "00000000"){
						
							$req_max_dev = date("Ymd", mktime(0, 0, 0, $req_dev[1], 
							$req_dev[2]+ALBUM_REQ_DEV, $req_dev[0] ) );
						
						}
						
						//Prazo máximo para levantar o filme
						$req_max_lev = 
						date("Ymd", mktime(0, 0, 0, $req_max[1], 
						$req_max[2]+ALBUM_REQ, $req_max[0] ) );
						
						/*if(!$flag_req){
						
							echo "A sua requesição foi concelada porque o item 
							pretendido não se encontra disponível";
						
						}else*/if( $req_max_lev < date("Ymd") && join("",$req_dev) == "00000000" ){
							
							echo "A sua requesição expirou x|";
							
						} else if( $req_max_dev < date("Ymd") 
						&& $req_max_dev != "00000000" ){
							
							$req_max_dev = 
							date("Y-m-d", mktime(0, 0, 0, $req_dev[1], 
							$req_dev[2]+ALBUM_REQ_DEV, $req_dev[0] ) );
							
							echo "<p>O prazo para a entrega do álbum 
							\"<b>".date("d/m/Y", mktime(0, 0, 0, $req_dev[1], 
							$req_dev[2]+ALBUM_REQ_DEV, $req_dev[0] ) )
							."</b>\" expirou tem a pagar: <font color=\"brown\">"
							.( (difDays($req_max_dev, "-") * MULTA) )." €</font>.</p>";
							
						} else if( join("",$req_dev) == "00000000") {
							
							$sup = mysql_result($query, 0, 2);
							
							$sup = $bd->submitQuery("
							Select `suport_album_nome` From `suport_album`
							Where `id_suport_album` = '$sup'
							");
							
							if(mysql_numrows($sup) > 0){
								
								$sup = mysql_result($sup, 0, 0);
								$sup = "<b>$sup</b>";
							
								echo "
								<p>Tem até <br />\"<b>".
								date("d/m/Y", mktime(0, 0, 0, $req_max[1], 
								$req_max[2]+ALBUM_REQ, $req_max[0] ) )
								.
								"
								</b>\" para levantar o álbum que requesitou em:<br /> $sup.</p>";
							
							} else {
								
								echo 
								"A sua requesição foi cancelada porque o suporte pretendido 
								não se encontra disponível.";
								
							}
							
							
							
						} else {
							
							echo "<p>Tem até <br />\"<b>".
							date("d/m/Y", mktime(0, 0, 0, $req_dev[1], 
							$req_dev[2]+ALBUM_REQ_DEV, $req_dev[0] ) )
							."</b>\" para entregar o álbum.<p/>";
							
						}
						
						echo "</div>";
							
						mysql_freeresult( $query );
						
					}
					/*Final da requesição*/
					
					
					
					echo "</div>";
					
					
					echo "</div>";
					
					mysql_freeresult( $query_alb );
					
					
					
		} else {
			
			echo "Lamentamos mas o álbum pretendido não se encontra disponível.";
			
		}	
	
	} else {
	
		echo "<div style=\"margin: 15px;\">
		<form method=\"get\" action=\"?elem=2&amp;accao=7\">
			<label for=\"psqalbum\">
			<input class=\"forms\" type=\"text\" name=\"psqalbum\" maxlenght=\"100\" />
			</label>
			<input class=\"forms\" type=\"submit\" value=\"Go\" />
			
			<input class=\"forms\" type=\"hidden\" name=\"accao\" value=\"7\" />
			<input class=\"forms\" type=\"hidden\" name=\"elem\" value=\"2\" />
		</form>
		</div>";
	
		if( isset( $_GET['psqalbum'] ) ){
			
			$pagi = clearGarbadge( $_GET['pagi'], false, false);

			$pagf = clearGarbadge( $_GET['pagf'], false, false);

			if ( ! is_numeric($pagi) || ! is_numeric($pagf) || $pagi < 0 || $pagf < 0 )
			{

				$pagi = 0;
		
				$pagf = 5;
		
			}
			
			$adi = " Limit $pagi,$pagf";
			
			include_once("albuns/album_funcoes.php");
		
			include_once("pesquisas/pesq_rap.php");
		
			$psqalbum = new pesq_rap( "Album", $host , $user_bd , $pass_bd, $db);
		
			$results = 
			$psqalbum->psqAlbum( clearGarbadge( $_GET['psqalbum'], false, false), $adi );
			
			
			$cont = 
			$psqalbum->psqAlbum( clearGarbadge( $_GET['psqalbum'], false, false), "" );
			
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
				<a href=\"?elem=2&amp;accao=7&amp;album="
				.current($results)."\">".next($results)."</a>
				</td></tr>";
				
				$i++;
				
				next($results);
				
			}
			
			echo "</table>";
			
			
			
			
			//Número de páginas totais
			$query_count_spam = floor( (mysql_numrows(
			$bd->submitQuery( 
			preg_replace("/Limit [0-9][0-9]*,[0-9][0-9]*$/","",$psqalbum->getLastQuery()) )
			)) / 6 );
			
			if($query_count_spam > 0){
			
			//Divisão do spam por páginas
			echo "<div class=\"listpags\" style=\"float: left;\">";
				
			//Página actual
			$pag_actual = floor ( $pagi / $pagf );

	if ( $pag_actual > 0 )
		echo "<div class=\"pags\" style=\"margin-left: 0px;\">
		<a href=\"?elem=2&amp;accao=7&amp;pagi=" . ( $pagi - 5 ) 
		. "&amp;pagf=5&amp;psqalbum=".$_GET['psqalbum']."\"
		 title=\"Recuar para página anterior\">&lt;</a></div>";

	echo "<div class=\"pags\" style=\"margin-left: 0px;\">
	$pag_actual de $query_count_spam</div>";

	if ( $query_count_spam > 0 && $pag_actual < $query_count_spam )
		echo "<div class=\"pags\" style=\"margin-left: 0px;\">
		<a href=\"?elem=2&amp;accao=7&amp;pagi=" . ( $pagi +  5 ) 
		. "&amp;pagf=5&amp;psqalbum=".$_GET['psqalbum']."\" 
		title=\"Avançar para a próxima página\">&gt;</a></div>";

	echo "</div>";
			
			
		}
	
	}
 }
	
	echo "</div>";
		
 }
 
 
 
?>