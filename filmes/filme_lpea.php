<?
/**
 * Listar os filmes.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

	/**
	 * Incluir as funções nativas dos filmes.
	 */
	include_once("filme_funcoes.php");
	
session_start();

validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );

if( $_SESSION['estat_carac'][6] ){

if( isset( $_POST['apg_fil'] ) ){
	
	
	if( !isset($_POST['flagedit']) ) 
		$id_film = array_values( $_POST['apg_fil'] );
	else 
		$id_film = $_POST['apg_fil'];
	
	$bd = ligarBD();
	
	if( !isset($_POST['flagedit']) ){
		
		foreach($id_film as $id) apgFilme( $id );
		
	} else apgFilme( $id_film );
	
	if( isset($_POST['flagedit']) ){
		
		echo "O filme foi apagado com sucesso 
		<img src=\"imagens/b_certo2.png\" alt=\"[Sucesso]\" />";
		
	}
	
	
	
} else if( defined('IN_PHPAP') ){
	//Se estiver definido no index.php
	
	$bd = ligarBD();
	
	/*Pesquisa*/
	echo "<div style=\"float:right;\">
	
	<form method=\"post\">
	<a href=\"".$_SERVER['PHP_SELF']."?elem=2&accao=4&opcao=2&cl=1\">Listar todos os filmes</a>
	<input class=\"forms\" type=\"text\" value=\"\" maxlength=\"100\" name=\"psqfilme\" />
	<select name=\"psqfilmeflag\">
		<option value=\"0\">Nome</option>
		<option value=\"1\">Etiqueta</option>
	</select>
	<input class=\"forms\" type=\"submit\" value=\"Go\" />
	</form>
	</div>";
	
	/*if( isset( $_POST['psqfilme'] ) && isset( $_POST['psqfilmeflag'] ) ){
		
		include_once("funcoes_avan.php");
		
		include_once("pesquisas/pesq_rap.php");
		
		$psqfilme = new pesq_rap(  "Filme", $host , $user_bd , $pass_bd, $db);
		
		print_r(  
		$psqfilme->psqFilme( clearGarbadge( $_POST['psqfilme'], false, false), $_POST['psqfilme'] ) 
		);
		
	}*/
	
	/*Esta query vai ser necessária para apresentar o gênero do filme
	SELECT * FROM `filme` As t1, `realizador_filme` As t2
	WHERE t1.geral_id_geral = t2.filme_geral_id_geral Order By `geral_id_geral`*/
	
	if( is_numeric($_GET['pagi']) && is_numeric($_GET['pagf']) 
	&& $_GET['pagf'] > 0 && $_GET['pagi'] > -1 ){
		
		$pagi = $_GET['pagi'];
		$pagf = $_GET['pagf'];
		
	} else {
		
		$pagi = 0;
		$pagf = 5;
		
	}
	
	
	
	if( isset($_POST['psqfilme']) && isset($_POST['psqfilmeflag']) ){
		
		//$_SESSION['psqquery'] = "";
		
		
		
		if ( strlen($_POST['psqfilme']) > 0 ){
			
			if ( $_POST['psqfilmeflag' ] == 1 ) {
			//echo "1-->".$_POST['psqfilme'];	
				$_SESSION['psqquery'] = 
				" Where `filme_etiqueta` Like '%".clearGarbadge($_POST['psqfilme'], false, false)."%' ";
					
			} else {
			
				$_SESSION['psqquery'] = 
				" Where `filme_nome` Like '%".clearGarbadge($_POST['psqfilme'], false, false)."%' ";
				//echo "2-->".$_SESSION['psqquery'];
			}
		
		}
	}
	
	if( isset($_GET["cl"]) && $_SESSION['psqquery'] != "" 
	&& !isset($_POST['psqfilme']) ) $_SESSION['psqquery'] = "";

	$qfilme = 
	$bd->submitQuery("SELECT * FROM `filme` ".$_SESSION['psqquery']." Limit $pagi,$pagf");
	
	//echo "SELECT * FROM `filme` ".$_SESSION['psqquery']." Limit $pagi,$pagf";
	
	echo "<table border=\"0\" width=\"578\">
	<tr>
	<td><div class=\"area\" style=\"width: auto;\"><b>Marca</b></div></td>
	<td><div class=\"area\" style=\"width: auto;\"><b>Nome</b></div></td>
	<td><div class=\"area\" style=\"width: auto;\"><b>Etiqueta</b></div></td>
	<td><div class=\"area\" style=\"width: auto;\"><b>Ano</b></div></td>
	<td><div class=\"area\" style=\"width: auto;\"><b>Gênero</b></div></td>
	</tr>";
	
		$c = 0;
		
		$cont = 0;
		
		for($i = 0; $i < mysql_numrows($qfilme); $i++){
			
			if( $c != mysql_result( $qfilme,$i,0 ) ){
				
				if($i%2==0)
					$fla = "E0E0DF";
				else
					$fla = "FFFFFF";
					
				$c = mysql_result($qfilme,$i,0);
				
				$fgen = $bd->submitQuery("Select `genero_filme_nome` 
				From `genero_filme` 
				Where `id_genero_filme` = '".mysql_result($qfilme,$i,1)."'");
				
				if( mysql_numrows($fgen) == 1 )
					$fgen = mysql_result($fgen ,0 ,0 );
				else
					$fgen = "";
					
				echo "
				<tr id=\"trf".mysql_result($qfilme,$i,0)."\" style=\"background-color: #$fla;\">
				<td>
				<input type=\"checkbox\" 
				value=\"".mysql_result($qfilme,$i,0)."\" class=\"mark\" id=\""
				.mysql_result($qfilme,$i,0)."\" /></td>
				<td><a href=\"".$_SERVER['PHP_SELF']."?elem=2&amp;accao=5&amp;filme="
				.mysql_result($qfilme,$i,0)."\" 
				title=\"".mysql_result($qfilme,$i,5)."\">"
				.mysql_result($qfilme,$i,4)."</a></td><td>".mysql_result($qfilme,$i,3)."</td>
				<td>".mysql_result($qfilme,$i,7)."</td>
				<td>$fgen</td>
				</tr>";
				
			} 
			
			$cont++;	
		
		}
	
		
	echo "<tr><td><input type=\"button\" class=\"forms\" name=\"apg_film\" id=\"apg_film\"
	value=\"Apagar\" />
	</td><td></td>
	<td></td><td></td><td></td></tr></table>";
	
	
	//Número de páginas totais
	$query_count_spam =  
	floor( 
	(mysql_result(
	$bd->submitQuery("Select Count(*) From `filme` ".$_SESSION['psqquery']), 0, 0)) / 6 );
	
	
	
	if($query_count_spam){
	//Divisão do spam por páginas
	echo "<div class=\"listpags\" style=\"float: left;margin-left: 2px;\">";
	

	//Página actual
	$pag_actual = ( $pagi / $pagf );
	
	if ( $pag_actual > 0 && isset($_GET['mod']) )
		echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a 
		href=\"?elem=2&amp;accao=4&amp;opcao=2&amp;pagi=" . ( $pagi -
			5 ) . "&amp;pagf=5\" title=\"Recuar para página anterior\">&lt;</a></div>";
	else
		if ( $pag_actual > 0 )
			echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a 
			href=\"?elem=2&amp;accao=4&amp;opcao=2&amp;pagi=" . ( $pagi - 5 ) 
			. "&amp;pagf=5\" title=\"Recuar para página anterior\">&lt;</a></div>";

	echo "<div class=\"pags\" style=\"margin-left: 0px;\">$pag_actual de $query_count_spam</div>";

	if ( $query_count_spam > 0 && isset($_GET['mod']) && $pag_actual < $query_count_spam )
	echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a 
	href=\"?elem=2&amp;accao=4&amp;opcao=2&amp;pagi=" 
	. ( $pagi + 5 ) . "&amp;pagf=5\" title=\"Avançar para a próxima página\">&gt;</a></div>";
	else
		if ( $query_count_spam > 0 && $pag_actual < $query_count_spam )
			echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a 
			href=\"?elem=2&amp;accao=4&amp;opcao=2&amp;pagi=" . ( $pagi + 5 ) 
			. "&amp;pagf=5\" title=\"Avançar para a próxima página\">&gt;</a></div>";

	echo "</div>";
	
	}
	
}

}
?>