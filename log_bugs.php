<?php

/**
 * Faz a submissão de um novo bug reportado por um utilizador.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

$root = "bugs/entradas.php";

/**
 * Incluir o ficheiro para o qual vão ser escritos os eventos e lido o tamanho dos mesmos.
 * 
 */
include_once ( "$root" );

/**
 * registarAccao()
 * 
 * Abre o ficheiro <b>entradas.php</b> e adiciona sob a forma de um array $event a 
 * $accao, com uma dada data com o autor dessa acçao sendo $por. 
 *   
 * @param mixed $por Autor do submeter
 * @param mixed $accao Reporte do bug
 *  
 * @return void
 */
function registarAccao($por, $accao)
{
	global $root;
	global $event;

	$log = "";

	$logConteudo = "";

	$count = "";


	if ( ! file_exists("$root") )
	{
		
		//echo $_POST["msg"]." ".$root;
		die();
		//Criar o ficheiro entradas.php

	}

	if ( file_exists("$root") )
	{
		
		//Abrir o ficheiro no modo prepend
		$log = fopen( "$root", "r+" );

		//Verificar se o tamanho do ficheiro é igual a 0 se for será escrito para o ficheiro
		//um cabeçalho com o formato dos dados.
		if ( filesize("$root") == 0 )
		{

			fwrite( $log, "<?//Identificação :: Data :: Acção\n\n" . "\$event[0] = \"" . $por .
				" :: " . date("Y-m-d H:i:s") . " :: " . $accao . "\";\n?>" );

		}
		else
		{

			$logConteudo = fread( $log, filesize("$root") );

			fseek( $log, strripos($logConteudo, "?") );

			fwrite( $log, "\$event[" . (sizeof($event)) . "] = \"" 
			. $por . " :: " . date("Y-m-d H:i:s") 
			. " :: " . $accao . "\";\n?>" );
			
			//Fechar a ligação com o ficheiro
			fclose( $log );
			
		}

		

	}

}

session_start();

if ( isset( $_POST["msg"] ) && isset( $_SESSION["id_user"] ) ){

	
	registarAccao( $_SERVER['REMOTE_ADDR'], rawurldecode( $_POST["msg"] ) );

}

?>