<?php

/**
 * Neste arquivo incluimos incluimos os arquivos .php indicados pela 
 * variável elem do tipo GET.
 * 
 * Regras para a inclusão de ficheiros:
 * <ol>
 * <li>$_GET['elem'] tem de ser numerico</li>
 * <li>$_GET['elem'] tem de ser superior a 0</li>
 * <li>Caso o ficheiro de ódigo $_GET['elem'] não seja encontrado será incluido home.php</li></ol>
 *   
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

if( ! defined( 'IN_PHPAP' ) ) die();


if ( is_numeric($_GET['elem']) && $_GET['elem'] > 0 )
{
	
	switch ( $_GET['elem'] )
	{
		//
		case 2:
			/**
		 	 * Caso $_GET['elem'] tome o valor númerico 2 gerir.php será incluido.	
			 */
			include_once ( "gerir.php" );
			break;
		//3
		case 3:
			include_once ( "faq.php" );
			break;
		//8
		case 8:
			include_once ( "forum.php" );
			break;
		//9
		case 9:
			include_once ( "bugs.php" );
			break;
		//10
		case 10:
			include_once("perfil.php");
			break;
		//11
		case 11:
			include_once ( "mp.php" );
			break;
		//12
		case 12:
			include_once ( "pesquisas/pesquisa_rap.php" );
			break;
		//13
		default: include_once ( "home.php" );
        

	}

}
else
{
	include_once ( "home.php" );
}







?>