<?
/**
 * Mover um tópico para outra área.
 * 
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

if( !isset($_SESSION['id_user']) ) session_start();

/**
 * Requerir as fuções básicas, "funcoes.php".
 */
require_once("funcoes.php");

validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );

if( $_SESSION['estat_carac'][0] && isset($_POST['id_area']) && isset($_POST['id_top']) && !defined( 'IN_PHPAP' ) ){
	
	$id_area = clearGarbadge($_POST['id_area'], false, false);
	
	$id_top = clearGarbadge($_POST['id_top'], false, false);
	
	$bd = ligarBD();
	
	if( existeNaBd( "area", "id_area", $id_area ) && existeNaBd( "topico", "id_topico", $id_top )	){
	
		$bd->submitQuery("Update `topico` Set `area_id_area` = $id_area  Where `id_topico` = $id_top");
		
		$bd->submitQuery("Update `post` Set `topico_area_id_area` = $id_area  Where `topico_id_topico` = $id_top");
		
	}

}

?>