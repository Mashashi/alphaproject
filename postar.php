<?php

/**
 * Por aqui passam quer tópicos quer posts submtidos pelos utilizadores.
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


if ( ! isset($_SESSION['user']) ) session_start();

validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );

//Editar mensagens sticky
if(isset($_POST["sticky_change"]) && is_numeric($_POST["sticky_change"])){
	
	$bd = ligarBD();
	
	$sticky_change = clearGarbadge($_POST["sticky_change"],false, false);
	
	if( existeNaBd("topico","id_topico",$sticky_change) == 1 ){
		
	
		
		$sticky_top = mysql_result($bd->submitQuery("Select `topico_sticky` From `topico` Where `id_topico` = $sticky_change "),0); 
		
		$bd->submitQuery("Update `topico` Set `topico_sticky` = If( ($sticky_top = 1) ,(0),(1)) Where `id_topico` = $sticky_change");
		
	}
	
}







if ( isset($_SESSION['user']) ){

	$tt = true;

	if ( isset($_POST['assunto']) )
	{

	
		$assunto = clearGarbadge( ucfirst(strtolower(utf8_decode($_POST['assunto']))), false, false);
	
		$txt = trim( utf8_decode ( $assunto ) );
		
		if ( empty($txt) )
			die( rawurlencode("O assunto não pode estar vazio ou conter só espaços em branco.") );
	
		if ( empty($assunto) )
			die( rawurlencode("O assunto não foi preenchido.") );

	} else die();


	if ( isset($_POST['texto']) )
	{
	
		//Nota a função esta a contaras tags html
		$txt = trim( utf8_decode ( $_POST['texto'] ) );
			
		if ( empty($txt) )
			die( 
			rawurlencode("O texto não pode estar vazio ou conter só espaços em branco.") );
		
		$texto = clearGarbadge( utf8_decode( $_POST['texto']), true, true);
		
		//die( rawurlencode( $texto ));
		
		if( (strpos($texto,"\n") != 0 && strpos($texto,"<br />") != 0) ||
		(strpos($texto,"\n") === false || strpos($texto,"<br />") === false) )
			$texto = "\n".$texto;
		
		if ( ! strWordCount( $texto, " ", 50 ) )
			die( 
			rawurlencode("O texto não pode conter palavras com mais de 50 caracteres :X") 
			);
			
	}
	else die();


	if ( isset($_POST['area']) && is_numeric($_POST['area']) && $_POST['area'] != 0 )
	{

		$id_area = clearGarbadge( $_POST['area'], false, false);

		if ( empty($id_area) )
			die();

	}
	else die();

	$bd = ligarBD();
	
	if ( isset($_POST['topico']) && is_numeric($_POST['topico']) && $_POST['topico'] > 0 )
	{

		$tt = false;

		$id_topic = clearGarbadge( $_POST['topico'], false, false);

		if ( empty($id_topic) ) die();

		$query = $bd->submitQuery( "Select `topico_pode_comentar` From `topico` Where `id_topico` = $id_topic" );

		if ( mysql_numrows($query) == 1 )
		{

			$pocomen = mysql_result( $query, 0, 0 );

			if ( ! $pocomen ) die();

		}
		else
			die();

		if ( ! isset($_POST['coment']) || ! is_numeric($_POST['coment']) 
		&& ($_POST['coment'] < 0 || $_POST['coment'] > 1) )
			die();

	}


	if ( ! isset($_SESSION['id_user']) || ! is_numeric($_SESSION['id_user']) ) die();


	if ( ! isset($_SESSION['id_estat']) || ! is_numeric($_SESSION['id_estat']) ) die();
	
	if( $_SESSION['estat_carac'][0] == 1 ){
	
		if ( !isset($_POST ['sticky']) || !is_bool((bool)$_POST ['sticky']) )  $sticky = 0;
	
	} else {
	
		$sticky = 0;
	
	}
	
	
	
	//die( "".$_POST ['sticky'] );
	
	/*Fim das verificações as variáveis para a postagem*/


	/*Fazer Tópico*/


	if ( $tt )
	{

		$query = "
 		INSERT INTO `topico` (
 		`id_topico` ,
		`area_id_area` ,
		`topico_visto` ,
		`topico_pode_comentar`,
		`topico_sticky`
		)
		VALUES (
			NULL , '$id_area', '0', '$coment',$sticky
		);";
		
		if ( ! $bd->submitQuery( $query ) ) die();

		
		$id_topic = mysql_insert_id();
		
	}


	/*Fazer post*/
	 
	$date = date( "YmdHis" );
	
	$query = "
	INSERT INTO `post` (
	`id_post` ,
	`registo_id_registo` ,
	`registo_estatuto_id_estatuto` ,
	`topico_area_id_area` ,
	`topico_id_topico` ,
	`post_titulo` ,
	`post_data_hora` ,
	`post_activo` ,
	`post_texto` ,
	`post_prin`
	)
	VALUES (
		NULL , '" . $_SESSION['id_user'] . "', '" . $_SESSION['id_estat'] . "', '$id_area',
		'$id_topic', '$assunto', '$date', '1', '$texto', '$tt'
	);";

	$query = $bd->submitQuery( $query );
	
	echo $tt == 1?"3":"2";
	
	//Actualizar a variável de sessão de respostas e tópicos feitos por este utilizador
	$query = "Select count(*) From `post` WHERE `registo_id_registo` = " . $_SESSION['id_user'];

	$query = $bd->submitQuery( $query );

	$_SESSION['num_posts'] = mysql_result( $query, 0 );
	
		

}

?>