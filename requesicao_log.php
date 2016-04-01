<?
/**
 * Listar o log de requesi��es.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 */
 if( defined('IN_PHPAP') ){
 	
	/**
 	 * Incluir fun��es avan�adas. 	
	 */
	include_once("funcoes_avan.php");
	
	validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
	
	if( ($_SESSION['estat_carac'][6] || $_SESSION['estat_carac'][7] || $_SESSION['estat_carac'][8]) ){
		
		$pagi = clearGarbadge( $_GET['pagi'], false, false);
		
		$pagf = clearGarbadge( $_GET['pagf'], false, false);
		
		$bd = ligarBD();
		
		if ( ! is_numeric($pagi) || ! is_numeric($pagf) || $pagi < 0 || $pagf < 0 )
		{
		
			$pagi = 0;
			
			$pagf = 5;
		
		}
		
			
		//Se tem permiss�o para visualizar o log de requesi��o do item
		if($_SESSION['estat_carac'][6])
			$permissao = "1,";
		
		if($_SESSION['estat_carac'][7])
			$permissao .= "2,";
		
		if($_SESSION['estat_carac'][8])
			$permissao .= "3,";
			
		$permissao{strlen($permissao)-1} = " ";
		
		$estat = $bd->submitQuery("Select 
		Count(*), 
		sum(`requesicao_log_multa_paga`) 
		From `requesicao_log` Where `requesicao_log_tipo` IN($permissao)");
		
		$entradas = mysql_result($estat,0,0);
		if( $entradas == 0 )
			$entradas = "N�o existem entradas";
		elseif( $entradas == 1 )
			$entradas = "Existe 1 entrada";
		else 
			$entradas = "Existem  $entradas entradas";
			
		echo "
		<div class=\"local\">
		<b><a href=\"?elem=2\" title=\"A tua �rea\">".$_SESSION['estat_nome']."</a></b>
		� A ver log de requesi��es apagadas
		</div>
		<div class=\"gestaopremainareaprevint\">
		Nota: As requesi��es expiradas n�o se encontram em base de dados. <br />
		A informa��o contida nesta tabela pode estar desactualizada.<br />
		As requesi��es expiradas n�o constam nesta lista.</div>
		
		<ul>
		<li>$entradas no log das requesi��es;</li>
		<li>A soma total das multas � ".mysql_result($estat,0,1)." &euro;.</li>
		</ul>
		<table border=\"0\">";
		
		/*
		`requesicao_log_numero`
		`requesicao_log_item_nome`,
		`requesicao_log_suport_nome`,
		DATE_FORMAT(`requesicao_log_levantado`, '%d-%m%-%Y'),
		DATE_FORMAT(`requesicao_log_requerido`, '%d-%m%-%Y'),
		`requesicao_log_multa_paga`,
		`requesicao_log_apagado`,
		`requesicao_log_tipo`
		*/
		
		$req_exp = $bd->submitQuery("Select 
		* From `requesicao_log` Where `requesicao_log_tipo` IN($permissao) Limit $pagi,$pagf");
		
		echo "
		<tr>
		<td><div class=\"area\" style=\"width: auto;\">N�mero requesitante</div></td>
		<td><div class=\"area\" style=\"width: auto;\">Nome do item</div></td>
		<td><div class=\"area\" style=\"width: auto;\">Nome do suporte</div></td>
		<td><div class=\"area\" style=\"width: auto;\">Tipo de item</div></td>
		<td><div class=\"area\" style=\"width: auto;\">Data requesiatado</div></td>
		<td><div class=\"area\" style=\"width: auto;\">Data levantado</div></td>
		<td><div class=\"area\" style=\"width: auto;\">Data apagado</div></td>
		<td><div class=\"area\" style=\"width: auto;\">Multa</div></td>
		</tr>
		";
		
		$color = "";
		
		$contador = 0;
		
		while( $row = mysql_fetch_row($req_exp) ){
			
			$contador++;
			
			$color = $contador % 2 == 0 ?"E0E0DF":"F0F0EF";
		
			
			echo "
			<tr style=\"background-color: #$color;height: 30px;\" >
			<td>$row[1]</td>
			<td>$row[2]</td>
			<td>$row[3]</td>
			<td>".seeNomElem( $row[8], false )."</td>
			<td>$row[5]</td>
			<td>$row[4]</td>
			<td>$row[7]</td>
			<td>$row[6]&euro;</td>
			</tr>
			";
			
		}
		
		echo "</table>";
		
	//N�mero de p�ginas totais
	$query_count_spam = floor( (mysql_result(
	$bd->submitQuery("Select Count(*) From `requesicao_log`"), 0, 0)) / 6 );
	
	if($query_count_spam > 0){
	//Divis�o do spam por p�ginas
	echo "<div class=\"listpags\" style=\"float: left;\">";
	
	//P�gina actual
	$pag_actual = floor ( $pagi / $pagf );

	if ( $pag_actual > 0 && isset($_GET['mod']) )
		echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a href=\"?elem=2&amp;accao=2&amp;mod=0&amp;pagi=" . ( $pagi -
			5 ) . "&amp;pagf=5\" title=\"Recuar para p�gina anterior\">&lt;</a></div>";
	else
		if ( $pag_actual > 0 )
			echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a href=\"?elem=2&amp;accao=2&amp;pagi=" . ( $pagi -
				5 ) . "&amp;pagf=5\" title=\"Recuar para p�gina anterior\">&lt;</a></div>";

	echo "<div class=\"pags\" style=\"margin-left: 0px;\">$pag_actual de $query_count_spam</div>";

	if ( $query_count_spam > 0 && isset($_GET['mod']) && $pag_actual < $query_count_spam )
		echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a href=\"?elem=2&amp;accao=2&amp;mod=0&amp;pagi=" . ( $pagi +
			5 ) . "&amp;pagf=5\" title=\"Avan�ar para a pr�xima p�gina\">&gt;</a></div>";
	else
		if ( $query_count_spam > 0 && $pag_actual < $query_count_spam )
			echo "
			<div class=\"pags\" style=\"margin-left: 0px;\"><a href=\"?elem=2&amp;accao=2&amp;pagi=" . ( $pagi +
			5 ) . "&amp;pagf=5\" title=\"Avan�ar para a pr�xima p�gina\">&gt;</a></div>";

	echo "</div>";
	
	}
		
		
		
	} else include_once("home.php");
 
 }
 
?>