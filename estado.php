<?php

/**
 * 
 * Função reponsável por ainformação na barra de estado. Com o ID <code>navtop</code>. 
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

if( ! defined( 'IN_PHPAP' ) ) die();

if ( ! isset($_SESSION['user']) )
	session_start();
	
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
		echo "Olá visitante, bem-vindo ao síte da biblioteca Eça de Queirós";


}



?>