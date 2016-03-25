<?php

/**
 * Gerir todos os conteúdos submetidos com a gestão do spam. 
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */



if ( ! isset($_SESSION['user']) ) session_start();
	
validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
	
//Verificar se o documento currente esta incluido no index.php
if( !defined( 'IN_PHPAP' ) ) die();

/**
 * Incluir a classe para fazer a variável de acesso a base de dados bd.php.
 * 
 *   	
 */

include_once ( "bd.php" );
/**
 * Incluir o ficheiro de configuração config.php.
 *  
 */
include_once ( "config.php" );

/**
 * Incluir o ficheiro com as funçoes básicas funcoes.php
 *  
 */
include_once ( "funcoes.php" );

if ( $_SESSION['estat_carac'][1] || $_SESSION['estat_carac'][0] )
{
	
	
	
	$bd = new bd();

	$bd->setLigar( $host, $user_bd, $pass_bd, $db );

	if ( isset($_POST['mrcspam']) )
	{
		//Retirar os valores do array
		$spam_values = array_values( $_POST['mrcspam'] );

		//Aplicar a função clearGarbadge a todos os elementos do array
		//array_walk( $spam_values, clearGarbadge );
		
		for($i = 0; $i < $i;$i++){
			
			$spam_values[$i] = clearGarbadge( $spam_values[$i], false, false);
			
		}
		
		$spam_values = " IN ('" . implode( "','", $spam_values ) . "') ";
		
		if ( $_POST['mod'] == 0 )
		{
				if($_SESSION['estat_carac'][1])
					$spam_values = "Delete From `post` 
						Where `id_post` ".$spam_values." And `post_prin` = 0 ";

		}
		else
			if ( $_POST['mod'] == 1 )
			{
				if($_SESSION['estat_carac'][1])
					$spam_values = "Update `post` Set `post_activo` = 1 
						Where `id_post` ".$spam_values." And `post_prin` = 0 ";

			}
			else 
				if ( $_POST['mod'] == 2 )
				{
					
				if($_SESSION['estat_carac'][0])
					$spam_values = "Delete From `post` 
						Where `topico_id_topico` ".$spam_values." And `post_prin` = 1 ";

				}
			else
				if ( $_POST['mod'] == 3 )
				{
				
				
				if($_SESSION['estat_carac'][0])
					$spam_values = "Update `post` Set `post_activo` = 1 
						Where `topico_id_topico` ".$spam_values." And `post_prin` = 1 ";
						
				} 
			else
				{
					
					die();
					
				} 
		
		//echo $spam_values;
						
		$spam_values = $bd->submitQuery( $spam_values );
		
		echo $spam_values ? "9" : 
		rawurlencode( "Não é possível de momento levar esta acção a cabo :X" );
		
		die();
		
	}

	if( ! defined( 'IN_PHPAP' ) ) die();
	
	echo "<div class=\"local\"><a href=\"?elem=2\" title=\"A tua área\">" 
	. $_SESSION['estat_nome'] . "</a> » Gerir spam</div>";

	if ( $_SESSION['estat_carac'][1] && ! isset($_GET["mod"]) )
	{
		//Post
		$carac[0] = "";
		$carac[1] = 1;
		$carac[2] = "respostas";
		$carac[3] = "apgspammarc";
		$carac[4] = "repspammarc";
		$carac[5] = 3;
		$carac[6] = "marcadas";
		$carac[7] = "uma";
	}
	else
		if ( $_SESSION['estat_carac'][0] && isset($_GET["mod"]) )
		{
			//Tópico
			$carac[0] = " checked=\"checked\" ";
			$carac[1] = 0;
			$carac[2] = "tópicos";
			$carac[3] = "apgspammarctop";
			$carac[4] = "repspammarctop";
			$carac[5] = 4;
			$carac[6] = "marcados";
			$carac[7] = "um";
		}
	else
		if($_SESSION['estat_carac'][0])
		{
			
			//Tópico
			$carac[0] = " checked=\"checked\" ";
			$carac[1] = 0;
			$carac[2] = "tópicos";
			$carac[3] = "apgspammarctop";
			$carac[4] = "repspammarctop";
			$carac[5] = 4;
			$carac[7] = "";
		}
	else
		if($_SESSION['estat_carac'][1])
		{
			
			//Post
			$carac[0] = "";
			$carac[1] = 1;
			$carac[2] = "posts";
			$carac[3] = "apgspammarc";
			$carac[4] = "repspammarc";
			$carac[5] = 3;
			$carac[7] = " `post_activo` ";
		}

	$pagi = clearGarbadge( $_GET['pagi'], false, false);

	$pagf = clearGarbadge( $_GET['pagf'], false, false);

	if ( ! is_numeric($pagi) || ! is_numeric($pagf) || $pagi < 0 || $pagf < 0 )
	{

		$pagi = 0;
		
		$pagf = 5;

	}
	
	//Limit $pagi,$pagf
	$query = $bd->submitQuery( "Select `registo_id_registo`,`post_titulo`,`post_texto`,`id_post`
	,`topico_id_topico` ,`topico_area_id_area` From `post` 
	Where `post_activo` = 0 And `post_prin` <> $carac[1] 
	Order By `id_post` Desc Limit $pagi,$pagf" );
	
	$querycount = $bd->submitQuery( "Select 
	`registo_id_registo`,`post_titulo`,`post_texto`,`id_post` 
	From `post` Where `post_activo` = 0 And `post_prin` <> $carac[1]" );
	
	$count = mysql_num_rows( $querycount );
		
	echo "<div class=\"gestaofixmain gestaopremain\" style=\"margin-top: 0px;\">
	<div class=\"tituloadmin\">Listar, repor &amp; apagar $carac[2] spam</div>
	<form id=\"spamadmin\">
	<table width=\"100%\">
	<tr>
	<td colspan=\"3\">
	";
	
	if ( $count > 1 )
		echo "Existem <b>" . $count . "</b> $carac[2] $carac[6] como spam.";
	else
		if ( $count == 1 )
			echo "Existe $carac[7] "
			.substr($carac[2],0,strlen($carac[2])-1)." "
			.substr($carac[6],0,strlen($carac[6])-1)." como spam.";
		else
			echo "Não existem $carac[2] $carac[6] como spam.";

	echo "</td>";
	
	if($_SESSION['estat_carac'][1] && $_SESSION['estat_carac'][0])	
		echo "<td>
	<label for=\"changetospam\">
	Mostrar tópicos<br />
	<input type=\"checkbox\" $carac[0] id=\"changetospam\" />
	</label>
	</td>";
	
	
	
	echo "
	</tr>
	<tr>
		<td style=\"width: 100px;\">Autor</td>
		<td>Título</td>
		<td>Texto</td>
		<td>Marcar</td>
	</tr>";

	for ( $i = 0; $i < mysql_numrows($query); $i++ )
	{

		$nom = $bd->submitQuery( "Select `registo_nick` From `registo` Where `id_registo` = " .
		mysql_result($query, $i, 0) );

		mysql_numrows( $nom ) == 1 ? $nom = mysql_result( $nom, 0, 0 ) : $nom =
			"<b><em>Indefinido
		</em></b>";


		$carac[6] = $carac[1] ? "<a href=\"?elem=8&amp;area=" 
		. mysql_result( $query, $i,5 ) . "&amp;topico=" . mysql_result( $query, $i, 4 ) 
		. "\" title=\"Ir para \" > Ir para o tópico deste post</a>" : "<br />";


		echo "<tr id=\"rowspam$i\">
		<td><a href=\"?elem=10&perfil=" . mysql_result( $query, $i, 0 ) . "\">
		$nom</a></td>
		<td>$carac[6]
		<textarea
		disabled=\"disabled\" style=\"width: 150px;\">" . mysql_result( $query, $i, 1 ) .
			"</textarea></td>
		<td>
		<br />
		<textarea disabled=\"disabled\" style=\"width: 200px;\">" . mysql_result( $query,
			$i, 2 ) . "</textarea></td>
		<td>
		<input type=\"checkbox\" class=\"marcspamtoh\" value=\"" 
		. mysql_result( $query, $i, $carac[5] ) . "\" id=\"$i\" name=\"mrcspam[$i]\" />
		</td>
		</tr>";

	}

	echo "
	<tr>
	<td></td>
	<td></td>
	<td>
	</td>
	<td>
	<input type=\"button\" style=\"width: 110px;\" id=\"$carac[3]\" class=\"font11 forms\" 
	value=\"Apagar Marcadas\" />
	</td>
	</tr>
	
	<tr>
	<td></td>
	<td></td>
	<td>
	</td>
	<td>
	<input type=\"button\" style=\"width: 110px;\" id=\"$carac[4]\"
	class=\"font11 forms\" value=\"Repor Marcadas\" />
	</td>
	</tr>
	
	<tr>
	<td></td>
	<td></td>
	<td></td>
	<td>";
	
	//Número de páginas totais
	$query_count_spam = floor( (mysql_result(
	$bd->submitQuery("Select Count(*) From `post` Where `post_activo` = 0 And 
	`post_prin` <> $carac[1] "), 0, 0)) / 6 );
	
	if($query_count_spam > 0){
	//Divisão do spam por páginas
	echo "<div class=\"listpags\" style=\"float: left;\">";
	
	//Página actual
	$pag_actual = floor ( $pagi / $pagf );

	if ( $pag_actual > 0 && isset($_GET['mod']) )
		echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a href=\"?elem=2&amp;accao=14&amp;mod=0&amp;pagi=" . ( $pagi -
			5 ) . "&amp;pagf=5\" title=\"Recuar para página anterior\">&lt;</a></div>";
	else
		if ( $pag_actual > 0 )
			echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a href=\"?elem=2&amp;accao=14&amp;pagi=" . ( $pagi -
				5 ) . "&amp;pagf=5\" title=\"Recuar para página anterior\">&lt;</a></div>";

	echo "<div class=\"pags\" style=\"margin-left: 0px;\">$pag_actual de $query_count_spam</div>";

	if ( $query_count_spam > 0 && isset($_GET['mod']) && $pag_actual < $query_count_spam )
		echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a href=\"?elem=2&amp;accao=14&amp;mod=0&amp;pagi=" . ( $pagi +
			5 ) . "&amp;pagf=5\" title=\"Avançar para a próxima página\">&gt;</a></div>";
	else
		if ( $query_count_spam > 0 && $pag_actual < $query_count_spam )
			echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a href=\"?elem=2&amp;accao=14&amp;pagi=" . ( $pagi +
				5 ) . "&amp;pagf=5\" title=\"Avançar para a próxima página\">&gt;</a></div>";

	echo "</div>";
	
	}
	
	echo "</td>
	</tr>
	
	</table>
	</form>
	</div>";

}
	


?>