<?php
/**
 * Gerir requesições dos álbuns. 
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */
 
/**
 * Incluir as funções direccionadas para os álbuns independetemente do contexto
 * em que a página foi apresentada. 
 */
 include_once(defined("IN_PHPAP")?"albuns/album_funcoes.php":"album_funcoes.php");
	
 $bd = ligarBD();
 
 if ( !isset($_SESSION['id_user']) ) session_start();
 
 validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
 
 if($_SESSION['estat_carac'][7]){
 
 
 //Apagar as requesições que expiraram
 if( isset( $_GET['delallreq'] ) ){ 
 	
	$num = delAllExpReq( 2, ALBUM_REQ ); 
	 
	if($num == 0)
		echo alertMsgJs("Não foram apagadas requesições");
	else if($num == 1)
		echo alertMsgJs("Foi apagada 1 requesição");
	else
		echo alertMsgJs("Foram apagadas ".$num." requesições");
		
 }
 
 
 
/*echo "->".$_POST['dat_min']."->".$_POST['dat_lev']."->".$_POST['id_album']
."->".$_POST['id_util']."->".$_POST['sup_alb'];*///die();


 if( isset($_POST['id_req']) && is_numeric($_POST['id_req']) ){
	
	//Apagar uma requesição
	
	if(delRequesicao( $_POST['id_req'], 2 ) > 0) 
		die("1");
	else
		die(rawurlencode("De momento não é possível atender ao seu pedido!"));



 } else if( isset($_POST['dat_min']) && isset($_POST['dat_lev']) && isset($_POST['id_album']) 
 && is_numeric($_POST['id_album'])&& isset($_POST['id_util']) && is_numeric($_POST['id_util'])
 && is_numeric($_POST['sup_alb'])&& isset($_POST['sup_alb'])
 ){
	
	
	
	
	$dat_min = clearGarbadge( $_POST['dat_min'], false, false );
	$dat_lev = cleargarbadge( $_POST['dat_lev'], false, false );
	$id = cleargarbadge( $_POST['id_album'], false, false );
	$sup_alb = cleargarbadge( $_POST['sup_alb'], false, false );
	
	$preg = "#[0-3][0-9]/[0-1][0-9]/2[0-9]{3}#";
	
	//Verificar se as datas são válidas
	if( !preg_match($preg, $dat_min) 
	|| !preg_match($preg, $dat_lev) && $dat_lev != "00/00/0000"){
		
		die(rawurlencode("Uma das datas não é válida"));
		
	} else {
		
		$dat_min = explode("/", $dat_min);
		$dat_lev_1 = explode("/", $dat_lev);
		$dat_lev = $dat_lev_1[2].$dat_lev_1[1].$dat_lev_1[0];
		
		//Verificar se a $data_min ultrapassa em ALBUM_REQ $dat_lev 
		//echo $dat_min[0].$dat_min[1].$dat_min[2]
		//."<br />".$dat_lev." > ".date("Ymd", mktime(0, 0, 0, $dat_min[1], 
		//$dat_min[0]+ALBUM_REQ, $dat_min[2]) );
		
		if( $dat_lev > date("Ymd", 
		mktime(0, 0, 0, $dat_min[1], $dat_min[0]+ALBUM_REQ, $dat_min[2]) ) ){
			
			die(rawurlencode("A data de levantamento é inválida."));
			
		}
		
		$dat_min = $dat_min[2].$dat_min[1].$dat_min[0];
		
		
	}
	
	//Tipo ?
	/*if(date( "Ymd", mktime( 0, 0, 0, 
	$dat_lev_1[1] , 
	($dat_lev_1[0]+ALBUM_REQ) , 
	$dat_lev_1[2] ) ) < $dat_lev
	) die("O prazo de requesição expirou");*/
	
		
	if( $dat_lev > date("Ymd") || $dat_lev < $dat_min && $dat_lev != "00000000"){
		
		$verult = explode("-", $dat_min);
		
		if( 
		date("Ymd", mktime( 0, 0, 0, $verult[1], $verult[2]+ALBUM_REQ, $verult[0]) )
		> $dat_min
		){
			
			die( rawurlencode("A data de levantamento não é válida.") );
			
		}
		
		die( rawurlencode("A data de levantamento não é válida.") );
		
	}
	
	if( mysql_result($bd->submitQuery("
	Select Count(*) From `requesicao` Join `geral`
	On `id_geral` = `geral_id_geral` Where 
	`id_requesicao` = $id
	And `id_elemento` = 2
	"),0 ,0 ) < 1 ) die();
	
	
	$query = $bd->submitQuery("
	UPDATE `requesicao`,`geral` SET 
	`requesicao_dat_min` = '$dat_min',
	`requesicao_dia_levantado` = '$dat_lev',
	`requesicao_id_suporte` = '$sup_alb'
	WHERE `requesicao`.`id_requesicao` = $id 
	And `geral_id_geral` = `id_geral`
	And `id_elemento` = 2
	");
	
	/*if( mysql_affected_rows() < 1){
		
		echo rawurlencode("Essa requesição não existe.");
	
	} else*/ 
		
	if( $query ){
	
		echo rawurlencode("Dados actualizados com sucesso.\nUma mensagem com o update da requesição foi enviada ao utilizador.");
		
	
		$query = $bd->submitQuery("Select `geral_id_geral`, `album_nome` From `album` Where geral_id_geral = (Select `geral_id_geral` From `requesicao` Where `id_requesicao` = $id)");
		
		$query = mysql_fetch_row( $query );
		
		$texto = "
		<b>Olá,</b>
		<p>Informamos que os dados relativos a sua requesição do álbum 
		<a href=\"?elem=2&amp;accao=7&amp;album=$query[0]\">$query[1]</a> foram modificados.</p>
		<i>Com os melhores comprimentos, a gerência.</i>";
		
	
		
		$assunto = "[Álbum] $query[1]";
		
		newMesage( $_POST['id_util'], true, $assunto, $texto );
		
	} else {
		
		echo rawurlencode("De momento não é possível atender ao seu pedido.");
		
	}
	
	die();
	
 }
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 $query_text = "
		Select `album_nome`, `registo_nick`,`registo_id_registo`
		,requesicao.geral_id_geral, id_requesicao
		From `registo`, `requesicao`, `album` 
		Where registo.id_registo = requesicao.registo_id_registo
		And requesicao.geral_id_geral = album.geral_id_geral
		";
 
 if( isset($_GET['req']) && is_numeric($_GET['req']) ){
	
	$id_req = $_GET['req'];
	
	if( existeNaBd( "requesicao", "id_requesicao", $id_req ) == 1 ){
		
		
		$q_req_1 = $bd->submitQuery("$query_text And `id_requesicao` = $id_req");
		
		$q_req_2 = $bd->submitQuery("
		Select DATE_FORMAT(`requesicao_dia_levantado`, '%d-%m%-%Y')
		,DATE_FORMAT(`requesicao_dat_min`, '%d-%m%-%Y'), `requesicao_id_suporte`
		From `requesicao`,`suport_album` Where `id_requesicao` = $id_req");
		
		$q_req_1 = mysql_fetch_row($q_req_1);
		
		$q_req_2 = mysql_fetch_row($q_req_2);
		
		
		$dat_limite = explode("-", $q_req_2[0]);
		$dat_limite = 
		date( "d-m-Y", mktime( 0, 0, 0, $dat_limite[1], 
		( $dat_limite[0]+ALBUM_REQ), $dat_limite[2] ) );
		
		//Operações relativas a multa
		$multa = explode("-",$dat_limite);
		
		$dat_limite = "0";
		
		if( $q_req_2[0] != null && $q_req_2[0] != "00-00-0000" 
		&& $multa[2].$multa[1].$multa[0]  < date("Ymd") ){
				
				$multa = explode("-",$q_req_2[0]);
				 
				$multa = 
				date( "Y-m-d", mktime( 0, 0, 0, $multa[1], 
				( $multa[0]+ALBUM_REQ_DEV ), $multa[2] ) );
					
				$dat_limite = explode("-",$multa);
					
				$dat_limite = $dat_limite[2]."-".$dat_limite[1]."-".$dat_limite[0];
					
				$multa = ( ( difDays($multa, "-") ) * MULTA);
			
		} else $multa = 0;
		
		
		
		
		//Se a requesição do álbum expirou
		$expirou = explode( "-", $q_req_2[1] );
		
		$expirou = 
		((date( "Ymd", mktime( 0, 0, 0, $expirou["1"], 
		( $expirou["0"]+ALBUM_REQ ), $expirou["2"] ) ) < date("Ymd")) && 
		($q_req_2[0] == "00-00-0000")) ?"Sim":"Não";
		
		$sup_list = listarSupAlbum("sup_alb", $id_req, true, false);
			
		
		echo "
		</div>
		<table width=\"580\">
		<tr>
		<td>
		<div class=\"area\" style=\"width: auto;\">
		Nome de álbum <input type=\"hidden\" value=\"".$_GET['req']."\" 
		name=\"id_req\" 
		id=\"id_req\" />
		</div>
		</td>
		<td><a href=\"?elem=2&amp;accao=7&amp;album=$q_req_1[3]\">$q_req_1[0]</a></td>
		</tr>
		
		<tr>
		<td>
		<div class=\"area\" style=\"width: auto;\">
		Requesitante <input type=\"hidden\" value=\"$q_req_1[2]\" name=\"id_util\" 
		id=\"id_util\" />
		</div>
		</td>
		<td><a href=\"?elem=10&amp;perfil=$q_req_1[2]\">$q_req_1[1]</a></td>
		</tr>
		
		<tr>
		<td>
		<div class=\"area\" style=\"width: auto;\">
		Data de requesição
		</div>
		</td>
		<td><input type=\"text\" value=\"$q_req_2[1]\" name=\"dat_req\" id=\"dat_req\" 
		class=\"forms\" /></td>
		</tr>
		
		<tr>
		<td>
		<div class=\"area\" style=\"width: auto;\">
		Data levantado
		</div>
		</td>
		<td><input type=\"text\" value=\"$q_req_2[0]\" name=\"dat_levant\" id=\"dat_levant\" 
		class=\"forms\" /></td>
		</tr>
		
		<tr>
		<td>
		<div class=\"area\" style=\"width: auto;\">
		Suporte
		</div>
		</td>
		<td>$sup_list</td>
		</tr>
		
		";
		
		if($dat_limite){
			
			echo "<tr>
			<td>
			<div class=\"area\" style=\"width: auto;\">
			Data de entrega limite
			</div>
			</td>
			<td><b>$dat_limite</b></td>
			</tr>";
		
		}
		
		echo "<tr>
		<td>
		<div class=\"area\" style=\"width: auto;\">
		Multa
		</div>
		</td>
		<td><font color=\"".($multa>0?"brown":"green")."\">$multa €</font></td>
		</tr>
		<tr>
		
		<td>
		<div class=\"area\" style=\"width: auto;\">
		Expirou?
		</div>
		</td>
		<td><font color=\"".($expirou=="Sim"?"brown":"green")."\">$expirou</font></td>
		</tr>
		<td>
		
		<tr>
		<td>
			<input type=\"button\" value=\"Apagar requesição\" class=\"forms\"
			name=\"apg_req_alb\" id=\"apg_req_alb\"/>
		</td>
		</tr>
		
		<tr>
		<td>
			<input type=\"button\" value=\"Guardar alterações\" class=\"forms\"
			name=\"edit_req_alb\" id=\"edit_req_alb\"/>
		</td>
		</tr>
		
		";
		
		echo "</table>";
		
		mysql_free_result($query);
	
	} else echo "Essa requesição não existe.";
	
	
	
 } else {
	
	/**/
	$pagi = clearGarbadge( $_GET['pagi'], false, false );

	$pagf = clearGarbadge( $_GET['pagf'], false, false );

	if ( ! is_numeric($pagi) || ! is_numeric($pagf) || $pagi < 0 || $pagf < 0 )
	{

		$pagi = 0;
		
		$pagf = 5;

	}
	
	$pesquisar = "";
	
	
	
	if(isset($_GET['psqreqtext']) && isset($_GET['modpsqreq'])){
		
		$pesquisar = clearGarbadge($_GET['psqreqtext'], true, true);
		
		if( isset($_GET['abranpsq']) ) 
			$pesquisar = "'%".$pesquisar."%'";
		else
			$pesquisar = "'".$pesquisar."'";
			
		switch($_GET['modpsqreq']){
			
			case 0: 
			$pesquisar = " And registo.registo_nick Like $pesquisar ";
			break;
			case 1: 
			$pesquisar = " And album.album_etiqueta Like $pesquisar ";
			break;
			case 2: 
			$pesquisar = " And requesicao.id_requesicao Like $pesquisar ";
			break;
			case 3: 
			$pesquisar 
			= " And DATE_ADD(`requesicao_dat_min`,INTERVAL ".ALBUM_REQ
			." DAY) < CURDATE() And `requesicao_dia_levantado` = '00000000' ";
			break;
			
		}
				
	}
	
	//echo "$query_text $pesquisar Order By `id_requesicao` Desc Limit $pagi,$pagf";
	
	$query = $bd->submitQuery("$query_text $pesquisar
	Order By `id_requesicao` Desc Limit $pagi,$pagf");
	
	
	
	//contagem de requesições expiradas
	$req_exp = mysql_result(
	$bd->submitQuery("Select Count(*) From `requesicao` Join `geral`
	On `geral_id_geral` = `id_geral` Where 
	DATE_ADD(`requesicao_dat_min`,INTERVAL ".ALBUM_REQ." DAY) < CURDATE() And
	`requesicao_dia_levantado` = '00000000' And `id_elemento` = 2"),0,0);
	
	if($req_exp == 0) $req_exp = "Não existe nenhuma requesição expirada.";
	elseif($req_exp == 1) $req_exp = "Existe 1 requesição expirada.";
	else $req_exp = "Existem $req_exp expiradas.";
	
	$req_exp .= ".<br />";
	
	$temp = mysql_result($bd->submitQuery("Select Count(*) 
	From `requesicao` Join `geral` On 
	`geral_id_geral` = `id_geral` And `id_elemento` = 2"),0,0);
	
	
	if($temp == 0)
		$req_exp .= "Não existem requesições de álbuns.";
	else if($temp == 1)
		$req_exp .= "Existe a requesição de 1 álbum.";
	else
		$req_exp .= "Existem $temp requesições.";
	
	$modpsqreq = "";
	$modpsqreq[0] = "";
	$modpsqreq[1] = "";
	$modpsqreq[2] = "";
	
	switch($_GET['modpsqreq']){
		
		case 1: $modpsqreq[1] = "checked=\"checked\"";break;
		
		case 2: $modpsqreq[2] = "checked=\"checked\"";break;
		
		default: $modpsqreq[0] = "checked=\"checked\"";break;
		
	}	
	
	if( isset($_GET['abranpsq']) ) $abranpsq = "checked=\"checked\"";
	
	echo "
	<b id=\"ferrareq\" style=\"cursor: pointer;\">
	<img src=\"imagens/ferramentas.png\" title=\"Ferramentas\" alt=\"[Ferramentas]\" />
	</b>
	<div class=\"ferraopen\">
		<p><img src=\"imagens/info.png\" title=\"Informação\" alt=\"[Informação]\" />
		$req_exp</p>
		<p>
		<a href=\"javascript:
		confirmOp('?elem=2&amp;accao=4&amp;opcao=3&amp;delallreq=1','Tem a certeza que deseja apagar todas as requesições expiradas?', '');\">
		Apagar requesições expiradas
		</a>
		</p>
		
		Procurar requesições de:
		
		<form action=\"?elem=2&accao=4&opcao=3&delallreq=1\" method=\"get\">
			<table>
			<tr>
			<td>Nick</td>
			<td><input type=\"radio\" $modpsqreq[0] name=\"modpsqreq\" 
			id=\"modpsqreq\" value=\"0\" /></td>
			</tr>
			<tr>
			<td>Etiqueta álbum</td>
			<td><input type=\"radio\" $modpsqreq[1] name=\"modpsqreq\" id=\"modpsqreq\" 
			value=\"1\" /></td>
			</tr>
			<tr>
			<td>ID requesição</td>
			<td><input type=\"radio\" $modpsqreq[2] name=\"modpsqreq\" id=\"modpsqreq\" 
			value=\"2\" /></td>
			</tr>
			<tr>
			<td>Pesquisa mais abrangente</td>
			<td><input type=\"checkbox\" $abranpsq value=\"0\" name=\"abranpsq\" /></td>
			</tr>
			</table>
			
			<input type=\"text\" class=\"forms\" name=\"psqreqtext\" 
			value=\"".$_GET['psqreqtext']."\" id=\"psqreqtext\" />
			<input type=\"hidden\" value=\"2\"  name=\"elem\" />
			<input type=\"hidden\" value=\"6\"  name=\"accao\" />
			<input type=\"hidden\" value=\"3\"  name=\"opcao\" />
			<input type=\"submit\" value=\"Procurar\" class=\"forms\" />
		</form>
		<a href=\"?elem=2&amp;accao=6&amp;opcao=3\">Listar todas</a><br />
		<a href=\"?elem=2&amp;accao=6&amp;opcao=3&amp;modpsqreq=3&amp;psqreqtext=\">
		Listar todas expiradas</a>
		<p></p>
	</div>
	
	<table width=\"580\">
	<tr>
	<td>
	<div class=\"area\" style=\"width: auto;\">Nome do álbum</div>
	</td>
	<td>
	<div class=\"area\" style=\"width: auto;\">
	Requerinte
	</div>
	</td>
	<td>
	<div class=\"area\" style=\"width: auto;\">Editar</div>
	</td>
	<!--<td>
	<div class=\"area\" style=\"width: auto;\">Marca</div>
	</td>-->
	</tr>";
	
	while( $req = mysql_fetch_row($query) ){
 
		echo "<tr><td><a href=\"?elem=2&amp;accao=7&amp;album=$req[3]\">$req[0]</a></td>
		<td><a href=\"?elem=10&amp;perfil=$req[2]\">$req[1]</a></td>
		<td><a href=\"?elem=2&amp;accao=6&amp;opcao=3&amp;req=$req[4]\">Editar</a></td>
		<!--<td><input type=\"checkbox\" name=\"\" id=\"\" /></td>-->";
	
	}
	
	//echo "<td><input type=\"button\" name=\"\" id=\"\" value=\"\" /></td>";
	echo "</tr></table>";
	
	
	/*Dividir as requesições por páginas*/
	echo "<div class=\"listpags\" style=\"float: left;\">";
 	
	 
	
	//Número de páginas totais
	$query_count_spam = floor( (mysql_result(
	$bd->submitQuery("Select Count(*) From `requesicao`,`registo`,`album`
	Where registo.id_registo = requesicao.registo_id_registo And
	requesicao.geral_id_geral = album.geral_id_geral
	$pesquisar"), 0, 0) ) / 6 );
	
	//Página actual
	$pag_actual = floor ( $pagi / $pagf );
	
	if( $query_count_spam > 0 ){
	
	if ( $pag_actual > 0 ){
		
		if(isset($_GET['psqreqtext']) && isset($_GET['modpsqreq'])){
			
			if(isset($_GET['abranpsq'])){
			
				echo "<div class=\"pags\" style=\"margin-left: 0px;\">
				<a href=\"?elem=2&amp;accao=4&amp;opcao=3&amp;pagi=" . ( $pagi - 5 ) 
				. "&amp;pagf=5&amp;psqreqtext=".$_GET['psqreqtext']."&amp;modpsqreq="
				.$_GET['modpsqreq']."&amp;abranpsq=".$_GET['abranpsq']
				."\" title=\"Recuar para página anterior\">&lt;</a></div>";
				
			}else{
				
				echo "<div class=\"pags\" style=\"margin-left: 0px;\">
				<a href=\"?elem=2&amp;accao=4&amp;opcao=3&amp;pagi=" . ( $pagi - 5 ) 
				. "&amp;pagf=5&amp;psqreqtext=".$_GET['psqreqtext']."&amp;modpsqreq="
				.$_GET['modpsqreq']."\" title=\"Recuar para página anterior\">&lt;</a></div>";
			
			}
			
	
		} else {
				
			echo "<div class=\"pags\" style=\"margin-left: 0px;\">
			<a href=\"?elem=2&amp;accao=4&amp;opcao=3&amp;pagi=" . ( $pagi - 5 ) 
			. "&amp;pagf=5\" title=\"Recuar para página anterior\">&lt;</a></div>";
			
		}
	}
	
	echo "<div class=\"pags\" style=\"margin-left: 0px;\">$pag_actual de $query_count_spam</div>";

	if ( $query_count_spam > 0 && $pag_actual < $query_count_spam ){
		
		if(isset($_GET['psqreqtext']) && isset($_GET['modpsqreq'])){
			if(isset($_GET['abranpsq'])){
				
				echo "<div class=\"pags\" style=\"margin-left: 0px;\">
				<a href=\"?elem=2&amp;accao=4&amp;opcao=3&amp;pagi=" . ( $pagi + 5 ) 
				. "&amp;pagf=5&amp;psqreqtext=".$_GET['psqreqtext']."&amp;modpsqreq="
				.$_GET['modpsqreq']."&amp;abranpsq=".$_GET['abranpsq']
				."\"title=\"Avançar para a próxima página\">&gt;</a></div>";	
				
			}else {
				
				echo "<div class=\"pags\" style=\"margin-left: 0px;\">
			<a href=\"?elem=2&amp;accao=4&amp;opcao=3&amp;pagi=" . ( $pagi + 5 ) 
			. "&amp;pagf=5&amp;psqreqtext=".$_GET['psqreqtext']."&amp;modpsqreq="
			.$_GET['modpsqreq']."\"title=\"Avançar para a próxima página\">&gt;</a></div>";		
				
			}
		
				
			
		} else {
			
			echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a href=\"?elem=2&amp;accao=4&amp;opcao=3&amp;pagi=" . ( $pagi + 5 ) 
		. "&amp;pagf=5\" title=\"Avançar para a próxima página\">&gt;</a></div>";
				
		}
	
	}
	
	}
	
	echo "</div>";
	
	
	mysql_free_result($query);
 
 }
 
 } else {
	
		defined("IN_PHPAP")?
 		include_once("home.php"):
 		include_once("../home.php");
			
	}
 
?>