<?php

/**
 * Ficheiro que processa os dados enviados por Ajax para inser��o de novos utilizadores.
 * Faz tamb�m a impress�o da interface. 
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 */

if ( ! isset($_SESSION['user']) )
	session_start();

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

/**
 * Incluir fucoes_avan.php.
 *  
 */
include_once ( "funcoes_avan.php" );

validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );

if ( $_SESSION['estat_carac'][10] && !isset($_POST['nick']) )
{

	$bd = ligarBD();

	$query = mysql_result( $bd->submitQuery("Select count(*) From `estatuto`"), 0 );

	if ( $query > 0 )
	{
		if( ! defined( 'IN_PHPAP' ) ) die();
		
		$query = $bd->submitQuery( "Select `id_estatuto`,`estatuto_nome` From `estatuto`" );
		$resposta .= "
	<div class=\"gestaofixmain gestaopremain\">
	<div class=\"tituloadmin\">Inserir utilizador</div>
	<div id=\"inserirutil\"></div>
	<form name=\"inserirutilreg\" id=\"inserirutilreg\">
	
	<table>
	<tr>
	<td width=\"170\">

	
	<br />
	<input type=\"button\" id=\"inserirutilsub\" value=\"Submeter\" class=\"forms\" />

	<input type=\"reset\" value=\"Limpar\" class=\"forms\" />
	
	<p><font color=\"brown\">De preenchimento obrigat�rio</font></p>
	<hr />
	<p><label for=\"estat\">
	Estatuto:
	<select name=\"estat\">";
		for ( $c = 0; $c < mysql_numrows($query); $c++ )
		{

			$resposta .= "<option value=\"" . mysql_result( $query, $c, 0 ) . "\">
		" . mysql_result( $query, $c, 1 ) . "</option>";

		}
		$resposta .= "</select></label></p>
	<font color=\"brown\">Espa�os em branco ser�o retirados do nick.</font>
	<hr />
	<label for=\"nick\">
	Nick:<br />
	<input type=\"text\" maxlength=\"15\" name=\"nick\" class=\"forms\" />
	</label>
	
	<p><label for=\"pass\">
	Pass:<br />
	<input type=\"password\" maxlength=\"100\" name=\"pass\" class=\"forms\" />
	</label></p>
	
	<p><label for=\"confpass\">
	Confirmar password:<br />
	<input type=\"password\" maxlength=\"100\" name=\"confpass\" class=\"forms\" />
	</label></p>
	
	<p><label for=\"sha1\">
	Password codificada:
	<select name=\"sha1\" title=\"Para saber mais sobre esta op��o consulte as FAQ\">
		<option value=\"0\">N�o</option>
		<option value=\"1\">Sim</option>
	</select>
	</label></p>
	
	<p><label for=\"pri\">
	Primeiro nome:<br />
	<input type=\"text\" name=\"pri\" maxlength=\"15\" class=\"forms\" /></p>	
	</label></p>
	
	<p><label for=\"ult\">
	Apelido:<br />
	<input type=\"text\" maxlength=\"15\" name=\"ult\" class=\"forms\" /></p>	
	</label></p>
	
	<p><label for=\"data\">
	Data de nascimento:<br />
	<input type=\"text\" name=\"data\" class=\"forms\"/>
	</label>
	
	<p><label for=\"num\">
	N�mero do cart�o:<br />
	<input type=\"text\" maxlength=\"20\" name=\"num\" class=\"forms\" /></p>	
	</label></p>
	";

		$resposta .= "</td><td>";

		$resposta .= "<p><font color=\"brown\">Est�o disponiveis os seguintes avatares, clique 
	num deles para o atribuir ao novo utilizador...</font></p>
	
	<iframe name=\"avatares\" frameborder=\"0\" src=\"listavat.php?id=avatar\"
	width=\"292\" height=\"200\" scrolling=\"auto\"></iframe>
	<input type=\"hidden\" name=\"utilavatar\" id=\"avatar\" class=\"forms\" />
	<br />
	
	<font color=\"brown\">De preenchimento n�o obrigat�rio</font>
	<hr />
		
	<p><label for=\"ass\">
	Assinatura:<br />
	<input type=\"text\" maxlength=\"150\" name=\"ass\" class=\"forms\" /></p>	
	</label></p>
	
	
	<p><label for=\"mail\">
	E-Mail:<br />
	<input type=\"text\" maxlength=\"35\" name=\"mail\" 
	id=\"utilmail\" class=\"forms\" /></p>	
	</label></p>
	
	<p><label for=\"home\">
	Homepage:<br />
	<input type=\"text\" name=\"home\" class=\"forms\" /></p>	
	</label></p>
	</td></tr></table>
	
	
	</div>
	</form>
	<div id=\"inserirutil\"></div>";

		echo $resposta;

	}
	else
		echo rawurlencode( "Para inserir um utilizador tem de primeiro criar um estatuto." );

	

}
else
	if (  $_SESSION['estat_carac'][10]  && isset($_POST['nick']) )
	{

		$nick = clearGarbadge( rawurldecode($_POST['nick']), false, false);

		$bd = ligarBD();
		
		//Verificar se j� existe um utilizador com este nick
		if ( mysql_result($bd->submitQuery("Select count(*) From `registo` 
		Where `registo_nick` like '$nick' 
		COLLATE latin1_general_cs"), 0, 0) > 0 )
		{
			
			die(rawurlencode( "J� existe um utilizador com o nick $nick..." ));

		}
		
		/*Fazer com que no nick s� sejam introduzidos caracteres 
		dentro dos par�metros [A-Za-z0-9]*/
		if( preg_match("/([^A-Za-z0-9])/", $nick) ){
			
			die( rawurlencode( "No nick s� s�o permitidos caracteres [A-Za-z0-9]" ) );
			
		}
		
		//Verificar se o campo nick foi preenchido
		if ( empty($nick) )
		{
			die(rawurlencode( "O campo nick tem de ser preenchido!" ));

		}

		$avatar_buf = clearGarbadge( rawurldecode($_POST['utilavatar']), false, false);
		
		$estat = clearGarbadge( rawurldecode($_POST['estat']), false, false);
		
		$pass = rawurldecode($_POST['pass']);
		
		$passcon = rawurldecode($_POST['confpass']);
		
		if( strpos( $pass  , "'") || strpos( $passcon  , "'") ) 
			die( rawurlencode( "O caracter ' n�o � permitido na password.") );
		
		$pass = clearGarbadge( $pass, true, true );
		
		$passcon = clearGarbadge( $passcon, true, true );

		
		//Verificar se o estatuto existe
		$query = mysql_result( $bd->submitQuery("Select count(*) 
		From `estatuto` Where `id_estatuto` = $estat"),0 );

		if ( ! is_numeric($estat) || $query != 1 ) die();
		
		if ( empty($pass) )
		
		{ die(rawurlencode( "A password n�o foi preenchida!" )); }
		
		else {
			
			if ( strlen($pass) < 4 )
			{ die( rawurlencode( "A password tem de ter pelo menos 4 caracteres!" ) ); }
			
			else {
				if ( $pass != $passcon )
				{ die(rawurlencode("As passwords n�o s�o identicas! :0" )); }
			}
			
		}
		
		$pri = clearGarbadge( rawurldecode($_POST['pri']), false, false);
		
		if ( empty($pri) )
			die(rawurlencode( "O primeiro nome n�o foi preenchido!" ) );
			
		if( !preg_match(
		"#^([A-Z�-�]{1}[a-z�-�]+)$#",$pri) ) 
			die(rawurlencode( "O primeiro nome do utilizador n�o � v�lido."));
		
		if( preg_match(
		"#^([����������������]+)$#",$pri) ) 
			die(rawurlencode( "O primeiro nome do utilizador n�o � v�lido."));
			
		$ult = clearGarbadge( rawurldecode($_POST['ult']), false, false);
		
		if ( empty($ult) )
			die(rawurlencode( "O apelido n�o foi preenchido!" ));
			
		if( !preg_match(
		"#^([A-Z�-�]{1}[a-z�-�]+)$#",$ult) ) 
			die(rawurlencode("O apelido do utilizador n�o � v�lido."));
		
		if( preg_match(
		"#^([����������������]+)$#",$ult) ) 
			die(rawurlencode( "O apelido nome do utilizador n�o � v�lido."));
			

		$data = clearGarbadge( rawurldecode($_POST['data']), false, false );

		$datanasc = explode( "/", $data );

		$datanasc = $datanasc[2] . $datanasc[1] . $datanasc[0];

		if ( ! validarData($data, "/") || $datanasc > date("Ymd") )
		{
			
			die( rawurlencode( "A data de nascimento n�o � v�lida." ) );

		}
		
		$num = clearGarbadge( rawurldecode($_POST['num']), false, false);

		if ( empty($num) )
		{
			
			die( rawurlencode( "O n�mero do cart�o n�o esta preenchido." ) );

		}
		
		
		//Verificar se j� existe algu�m com este n�mero de cart�o
		$query = mysql_result( $bd->submitQuery("Select count(*) From `registo` 
		Where `registo_numero` Like '$num' COLLATE latin1_general_cs"), 0 );
		
		if( $query > 0)
			die( rawurlencode("J� existe um registo com esse n�mero de cart�o.") );
		
		
		$mail = clearGarbadge( rawurldecode($_POST['mail']), false, false );

		if ( ! validarEmail($mail, $sis, $emaildomain) && ! empty($mail) )
			die( rawurlencode( "O e-mail n�o � v�lido :x" ) );

		$sha1 = clearGarbadge( rawurldecode($_POST['sha1']), false, false);

		if ( ! is_numeric($sha1) || ($sha1 != 0 && $sha1 != 1) )
			die();

		$ass = clearGarbadge( rawurldecode($_POST['ass']), true, false);

		$home = clearGarbadge( rawurldecode($_POST['home']), false, false);

		if ( ! empty($avatar_buf) )
		{

			if ( ! preg_match('/^imagens\/avatar\/.*\..{1,5}$/', $avatar_buf) )
			{

				die();

			}

		}

		$pass = $sha1 ? "SHA1( '$pass' )" : "'" . $pass . "'";


		$query = "INSERT INTO `registo` (
		`id_registo` ,
		`estatuto_id_estatuto` ,
		`registo_nick` ,
		`registo_pass` ,
		`registo_data` ,
		`registo_data_nas` ,
		`registo_avatar` ,
		`registo_ass` ,
		`registo_numero` ,
		`registo_data_ultima` ,
		`registo_homepage` ,
		`registo_mail` ,
		`registo_sha1` ,
		`registo_nome_pri` ,
		`registo_nome_ult` ,
		`registo_online`
		)
		VALUES (
		NULL , $estat, '$nick', $pass, '" . date( "Ymd" ) . "', '$datanasc'
		, '$avatar_buf' , '$ass' , '$num'
		, '" . date( "Ymd" ) . "', '$home' , '$mail' , $sha1, '$pri', '$ult', NULL)";
		
		$query = $bd->submitQuery( $query );

		if ( ! $query )
		echo rawurlencode( "Ops, n�o foi poss�vel adicionar o novo utilizador:\n$nick\nNeste momento!" );
		else{
			
			$msg = "<p>Ol� <b>$nick</b>...</p>
			$benvindo";
			
			$title = "Se benvindo! :)";
			
			newMesage(mysql_insert_id(), true, $title, $msg );
			
			echo rawurlencode( "Novo utilizador:\n$nick\nAdicionado com sucesso :)" );
			
		}

	}




?>