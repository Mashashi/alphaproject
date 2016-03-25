<?php

/**
 * Segundo ficheiro de funções.
 * 
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

/**
 * Incluir os métodos já definidos nos outros ficheiros de funções.
 *  	
 */
include_once("funcoes_avan.php");

/**
 * doBackupToBd()
 * 
 * Fazer um backup à base de dados
 * 
 * @global String bd
 * @global String Host da bd
 * @global String Pass da bd
 * @global String User da bd
 * @global String Porta da bd
 * @global String Opções de back up
 *   
 * @return void
 */
function doBackupToBd(){
	
	global $db;
	
	$dir = "backups_bd_$db";
	
	if ( confirmParamsBack($dir) ){
			
	global $host;
	
	global $pass_bd;
	
	global $user_bd;
	
	global $portdb;
	
	global $optbackup;
	
	if ( !file_exists  ( $dir  ) ){
		
		mkdir($dir);
	
	}
	
	$backupfile = "$dir/$db"."#". date("Y-m-d-H-i-s")  . ".sql";
	
	$command = 
	"mysqldump --opt $optbackup --host=\"$host\" --user=\"$user_bd\" --port=\"$portdb\" --password=\"$pass_bd\" \"$db\"  > $backupfile ";
	
	System($command);
	
	/*$retorno = "";
	echo $command."<br />";
	echo ($retorno);*/
	
	}
	
}

/**
 * confirmParamsBack()
 *  
 * Confirmar se é necessário fazer um backup.
 * 
 * @param $dir String Directório de pesquisa.
 *
 * @global Intervalo de espaço em horas que deve ser feito um back up 
 *
 * @return boolean
 */
function confirmParamsBack($dir){

	global $dothebackupin; 
	
	$response = false;
	
	if ( !file_exists  ( $dir  ) ){
		
		mkdir($dir);
	
	}
	
	if( $dir = opendir($dir) ){
		
		$aux = 0;
		
		while ( ($arquivos = readdir($dir)) != false )
		{
			
			$arquivos = substr($arquivos, strrpos($arquivos,"#"), strlen($arquivos) );
			
			$arquivos = preg_replace("/[^0123456789]/","",$arquivos);
			
			$arquivos = substr($arquivos, 0, strlen($arquivos)-4);
			
			if( $arquivos > $aux ) $aux = $arquivos;
			
		}
		
		if( 
		
			date("YmdH", mktime(substr($aux,8,2)+$dothebackupin,null,null,substr($aux,4,2),substr($aux,6,2),substr($aux,0,4))) < date("YmdH")
		
		){
		
			$response = true;
			
		}
		
	}
	
	return $response;
	
}


/**
 * sendEmail()
 * 
 * Enviar uma mensagem de email.
 * 
 * @param $to String Destinatário
 * @param $subject String Assunto
 * @param $body String Mensagem
 * 
 * @global Se se encontra um servidor SMTP disponivel localmente  
 * @global Email do admin.
 *
 * @return boolean
 */
function sendEmail($to, $subject, $body){
	
	global $availablesmtpserver;
	
	$response = false;
	
	if($availablesmtpserver){
	
		global $emailadmin;
	
		$subject = clearGarbadge(str_replace("\n", " ", $subject), false, false);
	
		$body = clearGarbadge( wordwrap( $body, 70, "\n" ), true, true );
	
		$body .= 
		"\n\nCom os melhores cumprimentos, a gestão da biblioteca da Escola Secundária Eça de Queirós @ Olivais @ Lisboa a ".date("j")." de ".nameMes(date("m")).", ".date("Y");
	
		$response = @mail($to, $subject, $body, "From: Alphaproject biblioteca <$emailadmin> \n\n");
	
	}
	
	return $response;
	
}



/**
 * Gerar um código randomicamente, neste código vão constar caracteres alfanumericos.
 * 
 * @param $digit Numero de dígitos que o código deve ter 
 * 
 * @return String  
 */
function genarateCod($digit){
	
// Neste array guardamos os caracteres para gerar o código.
$keys = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");

// Varre o array $keys para selecionar 4 caracteres.
$rand_keys = array_rand($keys, $digit);

// Guardamos nas respectivas variaveis
$key1 = $keys[$rand_keys[0]];
$key2 = $keys[$rand_keys[1]];
$key3 = $keys[$rand_keys[2]];
$key4 = $keys[$rand_keys[3]];

// Concatenando e depois registrando na sessão.
$gd_code = $key1.$key2.$key3.$key4;
	
return $gd_code;
	
}


?>