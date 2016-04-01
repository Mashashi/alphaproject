<?php

/**
 * Toda a gest�o de frases que s�o exibidas aleatoriamente na barra de estado 
 * � aqui feita.
 * A interface, encontra-se em funcoes_avan.php na fun��o printGerirFrases().
 * Esta ficheiro apenas responde a pedidos Ajax.  
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
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
			die( rawurlencode("A tua faq n�o tem conte�do.") );
		else
			if ( ! strWordCount($faq, " ", 50) )
				die( rawurlencode("A tua faq n�o pode ter mais que 50 caracteres por palavra :X") );


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

			echo ( rawurlencode("De momento n�o � poss�vel adicionar a tua frase :X") );

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

				echo rawurlencode( "De momento n�o � poss�vel atender ao teu pedido." );

			}

		


		}

}

?>