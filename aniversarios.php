<?php

/**
 * Aqui é feita uma pesquisa a base de dados pelos utilizadores que fazem anos 
 * à data actual. É imprimido o primeiro nome o último nome e o estatuto 
 * a negrito.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

if( ! defined( 'IN_PHPAP' ) ) die();

/**
 * Incluir funcoes.php.
 *  
 */
include_once ( "funcoes.php" );

$estat = "";

$array_estat = "";

$array_estat[0] = "id_estatuto";

$array_estat[1] = "estatuto_nome";

$array_estat = listarCamposTabela( $array_estat, "estatuto", "" );

$array_result = "";

$array_result[0] = "registo_nome_pri";

$array_result[1] = "registo_nome_ult";

$array_result[2] = "estatuto_id_estatuto";

$data = date( "m-d" );

$array_result = listarCamposTabela( $array_result, "registo", " 
 Where `registo_data_nas` like '%$data' Order By `$array_result[0]`" );

$data = "";

if ( sizeof($array_result) - 1 > 0 )
{

	for ( $i = 2; $i < sizeof($array_result); $i += 3 )
	{

		for ( $e = 0; $e < sizeof($array_estat); $e += 2 )
		{

			if ( $array_estat[$e] == $array_result[$i] )
			{

				$estat = $array_estat[$e + 1];

				break;

			}

		}

		$data .= "<p><b>Nome: " . $array_result[$i - 2] . " " . $array_result[$i - 1] .
			"<br />Estatuto: " . $estat . "</b></p>";

	}

	echo $data;

}
else
{

	echo "Hoje não existem aniversariantes.";

}

?>