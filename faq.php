<?php

/**
 * Ficheiro que responde aos pedidos por Ajax de actualização, remoção, ou 
 * inserção das FAQ.
 * Faz também o output das FAQ para os utilizadores que não tem permiçõ para as gerir. 
 * 
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */
if ( ! isset($_SESSION['user']) )
	session_start();

/**
 * Incluir a classe para fazer a variável de acesso a base de dados bd.php.
 * 
 *   	
 */
include_once ( "bd.php" );

/**
 * Incluir fucoes_avan.php.
 *  
 */
include_once ( "funcoes_avan.php" );

validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );

$bd = ligarBD();

if ( isset($_POST['content']) 
	&& isset($_POST['n']) 
	&& isset($_SESSION['estat_carac'][9]) 
	&& $_SESSION['estat_carac'][9] )
	
	{

		if ( ! empty($_POST['content']) && ! empty($_POST['n']) )
		{

			$content = clearGarbadge( rawurldecode($_POST['content']), false, false );

			if ( trim($content) == "" )
				die();

			$content = "'" . $content . "'";

			$_POST['n'] = clearGarbadge( $_POST['n'], false, false );

			if ( $_POST['n']
			{
				0}
			== "t" )
			{

				$campo = " `faq_titulo` ";

			}
		else
			if ( $_POST['n']
			{
				0}
			== "c" )
			{

				$campo = " `faq_texto` ";

			}

			$id = substr( $_POST['n'], 1, strlen($_POST['n']) - 1 );

		if ( ! is_numeric($id) )
			die();
		$bd = new bd();

		$bd->setLigar( $host, $user_bd, $pass_bd, $db );

		$query = $bd->submitQuery( "Update `faq` Set $campo = $content Where `id_faq` = $id" );

		/*if(!$query)
		echo rawurlencode("
		Aconteceu algo inesperado como resultado as sua modificações não foram salvas :(");*/

		

	}
 /*else {

echo rawurlencode("O campo editado não pode estar vazio!");

}*/

}
else if ( isset($_POST['conten']) && isset($_POST['titulo']) )
{

$resposta = "";

$titulo = clearGarbadge( rawurldecode($_POST['titulo']), false, false );

$conteudo = clearGarbadge( rawurldecode($_POST['conten']), false, false );

if ( empty($titulo) )
{

	echo rawurlencode( "O título da FAQ não foi definido :X" );

	die();

}

if ( empty($conteudo) )
{

	echo rawurlencode( "O texto da FAQ não foi definido :S" );

	die();

}

if ( ! strWordCount($titulo, " ", 24) )
{

	echo rawurlencode( "O título não pode ter palavras com mais de 24 caracteres." );
	die();

}

if ( ! strWordCount($conteudo, " ", 24) )
{

	echo rawurlencode( "O texto não pode ter palavras com mais de 24 caracteres." );
	die();

}
$bd->submitQuery( "
INSERT INTO `faq` (
`id_faq` ,
`faq_titulo` ,
`faq_texto`
)
VALUES (
NULL , '$titulo', '$conteudo'
);
" );

echo rawurlencode( "FAQ $titulo inserida com sucesso :D" );



}
else if ( isset($_POST['apgfaqcheck']) )
{
//Apagar FAQ

$query = "Delete From `faq` Where ";

$apagar = $_POST['apgfaqcheck'];

$flag = true;

foreach ( $apagar as $key => $out )
{

	if ( ! $flag )
		$query .= " Or ";

	if ( $flag )
		$flag = false;

	$query .= " `id_faq` = " . $apagar[$key];


}

$query = $bd->submitQuery( $query );

if ( $query )
	echo "5";

else
{

	if ( sizeof($apagar) > 1 )
		echo rawurlencode( "Não foi possível apagar as FAQs seleccionadas!" );
	else
		echo rawurlencode( "Não foi possível apagar a FAQ seleccionada!" );

}


} 
else 
{
	//Fazer a listagem das FAQ
	if( ! defined( 'IN_PHPAP' ) ) die();
	
	$query = $bd->submitQuery( "Select * From `faq`" );

	echo "<div style=\"width: 592px;\" class=\"gestaofixmain\">

<object width=\"590\" height=\"285\" data=\"flash/animatedhead.swf\" style=\"width:200px; height:100px;\">
</object>
</div>
";

	echo "<div class=\"faqcontain\" style=\"margin-bottom: 20px;\">
	  <div class=\"faqheader\"><b>Índice FAQ</b></div>
	  <div class=\"faqtext\">";

	if ( mysql_numrows($query) == 0 )
	{

		echo "Não existem FAQ definidas.";
	}
	for ( $i = 0; $i < mysql_numrows($query); $i++ )
	{

		echo "<a style=\"margin-left: 8px;\" href=\"#$i\">" . mysql_result( $query, $i,
			1 ) . "</a><br />";

	}

	echo "</div>
		<div class=\"faqfooter\"></div>
	</div>";


	for ( $c = 0; $c < mysql_numrows($query); $c++ )
	{

		echo "<div class=\"faqcontain\">
	
		<div class=\"faqheader\"><a name=\"$c\" style=\"color: black;\"><b>" .
			mysql_result( $query, $c, 1 ) . "</b></a></div>
		<div class=\"faqtext\">" . nl2br( mysql_result($query, $c, 2) ) . "</div>
		<div class=\"faqfooter\"></div>
		
	</div>";

	}

}
?>