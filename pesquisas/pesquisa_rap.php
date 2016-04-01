<?php
/**
 * Aqui s�o feitas as pesquisas r�pidas.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 */

/**
 * Incluir o ficheiro de configura��o config.php.
 *  
 */
 include_once ( "config.php" );
 
/**
 * Incluir a classe para fazer a vari�vel de acesso a base de dados bd.php.
 *   	
 */
 include_once ( "bd.php" );

/**
 * Incluir a classe pesq_rap no ficheiro pesq_rap.php
 *  
 */ 
 include_once ( "pesq_rap.php");
 
 if( ! defined( 'IN_PHPAP' ) ) die();
 
 
 if( $autorver ){
	
	//Mostrar a interface da pesquisa r�pida
	
	if(isset($_GET['rapipor']) && isset($_GET['rapitext'])){
		
		$params[0] = clearGarbadge($_GET['rapipor'] , false, false);
		
		$params[1] = clearGarbadge($_GET['rapitext'] , false, false);
		
		switch($params[0]){
			
			case 1: $params[2] = "T�picos";
			
					break;
			case 2: $params[2] = "Posts";
			
			        break;
			case 3: $params[2] = "Nomes utilizadores";
			
					break;
			default: $params[2] = "Tudo";
					 break;
		}
		
		echo "<div class=\"local\">Pesquisa r�pida :: $params[2] :: Resultados</div>";
		
		$psq_rap = new pesq_rap( "$params[2]", $host, $user_bd, $pass_bd, $db);
		
		$bd = new bd();
		
		$bd->setLigar( $host, $user_bd, $pass_bd, $db );
		
		$pagi = clearGarbadge( $_GET['pagi'], false, false);

		$pagf = clearGarbadge( $_GET['pagf'], false, false);

		if ( ! is_numeric($pagi) || ! is_numeric($pagf) || $pagi < 0 || $pagf < 0 )
		{

			$pagi = 0;
		
			$pagf = 5;
		
		}
		
		$adi = " Limit $pagi,$pagf";
		
		switch($params[0]){
			
			case 1: $params[3] = $psq_rap->psqTopiNom($params[1], $adi);
					$param[4] = "?elem=8&id_pesq=";
					break;
			case 2: $params[3] = $psq_rap->psqPostNom($params[1], $adi);
					$param[4] = "?elem=8&id_pesq=";
					break;
			case 3: $params[3] = $psq_rap->psqUtilNick($params[1], $adi);
					$param[4] = "?elem=10&perfil=";
					break;
			default: $params[3] = "Tudo";
					 break;
			
		}
		
		echo "<table border=\"0\" width=\"590\" style=\"border: 1px solid grey;\">
		<tr>
		<td>Encotradas " . ( 
		mysql_numrows($bd->submitQuery(
		preg_replace( "/Limit [0-9][0-9]*,[0-9][0-9]*$/","",$psq_rap->getLastQuery())
		))) . " entradas.</td>
		</tr>";
		
		for($i = 0; $i < ( floor( count($params[3])/2) * 2 );$i++){
			
			/*if ($params[0] == 1 || $params[0] == 2)
			{
				
				$query = $bd->submitQuery("Select `area_id_area` From `topico` Where `id_topico` 
				Like '" . $params[3][$i] . "' COLLATE latin1_general_cs ");
				
				if(mysql_numrows($query) == 1)	
					$param[4] = "?elem=8&amp;area=".mysql_result($query,0,0)."&amp;topico=";	
				else
					continue;
					
			}*/
			
			
			
			echo "<tr>
					<td><a href=\"$param[4]" . $params[3][$i] . "\">";
					
					$i++;
					
			echo $params[3][$i] . "</a></td>
				</tr>";
				
		}
		
		
		
		echo "</table>";
		
	$query_count_spam = floor(
	mysql_numrows($bd->submitQuery(
	preg_replace( "/Limit [0-9][0-9]*,[0-9][0-9]*$/","",$psq_rap->getLastQuery() ) ) 
	) / 6 );
	
	if( $query_count_spam > 0 ){
	//Divis�o do spam por p�ginas
	echo "<div class=\"listpags\" style=\"float: left;\">";
	
	//N�mero de p�ginas totais
	
		//P�gina actual
		$pag_actual = floor ( $pagi / $pagf );
		if ( $pag_actual > 0 )
		echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a href=\"?elem=12&amp;pagi=" 
		. ( $pagi - 5 ) . "&amp;pagf=5&amp;rapitext=".$_GET['rapitext']."&amp;rapipor="
		.$_GET['rapipor']."\" title=\"Recuar para p�gina anterior\">&lt;</a></div>";

	echo "<div class=\"pags\" style=\"margin-left: 0px;\">$pag_actual de $query_count_spam</div>";

		if ( $query_count_spam > 0 && $pag_actual < $query_count_spam )
		echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a href=\"?elem=12&amp;pagi=" 
		. ( $pagi + 5 ) . "&amp;pagf=5&amp;rapitext=".$_GET['rapitext']."&amp;rapipor="
		.$_GET['rapipor']."\" title=\"Avan�ar para a pr�xima p�gina\">&gt;</a></div>";

	echo "</div>";

		
	}
		
	mysql_freeresult($query);
		
	} 
	else
	{
	
	
	if( defined( 'INCLUDE_PHPAP_PESRAP' ) ) { include_once( "home.php" ); }


	}	
	
} else { include_once( "home.php" ); }

?>