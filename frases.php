<?php

/**
 * Toda a gestão de frases que são exibidas aleatoriamente na barra de estado 
 * é aqui feita.
 * A interface, encontra-se em funcoes_avan.php na função printGerirFrases().
 * Esta ficheiro apenas responde a pedidos Ajax.  
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

/**
 * Incluir fucoes_avan.php.
 *  
 */
include_once ( "funcoes_avan.php" );

if ( ! isset($_SESSION['user']) )
	session_start();


if ( isset($_SESSION['estat_carac'][12]) && $_SESSION['estat_carac'][12] )
{

	$bd = ligarBD();

	if ( isset($_POST['newfraserand']) )
	{
		//echo "ola";
		//Inserir frase
		$faq = clearGarbadge( rawurldecode($_POST['newfraserand']), false, false);

		if ( empty($faq) )
			die( rawurlencode("A tua faq não tem conteúdo.") );
		else
			if ( ! strWordCount($faq, " ", 50) )
				die( rawurlencode("A tua faq não pode ter mais que 50 caracteres por palavra :X") );


		$query = $bd->submitQuery( "
INSERT INTO `frase` (
`id_frase` ,
`frase_texto`
)
VALUES (
NULL , '$faq'
)" );

		if ( $query )
		{

			echo ( rawurlencode("Frase inserida com sucesso :)") );

		}
		else
		{

			echo ( rawurlencode("De momento não é possível adicionar a tua frase :X") );

		}

	}
	else
		if ( isset($_POST['marcafrase']) )
		{
			//Apagar frase

			$arraydel = array_values( $_POST['marcafrase'] );

			$query = "Delete From `frase` Where `id_frase` In(";

			for ( $i = 0; $i < count($arraydel); $i++ )
			{

				$query .= "'$arraydel[$i]',";

			}

			$query{strlen( $query ) - 1} = ")";

			$query = $bd->submitQuery( $query );

			if ( $query )
			{

				echo 8;

			}
			else
			{

				echo rawurlencode( "De momento não é possível atender ao teu pedido." );

			}

		


		}

}

?>