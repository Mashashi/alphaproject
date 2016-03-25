<?php
/**
 * Listar os álbuns.
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
	include_once( "album_funcoes.php" );

session_start();

validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
	
if( $_SESSION['estat_carac'][7] ){

if( isset( $_POST['apg_alb'] ) ){
	
	
	$id_alb = array_values( (array) $_POST['apg_alb'] );
	
	$bd = ligarBD();
	
	//print_r($id_alb);
	
	foreach($id_alb as $id){ 
		//echo $id;
		apgAlbum( $id ); 
		
	}
	
	if(isset($_POST['flagedit'])){
		
		echo "O álbum foi apagado com sucesso 
		<img src=\"imagens/b_certo2.png\" alt=\"[Sucesso]\" />";
		
	}
	
} else if( defined('IN_PHPAP') ){
	
	$bd = ligarBD();
	
	
	/*Pesquisa*/
	echo "<div style=\"float:right;\">
	
	<form method=\"post\">
	<a href=\"".$_SERVER['PHP_SELF']."?elem=2&accao=6&amp;opcao=2&amp;cl=1\">
	Listar todos os álbuns</a>
	<input class=\"forms\" type=\"text\" maxlength=\"100\" name=\"psqalbum\" />
	<select name=\"psqalbumflag\">
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
	
	
	
	if( isset($_POST['psqalbum']) && isset($_POST['psqalbumflag']) ){
		
		if ( strlen($_POST['psqalbum']) > 0 ){
			
			if ( $_POST['psqalbumflag' ] == 1 ) {	
				$_SESSION['psqqueryalb'] = 
				" Where `album_etiqueta` Like '%"
				.clearGarbadge($_POST['psqalbum'], false, false)."%' ";
					
			} else {
			
				$_SESSION['psqqueryalb'] = 
				" Where `album_nome` Like '%"
				.clearGarbadge($_POST['psqalbum'], false, false)."%' ";
			}
		
		}
	}
	
	if( isset($_GET["cl"]) && $_SESSION['psqqueryalb'] != "" 
	&& !isset($_POST['psqalbum']) ) $_SESSION['psqqueryalb'] = "";
	
	$qalbum = 
	$bd->submitQuery("SELECT * FROM `album` ".$_SESSION['psqqueryalb']." Limit $pagi,$pagf");
	
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
		
		for($i = 0; $i < mysql_numrows($qalbum); $i++){
			
			if( $c != mysql_result( $qalbum,$i,0 ) ){
				
				if($i%2==0)
					$fla = "E0E0DF";
				else
					$fla = "FFFFFF";
					
				$c = mysql_result($qalbum,$i,0);
				
				/*$fgen = $bd->submitQuery("Select `genero_filme_nome` 
				From `genero_filme` 
				Where `id_genero_filme` = '".mysql_result($qalbum,$i,1)."'");
				
				if( mysql_numrows($fgen) == 1 )
					$fgen = mysql_result($fgen ,0 ,0 );
				else
					$fgen = "";*/
					
				echo "
				<tr id=\"trf".mysql_result($qalbum,$i,0)."\" style=\"background-color: #$fla;\">
				<td>
				<input type=\"checkbox\" 
				value=\"".mysql_result($qalbum,$i,0)."\" class=\"mark\" id=\""
				.mysql_result($qalbum,$i,0)."\" /></td>
				<td><a href=\"".$_SERVER['PHP_SELF']."?elem=2&amp;accao=7&amp;album="
				.mysql_result($qalbum,$i,0)."\">"
				.mysql_result($qalbum,$i,2)."</a></td><td>".mysql_result($qalbum,$i,1)."</td>
				<td>".mysql_result($qalbum,$i,4)."</td>
				</tr>";
				
			} 
			
			$cont++;	
		
		}
	
		
	echo "<tr><td><input type=\"button\" class=\"forms\" name=\"apg_alb\" id=\"apg_alb\"
	value=\"Apagar\" />
	</td><td></td>
	<td></td><td></td><td></td></tr></table>";
	
	
	//Número de páginas totais
	$query_count_spam =  
	floor( 
	(mysql_result(
	$bd->submitQuery("Select Count(*) From `album` ".$_SESSION['psqqueryalb']), 0, 0)) / 6 );
	
	if($query_count_spam){
	//Divisão do spam por páginas
	echo "<div class=\"listpags\" style=\"float: left;margin-left: 2px;\">";
	

	//Página actual
	$pag_actual = ( $pagi / $pagf );
	
	if ( $pag_actual > 0 && isset($_GET['mod']) )
		echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a 
		href=\"?elem=2&amp;accao=6&amp;opcao=2&amp;pagi=" . ( $pagi -
			5 ) . "&amp;pagf=5\" title=\"Recuar para página anterior\">&lt;</a></div>";
	else
		if ( $pag_actual > 0 )
			echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a 
			href=\"?elem=2&amp;accao=6&amp;opcao=2&amp;pagi=" . ( $pagi - 5 ) 
			. "&amp;pagf=5\" title=\"Recuar para página anterior\">&lt;</a></div>";

	echo "<div class=\"pags\" style=\"margin-left: 0px;\">$pag_actual de $query_count_spam</div>";

	if ( $query_count_spam > 0 && isset($_GET['mod']) && $pag_actual < $query_count_spam )
	echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a 
	href=\"?elem=2&amp;accao=6&amp;opcao=2&amp;pagi=" 
	. ( $pagi + 5 ) . "&amp;pagf=5\" title=\"Avançar para a próxima página\">&gt;</a></div>";
	else
		if ( $query_count_spam > 0 && $pag_actual < $query_count_spam )
			echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a 
			href=\"?elem=2&amp;accao=6&amp;opcao=2&amp;pagi=" . ( $pagi + 5 ) 
			. "&amp;pagf=5\" title=\"Avançar para a próxima página\">&gt;</a></div>";

	echo "</div>";
	
	}
	
}

} else include_once(defined('IN_PHPAP') ? "home.php":"../home.php");

?>