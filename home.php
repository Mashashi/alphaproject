<?php
/**
 * Permite ao utilizador ficar a par do que se passa no f�rum
 * mostrando um pequeno testo de boas vindas, um video da biblioteca, 
 * os 5 t�picos mais recentes, as regras, as novas aquisi��es da biblioteca, 
 * os utilizadores online e todos os utilizadores.   
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 */

if( ! defined( 'IN_PHPAP' ) ) die();

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

$bd = ligarBD();

$blocos = $bd->submitQuery("Select * From `bloco` Order By `bloco_ordem`");

echo "<table border=\"0\">";

$controler_eval = 0;

//Nota n�o utilizar esta vari�vel $controler_eval
$bloqueado = 0;

while( $controler_eval < ( mysql_numrows( $blocos ) ) ){
	
	
	
		echo (( $controler_eval+$bloqueado) % 2 == 0)?"<tr><td width=\"295\">":"<td width=\"295\">";
		
		if( mysql_result( $blocos, $controler_eval, 5 ) ){
		
			echo mysql_result( $blocos, $controler_eval, 4 );
		
		} else { 
		
			if( isset($_SESSION['estat_carac']) ){
			
				
				if( array_search ( false , $_SESSION['estat_carac'] ) === false ){
				
					echo mysql_result( $blocos, $controler_eval, 4 );
				
				} else {
				
					$bloqueado--;
				
				}
				
			
			} else {
			
				$bloqueado--;
			
			}
			
		
		}
		
		if( isset($_SESSION['estat_carac']) ){
		
		if( array_search ( false , $_SESSION['estat_carac'] ) === false ) {
			
			echo "<div style=\"float:right;\">
			<input type=\"hidden\" value=\"".mysql_result( $blocos, $controler_eval, 0 )."\" class=\"edit_myBlock_id\" />
			<img src=\"imagens/edit_icon.jpg\" border=\"0\" alt=\"[Editar]\" class=\"edit_myBlock\" style=\"cursor:hand;\" title=\"Editar\" />
			</div><hr />";
			
		} else echo "<hr />";
		
		} else if( mysql_result( $blocos, $controler_eval, 5 ) ){
			
			echo "<hr />";
			
		}
		if( mysql_result( $blocos, $controler_eval, 5 ) ){
		
			eval( mysql_result( $blocos, $controler_eval, 2 ) );
	
		} 
		
		echo ( ($controler_eval+$bloqueado) % 2 == 0)?"</td>":"</td></tr>";
		
	
	
	$controler_eval++;
	
}

echo "</table>";


//Menu de inser��o de blocos
if( isset($_SESSION['estat_carac']) ){

if ( array_search ( false , $_SESSION['estat_carac'] ) === false ){

	echo "<hr /><a href=\"javascript:inserirBlock(700,'');\">Novo bloco de c�digo</a>";
	
}

}	
?>