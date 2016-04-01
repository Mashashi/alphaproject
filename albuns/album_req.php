<?php

/**
 * Requesitar �lbum.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 */
	/**
 	 * Incluir as fun��es direccionadas para os �lbuns independetemente do contexto
 	 * em que a p�gina foi apresentada.  
 	 */
	include_once("album_funcoes.php");
 
 session_start();

 validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
 
 if( !defined("IN_PHPAP") && $_SESSION['estat_carac'][4] ){
 	
	
	$dat_ini = clearGarbadge( rawurldecode( $_POST["dat_dis"] ), false, false )
	.date("Y");
	
	//ID do album
	$id_album = clearGarbadge( $_POST["id_album"] , false, false );
	
	//ID do suporte do album
	$id_sup = clearGarbadge( $_POST["sup_alb"], false, false );
	
	if( !is_numeric( $id_album )  ) die();
	
	//die( "-->".$dat_ini );
	
	/*if( !validarData( $dat_ini, "/" ) )
		die( rawurlencode("A data n�o � v�lida.") );
		
	$dat_ini = join( "", array_reverse( explode( "/", $dat_ini ) )  );
	
	if($dat_ini > 
	date( "Ymd",mktime( null, null, null, date("m"),date("d")+ALBUM_REQ,date("y") ) )
	
	) die( rawurlencode("A data de levantamento n�o � v�lida.") );
	
		
	if( $dat_ini < date( "Ymd" ) || substr($dat_ini,0,4) >  date("Y")  ) 
		die( rawurlencode("A data de levantamento n�o � v�lida.") );*/
	
	
	//
	if( !validarData( $dat_ini, "/" ) )
		die( rawurlencode("A data n�o � v�lida!") );
		
	$dat_ini = join( "", array_reverse( explode( "/", $dat_ini ) )  );
	
	if($dat_ini > 
	date("Ymd",mktime(null,null,null,date("m"),date("d")+ALBUM_REQ,date("y")))
	) die( rawurlencode("A data n�o � v�lida!") );
		
	if( $dat_ini < date( "Ymd" ) || substr($dat_ini,0,4) >  date("Y") ) 
		die( rawurlencode("A data n�o � v�lida!") );
	//
	
	
	$bd = ligarBD();
	
	//Ver se este utilizador j� tinha feito uma requesi��o para este album
	$queryr = mysql_result($bd->submitQuery("Select Count(*) From `requesicao` 
	Where `geral_id_geral` = $id_album
	And `registo_id_registo` = ".$_SESSION['id_user']), 0, 0 );
	
	//Ver se este album � requesit�vel
	$queryre = mysql_result($bd->submitQuery("Select `album_requesitavel` From `album` 
	Where `geral_id_geral` = $id_album"), 0, 0 );
	
	//Ver se as c�pias dispon�veis neste suporte s�o superiores a 0 
	if( ($queryr < 1) && ($queryre) && (getAlbunsDisSup( $id_album, $id_sup ) > 0) ){
	
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
	null, '$id_estat', '".$_SESSION['id_user']."', '$id_album', '$dat_ini'
	, '0000-00-00', '$id_sup'
	)
	");
	
	echo $query?1:"N�o � poss�vel atender ao seu pedido neste momento.";
	
	if( mysql_insert_id() > 0 ){
		
		$query =
		$bd->submitQuery("Select `suport_album_nome`,`id_suport_album`
		From `copi_album`,`suport_album` Where `album_geral_id_geral` = '$id_album' 
		And `suport_album_id_suport_album` = `id_suport_album`");
		
		$msg = "Encontram-se dispon�veis as seguintes c�pias: <br />";
		
		while( $row = mysql_fetch_array($query) ){
			
			if($dis < 0 ) $dis = 0;
			
			$msg .= "<br />".getAlbunsDisSup($id_film, $row[1])." em $row[0], ";
			
		}
		
		$msg = substr($msg, 0, strlen($msg)-2).".";
		
		$query_1 =  mysql_result( $bd->submitQuery("Select `album_nome` From `album` 
		Where `geral_id_geral` = '$id_album'"), 0, 0 );
		
		$msg .= "<p>A sua requesi��o foi feita em ".date("d/m/Y").".</p>
		
		<p>Tem ".ALBUM_REQ." dias apartir da data pretendida ".
		selectCampoReq( mysql_insert_id(), 
		"DATE_FORMAT(`requesicao_dat_min`, '%d-%m%-%Y')" )
		." para levantar o �lbum.<br />
		E aparir da data que levantar o �lbum tem ".ALBUM_REQ." dias para o entregar.</p>
		
		O ID da sua requesi��o � o ".mysql_insert_id().".<br />
		Traga-o consigo a quando o levantamento e devolu��o do �lbum.<br />
		<a href=\"?elem=2&amp;accao=7&amp;album=$id_album\">
		<b>Requesi��o do �lbum <u>$query_1</u> feita com sucesso</b><br />
		Clique aqui para visualizar a descri��o de \"<u>$query_1</u>\"</a>
		<br /><br />";
		
		newMesage( $_SESSION["id_user"], true, "[�lbum] $query_1", $msg );
			
			
		}
	
	}
	
 }
 
?>