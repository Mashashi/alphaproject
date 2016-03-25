<?php

/**
 * Recuperar a password.
 * 
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

/**
 * Incluir as funções mais avançadas para porder através da função sendEmail(),
 * enviar o email com a password.
 *   
 */
include_once("funcoes_mais_avan.php");

//Ver se existe um servidor SMTP disponível

if($availablesmtpserver){

if(isset($_POST["util_name"]) && !defined("IN_PHPAP") ){

	session_start();
	
	if($codAntiBot == $_SESSION['gd_code']){
	
	if($_SESSION['used_gd_code'] == 0){
	
	$_SESSION['used_gd_code']++;
		
	$bd = ligarBD();
	
	$util_name = clearGarbadge( $_POST["util_name"], false, false );
	
	$util_data = $bd->submitQuery("Select `registo_pass`,`registo_mail` 
	From `registo` 
	Where `registo_nick` Like '$util_name'");
		
	if( mysql_numrows($util_data) == 1){
		
		$subject = "Recuperação de password";
		
		$body = "A pedido da requesição que foi feita os dados do seu login são:
		Nick: $util_name
		Password: ".mysql_result($util_data,0,0).
		"\nSe recebeu este email por engano contacte a administração da bilioteca.";
		
	
		
		if( sendEmail(mysql_result($util_data,0,1), $subject, $body) ){
			
		echo 
		rawurlencode(
		"
		<font color=\"green\">Um 
		email com os dados do seu login foi enviado para o seu email.</font>");
			
		} else {
			
		echo 
		rawurlencode(
		"
		<font color=\"red\">N&atilde;o foi poss&iacute;vel enviar o email com os dados do seu login.</font>");
			
		}
		
	} else {
		
		echo rawurlencode("<font color=\"red\">Esse utilizador n&atilde;o existe.</font>");
		
	}
	} else {
	
		echo "<font color=\"red\">Esse texto anti-bot j&aacute; foi usado.</font>";
	
	} 
	} else {
		
		echo 
		rawurlencode("<font color=\"red\">O texto anti-bot est&aacute; incorrecto.</font>");
		
	}
} elseif( isset($_POST["recovinter"]) || defined("IN_PHPAP") ){
	
	echo "
	<div id=\"genericBlockId1\">
	
	<script type=\"text/javascript\">
	
		$(document).ready(function(){
			
		$(\"#recoverPassRepor\").click(function(){
		
			$(\"#genericBlockId1\").load(\"login.php\", {reporlogin: 1});
		
		});
		
		$(\"#dorecuperarpass\").click(function () {
	   	
		  
	   	
		$.ajax({
			
			type: \"POST\",
			url: \"recovpass.php\",
			data: \"util_name=\"+getText(\"nickrecovery\")+\"&codAntiBot=\"+
			getText(\"codAntiBot\"),
			dataType: \"html\",
			success: function(txt){	
				
				$(\"#errorrecovery\").html( unescape(txt) );
					
			},
			error: 
				function(){ 
				$(\"#errorrecovery\").html(
				\"Lamentamos mas de momento não é possível efectuar o pedido.\");
				
				}, beforeSend:
				function (){
					
					$(\"#errorrecovery\").html(
					'<img src=\"imagens/indicator.gif\"'+
					'alt=\"A carregar...\" title=\"A carregar\" />');
					
				}
		});
		
	});
	
	}); 
		
	</script>
	
	Recuperar a password<hr />
	
	<p><label for=\"nickrecovery\">
	Introduza o nome de utlizador<br />
	<input type=\"text\" maxlength=\"15\" name=\"nickrecovery\" id=\"nickrecovery\" 
	class=\"forms\" />
	</label></p>
	
	<div><img src=\"img.php\" 
	alt=\"Texto anti-bot\" id=\"imgrecoverbot\" title=\"Texto anti-bot\" /></div>
	
	<form method=\"get\" action=\"".$_SERVER['PHP_SELFs']."\">
		<input type=\"submit\"
		id=\"newcoderecov\" value=\"Outro texto\" class=\"forms\" />
		<input type=\"hidden\" value=\"1\" name=\"getnewcoderecov\">
	</form>
	
	<label for=\"codAntiBot\">
	<br />Insira o texto acima
	<input type=\"text\" name=\"codAntiBot\" id=\"codAntiBot\" class=\"forms\" />
	</label>
	
	<p><input type=\"button\" class=\"forms\" id=\"dorecuperarpass\" 
	value=\"Recuperar pass\" /></p>
	
	<div id=\"errorrecovery\"></div>
	
	<a href=\"#\" id=\"recoverPassRepor\">Repor menu de login</a>
	
	<p>Nota a pass vai ser enviada para o email da sua conta.</p>
	
	</div>
	";

}

}
?>