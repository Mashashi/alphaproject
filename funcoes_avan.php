<?php

/**
 * Fun��es avan�adas voltadas para a vertente administrativa.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 */

/**
 * Incluir funcoes.php.
 *  
 */
 require_once ( "funcoes.php" );

/**
 * printGerirRegistos()
 * 
 * Imprimir a interface de gest�o dos registos.
 *        
 * @return void
 */
function printGerirRegistos()
{

	echo "
	
		<div class=\"local\"><a href=\"?elem=2\" title=\"A tua �rea\">".$_SESSION['estat_nome']."</a> 
	� Gerir registos</div>
	
		<div class=\"gestaofixmain gestaopremain\" style=\"margin-top: 0px;\">
			
			
	
			<div class=\"tituloadmin\">Listar &amp; Editar Utilizadores</div>
			
			<table>

			<tr>
			
			<td>
			
			<fieldset>
			
			<legend>Op��es de listagem</legend>
			
			<form name=\"listarutil\" id=\"listarutil\">
			
				<input type=\"button\" id=\"listarutils\" class=\"forms\" value=\"Listar\" />
				
				<input type=\"reset\" class=\"forms\" value=\"Limpar\" />
				
				<input id=\"editutiloncli\" type=\"button\" class=\"forms\" 
				value=\"Editar Utilizador\" />
				
				<hr />
				
				<p>Identificar utilizadores por:</p>
				
				<select name=\"iden\" id=\"iden\">
					<option value=\"1\">Id do registo</option>
					<option value=\"2\">Nick</option>
					<option value=\"3\">Primeiro Nome</option>
					<option value=\"4\">�ltimo Nome</option>
					<option value=\"5\">Data de Registo</option>
					<option value=\"6\">Data de Nascimento</option>
					<option value=\"7\">Data de �ltima visita</option>
					<option value=\"8\">Estatuto</option>
				</select>
				
				<p>Listar utilizadores por:</p>
				
				<select name=\"listp\" id=\"listp\">	
					<option value=\"1\">Id do registo</option>
					<option value=\"2\">Nick</option>		
					<option value=\"3\">Primeiro Nome</option>
					<option value=\"4\">�ltimo Nome</option>
					<option value=\"5\">Data de Registo</option>
					<option value=\"6\">Data de Nascimento</option>
					<option value=\"7\">Data de �ltima visita</option>
					<option value=\"8\">Estatuto</option>
				</select>
				
				<label for=\"nickk\">
					<p>Nick:</p>
					<input class=\"forms\" type=\"input\" id=\"nickk\" name=\"nick\" />
				</label>
				
				<label for=\"numcart\">
					<p>N�mero do Cart�o:</p>
					<input class=\"forms\" type=\"input\" id=\"numcart\" name=\"numcart\" />
				</label>
					
				<hr />
				
				<p id=\"tagrever\">Excluir:</p>
					<table>
					<tr>
					
					
					<label for=\"excui\">
						<td>Utilizadores inactivos</td>
						<td><input title=\"Utilizadores que n�o visitam o s�te por um grande 
						peri�do de tempo\" type=\"checkbox\" value=\"1\" name=\"excui\" /></td>	
					</label>
					
					
					</tr>
					
					<tr>
					
					<label for=\"excuo\">
						<td>Utilizadores offline</td>
						<td><input type=\"checkbox\" value=\"1\" name=\"excuo\" /></td>			
					</label>
					
					</tr>
					
					<tr>
					
					<label for=\"excusus\">
						<td>Utilizadores suspensos</td>
						<td><input title=\"Utilizadores que est�o suspensos at� uma dada data\" 
						type=\"checkbox\" value=\"1\" name=\"excusus\" /></td>					  
					</label>
					
					</tr>
					</table>
					<br />
					<div id=\"tagreverr\">Utilizadores dentro da faixa et�ria:</div><br />
					  <label for=\"idai\">	
						�nicial<br />
						<input id=\"idai\"
						class=\"forms\" type=\"text\" value=\"\" 
						name=\"idai\" /><br />
						</label>
							
					  <label for=\"idaf\">	  
						Final<br />
						<input id=\"idaf\"
						class=\"forms\" type=\"text\" value=\"\" 
						name=\"idaf\" /><br /><br />
					  </label>	
					
					<label for=\"rever\">
						Pesquisar o reverso
						<input type=\"checkbox\" id=\"reverr\" value=\"1\" name=\"rever\" 
						title=\"Fazer a pesquisa pelo contrario do a cima assinalado\" />		
						<br />
					</label>
				
				<hr />
						
				<p>Ordem:</p>
				
				<label for=\"ordem\">	
					Ascendente
					<input type=\"radio\" value=\"1\" name=\"ordem\" checked=\"check\" />
					<br />
				</label>
				
				<label for=\"ordem\">
					Descendente
					<input type=\"radio\" value=\"0\" name=\"ordem\" />
				</label>
				
			</form>
			
			</fieldset>
			
			</td>
			
			<td>
				
				<select size=\"20\" name=\"nemsaidautil\" id=\"idsaidautil\" 
				class=\"listboxutil\"></select>
				
			</td>
			
			</tr>
			
			</table>
			
		</div>";

}


/**
 * editVisUtil()
 * 
 * Imprime e recebe os pedidos Ajax para edi��o dentro de um popup dos dados do 
 * utilizador. Esta fun��o ao ser executada recebe $util, isto � o id do utilizador.
 * Pela vari�vel $_GET['faz'] � determinada a opra��o a elaborar isto � apagar 
 * o utilizador ou salvar as mudan�as.
 *    
 * @uses clearGarbadge()
 * @uses validarData()
 * @uses strWordCount()
 * @uses fazerErro()
 * @uses ligarBD()
 *  
 * @param integer $util ID do utilizador a editar
 * 
 * @global String Avatar que � atribuido por defeito
 * @global String Se verifica a validade do dominio do email 
 * @global boolean Sistema operativo utilizado ver mais em config.php
 * 
 *  
 * @return void
 */
function editVisUtil( $util )
{
	global $avatdefault;
	
	global $emaildomain;

	global $sis;

	$bd = ligarBD();
	
	if ( is_numeric($util) && $util > 0 )
	{

		if ( $_GET['faz'] != 2 || ! isset($_GET['faz']) )
		{

			echo "
			<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" 
			\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">  
			<html xmlns=\"http://www.w3.org/1999/xhtml\">
			
			<meta http-equiv=\"content-type\" content=\"text/html; charset=iso-8859-1\" />
			
				<title>Editar/Visualizar Utilizador</title>
				
				<link href=\"css/editutilestilo.css\" rel=\"stylesheet\" type=\"text/css\" />
				
				<!--JQuery-->
				<script type=\"text/javascript\" src=\"javascript/jquery-1.2.6.js\"></script>
 			
 				<!--Plugin para trabalhar com forms-->
 				<script type=\"text/javascript\" src=\"javascript/jquery.form.js\"></script>
 	
				<!--M�scara de input-->
				<script type=\"text/javascript\" src=\"javascript/jquery.maskedinput-1.1.3.js\">
				</script>
				
				<style>body{text-align: center;margin: auto;}</style>
				
				<script type=\"text/javascript\">
				
				function getText(ele){return document.getElementById(ele).value } 	
				
				jQuery(function($){ 
					$(\"input[@name=updatanas]\").mask(\"99/99/9999\"); 
					$(\"input[@name=upblo]\").mask(\"99/99/9999\");
					});	
					
				$(document).ready(function(){
				
					function showValues(id){return $(id).serialize(true);}
				
					$('#serialediutill').click(function(){
						
						log ('?accao=1&faz=2&upidedit='+getText('upidedit'),'POST'
						,showValues('#editutilizador'),true);
						
						reloadiframe ( $(\"#upurlavat\").val() );
						
						})
				})				
				
function log(url,metodo,parametros,responseid){
	var xmlHttp;
	try{
		xmlHttp=new XMLHttpRequest();
		}catch(e){
			try{xmlHttp=new ActiveXObject(\"Msxml2.XMLHTTP\");
			}catch(e){
				try{xmlHttp=new ActiveXObject(\"Microsoft.XMLHTTP\");
				}catch(e){
					alert(\"O teu browser n�o suporta AJAX!\");
					return false;
					};
					};
					};
					xmlHttp.onreadystatechange=function(){
						if(xmlHttp.readyState==4&&xmlHttp.status==200){
							resposta=unescape(xmlHttp.responseText);
							if(responseid==true&&resposta.length!=0){
								window.alert(resposta);
								};
								}else{
									if(xmlHttp.readyState==4&&xmlHttp.status!=200){
							window.alert(\"De momento n�o�poss�vel atender ao teu pedido:(\");
							};
							};
							};
							xmlHttp.open(metodo,url,true);
	
	if((metodo.toLowerCase())=='post')
	xmlHttp.setRequestHeader(\"Content-Type\",\"application/x-www-form-urlencoded\");
	xmlHttp.send(parametros);
	
	};
	
	
function confirmOp(params,mens,posmens){
if (window.confirm (mens))
{
	location.href=params;
	if(posmens.length>0)
		window.alert(posmens);
}	
}
function reloadiframe (img) {
var f = document.getElementById('avatares');
f.src = 'listavat.php?id=upurlavat&previous='+img;
}				
				</script>
			</head>
			
			<body>	
				
			";

		}

		$quryapag = "";

		if ( isset($_GET['faz']) && is_numeric($_GET['faz']) )
		{

			switch ( $_GET['faz'] )
			{

				case 1:
					
					$query = $bd->submitQuery( "Delete From `mensagem` 
					Where `registo_id_registo` = $util Or `mensagem_destinatario` = $util" );

					if ( ! $query )
						die( rawurlencode( "Erro ao apagar mensagens do utilizador!" ) );

					
					$query = $bd->submitQuery( 
					"Delete From `requesicao` Where `registo_id_registo` = $util" );

					if ( ! $query )
						die( rawurlencode( "Erro ao apagar requesi��es do utilizador!" ) );

					del_prev_avatar_uploaded( $util );
					
					$query = $bd->submitQuery( 
					"Delete From `registo` Where `id_registo` = $util" );
					
					if ( ! $query )
						die( rawurlencode( "Erro ao apagar utilizador!" ) );
					
					
					break;

				case 2:
					$estat = clearGarbadge( rawurldecode($_POST['upestat']), false, false);
					$nick = clearGarbadge( rawurldecode($_POST['upnick']), false, false); //
					
					$pass = rawurldecode($_POST['uppass']);
					if( strpos( $pass  , "'") ){
						die( rawurlencode( "O caracter ' n�o � permitido na password.") );
					}
					$pass = clearGarbadge( $pass, false, false); //
					
					$datanasc = clearGarbadge( rawurldecode($_POST['updatanas']), false, false);
					$avatar = clearGarbadge( rawurldecode($_POST['upurlavat']), false, false);
					$assi = clearGarbadge( rawurldecode($_POST['upassi']), true, false);
					$nume = clearGarbadge( rawurldecode($_POST['upnumcar']), false, false); //
					$homepage = clearGarbadge( rawurldecode($_POST['uphome']), false, false); //
					$mail = clearGarbadge( rawurldecode($_POST['upmail']), false, false); //
					$sha1 = clearGarbadge( rawurldecode($_POST['upshaon']), false, false); //
					$pri = clearGarbadge( rawurldecode($_POST['uppri']), false, false ); //
					$ult = clearGarbadge( rawurldecode($_POST['upult']), false, false ); //
					$blo = clearGarbadge( rawurldecode($_POST['upblo']), false, false); //

					if( preg_match("/([^A-Za-z0-9])/", $nick) ){
			
						die( 
						rawurlencode( "No nick s� s�o permitidos caracteres [A-Za-z0-9]" ) );
			
					} 
					
					if( mysql_result( $bd->submitQuery( "Select Count(*) From `registo` 
					Where `registo_nick` like '$nick' 
					And
					`id_registo` <> $util
					" ),0,0) > 0){
						
						die( rawurlencode( "Esse nick j� est� a ser utilizado." ) );
						
					}
					
					if ( empty($nick) )
					{
						
						die( rawurlencode( "O campo nick tem de ser preenchido!" ) );
	
					}
					
					
					
					
					
					
		
					if ( empty($pri) )
						die(rawurlencode( "O primeiro nome n�o foi preenchido!" ) );
			
					if( !preg_match(
						"#^([A-Z�-�]{1}[a-z�-�]+)$#",$pri) ) 
						die(rawurlencode( "O primeiro nome do utilizador n�o � v�lido."));
		
					if( preg_match(
						"#^([����������������]+)$#",$pri) ) 
						die(rawurlencode( "O primeiro nome do utilizador n�o � v�lidoa."));
		
					if ( empty($ult) )
						die(rawurlencode( "O apelido n�o foi preenchido!" ));
			
					if( !preg_match(
						"#^([A-Z�-�]{1}[a-z�-�]+)$#",$ult) ) 
						die(rawurlencode("O apelido do utilizador n�o � v�lido."));
		
					if( preg_match(
						"#^([����������������]+)$#",$ult) ) 
						die(rawurlencode( "O apelido nome do utilizador n�o � v�lido."));
					
					
					/*
					if( !preg_match("/^[a-zA-Z]*$/",$pri) ){
					
						die(rawurlencode( "O primeiro nome do utilizador n�o � v�lido."));
					
					}
					if ( empty($pri) )
					{
						die( rawurlencode( "O primeiro nome n�o foi preenchido!" ) );

					}

					if( !preg_match("/^[a-zA-Z]*$/",$ult) ){
					
						die(rawurlencode("O apelido do utilizador n�o � v�lido."));
					
					}
					
					if ( empty($ult) )
					{

						die( rawurlencode( "O apelido n�o foi preenchido!" ) );

					}*/
					
					
					
					
					
					
					
					
					
					
					
					$query = $bd->submitQuery( "Select `id_estatuto` From `estatuto`" );

					$array = mysql_fetch_array( $query, MYSQL_NUM );

					foreach ( $array as $id )
					{

						if ( $estat == $id )
						{

							$array = true;

							break;

						}
					}

					if ( $array != true ) die();

					if( preg_match("/'|\|/",$pass) ){
						die( rawurlencode( "O caracter ' e | n�o s�o permitidos na password.") );
					}
					
					if ( empty($pass) )
					{
						die( rawurlencode( "A password n�o foi preenchida!" ) );

					} 
					
					if ( strlen($pass) < 4 )
					{

						die( rawurlencode( "Password demasiado curta!" ) );

					}

					if ( $sha1 != 0 && $sha1 != 1 )die();

					$data = explode( "/", $datanasc );

					$data = $data[2] . $data[1] . $data[0];

					if ( ! validarData($datanasc, "/") || $data > date("Ymd") )
					{
						
						die( rawurlencode( "A data de nascimento n�o � v�lida!" ) );

					}


					$comp = explode( "/", $blo );

					$comp = $comp[2] . $comp[1] . $comp[0];
					
					if ( $comp != "00000000" && ! empty($comp) )
					{
						if ( ! validarData($blo, "/") )
						{
							die( rawurlencode( "A data de bloqueio n�o � v�lida!" ) );
						}
					}


					if ( ! strWordCount($assi, " ", 24) )
					{

						die( 
						rawurlencode(
						"A assinarura n�o pode conter palavras com mais de 24 caracteres :X") );

					}


					if ( empty($nume) )
					{
						die( rawurlencode( "O n�mero n�o foi definido!"  ) );

					}

					if ( ! validarEmail($mail, $sis, $emaildomain) && ! empty($mail) )
					{

						die( rawurlencode( "O e-mail n�o � v�lido :x" ) );

					}

					if ( ! empty($avatar) )
					{

						if ( ! preg_match('/^imagens\/avatar\/.*\..{1,5}$/', $avatar) )
						{

							die();

						}

					}
				
				if ( !preg_match('#^imagens/avatar/common_users/(.*)$#', $avatar) )
				{
						
						if( preg_match('/^imagens\/avatar\/.*\..{1,5}$/', $avatar) ){
						
							del_prev_avatar_uploaded( $util );
						
						}
						
						
				}
				
				$pass = isset($_POST['codagora'])
				? "SHA1( '$pass' )": "'" . $pass . "'";
					
					$query = $bd->submitQuery( "Update `registo` Set 
						`estatuto_id_estatuto`= $estat 
						,`registo_nick`= '$nick'
						,`registo_pass`= $pass
						,`registo_data_nas`= '$data'
						,`registo_avatar`= '$avatar'
						,`registo_ass`= '$assi' 
						,`registo_numero` = '$nume'
						,`registo_homepage`= '$homepage'
						,`registo_mail` = '$mail' 
						,`registo_sha1`= $sha1
						,`registo_nome_pri` = '$pri'
						,`registo_nome_ult` = '$ult' 
						,`registo_is_activo` = '$comp'
						Where `id_registo` = $util" );

					if ( ! $query )
					{

						echo rawurlencode( 
						"De momento � imposs�vel actualizar os dados do utilizador:\n$nick" );

					}
					else
					{

						echo rawurlencode( 
						"Dados do utilizador:\n$nick\nActualizados com sucesso." );

					}
					break;


				default: break;

			}

		}


		if ( ! (isset($_GET['faz'])) )
		{
			
			
			
			$query = $bd->submitQuery( "SELECT 
			`id_registo`
			,`estatuto_id_estatuto`
			,`registo_nick`
			,`registo_pass`
			,DATE_FORMAT(`registo_data`, '%d/%m/%Y')
			,DATE_FORMAT(`registo_data_nas`, '%d/%m/%Y')
			,`registo_avatar`
			,`registo_ass`
			,`registo_numero`
			,DATE_FORMAT(`registo_data_ultima`, '%d/%m/%Y')
			,`registo_homepage`
			,`registo_mail`
			,`registo_sha1`
			,`registo_nome_pri`
			,`registo_nome_ult`
			,`registo_online`
			,DATE_FORMAT(`registo_is_activo`, '%d/%m/%Y') 
			FROM `registo` Where `id_registo` = $util" );

			if ( mysql_numrows($query) > 0 )
			{

				$mail = mysql_result( $query, 0, 11 );

				$isactivo = mysql_result( $query, 0, 16 );

				$nomepri = mysql_result( $query, 0, 13 );

				$nomeult = mysql_result( $query, 0, 14 );

				$pass = mysql_result( $query, 0, 3 );

				$homepage = mysql_result( $query, 0, 10 );

				$assinatura = mysql_result( $query, 0, 7 );

				$nick = mysql_result( $query, 0, 2 );

				$urlavatar = mysql_result( $query, 0, 6 );
				
				$numcartao = mysql_result( $query, 0, 8 );

				$id_esta = mysql_result( $query, 0, 1 );


				/*if ( ! preg_match('/^imagens\/avatar\/.*\..{1,5}$/', $profavatbuffer ) )
				$urlavatar = "imagens/avatar/$avatdefault";*/
					
				$queryesta = $bd->submitQuery( "
			Select `estatuto_nome` From `estatuto` Where `id_estatuto` = $id_esta" );

				if ( mysql_numrows($queryesta) == 1 )
					$estatuto = mysql_result( $queryesta, 0, 0 );

				else
				{

					$estatuto = "";

					$id_esta = 0;

				}

				$sha1 = mysql_result( $query, 0, 12 );

				$id_sha1 = $sha1;

				$online = mysql_result( $query, 0, 15 );

				if ( $online == null )
					$online = "<img src=\"imagens/msn1.gif\" alt=\"N�o\" title=\"Offline\" />";
				else
					$online = "<img src=\"imagens/msn2.gif\" alt=\"Sim\" title=\"Online\" />";

				if ( $sha1 == 0 )
					$sha1 = "N�o";
				else
					$sha1 = "Sim";

				$regdat = mysql_result( $query, 0, 4 );

				$ultvis = mysql_result( $query, 0, 9 );

				$queryestat = $bd->submitQuery( "Select `id_estatuto`,`estatuto_nome` 
				From `estatuto`" );

				$estateBox = "";

				for ( $i = 0; mysql_numrows($queryestat) > $i; $i++ )
				{

					if ( mysql_result($queryestat, $i, 0) != $id_esta )
					{

						$estateBox .= "<option value=\"" . mysql_result( $queryestat, $i, 0 ) 
						. "\">";

						$estateBox .= mysql_result( $queryestat, $i, 1 ) . "</option>";

					}

				}

				$datanasc = mysql_result( $query, 0, 5 );

				if ( $id_sha1 == 1 )
					$shaBox = "<option value=\"0\">N�o</option>";

				else
					$shaBox = "<option value=\"1\">Sim</option>";


				echo "
			
			<form name=\"editutilizador\" id=\"editutilizador\">
			
			<table>
				<tr>
					<td>
					
			<table>
			
				<tr>
					
					<td>Op��es</td>
					
					<td>
					
					<a href=\"javascript:confirmOp('?elem=2&amp;accao=1&amp;faz=1&amp;upidedit=$util','Apagar este utilizador sigifica, apagar consequentemente todas as entradas do utilizador no s�te a excep��o do f�rum. Deseja continuar?','');\">
						<img src=\"imagens/apagar.png\" title=\"Apagar este utilizador\"  
						border=\"0\" alt=\"[Apagar]\"/>
					</a>
					
					<a href=\"#\" id=\"serialediutill\">
						<img src=\"imagens/salvar.png\" title=\"Salvar mudan�as\" 
						border=\"0\" alt=\"[Salvar]\"/> 
					</a>
					
					<a href=\"javascript:window.close()\">
						<img src=\"imagens/fechar.png\" 
						title=\"Fechar esta janela\" border=\"0\" alt=\"[Fechar]\"/>
					</a></td>
					
				</tr>
				
				<tr>
					
					<td>Id do registo</td>
					
					<td><input type=\"text\" id=\"upidedit\" name=\"upidedit\" value=\"$util\" 
					class=\"inpfix forms\" disabled=\"disabled\" /></td>
					
				</tr>
				
				<tr>
					
					<td>Nick</td>
					
					<td><input type=\"text\" name=\"upnick\" 
					value=\"$nick\" maxlength=\"15\" class=\"inpfix forms\" /></td>
					
				</tr>
				
				<tr>
					
					<td>Primeiro nome</td>
					
					<td><input maxlength=\"15\" type=\"text\" name=\"uppri\" value=\"$nomepri\" 
					class=\"inpfix forms\" /></td>
					
				</tr>
				
				<tr>
					
					<td>Apelido</td>
					
					<td><input maxlength=\"15\" type=\"text\" name=\"upult\" 
					value=\"".rawurldecode($nomeult)."\" 
					class=\"inpfix forms\"></td>
					
				</tr>
				
				<tr>
					
					<td>Estatuto</td>
					
					<td>
					
					<select name=\"upestat\" style=\"font-size:11px;\">	
						<option value=\"" . ( mysql_result($query, 0, 1) ) . "\">$estatuto
						</option>
						$estateBox
					</select>
					
					</td>
						
				</tr>
				
				<tr>
					
					<td>Pass</td>
					
					<td><input maxlength=\"100\" name=\"uppass\" type=\"text\" 
					value=\"$pass\" class=\"inpfix forms\" /></td>
					
				</tr>
				
				<tr>
					
					<td>Password codificada</td>
					
					<td>
					
					<select name=\"upshaon\">	
						<option value=\"$id_sha1\">$sha1</option>
						$shaBox
					</select>
					
					</td>
					
				</tr>
				
				<tr>
					
					<td>Codificar agora</td>
					
					<td>
					
					<input type=\"checkbox\" name=\"codagora\" />
					
					</td>
					
				</tr>
				
				<tr>
					
					<td>Data de registo</td>
					
					<td>$regdat</td>
					
				</tr>
				
				<tr>
					
					<td>Data da �ltima visita</td>
					
					<td>$ultvis</td>
					
				</tr>
			
				<tr>
					
					<td>Data de nascimento</td>
					
					<td><input type=\"text\" name=\"updatanas\"
					value=\"$datanasc\" class=\"inpfix forms\" /></td>
				
				</tr>
				
				<tr>
					
					<td>N� Cart�o</td>
					
					<td><input name=\"upnumcar\" type=\"text\" 
					value=\"$numcartao\" maxlength=\"20\" class=\"inpfix forms\" /></td>
					
				</tr>
				
				<tr>
					
					<td>Bloqueado at�</td>
					
					<td>
					
					<input type=\"text\" name=\"upblo\"
					value=\"$isactivo\" class=\"inpfix forms\" />
					
					</td>
					
				</tr>
				
				<tr>
					
					<td>Assinatura</td>
					
					<td><input name=\"upassi\" type=\"text\" 
					value=\"" . rawurldecode( $assinatura ) . "\" class=\"inpfix forms\" /></td>
					
				</tr>
				
				<tr>
					
					<td>Homepage</td>
					
					<td><input name=\"uphome\" type=\"text\" value=\"$homepage\" 
					class=\"inpfix forms\" /></td>
					
				</tr>
				
				<tr>
					
					<td>E-Mail</td>
					
					<td><input name=\"upmail\" type=\"text\" value=\"$mail\" 
					class=\"inpfix forms\" /></td>
					
				</tr>
				
				<tr>
					
					<td>Estado</td>
					
					<td>$online</td>
				
				</tr>
				
			</table>
			
			</td>
			<td>
			
			<table>
					
						
				
				<tr>
					<td>
					
					<iframe name=\"avatares\" id=\"avatares\" src=\"listavat.php?id=upurlavat&amp;previous=" .
					rawurlencode( substr( $urlavatar, 15, strlen($urlavatar) ) ) . "\" 
					width=\"200\" height=\"325\" scrolling=\"auto\" frameborder=\"0\">
					</iframe>
					
					<input name=\"upurlavat\" id=\"upurlavat\" type=\"hidden\" 
					value=\"$urlavatar\" class=\"inpfix forms\" />
					</td>
				</tr>
			</table>
			
					</td>
				</tr>
			</table>
			</form>";

			}
			else
				fazerErro( "Este registo j� n�o est� presente!" );

			echo "</body></html>";

		}
		else
		{

			if ( (mysql_affected_rows() > 0 && $_GET['faz'] == 1) )
			{


				echo "
					<table>
						<tr>
							<td>
								Registo apagado com sucesso :D
							</td>
						</tr>
						<tr>
							<td>
								<a href=\"javascript:window.close()\" 
								title=\"Fechar esta janela\">
									<img src=\"imagens/fechar.png\" border=\"0\" alt=\"[Fechar]\"/>
								</a>
							</td>
						</tr>
					</table>
					";

			}
			else
				if ( $_GET['faz'] != 2 )
					fazerErro( "N�o foi poss�vel satisfazer o teu pedido :$" );


			if ( $_GET['faz'] != 2 )
				echo "</body></html>";


		}


	}
	else
	{

		fazerErro( "Imposs�vel construir a p�gina" );

	}


}


/**
 * printGerirFaq()
 * 
 * Impress�o da interface para a gest�o das FAQ.
 * 
 * @return void
 */
function printGerirFaq()
{

	$bd = ligarBD();

	$query = $bd->submitQuery( "Select * From `faq`" );

	echo "
	<form name=\"editfaqres\" id=\"editfaqres\">
	<div class=\"local\">
	<a href=\"?elem=2\" title=\"A tua �rea\">".$_SESSION['estat_nome']."</a> � Gerir FAQ</div>
	
	<div class=\"gestaofixmain gestaopremain\">
	<div class=\"tituloadmin\">Editar &amp; Apagar FAQ</div>
	<table id=\"duh\" style=\"margin-bottom: 5px; table-layout: fixed;\" 
	width=\"589\">
	<td class=\"homedes\" id=\"apagarfaqs\" title=\"Apagar as FAQs seleccionadas\" 
	style=\"width: 50px;cursor: pointer;border-right: 1px #FF9900 solid;\">Apagar</td>
	<td style=\"width: 200px;border-right: 1px #FF9900 solid;\">Titulo</td>
	<td>Conte�do</td></tr>";

	for ( $i = 0; $i < mysql_numrows($query); $i++ )
	{
		//ID da FAQ
		$idd = mysql_result( $query, $i, 0 );
		//Titulo
		$tit = mysql_result( $query, $i, 1 );
		//Texto
		$texto = nl2br( mysql_result($query, $i, 2) );

		if ( trim($tit) == "" )
			$tit = "(Clica aqui para adicionar texto)";

		if ( trim($texto) == "" )
			$texto = "(Clica aqui para adicionar texto)";

		echo "
		<tr class=\"overviewhover\" id=\"trover$i\">
		<td style=\"border-right: 1px #FF9900 solid;\">
		<input type=\"checkbox\" name=\"apgfaqcheck[$i]\" 
		value=\"" . mysql_result( $query, $i, 0 ) . "\" class=\"overcheck\" id=\"$i\" /></td>
		<td style=\" padding: 5px;border-right: 1px #FF9900 solid;\" />
		<div class=\"editaraqui\" 
		id=\"t$idd\">" . $tit . "</div></td>
		<td style=\" padding: 5px; \"><div class=\"editaraqui\" 
		id=\"c$idd\">" . $texto . "</div></td></tr>";

	}

	echo "</form>

	<!--Para adicionar as faqs automaticamente sem recarregar a p�gina
	<div id=\"addedfaqs\"></div>-->
	
	</table>
	</div>
	
	<div class=\"gestaofixmain gestaopremain\">
	<div class=\"tituloadmin\">Inserir FAQ</div>
	<div id=\"infofaq\"></div>
	<form name=\"adifaq\" id=\"adifaq\">
	<table id=\"duh\" style=\"
	margin-bottom: 5px; table-layout: fixed;\" 
	width=\"589\">
	
		<th style=\"text-align: right;\">Adicionar FAQ</td>
		<tr>
			
			<td>
				<labael for=\"titulo\">
				<strong>T�tulo</strong><br />
					<input style=\"width: 577px;\" type=\"text\" maxlength=\"60\" 
					class=\"forms\" name=\"titulo\" />
				</label>
			</td>
			
		</tr>
		<tr>
			
			<td>
				<label for=\"texto\">
				<strong>Conte�do</strong><br />
				<textarea id=\"conten\"
					style=\"font-family: verdana; font-size: 11px; width: 577px;\" 
					rows=\"7\" 
					name=\"conten\"
					class=\"forms\"></textarea>
				</label>
			</td>
		</tr>
		
		<tr>
			
			<td style=\"text-align: center;\">
				<input type=\"button\" value=\"Adicionar\" class=\"forms\" id=\"submitnewfaq\" />
				<input type=\"reset\" value=\"Limpar\" class=\"forms\" />
			</td>
		</tr>
	</table>
	</form>
	</div>
	";

}


/**
 * printGerirEstatuto()
 * 
 * Impress�o da interface para gest�o dos estatutos. De notar que para editar ou apgar o 
 * estatuto � incluida uma iframe com src="estat.php".
 * 
 *     
 * @return void
 */
function printGerirEstatuto()
{

	$bd = ligarBD();

	$query = $bd->submitQuery( "Select * From `estatuto`" );

	echo "
			<div class=\"local\"><a href=\"?elem=2\" title=\"A tua �rea\">".$_SESSION['estat_nome']."</a> 
	� Gerir estatutos</div>
		
		<div class=\"gestaofixmain gestaopremain\" style=\"margin-bottom: 3px; \">
		
		
		<div class=\"tituloadmin\">Apagar &amp; Editar Estatuto</div>
			
			<iframe frameborder=\"0\" name=\"estaiframe\" id=\"estaiframe\" src=\"estat.php\" 
			width=\"585\" height=\"200\" scrolling=\"auto\">
			</iframe>
			
		</div>";


	echo "
		<form name=\"newestatutoutil\" id=\"newestatutoutil\">
		<div class=\"gestaofixmain gestaopremain\" style=\"margin-top: 0px;\">
		<div class=\"tituloadmin\">Inserir Estatuto</div>
		<table>
		<tr>
				<td>Nome Estatuto:</td>
				<td>
					<input type=\"text\" name=\"estatnome\" id=\"esno\" 
					maxlength=\"35\" class=\"forms\" />
				</td>
		</tr>
		<tr>
		<td>
		<table>
		
			<tr>
				<td><font color=\"brown\">F�rum</font><hr /></td>
			</tr>
			
			<tr>
				<td>Pode gerir �reas</td>
				<td><input type=\"checkbox\" value=\"1\" name=\"estatutoop[0]\" /></td>
			</tr>
			<tr>	
				<td>Pode gerir t�picos</td>
				<td><input type=\"checkbox\" value=\"2\" name=\"estatutoop[1]\" /></td>
			</tr>
			<tr>	
				<td>Pode gerir posts</td>
				<td><input type=\"checkbox\" value=\"3\" name=\"estatutoop[2]\" /></td>
			</tr>
			<tr>	
				<td><font color=\"brown\">Filmes</font><hr /></td>
			</tr>
			<tr>	
				<td>Pode gerir Filmes</td>
				<td><input type=\"checkbox\" value=\"4\" name=\"estatutoop[3]\" /></td>
			</tr>
			<tr>	
				<td>Pode requesitar Filmes</td>
				<td><input type=\"checkbox\" value=\"5\" name=\"estatutoop[4]\" /></td>
			</tr>
		</table>
		</td>
		<td>
		<table>
			
			<tr>
				<td><font color=\"brown\">�lbuns</font><hr /></td>
			</tr>
			
			<tr>
				<td>Pode gerir �lbuns</td>
				<td><input type=\"checkbox\" value=\"6\" name=\"estatutoop[5]\" /></td>
			</tr>
			<tr>	
				<td>Pode requesitar �lbum</td>
				<td><input type=\"checkbox\" value=\"7\" name=\"estatutoop[6]\" /></td>
			</tr>
			<tr>	
				<td><div style=\"height: 23px;\"></div><font color=\"brown\">Outros Itens</font>
				<hr /></td>
			</tr>
			<tr>	
				<td>Pode gerir Outros Itens</td>
				<td><input type=\"checkbox\" value=\"8\" name=\"estatutoop[7]\" /></td>
			</tr>
			<tr>	
				
				<td>Pode requesitar Outros Itens</td>
				<td><input type=\"checkbox\" value=\"9\" name=\"estatutoop[8]\" /></td>
			</tr>
		</table>
		</td>
		<td>
		<table>
			
			<tr>
				<td><font color=\"brown\">Geral</font><hr /></td>
			</tr>
			
			<tr>
				<td>Pode gerir FAQs</td>
				<td><input type=\"checkbox\" value=\"10\" name=\"estatutoop[9]\" /></td>
			</tr>
			<tr>
				<td>Pode gerir Registos</td>
				<td><input type=\"checkbox\" value=\"11\" name=\"estatutoop[10]\" /></td>
			</tr>
			<tr>
				<td>Pode gerir Estatutos</td>
				<td><input type=\"checkbox\" value=\"12\" name=\"estatutoop[11]\" /></td>
			</tr>
			<tr>
				<td>Pode gerir Frases</td>
				<td><input type=\"checkbox\" value=\"13\" name=\"estatutoop[12]\" /></td>
			</tr>
			<tr>
				<td>
				<input type=\"button\" name=\"estatcriestat\" class=\"forms\" 
				value=\"Criar Estatuto\" id=\"estatcriestat\" />
				</td>
			</tr>
			<tr>
				<td>
				<input id=\"inserestats\" type=\"reset\" class=\"forms\" 
				value=\"Limpar\" />
				</td>
			</tr>
		</table>
		</td>
		</tr>
		</table>
		</div>
		</form>
		";

}


/**
 * printGerirFrases()
 * 
 * Impress�o da interface para gerir as frases que aparecem na barra de navega��o 
 * de id navtop. 
 * 
 *    
 * @return void
 */
function printGerirFrases()
{

	echo "
	
	<div class=\"local\"><a href=\"?elem=2\" title=\"A tua �rea\">".$_SESSION['estat_nome']."</a> 
	� Gerir frases</div>
	
		<div class=\"gestaofixmain gestaopremain\" style=\"margin-top: 0px;\">
		<div class=\"tituloadmin\">Apagar &amp; Editar Frases</div>
		
		<table width=\"585\">
		<form name=\"editdelfrase\" id=\"editdelfrase\">
		<tr class=\"overviewhover\">
				
				<td class=\"linempe\">#</td>
				<td class=\"linempe\">Marca</td>
				<td class=\"linempe\">Texto</td>
		
		</tr>
		";


	$bd = ligarBD();

	$query = $bd->submitQuery( "Select * From `frase`" );


	for ( $i = 0; $i < mysql_numrows($query); $i++ )
	{


		echo "
			<tr id=\"row$i\" class=\"overviewhover\">
				<td>" . ( $i + 1 ) . "</td>
				<td><input type=\"checkbox\" 
				name=\"marcafrase[$i]\" style=\"cursor:pointer;\" class=\"marcafrase\"
				value=\"" . mysql_result( $query, $i, 0 ) . "\" id=\"$i\" />
				</td>
				<td>" . mysql_result( $query, $i, 1 ) . "</td>
			</tr>";


	}
	echo "
			<tr>
				<td></td>
				<td><input type=\"button\" class=\"font11 forms\" value=\"Apagar\" 
				id=\"apgfrase\" /></td>
			</tr>";
	echo "</form></table>
		<div class=\"float-divider\"></div>
		<div class=\"tituloadmin\">Inserir Frases</div>
		<table width=\"585\">
			<form name=\"insrinewfra\" id=\"insrinewfra\">
			<tr>
				<td>
				<textarea id=\"newfraserand\" class=\"font11 forms\" 
				name=\"newfraserand\"></textarea>
				</td>
			</tr>
			<tr>
				<td>
				<input type=\"button\" class=\"font11 forms\" value=\"Introduzir\" 
				id=\"introfrase\" />
				</td>
			</tr>
			</form>
		</table>
		</div>";


}


/**
 * validarEmail()
 * 
 * Validar email usa-se a express�o regular(ER) 
 * <code>^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$</code> combinada com a fun��o
 * eregi se <code>eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$',$mail)</code>
 * Isto quer dizer que o email preenche as condi��es necess�rias a ser v�lido.
 * 
 * {@link http://pt.php.net/eregi A fun��o eregi()}
 * {@link http://pt.php.net/manual/pt_BR/regex.examples.php Exemplos de utiliza��o de express�es regulares}   
 * 
 * Como op��o avan�ada pode-se configurar o sistema de verifica��o de email para verificar
 * tamb�m o dominio do email embora este trecho de c�digo demore algum tempo a 
 * executar porque se baseia em comandos ping (ICMP) para tal, por via da 
 * fun��o <code>exec()</code>.</p>
 * 
 * Os parametros
 * <ol>
 * 	<li>$mail: O email</li>
 * 	<li>$sis: O sistema do servidor true para windows false para linux</li> 
 * </ol>
 *     
 *   
 * @param String $mail Email a ser verificado
 * @param boolean $sis Sistema operativo do servidor
 * @param boolean $validardominio Se valida o dominio indicado ou n�o
 *       
 * @return boolean
 */
function validarEmail( $mail, $sis, $validardominio )
{

	$flag = 0;

	if ( eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$', $mail) && ! empty
		($mail) )
	{

		$flag = 1;

	}

	if ( $validardominio )
	{

		$dominio = "www." . substr( $mail, strpos($mail, "@") + 1 );

		if ( $sis )
			exec( "ping -n 1 $dominio", $list );

		else
			exec( "ping -t 1 $dominio", $list );

		if ( count($list) > 1 )
		{

			foreach ( $list as $line )
			{

				if ( $sis == 2 || $sis == 1 )
				{

					if ( stristr($line, "ttl") != 0 )
					{

						$flag = 1;

						break;

					}

				}

			}

		}

	}


	return $flag;

}


/**
 * validarData()
 * 
 * Valida uma data usando como regra n�mero de meses inferiores a 
 * 13 e superiores e superiores a 0, n�umero de dias superiores a 0 e inferiores  
 * a 32 e para verificar se o n�mero de dias coincide com  m�s utiliza-se a fun��o 
 * <code>date()</code> para retornar a data com o timestamp mktime do UNIX.
 * 
 * Mais sobre a fun��o mktime():
 * 
 * <i>"Retorna o timestamp Unix correspondente para os argumentos dados. 
 * Este timestamp � um longo inteiro contendo o n�mero de segundos entre a 
 * Era Unix (January 1 1970 00:00:00 GMT) e o tempo especificado."</i>
 * {@link http://br.php.net/mktime A fun��o mktime()}
 *    
 * @return boolean
 */
function validarData( $data, $exp )
{

	$flag = 1;

	if ( ! empty($data) )
	{

		$array = explode( $exp, $data );

		if ( count($array) > 0 && count($array) < 4 )
		{

			if ( $array[1] > 12 || $array[1] < 1 || $array[0] > 31 || $array[0] < 1 
			|| $array[2] < 0 || date("m", mktime(0, 0, 0, $array[1], $array[0], $array[2])) 
			!= $array[1] )
			{

				//Ver se os meses est�o em conformidade com o n�mero de dias
				$flag = 0;
				
			}

		}
		else
			$flag = 0;

	}
	else
		$flag = 0;


	return $flag;
}

/**
 * strWordCount()
 * 
 * strWordCount(), � uma fun��o muito no caso de n�o se querer ver o design da 
 * p�gina estragado por texto sem espa�os.  
 * 
 * Esta fun��o pega em $text executa a fun��o explode() com o
 * delimitador $delimitador parte do array que da� resulta e confirama se a 
 * contagem de cada palavra � menor que $num 
 * se isto n�o se verificar ser� devolvido false como retorno.
 * {@link http://pt.php.net/explode A fun��o explode()}
 * 
 *    
 * @param String $text
 * @param String $delimitador
 * @param integer $num
 *  
 * @return boolean
 */
function strWordCount( $text, $delimitador, $num )
{
	
	$text = preg_replace("#\[youtube\]http://(?:www\.)?youtube.com/watch\?v=([0-9A-Za-z-_]{11})[^[]*\[/youtube\]#is", "", $text);
	
	$text = strip_tags($text);
	
	$flag = true;
	
	$ver = explode( $delimitador, $text );
	
	for ( $i = 0; $i < count($ver); $i++ )
	{

		if ( strlen($ver[$i]) > $num )
		{

			$flag = false;

		}

	}

	return $flag;
	
 }




/**
 * newArea()
 * 
 * Cria uma nova �rea no f�rum.
 * 
 * @param String $nome
 * @param String $descricao
 * 
 * @return mysql_query  
 */
 function newArea($nome,$descricao){
	
	
	
	if($_SESSION['estat_carac'][2]){
		
		$bd = ligarBD();
	
		$nome = clearGarbadge($nome, false, false);
		
		$nome = wordwrap($nome, 15, " ");
		
		$descricao = clearGarbadge($descricao,true, false);
		
		if( trim($nome) != "" ){ 
		    
		    if( trim($descricao) == "" ) $descricao = "<p></p>";
			
			$query = $bd->submitQuery( "
			INSERT INTO `area` (
			`id_area`,
			`area_nome`,
			`area_descricao`
			)
			VALUES (
			NULL , '$nome', '$descricao'
			)" );
			
			$query = 
			alertMsgJs("Nova �rea $nome criada com sucesso.")
			."
			<script type=\"text/javascript\">
			 location.href = \"".$_SERVER['PHP_SELF']."?elem=8\";
			</script>";
			
		} else {
			
			$query = 
			alertMsgJs(
			"O nome da �rea n�o foi preenchido ou � somente formada por espa�os em branco.");
			
		}
		
	} /*else {
		
		$query = 
		alertMsgJs("Ocorreu um erro fatal, a ac��o n�o pode ser levada a cabo.");
		
	}*/
	
	return $query;
		
 }




/**
 * delArea()
 * 
 * Apagar uma �rea do f�rum.
 * 
 * @param integer $is_area �rea a apagar
 * 
 * @return mysql_query     
 */
 function delArea($id_area){
	
	if( $_SESSION['estat_carac'][2] && is_numeric($id_area) ){
		
		$nome_area = getAreaNome($id_area);
		
		$bd = ligarBD();
		
		if( $nome_area == true ){
		
		$query = $bd->submitQuery( "
			DELETE FROM `post` WHERE `topico_area_id_area` = $id_area
			" );
		
		if($query) {
			
			$query = $bd->submitQuery( "
			DELETE FROM `area` WHERE `id_area` = $id_area
			" );
			
			if(!$query) 
			
			$query = 
			alertMsgJs("Um acontecimento inesperado ocorreu ao apagar a �rea $nome_area.");	
			
			else
			
			$query = alertMsgJs("A �rea $nome_area foi apagada com sucesso.")
			."<script type=\"text/javascript\">
			 location.href = \"".$_SERVER['PHP_SELF']."?elem=8\";
			</script>";
					
		} else 
			
			$query = alertMsgJs("Um acontecimento inesperado ocorreu ao apagar os posts e t�picos relacionados com a �rea $nome_area.");
			
		} else 
		
			$query = alertMsgJs("A �rea refer�nciada n�o existe.");
			
		
	} /*else 
		
		$query = alertMsgJs("Ocorreu um erro fatal a ac��o n�o pode ser levada a cabo.");
		*/
		
	return $query;
		
 }


/**
 * printEditArea()
 * 
 * Editar uma �rea do f�rum.
 * 
 * @param String $id_area ID da �rea  
 */
 function printEditArea($id_area){
	
	$query = "";
	
	if($_SESSION['estat_carac'][2]){
	
	$area_nome = getAreaNome($id_area);
	
	if( !is_bool( $area_nome )  ){
	
	$area_descri = getAreaDescricao($id_area);
	
	$query = "
		<div style=\"width:182px;\" class=\"basic\" style=\"float:left;margin-bottom:6px;\" >
		<div class=\"toogle\">Editar a �rea $area_nome</div><div style=\"padding-left:11%;\">
		
		<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."?elem=8\">
		
		<input type=\"hidden\" value=\"$id_area\" name=\"editarea\" />
		
		<label for=\"titulo\">
			Nome:<br />
			<input name=\"edititulo\" maxlength=\"255\" type=\"text\" class=\"forms\"
			value=\"$area_nome\" />
		</label>
		
		<br />
		
		<label for=\"descricao\">
					Descri��o:<br />
					
				
			<div style=\"
			background-image: URL('imagens/bg.gif');
			background-repeat: repeat-x;
			text-align:left;width:142px;\">
			" . drawToolBar( "editdescricaoo", "edittoolbarhtml", false ) . "</div>
			<textarea name=\"editdescricao\" id=\"editdescricaoo\" 
			class=\"font11 forms\" style=\"width: 138px;\">$area_descri</textarea>
			
		</label>
		
		<p><input type=\"submit\" value=\"Editar\" class=\"forms\" /></p>
		
		</form>
		
		</div></div>
		
	";	
	
	} /*else
		
		$query = alertMsgJs("Ocorreu um erro fatal a ac��o n�o pode ser levada a cabo.");*/

	}
		
	return $query;
		
 }


/**
 * delPostOrTopic()
 * 
 * Apagar um t�pico ou um post com base no seu ID.
 * 
 * @param integer $id ID do post ou t�pico
 * 
 * @return String    
 */
function delPostOrTopic($id){
	
	
	// $_SESSION['estat_carac'][1], controla a manipula��o de topicos
	// $_SESSION['estat_carac'][0], controla a manipula��o de posts
	
	$retorno = "";
	
	if( existeNaBd("post","id_post",$id) == 1 ){
		
		$bd = ligarBD();
		
		if(
		mysql_result( 
		$bd->submitQuery( 
		"Select `post_prin` From `post` Where `id_post` = $id"
		),0,0 )
		){
		
			if($_SESSION['estat_carac'][0]){
				
				$id_topico = 0;
				
				$id_topico = 
				mysql_result($bd->submitQuery( 
				"Select `topico_id_topico` From `post` Where `id_post` = $id"
				),0);
				
				if( $id_topico > 0 ){
					
					
					
					$bd->submitQuery( "Delete From `post` Where `topico_id_topico` = $id_topico");
					
					if(mysql_affected_rows() > 0){
						
						$bd->submitQuery( "Delete From `controlo_respeito` Where `post_topico_id_topico` = $id_topico");
						
						$bd->submitQuery( "Delete From `topico` Where `id_topico` = $id_topico");
						
						if(mysql_affected_rows() == 1){
							
								$retorno = alertMsgJs("T�pico apagado com sucesso.");
								
						} else $retorno = alertMsgJs("O t�pico n�o pode ser apagado.");
						
					} else $retorno = alertMsgJs("N�o � poss�vel apagar os posts do t�pico de momento.");
					
				} else $retorno = alertMsgJs("N�o � poss�vel apagar este t�pico de momento.");
				
			}
			
		} else {
			
			if($_SESSION['estat_carac'][1]){
				
					
					$bd->submitQuery( "Delete From `post` Where `id_post` = $id");
					
					if(mysql_affected_rows() == 1){
					
						$bd->submitQuery( "Delete From `controlo_respeito` Where `post_id_post` = $id");
						
						$retorno = alertMsgJs("Post apagado com sucesso.");
					
					} else $retorno = alertMsgJs("De momento n�o � poss�vel apagar este post.");
				
				
			}
		
		}
	 

	 
	}
	
	return $retorno;
	
}

/**
 * editArea()
 * 
 * Editar uma �rea.
 * 
 * @param Integer $id_area ID da �rea
 * @param String $id_area Novo nome que a �re vai tomar
 * @param String $descricao Nova descri��o que a �rea vai tomar
 * 
 * @return void 
 */ 
 function editArea($id_area,$nome,$descricao){
	
		$bd = false;
		
		if($_SESSION['estat_carac'][2]){
			
			$bd = ligarBD();
			
			$nome = clearGarbadge($nome,false, false);
			
			$descricao = clearGarbadge($descricao,true, false);
			
			$bd = $bd->submitQuery("Update `area` Set 
			`area_nome` = '$nome', `area_descricao` = '$descricao' 
			Where `id_area` = '$id_area'");
			
		}	
		
		return $bd ? 
		alertMsgJs("�rea editada com sucesso.")."
		<script type=\"text/javascript\">
			location.href = \"".$_SERVER['PHP_SELF']."?elem=8\";
		</script>" : 
		alertMsgJs("Lamentamos mas a �rea refer�nciada n�o pode ser editada.") ;
		
	}
 

/**
 * getAreaNome()
 * 
 * Saber o nome de uma �rea com base no seu ID.
 * 
 * @param Integer $id_area
 *  
 * @return mixed  
 */
function getAreaNome($id_area){
	
	$retorno = false;
	
	if( is_numeric($id_area) && $id_area > 0 ){
	
		$bd = ligarBD();
		
		$query = $bd->submitQuery( "
		Select `area_nome` From `area` Where `id_area` = $id_area
		" );
		
		if(mysql_numrows($query) == 1) 
			$retorno = mysql_result( $query, 0 );
		
		mysql_freeresult($query);
		
	} 
	
	
	
	return $retorno;
	
}

/**
 * getAreaDescricao()
 * 
 * Saber a descri��o de uma �rea com base no seu ID.
 * 
 * @param Integer $id_area
 * 
 * @return mixed    
 */
function getAreaDescricao($id_area){
	
	$retorno = false;
	
	if( is_numeric($id_area) && $id_area > 0 ){
	
		$bd = ligarBD();
		
		$query = $bd->submitQuery( "
		Select `area_descricao` From `area` Where `id_area` = $id_area
		" );
		
		if(mysql_numrows($query) == 1) 
			$retorno = mysql_result( $query, 0 );
		else 
			$retorno = true;
		

			
	} 
	
	
	
	return $retorno;
	
}








/**
 * newMesage()
 * 
 * Faz a inser��o de uma nova entrada na tabela <b>`mensagem`</b>. Os c�digos de retorno: 
 * 
 *  $flag_reg = 1
 *  A mensagem foi inserida s/problemas
 *  $flag_reg = 0 
 *  O utilizador n�o existe
 *  $flag_reg = 2 
 *  O texto da mensagem cont�m uma ou mais palavras com mais de 50 caract�res
 *  $flag_reg = 3
 *  Erro ao enviar mensagem :X
 *  $flag_reg = 4 
 *  O assunto tem de conter mais caract�res para al�m de espa�os em branco
 *  $flag_reg = 5
 *  O texto tem de conter mais caract�res para al�m de espa�os em branco
 *  $flag_reg = 6 
 *  $flag n�o � booleano ou 
 *  $flag_reg = 7 
 *  $to n�o � n�merico quando $flag est� inicializada a 1
 *   
 * @uses existeNaBd() 
 *  
 * @param integer $to
 * @param boolean $flag Indica-se se $to � o nome de utilizador ou o id do utilizador, 
 * true para o ID false para o nick.  
 * @param String $assunto
 * @param String $texto
 *  
 * @return byte
 */
function newMesage( $to, $flag, $assunto, $texto){
	
	if(! (existeNaBd( "registo", "id_registo", $_SESSION['id_user'] ) == 1) ){
				
		session_destroy();	
					
	} else {
	
	$flag_reg = 0;
	
	if( is_bool( $flag ) ){
	
		if( ( $flag && is_numeric( $to ) ) || ( ! $flag ) ){

			$to = clearGarbadge($to, false, false);
			
			if( $flag ){
		
				if(existeNaBd( "registo", "id_registo", $to ) == 1){
				
					$flag_reg = true;
		
					$query = " `id_registo` ";
					
				}
				
				
					
			} else {
				
				
				 
				if(existeNaBd( "registo", "registo_nick", $to ) == 1){
					
					
					$flag_reg = true;
					
					$query = " `registo_nick` ";
					
					$bd = ligarBD();
				
					$to =
					$bd->submitQuery("Select `id_registo` From `registo` 
					Where `registo_nick` Like '$to'");	
						
					$to = mysql_result($to,0);
					
				}
				
			}
			//$flag_reg = false;
			if( $flag_reg ) {
		
				$texto = clearGarbadge($texto, true, true);
		
				$assunto = clearGarbadge($assunto, false, false);
					
				if( strlen( trim( $assunto ) ) > 0  ){
					
					if ( strWordCount($texto, " ", 50) ) {
						
						$bd = ligarBD();
						$query = $bd->submitQuery( 
							"Select `estatuto_id_estatuto` From `registo` Where 
							`id_registo` = $to" );
						
						$query = mysql_result($query,0);
						
						$query = $bd->submitQuery( "
							INSERT INTO `mensagem` (
							`id_mensagem` ,
							`registo_id_registo` ,
							`registo_estatuto_id_estatuto` ,
							`mensagem_destinatario` ,
							`mensagem_data` ,
							`mensagem_corpo` ,
							`mensagem_assunto`
							)
							VALUES (
							NULL , '" . $_SESSION['id_user'] . "', '$query', '$to', 
							'" . date("YmdHis") . "', '$texto', '$assunto'
							)" );
						/*echo "
							INSERT INTO `mensagem` (
							`id_mensagem` ,
							`registo_id_registo` ,
							`registo_estatuto_id_estatuto` ,
							`mensagem_destinatario` ,
							`mensagem_data` ,
							`mensagem_corpo` ,
							`mensagem_assunto`
							)
							VALUES (
							NULL , '" . $_SESSION['id_user'] . "', '$query', '$to', 
							'" . date("YmdHis") . "', '$texto', '$assunto'
							)";	*/
						if( !$query ) $flag_reg = 3; else $flag_reg = 8;
			

	    			
					} else $flag_reg = 2;
	    			
				} else $flag_reg = 4;
				
			}
		
		} else $flag_reg = 7;
		
	} else $flag_reg = 6;
		
	}
	
	return  $flag_reg;	
	
}




/**
 * classiItem()
 * 
 * Faz a divis�o do n�mero de classifica��es de um item pelo o 
 * somat�rio dessas classifica��es 
 * de modo a achar a m�dia. 
 * 
 * @param integer $total
 * @param integer $num
 *  
 * @return String
 */
function classiItem($soma_classi, $num_classi){
	
	$retorno = 0;
	
	if($num_classi > 0) $retorno = round($soma_classi/$num_classi,0);
	
	return $retorno;
	
}


/**
 * addNewLineAt()
 * 
 * A fun��o wordwrap() faz a mesma coisa e melhor, ou seja separa uma sequ�ncia de 
 * caracteres de X em X caracteres, com um outro neste caso o parametro $char. 
 * 
 * Nota esta fun��o n�o � usada.
 *   
 * @deprecated 
 * 
 * @param String $str A sequ�ncia de caracteres a quebrar 
 * @param char $char O separador
 * @param integer $lim O limite de caracteres
 *   
 * @return String
 */
function addNewLineAt($str, $char, $lim){
	
	//$lim++;
	
	$count = 0;
	
	$retorno = "";
	
	$flag = 0;
	
	for($i = 0; $i < strlen($str); $i++){
		
		if( $str{$i} != $char ) $count++;
		
		if( $count == $lim ){
			
			$retorno .= substr($str, $flag, $lim)." ";
			
			$flag = $i+1;
			
			$count = 0;
			
		}
			
	}
	
	$retorno .= substr($str, $flag, strlen($str));
	
	return $retorno;
	
}








/**
 * selectCampoReq()
 * 
 * Seleccionar um campo da requesi��o.
 *   
 * @return mixed
 */
function selectCampoReq($id_req, $campo){

	$bd = ligarBD();
	
	$bd = $bd->submitQuery("Select $campo From `requesicao` 
	Where `id_requesicao` = '$id_req'");
	
	if(mysql_num_rows($bd) > 0)
		$bd = mysql_result($bd, 0,0);
	else
		$bd = null;
		
	return $bd;
	
}




/**
 * delAllExpReq()
 * 
 * Apagar requesi��es expiradas de dados elementos.
 * 
 * @param $id_elem ID do elemento, 1 = filmes, 2 = �lbuns, 3 = outros
 * @param $const Contante que define o tempo passado o qual uma requesi��o � encarada como expirada.  
 *     
 * @return integer
 */
function delAllExpReq( $id_elem, $const ){
	
	$bd = ligarBD();
	
	$bd->submitQuery("Delete From `requesicao` Using `requesicao`,`geral` 
	Where `geral_id_geral` = `id_geral` 
	And DATE_ADD(`requesicao_dat_min`,INTERVAL $const DAY) < CURDATE() 
	And `requesicao_dia_levantado` = '00000000' And `id_elemento` = $id_elem");	
	
	return mysql_affected_rows();
	
}




/**
 * uploadImagem()
 * 
 * Fazer o upload de uma imagem.
 * 
 * @param File $arquivo A imagem de queal se vai fazer o upload
 * @param integer $id_user ID do utilizador
 *     
 * @return mixed
 */
function uploadImagem( $arquivo, $id_user){

$erro = array();

//$arquivo = isset($_FILES["imagem"]) ? $_FILES["imagem"] : false;

// Tamanho m�ximo do arquivo (em bytes)
$config["tamanho"] = 150000;
// Largura m�xima (pixels)
$config["largura"] = 139;
// Altura m�xima (pixels)
$config["altura"]  = 139;
//Direct�rio de grava��o da imagem
$imagem_dir = "imagens/avatar/common_users/";

// Formul�rio postado... executa as a��es
//if( $arquivo )
//{  



    // Verifica se o mime-type do arquivo � de imagem
    if( !eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $arquivo["type"]) )
    {
        $erro[] = "Arquivo em formato inv�lido! A imagem deve ser jpg, jpeg, 
			bmp, gif ou png. Envie outro arquivo".$arquivo["type"];
    }
    else
    {
        // Verifica tamanho do arquivo
        if($arquivo["size"] > $config["tamanho"])
        {
            array_push($erro, "Arquivo em tamanho muito grande! 
		A imagem deve ser de no m�ximo " . $config["tamanho"] . " bytes. 
		Envie outro arquivo");
        }
        
        // Para verificar as dimens�es da imagem
        $tamanhos = getimagesize($arquivo["tmp_name"]);
        
        // Verifica largura
        if($tamanhos[0] > $config["largura"])
        {
            array_push($erro, "Largura da imagem n�o deve 
				ultrapassar " . $config["largura"] . " pixels");
				
        }

        // Verifica altura
        if($tamanhos[1] > $config["altura"])
        {
            array_push($erro, "Altura da imagem n�o deve 
			ultrapassar " . $config["altura"] . " pixels");
        }
    }

    // Verifica��o de dados OK, nenhum erro ocorrido, executa ent�o o upload...
    if(sizeof($erro) < 1)
    {
        // Pega extens�o do arquivo
        preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $arquivo["name"], $ext);
		
		if( !is_dir($imagem_dir) )
			mkdir ("$imagem_dir");
			
        // Gera um nome �nico para a imagem
        $imagem_dir .= md5( uniqid( time() ) ) . "." . $ext[1];

     	// Faz o upload da imagem
        move_uploaded_file($arquivo["tmp_name"], $imagem_dir);
		
		$match = "";
		
		del_prev_avatar_uploaded(null);
		
		$bd = ligarBD();
		
		$bd->submitQuery("Update `registo` Set `registo_avatar` = '$imagem_dir' 
		Where `id_registo` = $id_user");
		
		$_SESSION['avatar'] = $imagem_dir;
		
    }
    
    return $erro;
    
//}
	
}



/**
 * del_prev_avatar_uploaded()
 * 
 * Apagar o avatar actual se este tiver sido submtido por upload.
 * 
 * Se $id_user estiver a null ser� apagado caso a condi��o anterior seja 
 * verdadeira o avatar
 * do utilizador com sess�o inciada. 
 * 
 * Sen�o campo avatar do registo que tem por id $id_post ser� apagado, 
 * novamente caso o avatar, tenha sido submetido por upload.  
 * 
 * @param $id_user 
 * 
 * @return void
 */
function del_prev_avatar_uploaded($id_user){
	
	$id_user = clearGarbadge($id_user, false, false);
	
	if( isset($_SESSION['avatar']) && $id_user == null ){
		
		if( preg_match("#^imagens/avatar/common_users/(.*)$#", 
		$_SESSION['avatar'], $match) ){
			
			//print_r($match);
			//Apagar a imagem anterior de que o utilizador fez o upload
			@unlink("imagens/avatar/common_users/$match[1]");
			
		}
	
	} else if($id_user != null){
		
		$bd = ligarBD();
		
		$query = 
		$bd->submitQuery("Select `registo_avatar` From `registo` 
		Where `id_registo` = $id_user");
		
		if(mysql_numrows($query) == 1){
			
			if( preg_match("#^imagens/avatar/common_users/(.*)$#", 
			mysql_result($query,0,0), $match) ){
			
			//print_r($match);
			//Apagar a imagem anterior de que o utilizador fez o upload
			@unlink("imagens/avatar/common_users/$match[1]");
			
			}
			
		}
		
		mysql_free_result( $query );
		
	}
	
}


/**
 * delRequesicao()
 * 
 * Apagar uma requesi��o atrav�s do seu id e tipo de elemento.
 * 
 * @uses insert_log_req()
 *   
 * @param integer $id_req ID da requesi��o a apagar.
 * @param byte $id_elem ID do elemento 1 = filme, 2 = �lbum, 3 = outro. 
 *    
 * @return mixed
 */
function delRequesicao($id_req, $id_elem){
	
	$bd = ligarBD();
	
	insert_log_req($id_req, $id_elem);
	
	$bd->submitQuery("Delete From `requesicao` Using `requesicao`,`geral` 
	Where `geral_id_geral` = `id_geral`  
	And `id_requesicao` = $id_req And `id_elemento` = $id_elem");
	
	
	
	return mysql_affected_rows();
	
}


/**
 * insert_log_req()
 * 
 * Inserir uma nova entrada na tabela, de log de requesi��es.
 * 
 * @param integer $id_req ID da requesi��o a apagar.
 * @param byte $id_elem ID do elemento 1 = filme, 2 = �lbum, 3 = outro. 
 *    
 * @return boolean
 */
function insert_log_req($id_req, $id_elem){
	
	$bd = ligarBD();
	
	$tabela = "outro";
	
	$dias = OUTRO_REQ_DEV;
	
	$multa = 0;
	
	$flag = false;
	
	if( existeNaBd("requesicao","id_requesicao",$id_req) == 1 ){
		
		$num = $bd->submitQuery("
		Select `registo_numero` From `registo`
		Where `id_registo` = (Select `registo_id_registo` 
		From `requesicao` Where `id_requesicao` = $id_req) 
		");
		
		if ($id_elem == 1){ 
		
			$tabela = "filme"; 
			$dias = FILME_REQ_DEV;
			
		} elseif ($id_elem == 2){ 
			
			$tabela = "album";
			$dias = ALBUM_REQ_DEV;
			
		}
		
		$nome = $bd->submitQuery("
		Select `".$tabela."_nome` From `$tabela`
		Where `geral_id_geral` = (Select `geral_id_geral` 
		From `requesicao` Where `id_requesicao` = $id_req) 
		");
		
		
		$sup = $bd->submitQuery("
		Select `suport_".$tabela."_nome` From `suport_".$tabela."`
		Where `id_suport_".$tabela."` = (Select `requesicao_id_suporte` 
		From `requesicao` Where `id_requesicao` = $id_req) 
		");
		
		if( mysql_numrows($sup) == 1) $sup = mysql_result($sup,0,0);
		else $sup = "";
		
		
		$lev_req = $bd->submitQuery("
		Select `requesicao_dia_levantado`, `requesicao_dat_min` From `requesicao` 
		Where `id_requesicao` = $id_req
		");
		
		if( mysql_result($lev_req,0,0) != "0000-00-00" ){
		
			//C�lculo da multa em euros
			$multa = explode("-",mysql_result($lev_req,0,0));
			//Se a data for maior que a data limite de entrega
		
		
			if( date("Ymd") > date("Ymd", 
			mktime(0, 0, 0, $multa[1], $multa[2]+$dias, $multa[0] ) ) 
			&& $multa[0] != "0000"  ){
		
				$multa = date("Y-m-d", mktime(0, 0, 0, $multa[1], $multa[2]+$dias, $multa[0] ) );
		
				$multa = ( difDays($multa, "-") * MULTA);
		
			} else $multa = 0;
		
			$bd->submitQuery("
			INSERT INTO `requesicao_log` (
			`id_requesicao_log`
			,`requesicao_log_numero`
			,`requesicao_log_item_nome`
			,`requesicao_log_suport_nome`
			,`requesicao_log_levantado`
			,`requesicao_log_requerido`
			,`requesicao_log_multa_paga`
			,`requesicao_log_apagado`
			,`requesicao_log_tipo`)
			VALUES (NULL, '".mysql_result($num,0,0)."'
			,'".mysql_result($nome,0,0)."'
			,'$sup'
			,'".mysql_result($lev_req,0,0)."'
			,'".mysql_result($lev_req,0,1)."' 
			,'$multa'
			,'".date("Ymd")."'
			,$id_elem)
			");
		
			$flag = true;
		}
		
	}
	
	return $flag;
	
}


?>