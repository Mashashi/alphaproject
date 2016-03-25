<?php
/**
 * Requesitar outro.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */
 
 session_start();

 if( !defined("IN_PHPAP") && $_SESSION['estat_carac'][4] ){
 	/**
	 * Incluir as funções nativas dos outros itens.
	 *
	 */
	include_once("outro_funcoes.php");
	
	$dat_ini = clearGarbadge( rawurldecode( $_POST["dat_dis"] ), false, false ).date("Y");
	
	//ID do outro
	$id_outro = clearGarbadge( $_POST["id_outro"] , false, false );
	
	//ID do suporte do outro
	$id_sup = clearGarbadge( $_POST["sup_outr"], false, false );
	
	if( !is_numeric( $id_outro )  ) die();
	
	//die( "-->".$dat_ini );
	
	if( !validarData( $dat_ini, "/" ) )
		die( rawurlencode("A data não é válida.") );
		
	$dat_ini = join( "", array_reverse( explode( "/", $dat_ini ) )  );
	
	if($dat_ini > 
	date( "Ymd",mktime( null, null, null, date("m"),date("d")+OUTRO_REQ,date("y") ) )
	
	) die( rawurlencode("A data de levantamento não é válida.") );
	
		
	if( $dat_ini < date( "Ymd" ) || substr($dat_ini,0,4) >  date("Y")  ) 
		die( rawurlencode("A data de levantamento não é válida.") );
	
	$bd = ligarBD();
	
	//Ver se este utilizador já tinha feito uma requesição para este outro
	$queryr = mysql_result($bd->submitQuery("Select Count(*) From `requesicao` 
	Where `geral_id_geral` = $id_outro
	And `registo_id_registo` = ".$_SESSION['id_user']), 0, 0 );
	
	//Ver se este outro é requesitável
	$queryre = mysql_result($bd->submitQuery("Select `outro_requesitavel` From `outro` 
	Where `geral_id_geral` = $id_outro"), 0, 0 );
	
	//Ver se as cópias disponíveis neste suporte são superiores a 0 
	if( ($queryr < 1) && ($queryre) && (getOutrosDisSup( $id_outro, $id_sup ) > 0) ){
	
	$id_estat = getCampoFromRegistoWhereId( $_SESSION['id_user'], "estatuto_id_estatuto" );
	
	$query = $bd->submitQuery("
	INSERT INTO `requesicao` (
		`id_requesicao` ,
		`registo_estatuto_id_estatuto` ,
		`registo_id_registo` ,
		`geral_id_geral` ,
		`requesicao_dat_min` ,
		`requesicao_dia_levantado`,
		`requesicao_id_suporte`
	)VALUES (
	null, '$id_estat', '".$_SESSION['id_user']."', '$id_outro', '$dat_ini'
	, '0000-00-00', '$id_sup'
	)
	");
	
	echo $query?1:"Não é possível atender ao seu pedido neste momento.";
	
	if( mysql_insert_id() > 0 ){
		
		$query =
		$bd->submitQuery("Select `suport_outro_nome`,`id_suport_outro`
		From `copi_outro`,`suport_outro` Where `outro_geral_id_geral` = '$id_outro' 
		And `suport_outro_id_suport_outro` = `id_suport_outro`");
		
		$msg = "Encontram-se disponíveis as seguintes cópias: <br />";
		
		while( $row = mysql_fetch_array($query) ){
			
			if($dis < 0 ) $dis = 0;
			
			$msg .= "<br />".getOutrosDisSup($id_film, $row[1])." em $row[0], ";
			
		}
		
		$msg = substr($msg, 0, strlen($msg)-2).".";
		
		$query_1 =  mysql_result( $bd->submitQuery("Select `outro_nome` From `outro` 
		Where `geral_id_geral` = '$id_outro'"), 0, 0 );
		
		$msg .= "<p>A sua requesição foi feita em ".date("d/m/Y").".</p>
		
		<p>Tem ".OUTRO_REQ." dias apartir da data pretendida ".
		selectCampoReq( mysql_insert_id(), 
		"DATE_FORMAT(`requesicao_dat_min`, '%d-%m%-%Y')" )
		." para levantar o outro item.<br />
		E aparir da data que levantar o outro item tem ".OUTRO_REQ." dias para o entregar.</p>
		
		O ID da sua requesição é o ".mysql_insert_id().".<br />
		Traga-o consigo a quando o levantamento e devolução do item.<br />
		<a href=\"?elem=2&amp;accao=9&amp;outro=$id_outro\">
		<b>Requesição do outro item <u>$query_1</u> feita com sucesso</b><br />
		Clique aqui para visualizar a descrição de \"<u>$query_1</u>\"</a>
		<br /><br />";
		
		newMesage( $_SESSION["id_user"], true, "[Outro] $query_1", $msg );
			
			
		}
	
	}
	
 }
 
?>