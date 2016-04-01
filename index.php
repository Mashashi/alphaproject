<?php

/**
 * Index do s�te, todos os conte�dos iram aparecer dentro da div principal,
 * (O contentor principal), seram chamados atrav�s da passgem de vari�veis 
 * pelo metodo GET que seram validadas pelo ficheiro principal.php.
 * Em resumo esta p�gina faz com que atrav�s de css v�lido a desposi��o de todos os 
 * elementos na p�gina.
 * Neste documento existem duas fun��es embutidas que fazem a verifica��o dos 
 * utilizadores online
 * e a valida��o do utilizador a quando do login.
 * Possui tamb�m ainda um pequeno exerto de c�digo que faz o refrescamento da 
 * p�gina a quando o logout.   
 *  
 * Neste documento e ao longo do trabalho � usada com frequ�ncia  a fun��o 
 * include_once() que noscorrige no caso de incluirmos um ficheiro 
 * php mais do que uma vez.
 * 
 * Mais informa��o sobre a fun��o include_once():
 * <i>"include_once() pode ser utilizado nos casos em que o mesmo arquivo pode acabar 
 * sendo inclu�do mais de uma vez durante a execu��o de um script em particular, 
 * quando na verdade ele s� pode ser inclu�do apenas uma para evitar problemas com 
 * redefini��es de fun��es, altera��es nos valores de vari�veis, etc."</i>
 * 
 *  {@link http://pt2.php.net/include_once Documenta��o oficial da fun��o include_once()} 
 *    
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 */
 
/**
 * Definir a constante que vai servir de flag a documentos como anivers�rios para
 * saberem se est�o ou n�o inclu�dos em index.php.
 */
define('IN_PHPAP', true);

/**
 * Incluir funcoes_avan.php.
 *  
 */
include_once ( "funcoes_mais_avan.php" );

$bd = ligarBD();

session_start();

/**
 * Verificar se � preciso fazer o update da bd.
 *
 * @see funcoes_mais_avan.php
 */
doBackupToBd();

/**
 * Fazer tentativa de login caso as vari�veis abaixo estejam inicializadas.
 *
 * @see funcoes.php
 */
validarUser( $_POST['user'], $_POST['pass'] );

/**
 * Garantir a integridade da sess�o.
 *
 * @see funcoes.php
 */
onLine( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );


/*echo sendEmail("r.rafael.campos@gmail.com", "Recupera��o de password"
, "A sua password �.");*/

//Criar um direct�rio novo onde o user guarda os cookeis para que n�o haja misturas entre sess�es do pes e da biblioteca
/*if(	!strpos(session_save_path(),"biblioteca") )
	session_save_path(session_save_path()."biblioteca");*/
//echo session_save_path();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	
	<!--Autores-->
	<meta name="author" content="11_12_TurmaI1_2008_2009" />
	
	<!--Email de contacto-->
	<meta name="reply-to" content="<?=$emailadmin;?>" />
	
<?
//<!--Fazer refresh passados 5 minutos de inactividade-->
if($temprefre>0)
echo "<meta http-equiv=\"refresh\" content=\"".($temprefre*60).";url=".$_SERVER['PHP_SELF']."\">";
?>
	
	<link rel="shortcut icon" href="imagens/favicon.png" />
	
	<link href="css/estilo.css" rel="stylesheet" type="text/css" />
	
	<!--[if IE]>
		<link href="css/estilo_fix.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	
	<!--Adicionar as tags dos elementos em que pngs se utilizam-->	
	<!--[if lt IE 7.]>
	<style type="text/css">
		img, p, div { behavior: url(css/iepngfix.htc); }
	</style> 
	<![endif]-->
	
	<!--JQuery-->
	<script type="text/javascript" src="javascript/jquery-1.2.6.js"></script>
	
	
	<?
	//Este js est� configurado s� para aparecer quando estamos a observar um �lbum como administrador
	if(  ( $_GET["elem"] == 2 ) &&  ( $_GET["accao"] == 7 ) 
	&& isset($_GET["album"]) && $_SESSION['estat_carac'][7] )
	echo "<!--table drag-drop plugin-->
	<script type=\"text/javascript\" src=\"javascript/jquery.tablednd_0_5.js\"></script>";
	?>
	
	
	<?
	//Este js est� configurado s� para aparecer quando estamos a editar uma faq
	if(  $_GET["elem"] == 2 &&  $_GET["accao"] == 11 && $_SESSION['estat_carac'][9]  )
	echo "<!--Editar FAQ-->
	<script type=\"text/javascript\" src=\"javascript/jqueryEIP.js\" ></script>";
	?>
	
	
	<!--Acordion-->
	<script type="text/javascript" src="javascript/acordion/chili-1.7.pack.js">
	</script>
	<script type="text/javascript" src="javascript/acordion/jquery.easing.js">
	</script>
	<script type="text/javascript" src="javascript/acordion/jquery.dimensions.js">
	</script>
	<script type="text/javascript" src="javascript/acordion/jquery.accordion.js">
	</script>
	
	
		
	
	<?
	if(  isset( $_SESSION['id_user']  )  )
	echo "
	<!--Calculadora js-->
	<script type=\"text/javascript\" src=\"javascript/jquery.calculator.pack.js\"></script>
	<!--Calculadora css-->
	<link href=\"css/jquery.calculator.css\" rel=\"stylesheet\" type=\"text/css\" />
	<!--M�scara de input-->
	<script type=\"text/javascript\" src=\"javascript/jquery.maskedinput-1.1.3.js\"></script>
	<!--Plugin para trabalhar com forms-->
 	<script type=\"text/javascript\" src=\"javascript/jquery.form.js\"></script>";
	?>
	

	
	<!--Rotinas-->
	<script type="text/javascript" src="javascript/javascript.js" ></script>
	
	<?
	 	$mes = date("m");
	 	
		if( ($mes > 10 || $mes < 3) && $snow_active ){
			
			echo 
			"<!--Neve-->
			<script type=\"text/javascript\" src=\"javascript/snowstorm.js\"></script>"
			;
		}
		
	?>
	


	<script type="text/javascript">
	
	$(document).ready(function(){
			
		$(window).load(function () {
				
		to =  <? echo  $temprefre ; ?>;
		
		if(to > 0)
    		setTimeout("reload('"+$_SERVER['PHP_SELF']+"')", to * 60000);
		time = Val( stripHTML( $('#timelefttt').html() ) );
		
		time == 0?$('#timeleftt').html(""):moveRelogio( time );
		
    	});
		
		

    });

	<?
		
			//Editar t�picos e posts quando se � tem permiss�es para tal		
			$perm = "1";
			
			if ( $_SESSION['estat_carac'][1] && $_SESSION['estat_carac'][0] )
				$perm = ".editaraquitopico, .editaraquipost";
			else
			if( $_SESSION['estat_carac'][0] ) 
				$perm = ".editaraquitopico";
			else
			if( $_SESSION['estat_carac'][1] ) 
				$perm = ".editaraquipost";
			
			if($perm != "1"){
				
				echo "var classesEdit = '$perm';";
				
				echo "var tt = '";

				if ( count($tooltiptags) > 0 ) echo join( '?', $tooltiptags );
					echo "';tt = explode('?',tt);var ts = '";
			
				if ( count($tagstoshow) > 0 ) echo join( '?', $tagstoshow );
					echo "';ts = explode('?',ts);";
				
				
			}			
		
	?>
		
	</script>
	
	<?
		
		if($perm != "1"){
			
			echo 
			"<!--Editar posts e t�picos-->
			<script type=\"text/javascript\" src=\"javascript/EIPposttopico.js\">
			</script>";
			
		}
	
	?>
	
	<title>Alpha Project</title>
	
</head>

<body onload="javascript:waitPreloadPage();">
<!--preloader-->

<div id="prepage" style="position:absolute; font-family:arial; font-size:16; left:0px; top:0px; background-color:white; layer-background-color:white; height:100%; width:100%;"> 
<table width="100%"><tr><td><b></b></td></tr></table>
</div>

<noscript>
<?
fazerErro( "Lamentamos mas para visualizar esta p�gina por 
completo o javascript presica de estar activado." );
/*die();*/
?>
</noscript>

<div id="total">

	<div id="top">
		
		<div id="banner">
		
		<a href="?elem=1">
			<img src="imagens/alpha.png"
			border="0" alt="[Home]" title="Home" id="cli" />
		</a>
			
		</div>
		
		<div id="contlibri" style="z-index: 1;position: absolute;" >
		<div id="libri"><img src="imagens/logobil.bmp" title="Biblioteca" alt="[Biblioteca]" /></div><div id="toglelibri"><img src="imagens/libritext.png" title="Deslizar" alt="[Deslizar]" />
		</div>
		</div>
		
		<div id="data">
		
		</div>
		
	</div>

	<div class="float-divider"></div>
		
		<div id="navtop">
			
			<?
/**
 * Incluir o ficheiro estado.php respons�vel por mostrar as frases aleat�rias na barra
 * de inform��o.
 *  	
 */
include_once ( "estado.php" );

		echo "<p>";
		mensagensInfomacao($mensagens_informacao);
		echo "</p>"; ?>	
		
		</div>
		
		<div class="float-divider"></div>

		
	<div id="menu">
	
	Data &amp; Hora<hr />
	<script type="text/javascript">
	if(navigator.javaEnabled())
	document.write(
	'<applet codebase="applets/" code="ClockApplet.class" width="184" height="45"></applet>');
	else
	document.write(
	'Instale e/ou configure correctamente o seu JRE para visualizar esta aplica��o.');
	</script>
	<p></p>
	
<?

/**
 * Incluir o ficheiro menu.php respons�vel por mostrar o menu principal.
 *  	
 */
include_once ( "menu.php" ); ?>
		
		</div>
	

		<div id="rightcont">
			
			<div id="principal">

				<?
/**
 * Incluir o ficheiro principal.php onde todos os conte�dos aparecem.
 *  	
 */
include_once ( "principal.php" ); ?>
					
		</div>
			
			<div id="extra">
				
				
				
				<div id="login">
					
				<?

/**
 * Incluir o ficheiro login.php onde � feito o output da form login e o output da �rea de logout.
 *  	
 */
include_once ( "login.php" ); ?>
					
				</div>
				
				<div class="float-divider"></div>
				
		<div class="basic" style="float:left;">
			<a class="trogle toogle">Quadro de Anivers�rios</a>
			<div>
				<p>
				
			<?

/**
 * Incluir o ficheiro aniversarios.php respons�vel por mostrar os aniversriantes que fazem anos
 * na data actual. 
 *  	
 */
include_once ( "aniversarios.php" ); ?>
			
			</p>
			</div>
				
				
				
		<a class="trogle toogle">Sondagens</a>
			<div>
				<p>
				
		<? echo commonInfo(); ?>
			
			</p>
			</div>
			</div>
				
			</div>
			
		</div>
	
	<div id="copyright">
		<div style="float: left;">
			Escola Secund�ria E�a de Queir�s | 2008 | 2009<br />
			Vers�o <?=VERSON_AP?> Alphaproject
		</div>	
		<!--
		Link para s�te de protec��o contra spam
		<div id="contends">
			<a href="http://portuguese-129759745111.spampoison.com">
			<img src="http://pics4.inxhost.com/images/sticker.gif" alt=\"[Spam]\" border="0" 
			width="80" height="15"/>		  
			</a>
		<div>-->
		<div id="contends">
			<a href="http://validator.w3.org/check?uri=referer">
		<img border="0" src="imagens/valid-xhtml10.png" alt="[CSS V�lido]" title="XHTML V�lido" />
			</a>
			<a href="http://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fwww.dominionautoauction.net%2F">
		<img border="0" src="imagens/vcss.gif" alt="[CSS V�lido]" title="CSS V�lido" />
			</a>
		</div>
	</div>

</div>
</body>
</html>

