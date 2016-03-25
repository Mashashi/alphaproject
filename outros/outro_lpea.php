<?php
/**
 * Listar os outros.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */


session_start();



	
if( $_SESSION['estat_carac'][8] ){

if( isset( $_POST['apg_outro'] ) ){
	/**
	 * Incluir as funções nativas dos outros.
	 */
	include_once( "outro_funcoes.php" );
	
	$id_outro = array_values( (array) $_POST['apg_outro'] );
	
	$bd = ligarBD();
	
	foreach($id_outro as $id) apgOutro( $id );	
	
	if( isset($_POST['flagedit']) ){
		
		echo "O item outro foi apagado com sucesso 
		<img src=\"imagens/b_certo2.png\" alt=\"[Sucesso]\" />";
		
	}
	
} else if( defined('IN_PHPAP') ){
	
	$bd = ligarBD();
	
	
	/*Pesquisa*/
	echo "<div style=\"float:right;\">
	
	<form method=\"post\">
	<a href=\"".$_SERVER['PHP_SELF']."?elem=2&accao=8&amp;opcao=2&amp;cl=1\">
	Listar todos os outros</a>
	<input class=\"forms\" type=\"text\" maxlength=\"100\" value=\"\" name=\"psqoutro\" />
	<select name=\"psqoutroflag\">
		<option value=\"0\">Nome</option>
		<option value=\"1\">Etiqueta</option>
	</select>
	<input class=\"forms\" type=\"submit\" value=\"Go\" />
	</form>
	</div>";
	

	if( is_numeric($_GET['pagi']) && is_numeric($_GET['pagf']) 
	&& $_GET['pagf'] > 0 && $_GET['pagi'] > -1 ){
		
		$pagi = $_GET['pagi'];
		$pagf = $_GET['pagf'];
		
	} else {
		
		$pagi = 0;
		$pagf = 5;
		
	}
	
	
	
	if( isset($_POST['psqoutro']) && isset($_POST['psqoutroflag']) ){
		
		if ( strlen($_POST['psqoutro']) > 0 ){
			
			if ( $_POST['psqoutroflag' ] == 1 ) {
				
				$_SESSION['psqqueryoutr'] = 
				" Where `outro_etiqueta` Like '%"
				.clearGarbadge($_POST['psqoutro'], false, false)."%' ";
					
			} else {
			
				$_SESSION['psqqueryoutr'] = 
				" Where `outro_nome` Like '%"
				.clearGarbadge($_POST['psqoutro'], false, false)."%' ";
			}
		
		}
	}
	
	if( isset($_GET["cl"]) && $_SESSION['psqqueryoutr'] != "" 
	&& !isset($_POST['psqoutro']) ) $_SESSION['psqqueryoutr'] = "";
	
	$qoutro = 
	$bd->submitQuery("SELECT * FROM `outro` ".$_SESSION['psqqueryoutr']." Limit $pagi,$pagf");
	
	echo "<table border=\"0\" width=\"580\">
	<tr>
	<td><div class=\"area\" style=\"width: auto;\"><b>Marca</b></div></td>
	<td><div class=\"area\" style=\"width: auto;\"><b>Nome</b></div></td>
	<td><div class=\"area\" style=\"width: auto;\"><b>Etiqueta</b></div></td>
	<td><div class=\"area\" style=\"width: auto;\"><b>Ano</b></div></td>
	</tr>";
	
		$c = 0;
		
		$cont = 0;
		/*
		$fla = "E0E0DF";
		$fla = "FFFFFF";
		*/
		
		for($i = 0; $i < mysql_numrows($qoutro); $i++){
			
			if( $c != mysql_result( $qoutro,$i,0 ) ){
				
				if($i%2==0)
					$fla = "E0E0DF";
				else
					$fla = "FFFFFF";
					
				$c = mysql_result($qoutro,$i,0);
					
				echo "
				<tr id=\"trf".mysql_result($qoutro,$i,0)."\" style=\"background-color: #$fla;\">
				<td>
				<input type=\"checkbox\" 
				value=\"".mysql_result($qoutro,$i,0)."\" class=\"mark\" id=\""
				.mysql_result($qoutro,$i,0)."\" /></td>
				<td><a href=\"".$_SERVER['PHP_SELF']."?elem=2&amp;accao=9&amp;outro="
				.mysql_result($qoutro,$i,0)."\">"
				.mysql_result($qoutro,$i,2)."</a></td><td>".mysql_result($qoutro,$i,6)."</td>
				<td>".mysql_result($qoutro,$i,4)."</td>
				</tr>";
				
			} 
			
			$cont++;	
		
		}
	
		
	echo "<tr><td><input type=\"button\" class=\"forms\" name=\"apg_outro\" id=\"apg_outro\"
	value=\"Apagar\" />
	</td><td></td>
	<td></td><td></td><td></td></tr></table>";
	
	
	//Número de páginas totais
	$query_count_spam =  
	floor( 
	(mysql_result(
	$bd->submitQuery("Select Count(*) From `outro` ".$_SESSION['psqqueryoutr']), 0, 0)) / 6 );
	
	if($query_count_spam){
	//Divisão do spam por páginas
	echo "<div class=\"listpags\" style=\"float: left;margin-left: 2px;\">";
	

	//Página actual
	$pag_actual = ( $pagi / $pagf );
	
	if ( $pag_actual > 0 && isset($_GET['mod']) )
		echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a 
		href=\"?elem=2&amp;accao=8&amp;opcao=2&amp;pagi=" . ( $pagi -
			5 ) . "&amp;pagf=5\" title=\"Recuar para página anterior\">&lt;</a></div>";
	else
		if ( $pag_actual > 0 )
			echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a 
			href=\"?elem=2&amp;accao=8&amp;opcao=2&amp;pagi=" . ( $pagi - 5 ) 
			. "&amp;pagf=5\" title=\"Recuar para página anterior\">&lt;</a></div>";

	echo "<div class=\"pags\" style=\"margin-left: 0px;\">$pag_actual de $query_count_spam</div>";

	if ( $query_count_spam > 0 && isset($_GET['mod']) && $pag_actual < $query_count_spam )
	echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a 
	href=\"?elem=2&amp;accao=8&amp;opcao=2&amp;pagi=" 
	. ( $pagi + 5 ) . "&amp;pagf=5\" title=\"Avançar para a próxima página\">&gt;</a></div>";
	else
		if ( $query_count_spam > 0 && $pag_actual < $query_count_spam )
			echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a 
			href=\"?elem=2&amp;accao=8&amp;opcao=2&amp;pagi=" . ( $pagi + 5 ) 
			. "&amp;pagf=5\" title=\"Avançar para a próxima página\">&gt;</a></div>";

	echo "</div>";
	
	}
	
}

} else include_once(defined('IN_PHPAP') ? "home.php":"../home.php");

?>