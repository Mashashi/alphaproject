<?php

/**
 * Tudo o que tem a ver com a gestão do perfil por parte do utilizador esta neste 
 * ficheiro interface e operações relacionadas com o update de dados pessoais que podem 
 * ser editados. 
 * E listagem de dados pessoais que não podem ser editados
 * 
 * Dados pessoais listados na página de perfil que podem se editados:
 * <ol>
 *  <li>Assinatura.</li>
 * 	<li>Password.</li> 
 * 	<li>Homepage.</li>
 * 	<li>Avatar.</li> 
 *  <li>Email.</li> 
 * </ol>    
 * 
 * Dados pessoais listados na página de perfil que podem se editados:
 * <ol>
 *  <li>Nº de mensagens no fórum.</li>
 * 	<li>Data de Registo</li> 
 * 	<li>Password.</li>
 * 	<li>Estatuto.</li> 
 * </ol> 
 * 
 * @see listavat.php 
 *   
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */


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

/**
 * Incluir o ficheiro de configuração config.php.
 *  
 */
include_once ( "config.php" );

if ( ! isset($_SESSION['user']) )
	session_start();

$update = false;

$bd = ligarBD();

validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );

if ( isset($_SESSION['user']) && isset($_POST['edit']) ){
	
	//Primeira etapa para mudar password
	if ( isset($_POST['profpass']) )
	{
	//Mudar a password
		$_POST['profpass'] = rawurldecode($_POST['profpass']);
		
		if( preg_match("/'/",$_POST['profpass'] ) ) 
			die( rawurlencode("O caracter ' não é permitido na password.") );
		
		$_POST['profpass'] = clearGarbadge( $_POST['profpass'], false, false);
		
		if ( $_POST['profpass'] == $_SESSION['user_pass'] )
		{
				
				echo "1";
				$_SESSION['newpassword'] = $_POST['profpass'];

		} else echo "2";
			
	}
	
	
	
	
	//Segunda etapa para mudar password
	if ( isset($_POST['profnewpass']) )
	{
		
		$_POST['profnewpass'] = rawurldecode($_POST['profnewpass'] );
		
		if( preg_match("/'/",$_POST['profnewpass'] ) ){
			
			$_SESSION['newpassword'] = "";
			die(rawurlencode("O caracter ' não é permitido na password."));
		
		}
		
		$_POST['profnewpass'] = clearGarbadge($_POST['profnewpass'], true, true );
			
		if ( strlen($_POST['profnewpass']) < 4 || ! isset($_SESSION['newpassword']) ||
		$_SESSION['newpassword'] != $_SESSION['user_pass']) 
			die();
		
		if (array_search($_SESSION['user'],$utils_pass_block)!==false){
		
			die  (rawurlencode("Não tens permissão para mudar a password de ".$_SESSION['user'].".\nAchas bem andar a estragar o trabalho dos outros ?:P"));
		
		}
		
		$query = $bd->submitQuery( "Update `registo` Set `registo_pass` = '" 
		. $_POST['profnewpass'] ."' Where `id_registo` = " 
		. $_SESSION['id_user'] . " And `registo_pass` Like '" .$_SESSION['newpassword'] 
		. "' COLLATE latin1_general_cs" );

		if ( $query )
		{
			$_SESSION['user_pass'] = $_POST['profnewpass'];
			$_SESSION['newpassword'] = "";
			die("4");
			
		} else die("3");


	}









	//Actualizar os dados pessoais po ajax
	if ( ! isset($_POST['profassi']) || ! isset($_POST['profmail']) 
	|| ! isset($_POST['profavatbuffer']) || ! isset($_POST['profhome']) 
	|| ! isset($_POST['profsobre'])  ) die();
	
	//$_POST['profdtnas'] = clearGarbadge( rawurldecode($_POST['profdtnas']), false, false);
	
	$profhome = clearGarbadge( rawurldecode($_POST['profhome']), false, false);

	$profassi = clearGarbadge( rawurldecode($_POST['profassi']), true, false);
	
	$profsobre = clearGarbadge( str_replace( "'", "´", rawurldecode( $_POST['profsobre'] ) ), true, true );
	
	if ( ! strWordCount($profassi, " ", 24) )
	{

		die( 
		rawurlencode("A assinatura não pode conter palavras com mais de 24 caracteres :X") 
		);

	}

	$profavatbuffer = 
		clearGarbadge( rawurldecode($_POST['profavatbuffer']), false, false);
	
	
		
	if ( ! preg_match('/^imagens\/avatar\/.*\..{1,5}$/', $profavatbuffer )
	&& !empty( $profavatbuffer ) ) die();

	$profmail = 
		clearGarbadge( trim(rawurldecode($_POST['profmail'])), false, false);
	
	if ( ! empty($profmail) )
	{

		if ( ! validarEmail($_POST['profmail'], $sis, $emaildomain) )
		{ die(rawurlencode( "Email inválido :X" )); }

	}


	/*Fazer o update da home*/
	$query = $bd->submitQuery( "Update `registo` Set `registo_homepage` = '$profhome'
	Where `id_registo` = " . $_SESSION['id_user'] );

	if ( $query ) $_SESSION['home_pag'] = $profhome;
	else die("3");

	/*Fazer o update da assinatura*/
	$query = $bd->submitQuery( "Update `registo` Set `registo_ass` = '$profassi' 
	Where `id_registo` = " . $_SESSION['id_user'] );

	if ( $query ) 
		$_SESSION['assi'] = $profassi;
	else 
		die("3");
	
	if ( !preg_match('#^imagens/avatar/common_users/(.*)$#', $profavatbuffer) )
	{
						
		if( preg_match('/^imagens\/avatar\/.*\..{1,5}$/', $profavatbuffer) ){
						
			del_prev_avatar_uploaded( null );
						
		}
						
						
	}
	
	/*Fazer o update da imagem*/
	$query = $bd->submitQuery( "Update `registo` Set `registo_avatar` = '$profavatbuffer' 
	Where `id_registo` = " . $_SESSION['id_user'] );
	
	
	
	if ( $query )
		$_SESSION['avatar'] = $profavatbuffer;
	else 
		die("3");
	
	/*Fazer o update do e-mail*/
	$query = $bd->submitQuery( "Update `registo` Set `registo_mail` = '$profmail' 
	Where `id_registo` = " . $_SESSION['id_user'] );
	
	if ( $query ) 
		$_SESSION['e_mail'] = $profmail;
	else 
		die("3");
	
	/*Fazer o update do sobre mim*/
	$query = $bd->submitQuery( "Update `registo` Set `registo_mim` = '$profsobre' 
	Where `id_registo` = " . $_SESSION['id_user'] );

	if ( $query ) 
		$_SESSION['mim'] = $profsobre;
	else 
		die( "3");
	
	die ( "4" );
	/*Enviar mensagem a confirmar que tudo correu bem*/
	







} else {
	
	echo "
 <script type=\"text/javascript\">
 	
	function redirectParent(to){ parent.window.document.location.href = to; }
 
 	function showValues(id){return $(id).serialize(true);}
			    
	function setText(str,ele){document.getElementById(ele).value = str;}
  
   	function getText(ele){return document.getElementById(ele).value;} 
	
	function clearInPass(){ setText('','profpass');} 
	
	$(document).ready(function(){
			  
		$('#profpassgrav').click(function(){
		
			$.post(\"perfil.php\",{
  				
				profpass: escape( getText('profpass') ),
				edit: 1
				
			} , function(txt){
				
				if(txt.length > 0){
					
					if(txt == 1){
				
					conf = '';
						
						try{
							while(conf.length < 4){
							
								conf = prompt('Nova password:','');
							
							
									if(conf.length<4)
										alert('Nova password demasiado curta!');
							
							
							}
							
						} catch(e){}
						
					if( conf != null) {
					
					confcheck = prompt('Confirma a nova password:','');
					
					if(conf == confcheck){
						
						try{
							conf = confcheck.length;
						}catch(e){
							conf =0;
						}	
						if(conf>=4){
						
							$.post(\"perfil.php\",{
  				
								profnewpass: escape( confcheck ),
								edit: 1
				
							} , function(txt){
				
								if(txt.length > 0){
									
									if (txt == 4){
										window.alert('Dados actualizados com sucesso.');
										resposta = '';
									} else { window.alert(unescape(txt)); }
								
								}
		
							});
							
						}
					
					
					
					} else	{ alert('Oooops passwords diferentes!');	}
							
					}
									
					} else if(txt == 2) window.alert('Password incorrecta!');
					
				 
					
				}
				
			});
			
			setTimeout('clearInPass()',300);
		
		});
		
	$('#prof').click(function(){
		
		
		
		$.post(\"perfil.php\",{
  				
				profavatbuffer: escape(getText('profavatbuffer')),
				profassi: escape(getText('profassi')),
				profhome: escape(getText('profhome')),
				profmail: escape(getText('profmail')),
				profsobre: escape(getText('sobre_mim')),
				edit: 1
				
		} , function(txt){
				if(txt.length > 0){
					
					if(txt == 4)
						alert('Dados actualizados com sucesso.');
					else
						alert( unescape(txt) );
	
					
						
				}
		
		});

		reloadiframe ( getText('profavatbuffer') );
		
	});
	
	$('#profpassalter').click(function(){ 
			
			$('#profpassgrav').css('visibility','visible');
			$('#profpass').css('visibility','visible');
			$(this).css('cursor','text');
			$(this).css('color','black');
			$(this).html('Password actual:');
		
		});
	
	});
 	 
	function reloadiframe (img) {
		
		var f = document.getElementById('profimgalter');
		f.src = 'listavat.php?id=profavatbuffer&previous='+img;
		
	}	
 </script> 
 ";

	if ( isset($_GET['modedit']) && is_numeric($_GET['modedit']) && isset($_GET['perfil']) &&
		is_numeric($_GET['perfil']) && isset($_SESSION['id_user']) && $_SESSION['id_user'] ==
		$_GET['perfil'] )
	{
		
		$id = $_GET['perfil'];
		
		
		$num = mysql_result( $bd->submitQuery("Select count(*) From `post` Where 
		`registo_id_registo` = " . $_SESSION['id_user']), 0, 0 );

		echo "<div class=\"local\"> Status :: Dados pessoais </div>";
		
		if(isset($_FILES["foto"])){
			
				$erros = uploadImagem( $_FILES['foto'], $_GET['perfil'] );
				
				if( count($erros) > 0 ){
					
					print_r($erros);
					
				} else {
						
					echo "O upload da sua imagem foi feito com sucesso.
					<img src=\"imagens/b_certo2.png\" alt=\"[Sucesso]\" />";
					
				}
				
		}
		
		echo "<table style=\"width: 590px;\">
		<tr>
			<td colspan=\"2\">
				<input style=\"width: 580px;\" type=\"button\" 
				value=\"Alterar\" class=\"forms\" id=\"prof\" />
			</td>
		</tr>
		
		<tr>
			<td>
			<div class=\"right\">Nick:</div></td>
			<td>" . $_SESSION['user'] . "</td>
		</tr>
		
		<tr>
			<td>
				<div class=\"right\">Estatuto:</div>
			</td>
			
			<td>
				" . $_SESSION['estat_nome'] . "
			</td>
			
		</tr>
		
		<tr>
			<td>
				<div class=\"right\">Nº Mensagens no fórum:</div>
			</td>
			
			<td>
				$num
			</td>
			
		</tr>
		
		<tr>
			<td>
				<div class=\"right\">Data de Registo:</div>
			</td>
			
			<td>
				" . $_SESSION['data_registo'] . "
			</td>
			
		</tr>
		<tr>
			<td><div class=\"right\">Password:</div></td>
			<td>
			
			<font color=\"blue\">
			<span id=\"profpassalter\" style=\"cursor: pointer;\">Alterar</span>
			
			<input maxlength=\"100\" style=\"visibility: hidden;width: 100px;\" 
			type=\"password\" class=\"forms\" name=\"profpass\" id=\"profpass\" />
			
			<span id=\"profpassgrav\" style=\"cursor: pointer;visibility: hidden;\">Confirmar
			</span>
			
			</font>
			</td>
		</tr>
		<form id=\"profuppr\" name=\"profuppr\">	
		<tr>
			<td colspan=\"2\">
			<input type=\"hidden\" value=\"" . $_SESSION['avatar'] . "\" 
		    class=\"forms\"  name=\"profavatbuffer\" 
			id=\"profavatbuffer\" />
			<iframe frameborder=\"0\" id=\"profimgalter\" name=\"listutilsiframe\" 
			src=\"listavat.php?id=profavatbuffer
			&amp;previous=" . substr( $_SESSION['avatar'], 15, strlen($_SESSION['avatar']) ) .
			"\" 
			width=\"580\" 
			height=\"100\" scrolling=\"auto\">
			</iframe>
			</td>
		</tr>
		<tr>
			<td>
				<div class=\"right\">Assinatura:</div>
			</td>
			
			<td>
				<input maxlength=\"150\" type=\"text\" 
				value=\"" . rawurldecode( $_SESSION['assi'] ) . "\" 
				style=\"width: 300px;\" 
				class=\"forms\" name=\"profassi\" id=\"profassi\" />
			</td>
			
		</tr>
		<tr>
			<td>
				<div class=\"right\">Homepage:</div>
			</td>
			
			<td>
				<input maxlength=\"150\" type=\"text\" 
				value=\"" . $_SESSION['home_pag'] . "\"
				style=\"width: 300px;\" 
				class=\"forms\" name=\"profhome\" id=\"profhome\" />
			</td>
			
		</tr>
		
		<tr>
		
			<td>
				<div class=\"right\">Email:</div>
			</td>
			
			<td>
				<input
				maxlength=\"35\"
				type=\"text\" value=\"" . $_SESSION['e_mail'] . "\" 
				style=\"width: 300px;\" 
				class=\"forms\" name=\"profmail\" id=\"profmail\" />
			</td>
			
		</tr>
		
		</form>
		
		<tr>
		
			<td>
				<div class=\"right\">Upload de avatar:</div>
			</td>
			
			<td>
				<form enctype=\"multipart/form-data\" 
				action=\"?elem=10&amp;perfil=$id&amp;modedit=1\" method=\"post\">
				<input class=\"forms\" type=\"file\" size=\"32\" name=\"foto\" value=\"\" />
				<input class=\"forms\" type=\"submit\" value=\"Enviar\" />
				</form>
			</td>
			
		</tr>
		
		<tr>
		
			<td>
				<div class=\"right\">Sobre mim:</div>
			</td>
			
			<td>
				<textarea name=\"sobre_mim\" id=\"sobre_mim\" class=\"forms\" style=\"width:300px;height:170px;\">" . $_SESSION['mim'] . "</textarea>
			</td>
			
		</tr>
		
		
		
		</table>
		
		";
		
		
	}
	else
		if ( isset($_GET['perfil']) && is_numeric($_GET['perfil']) )
		{
			
			$id_registro = $_GET['perfil'];

			$query = $bd->submitQuery( "Select `estatuto_id_estatuto`,`registo_nick`
		,DATE_FORMAT(`registo_data`, '%d-%m%-%Y'),`registo_avatar`,`registo_ass`
		,`registo_data_ultima`,`registo_homepage`
		,`registo_mail`,`registo_online` From `registo` Where `id_registo` = $id_registro" );


			if ( mysql_num_rows($query) == 1 )
			{
			
				$nick = mysql_result( $query, 0, 1 );
				
				$datregistro = mysql_result( $query, 0, 2 );
				
				$avatar_buf = mysql_result( $query, 0, 3 );
				
				$email = mysql_result( $query, 0, 7 );
				
				if ( trim($email) == "" || $email == null )
					$email = "";
				else
					$email = "<a href=\"mailto:$email\">
							<img src=\"imagens/email.png\" border=\"0\" alt=\"[PM]\" 
							title=\"Enviar email para $email\" /></a>";
				
				$assi = mysql_result( $query, 0, 4 );

				$estat = $bd->submitQuery( "Select `estatuto_nome` From `estatuto` 
				Where `id_estatuto` = " . mysql_result($query, 0, 0) );

				
				
				if ( mysql_num_rows($estat) == 1 )
					$estat = mysql_result( $estat, 0, 0 );
				else
					$estat = "";

				$num_posts = mysql_result( $bd->submitQuery("Select count(*) From `post` 
				Where `registo_id_registo` = $id_registro"),0, 0 );

				$home = mysql_result( $query, $i, 6 );

				if ( trim($avatar_buf) == "" || $avatar_buf == null )
				{
					$avatar_buf = "imagens/avatar/$avatdefault";
				}
				
					if ( $home == "" || $home == null )
						$home = "";
					else{
					
					if( ! preg_match("/^http:\/\/.*$/",$home) ) $home = "http://".$home;
					
					$home = "<a href=\"$home\" target=\"_blank\">
					<img src=\"imagens/home.png\" alt=\"[Home]\" 
					title=\"Home\" border=\"0\">
					</a>";
					
					}
					
				$respeito = mysql_result($bd->submitQuery("SELECT 
				((SELECT Count( * ) 
				FROM `controlo_respeito` 
				WHERE `controlo_respeito_tipo` = 1 And `post_registo_id_registo` = $id_registro) - 
				(SELECT Count( * ) FROM `controlo_respeito` 
				WHERE `controlo_respeito_tipo` = 0 And `post_registo_id_registo` = $id_registro))"	
				),0,0);
					
				echo "
		
		<div class=\"local\"> A ver o perfil :: " . $nick . " </div>
		
		<div id=\"perfilgeral\">
			
			<div id=\"perfilhead\">
			
			<b>A ver o perfil :: " . $nick . "</b> 
			
			</div>
			
			<div class=\"floatleft\">
			
				<div class=\"floatleft\">
					
					<div><div class=\"avattitwidth perfilgehead\">
					<b>Avatar</b></div>
					<div class=\"avatbodywidth bodyperfilpre\">
					
					<center><div class=\"avatarcontent\">
					<img src=\"$avatar_buf\" title=\"Avatar $nick\" 
					alt=\"[Indisponível]\"></div></center>
					
					</div></div>
					
					<div>
					<div class=\"avattitwidth perfilgehead\">
					
					<b>Contacto de " . $nick . "</b>
					
					</div>
					<div class=\"avatbodywidth bodyperfilpre\">
					<table>";

				if ( isset($_SESSION['id_user']) )
					echo "<tr>
							<td style=\"text-align: right;\">Mensagem privada:</td>
							<td>
							<a href=\"".$_SERVER['PHP_SELF']
							."?elem=11&amp;nova=1&amp;to=$id_registro\">
							<img src=\"imagens/icon_pm.gif\" border=\"0\" alt=\"[PM]\" 
							title=\"Private Message\" /></a>
							</td>
						</tr>";

				echo "<tr>
							<td style=\"text-align: right;\">Email:</td>
							<td>$email</td>
						</tr>
					</table>
					</div></div>
					
				</div>
				
				<div id=\"perfiltudo\">
				<div class=\"perfilgehead\" style=\"margin-right: 0px;\">
				
				<b>Tudo acerca de " . $nick . "</b>
				
				</div>
				<div class=\"pretudo bodyperfilpre\">
				<table>
				
				<tr>
					<td>
						Registrado em<br />
					</td>
					<td>
						$datregistro<br />
					</td>
				</tr>
				
				<tr>
					<td>
						Estatuto:<br />
					</td>
					<td>
						$estat<br />
					</td>
				</tr>
				
				<tr>
					<td>
						Pontos de respeito:<br />
					</td>
					<td>
						$respeito<br />
					</td>
				</tr>
				
				<tr>
					<td>
						Total de mensagens:<br />
					</td>
					<td>
						$num_posts<br />
					</td>
				</tr>
				
				<tr>
					<td>
						Página/WWW:<br />
					</td>
					<td>
						$home<br />
					</td>
				</tr>
				
				<tr>
					<td>
						Assinatura:<br />
					</td>
					<td>
						$assi<br />
					</td>
				</tr>
				
				</table>
				</div>
				</div>

			</div>
			
			<div class=\"perfilfooter\"></div>
		</div>";

			

			}
			else
			{

				fazerErro( "Esse utilizador não existe" );

			}


		}
		else
			if ( isset($_GET['u']) && $_GET['u'] == 1 )
			{
				//Como esta dentro de uma iframe esta acção não pode ser levada a cabo
				//if( ! defined( 'IN_PHPAP' ) ) die();
				
				listarUtils( " Where `registo_online` Is Not Null ", "Online" );


			}
			else
			{
				//Como esta dentro de uma iframe esta acção não pode ser levada a cabo
				//if( ! defined( 'IN_PHPAP' ) ) die();
				
				
				listarUtils( "", "Todos" );

			}

}

/**
 * listarUtils()
 * 
 * Lista utilizadores off ou online.
 * 
 * @param $param Parametro de pesquisa na tabela `registo` para listagem
 * @param $tit Título dos utilizadores listados
 *   
 * @return void
 */
function listarUtils( $param, $tit )
{
	
	$bd = ligarBD();
	
	//Número de páginas totais
	$query_count_spam = floor( (mysql_result(
	$bd->submitQuery("Select Count(*) From `registo` $param"), 0, 0) ) / 6 );
	
	$pagi = clearGarbadge( $_GET['pagi'], false, false);

	$pagf = clearGarbadge( $_GET['pagf'], false, false);

	if ( ! is_numeric($pagi) || ! is_numeric($pagf) || $pagi < 0 || $pagf < 0 )
	{

		$pagi = 0;
		
		$pagf = 5;

	}

	echo "<div class=\"local\">A ver utilizadores :: $tit</div>";


	echo "<div >
	<table style=\"border: 1px solid #FF9900;width: 590px;height:auto;margin: auto;\">
		  
		  <tr style=\"text-align: center;background-image: URL('imagens/faqtit.png');height:27px;
		  background-repeat: repeat-x;\">
		  	<td style=\"padding: 2px;\"><center>#</center></td>";

	if ( isset($_SESSION['id_user']) )
		echo "<td style=\"padding: 2px;\"><center>PM</center></td>";

	echo "<td style=\"padding: 2px;\"><center>Utilizador</center></td>
		  	<td style=\"padding: 2px;\"><center>Email</center></td>
		  	<td style=\"padding: 2px;\"><center>Registrado em</center></td>
		  	<td style=\"padding: 2px;\"><center>Mensagens</center></td>
		  	<td style=\"padding: 2px;\"><center>Página/WWW</center></td>
		  <tr>";


	$query = $bd->submitQuery( "Select `registo_nick`,`registo_mail`,`registo_data`
	,`registo_homepage`,`id_registo` From `registo` $param Order By `id_registo` Asc 
	Limit $pagi,$pagf" );

	$ind = false;


	for ( $i = 0; $i < mysql_num_rows($query); $i++ )
	{

		if ( $ind )
		{
			$fla = "E0E0DF";
			$ind = false;
		}
		else
		{
			$fla = "F0F0EF";
			$ind = true;
		}

		$home = mysql_result( $query, $i, 3 );
			
		if ( $home == "" || $home == null ){
		
			$home = "";
		
		}else{
			
		
		
			if(substr($home,0,7) != "http://") $home = "http://".$home;
		
			$home = "<center><a href=\"$home\">
			<img src=\"imagens/home.png\" alt=\"[Home]\" title=\"Home\" border=\"0\">
			</a></center>";
		
		}
		$email = mysql_result( $query, $i, 1 );

		$id = mysql_result( $query, $i, 4 );

		if ( $email != "" && $email != null )
			$email = "<center><a href=\"mailto:$email\">
					<img src=\"imagens/email.png\" title=\"Email\" border=\"0\"
					alt=\"[Email]\"></center>";
		else
			$email = "";


		$nick = mysql_result( $query, $i, 0 );

		$regdate = mysql_result( $query, $i, 2 );

		$men = mysql_result( $bd->submitQuery("Select count(*) From `post` 
		Where `registo_id_registo` = $id"),
			0, 0 );

		echo "
		<tr style=\"margin-right: 10px;background-color: #$fla;\">
		  	<td><center>" . ( $i + 1 + $pagi ) . "</center></td>";
		if ( isset($_SESSION['id_user']) )
		{
			echo "<td>
			<center>
			<a href=\"".$_SERVER['PHP_SELF']."?elem=11&amp;nova=1&amp;to=$id\">
			<img src=\"imagens/icon_pm.gif\" border=\"0\" alt=\"[PM]\" 
			title=\"Private Message\" />
			</a></center>
			</td>";
		}

		echo "<td><center><a href=\"".$_SERVER['PHP_SELF']
		."?elem=10&amp;perfil=$id\">$nick</a></center></td>
		  	<td>$email</td>
		  	<td><center>$regdate</center></td>
		  	<td><center>$men</center></td>
		  	<td>$home</td>
		  <tr>";

	}

	echo "</table></div>
	";
	
	mysql_free_result($query);
	
	if( $query_count_spam > 0 ) {
	
	//Divisão do spam por páginas
	echo "<div class=\"listpags\" style=\"margin-left: 4px;float: left;\">";

	//Página actual
	$pag_actual = floor ( $pagi / $pagf );

		if ( $pag_actual > 0 )
			echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a href=\"?elem=10&amp;pagi=" . ( $pagi - 5 ) . "&amp;pagf=5\" title=\"Recuar para página anterior\">&lt;</a></div>";

	echo "<div class=\"pags\" 
	style=\"margin-left: 0px;\">$pag_actual de $query_count_spam</div>";

		if ( $query_count_spam > 0 && $pag_actual < $query_count_spam )
			echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a href=\"?elem=10&amp;pagi=" . ( $pagi + 5 ) . "&amp;pagf=5\" title=\"Avançar para a próxima página\">&gt;</a></div>";

	echo "</div>";
	
	}
	
	
	
}
?>