<?php

/**
 * 
 * Fun��o repons�vel por ainforma��o na barra de estado. Com o ID <code>navtop</code>. 
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 */

if( ! defined( 'IN_PHPAP' ) ) die();

if ( ! isset($_SESSION['user']) )
	session_start();
	
/**
 * Incluir a classe para fazer a vari�vel de acesso a base de dados bd.php.
 * 
 *   	
 */
include_once ( "bd.php" );

/**
 * Incluir o ficheiro de configura��o config.php.
 *  
 */
include_once ( "config.php" );

if ( $frases )
{

	$query = $bd->submitQuery( "Select `frase_texto` from `frase` ORDER BY RAND() LIMIT 5" );

	if ( mysql_numrows($query) > 0 )
		echo mysql_result( $query, 0 );

	else
		echo "<i>(Por favor insira algumas frases para que possam ser mostradas aleatoriamente)</i>";


}
else
{


	if ( isset($_SESSION['user']) )
	{


		$hora = date( "H" );

		switch ( $hora )
		{

			case ( $hora > 6 ) && ( $hora < 12 ):
				$hora = "Bom dia";
				break;

			case ( $hora > 11 ) && ( $hora < 20 ):
				$hora = "Boa tarde";
				break;

			case ( $hora > 19 ) || ( $hora < 7 ):
				$hora = "Boa noite";
				break;

		}

		echo "$hora, " . $_SESSION['user'];

	}
	else
		echo "Ol� visitante, bem-vindo ao s�te da biblioteca E�a de Queir�s";


}



?>