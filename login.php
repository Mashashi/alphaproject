<?php

/**
 * O nome pode ser enganador este ficheiro não só se encarrega das operações de 
 * login mas também de logout.
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
 * Incluir o ficheiro de configuração config.php.
 *  
 */
include_once ( "config.php" );

if( isset($_SESSION['id_user']) ){

if( !validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] ) ){
	
		
		session_unset();
			
		echo "<script type=\"text/javascript\">location.href = \"index.php\"</script>";
		
	
}

}

if ( isset($_GET['act']) )
{

	if ( ($_GET['act'] == "logout") && isset($_SESSION['user']) )
	{
		
		if( validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] ) ){
		
			$basedados = new bd();
			
			$basedados->setLigar( $host, $user_bd, $pass_bd, $db );
			
			$basedados->submitQuery( "Update `registo` Set `registo_online` = null , `registo_data_ultima` = '" . date("YmdHis") . "' 
			Where `id_registo` = " . $_SESSION['id_user'] );
			
			$basedados->submitQuery( "Delete From `session_control` Where `registo_id_registo` = " . $_SESSION['id_user'] );
			
			session_unset();
			
			echo "<script type=\"text/javascript\">location.href = \"index.php\"</script>";
		
		}
		
	}

}


if ( ! isset($_SESSION['user']) )
{

	if ( (isset($_POST['pass'])) && (isset($_POST['user'])) )
	{

		$pass = rawurldecode( $_POST['pass'] );

		$user = rawurldecode( $_POST['user'] );

		addslashes( $user );

		addslashes( $pass );

		strip_tags( $user );

		strip_tags( $pass );

		$index = 0;

		if ( empty($user) )
		{

			echo "O Utilizador tem de ser informado!";

			die;

		}

		if ( empty($pass) )
		{

			echo "A Password tem de ser informada!";

			die;
		}

		$basedados = "";

		$basedados = new bd();

		$basedados->setLigar( $host, $user_bd, $pass_bd, $db );


		$is = $basedados->submitQuery( "Select 
	   `registo_pass`,`estatuto_id_estatuto`,`registo_sha1`,`registo_data_ultima`,`registo_online` 
	   ,DATE_FORMAT(`registo_is_activo`, '%Y-%m%-%d') From `registo` Where `registo_nick` 
	   Like '$user' COLLATE latin1_general_cs" );
	
		if ( mysql_num_rows($is) > 0 )
		{

			$array_result = "";
			$index = 0;
			
			while ( $row = mysql_fetch_array($is) )
			{
			
				for ( $i = 0; $i < count($row) - (count($row) / 2); $i++ )
					$array_result[$index++] = $row[$i];
			
			}


			if ( $array_result[4] != null && $canlogsimul )
				die( rawurlencode("Por favor confirma os dados de login!") );

			$pass_user_bd = $array_result[0];
			//Ver se o utilizador tem o sha activado
			if ( $array_result[2] == 1 )
				$pass = sha1( $pass );


			if ( ($pass_user_bd) == ($pass) )
			{

				$blo = mysql_result( $is, 0, 5 );
				
				$comp = str_replace( "-", "", $blo );
				
				//Ver se o utilizador esta bloqueado
				if ( $comp < date("Ymd") )
					echo "1";
				else
				{

					$blo = explode( "-", $blo );
					$blo = $blo[2] . "-" . $blo[1] . "-" . $blo[0];
					echo rawurlencode( "Este cadastro esta temporariamente indesponível até:\n$blo\nPor má conduta dentro do síte." );

				}

			}
			else
			{

				echo rawurlencode( "Por favor confirma os dados de login!" );

			}

		}
		else
		{

			echo rawurlencode( "Por favor confirma os dados de login!" );

		}

		


	}
	else
	{
	
	if( ! defined( 'IN_PHPAP' ) && ! isset($_POST["reporlogin"]) ) die();
		
		$request_uri = $_SERVER['REQUEST_URI'];
		
		if( isset($_POST["reporlogin"]) ){
			
			$request_uri = substr($_SERVER['REQUEST_URI'],0,strrpos($_SERVER['REQUEST_URI'],"/") )."/index.php";
			
		}
		
		if($_GET["getnewcoderecov"] == 1){
			
			
			include_once("recovpass.php");
			
			
		} else {
	
	echo "
	<div id=\"genericBlockId1\">

	<script type=\"text/javascript\">
		$(document).ready(function(){
		
		$(\"#recoverPass\").click(function(){
		
				
			$(\"#genericBlockId1\").load(\"recovpass.php\", {recovinter: 1} );

			
		});
			
		/*Meter os campos de login sempre que não estam preechidos
		como texto Utilizador e Password*/
		$(\"#userlogin, #passlogin\").blur(function(){
			
			if(getText(this.id) == \"\"){
			
				if(this.id == \"userlogin\")
				
					setText('Utilizador',this.id);
				
				else
					
					setText('Password',this.id);
			}
			
		});
		
		$(\"#userlogin, #passlogin\").click(function(){
		
			setText('',this.id);
			
		});
		
		$(\"#fazerlogin\").click(function(){
			
			log('login.php','POST',$('#loginform').serialize(true),true);
		
		});
		
		
		
		});
		
	</script>

Entrar
<hr />

<form name=\"loginform\" id=\"loginform\" action=\"" . $request_uri . "\" method=\"post\">

<input maxlength=\"15\" class=\"forms\" type=\"text\" name=\"user\" 
id=\"userlogin\" value=\"Utilizador\" />

<div class=\"float-divider\"></div>

<input maxlength=\"100\" class=\"forms\" type=\"password\" name=\"pass\" id=\"passlogin\" 
value=\"Password\" />

<div class=\"float-divider\"></div>

<input type=\"button\" id=\"fazerlogin\" value=\"Entrar\" class=\"forms\" />";

if($availablesmtpserver)
echo "<p><a href=\"#\" id=\"recoverPass\">Esqueci-me da password</a></p>";

/*if(!isset($_POST["reporlogin"]))
echo "<div id=\"timeleftt\"><div id=\"timelefttt\">0</div></div>";*/

echo "</form>

</div>
";
	
	}



	}


}
else
{
	
	if( ! defined( 'IN_PHPAP' ) ) die();
	
	echo "<div style=\"text-align: center;\">";

	echo "<p>Bem-vindo <font color=\"#FF9900\"><b><a class=\"underline\" href=\"?elem=10&amp;perfil=" . $_SESSION['id_user'] . "&modedit=1\" title=\"Perfil\">" . $_SESSION['user'] .
		"</a></b></font>";


	$bd = new bd();
	
	$bd->setLigar( $host, $user_bd, $pass_bd, $db );
	
	

	$query = $bd->submitQuery( "Select count(*) From `mensagem` Where `mensagem_data` >= 
	(Select `registo_data_ultima` From `registo` Where `id_registo` = " . $_SESSION['id_user'] .") 
	And `mensagem_destinatario` = " . $_SESSION['id_user'] );

	if ( mysql_result($query, 0, 0) > 1 )
	{
		$nummen = "Tens " . mysql_result( $query, 0, 0 ) . " novas mensagens";
		$img = "imagens/envelope_48.png";
	}
	else
		if ( mysql_result($query, 0, 0) == 1 )
		{
			$nummen = "Tens " . mysql_result( $query, 0, 0 ) . " nova mensagem";
			$img = "imagens/envelope_48.png";
		}
		else
			if ( mysql_result($query, 0, 0) == 0 )
			{
				$nummen = "Não tens novas mensagens";
				$img = "imagens/fpagtoCorreio2.png";
			}
	

	echo "
	<a href=\"?elem=11\">
	<img border=\"0\" src=\"$img\" title=\"$nummen\"
	alt=\"[Mensagens Privadas]\" /></a></p>
	<div class=\"avatarcontent\">
	<img src=\"" . $_SESSION['avatar'] . "\" title=\"Avatar\" alt=\"[Avatar]\" />
	</div></p>";

	if ( str_replace("-", "", $_SESSION['ultvisi']) < date("dmY") )
		echo "<p>Sentiamos a sua falta desde <font color=\"#FF9900\">
			<b>" . $_SESSION['ultvisi'] . "</b></font></p>";

	echo "
	<p><a href=\"$phpself?act=logout\" title=\"Logout\">Sair</a></p>";

	echo "	
	<div id=\"timeleftt\">Serás dado como offline em: 
	
	<div id=\"timelefttt\">" . $_SESSION['timelefttt'] . "</div>
	
	</div>
	
	</div>";

}



?>