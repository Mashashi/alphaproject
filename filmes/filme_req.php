<?
/**
 * Requesitar um filme. 
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

	/**
 	 *  Requerir as funções nativas dos filmes.
     */ 	
	require_once("filme_funcoes.php");
 
 session_start();
 
 validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
 
 if( !defined("IN_PHPAP") && $_SESSION['estat_carac'][3] ){

	
	
	//Data apartir de quando se deseja requesitar o filme num máximo de dias 
	//definido pelo administrador
	$dat_ini = clearGarbadge( rawurldecode( $_POST["dat_dis"] ), false, false ).date("Y");
	
	//ID do filme
	$id_film = clearGarbadge( rawurldecode( $_POST["id_film"] ), false, false );
	
	//ID do suporte do filme
	$id_sup = clearGarbadge( rawurldecode( $_POST["sup_fil"] ), false, false );
	//die("-->".$id_sup);
	if( !is_numeric( $id_film )  ) die();
	
	if( !validarData( $dat_ini, "/" ) )
		die( rawurlencode("A data não é válida!") );
		
	$dat_ini = join( "", array_reverse( explode( "/", $dat_ini ) )  );
	
	if($dat_ini > 
	date("Ymd",mktime(null,null,null,date("m"),date("d")+FILME_REQ_DEV,date("y")))
	) die( rawurlencode("A data não é válida!") );
		
	if( $dat_ini < date( "Ymd" ) || substr($dat_ini,0,4) >  date("Y") ) 
		die( rawurlencode("A data não é válida!") );
	
	$bd = ligarBD();
	
	
	//Ver se este utilizador já tinha feito uma requesição para este filme
	$queryr = mysql_result($bd->submitQuery("Select Count(*) From `requesicao` 
	Where `geral_id_geral` = '$id_film' 
	And `registo_id_registo` = ".$_SESSION['id_user']), 0, 0 );
	
	//Ver se este filme é requesitável
	$queryre = mysql_result($bd->submitQuery("Select `filme_requesitavel` From `filme` 
	Where `geral_id_geral` = $id_film"), 0, 0 );
	
	//Ver se as cópias disponíveis neste suporte são superiores a 0 
	
	
	
	/*$bd->submitQuery( 
	"SELECT `copi_filme_totais`-
	(Select Count(*) From `requesicao` Where `geral_id_geral` = '4')
	As copi_tot, 
	( Select `suport_filme_nome` From `suport_filme`,`copi_filme` 
	Where `id_suport_filme` = `suport_filme_id_suport_filme` Limit 1) As suport 
	FROM `copi_filme` Where `filme_geral_id_geral` = '4'";*/
	
	
	
	if( ($queryr < 1) && ($queryre) && (getFilmesDisSup( $id_film, $id_sup) > 0) ){
	
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
	null, '$id_estat', '".$_SESSION['id_user']."', '$id_film', '$dat_ini'
	, '0000-00-00', '$id_sup'
	)
	");
	
	
	if( $query ) echo 1; else echo 0;
	
	
	if( mysql_insert_id() > 0 ){
		
		$query =
		$bd->submitQuery("Select `suport_filme_nome`,`id_suport_filme`
		From `copi_filme`,`suport_filme` Where `filme_geral_id_geral` = '$id_film' 
		And `suport_filme_id_suport_filme` = `id_suport_filme`");
		
		$msg = "Encontram-se disponíveis as seguintes cópias: <br />";
		
		while( $row = mysql_fetch_array($query) ){
			
			if($dis < 0 ) $dis = 0;
			
			$msg .= "<br />".getFilmesDisSup($id_film, $row[1])." em $row[0], ";
			
		}
		
		$msg = substr($msg, 0, strlen($msg)-2).".";
		
		//$msg = substr($msg, 0, -2).".";
		
		$query_1 =  mysql_result( $bd->submitQuery("Select `filme_nome` From `filme` 
		Where `geral_id_geral` = '$id_film'"), 0, 0 );
		
		$msg .= "<p>A sua requesição foi feita em ".date("d/m/Y").".</p>
		
		<p>Tem ".FILME_REQ." dias apartir da data pretendida ".
		selectCampoReq( mysql_insert_id(), 
		"DATE_FORMAT(`requesicao_dat_min`, '%d-%m%-%Y')" )
		." para levantar o filme.<br />
		E aparir da data que levantar o filme tem ".FILME_REQ_DEV." dias para o entregar.</p>
		
		O ID da sua requesição é o ".mysql_insert_id().".<br />
		Traga-o consigo a quando o levantamento e devolução do filme.<br />
		<a href=\"?elem=2&amp;accao=5&amp;filme=$id_film\">
		<b>Requesição do filme <u>$query_1</u> feita com sucesso</b><br />
		Clique aqui para visualizar a descrição de \"<u>$query_1</u>\"</a>
		<br /><br />";
		
		
		
		
		//if( mysql_numrows( $query_1 ) == 1  )
		
		newMesage( $_SESSION["id_user"], true, "[Filme] $query_1", $msg );
			
		//newMesage( $_SESSION["id_user"], true, "Ola2", "Ola2" );	
			
		}
	
	}
	
 }
 
?>