<?php

/**
 * Ficheiro que lista os avatares dentro de uma iframe, e no evento onclick
 * a vari�vel $_GET['id'] que corresponde a o id do elemento input no parent ser� 
 * alterado para o valor do caminho da imagem no servidor que por defeito � 
 * imagens/avatar. $_GET['previous'] tem como fun��o indicar uma imagem 
 * a seleccionada quando o elemento � carregado.    
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
include_once ( "funcoes.php" );

/**
 * listArqDirec()
 * 
 * Retorna todas as imagens num direct�rio, j� com a formata��o feita.
 * Utiliza uma fun��o javascript que altera o valor do campo com o id $idresposta
 * quando esta fun��o se encontra dentro de uma iframe. 
 * 
 * @param String $pasta
 * @param String $idresposta 
 * 
 * @global String O endere�o do avatar que se encontra atribu�do ao user
 *      
 * @return String
 */
function listArqDirec( $pasta, $idresposta )
{
	global $pre;
	
	$pre = str_replace( "avatar/", "", str_replace("imagens/","",$pre) );
	
	if( preg_match("#^common_users/(.*)$#", $pre, $match) ){
		
		$resposta = 
		"<br />
		Imagem submetida por upload:<br/> 
		<img width=\"44\" height=\"44\"
		id=\"$pre\"
		src=\"imagens/avatar/$pre\"
		onclick=\"javascript: setText('$pasta/$pre','$idresposta');\"
		class=\"bor selected\" /> ";
		
	} 
		
	

	//Exibe os aquivos que tem na pasta
	if ( $dir = opendir($pasta) )
	{ //Diret�rio a ser listado

		$count = 0;

		while ( ($arquivos = readdir($dir)) != false )
		{
			$count++;
			if ( $arquivos == "." or $arquivos == ".." )
				continue;
			{
				if ( eregi("^.*\.(pjpeg|jpeg|png|gif|bmp)$", $arquivos) )
					if ( $pre == $arquivos )
						$resposta .= "<img width=\"44\" height=\"44\" 
	   class=\"bor selected\"
	   src=\"$pasta/$arquivos\"
	   id = \"$arquivos\"
	   alt=\"[$arquivos]\" 
	   onclick=\"javascript: setText('$pasta/$arquivos','$idresposta');\" />
	   ";
					else
						$resposta .= "<img width=\"50\" height=\"50\" 
	   class=\"bor\"
	   src=\"$pasta/$arquivos\"
	   id = \"$arquivos\"
	   alt=\"[$arquivos]\" 
	   onclick=\"javascript: setText('$pasta/$arquivos','$idresposta');\" />
		";

			} //fecha if

		} //fecha while

	}
		
	return $resposta;
}


/**
 * countArqDirec()
 * 
 * Conta os ficheiros num direct�rio. A excep��o daqueles que se chamem 
 * "index.html" ou "Thumbs.db"</code> 
 * 
 * @param String $pasta 
 *    
 * @return integer
 */
function countArqDirec( $pasta )
{

	$resposta = 0;

	if ( $dir = opendir($pasta) )
	{ //Diret�rio a ser listado

		while ( ($arquivos = readdir($dir)) != false )
		{

			if ( $arquivos == "." or $arquivos == ".." )
				continue;
			{

				if ( ! ($arquivos == "index.html" || $arquivos == "Thumbs.db") )
					$resposta++;

			}

		}

	}

	return $resposta;
}


$numero_avatares = countArqDirec( "imagens/avatar" );


if ( isset($_GET['id']) )
{

	$idres = clearGarbadge( $_GET['id'], false, false);

}
else
	die();

if ( isset($_GET['previous']) )
{

	$pre = rawurldecode( $_GET['previous'] );
  	
}

if ( $numero_avatares > 1 )
	$numero_avatares = "
		 <font color=\"brown\" face=\"verdana\">Est�o dispon�veis  $numero_avatares avatares.
		 </font>";
else
	if ( $numero_avatares == 0 )
		$numero_avatares = "
		 <font color=\"brown\" face=\"verdana\">N�o existem avatares no url refer�nciado!</font>";
	else
		if ( $numero_avatares == 1 )
			$numero_avatares = "
		 <font color=\"brown\" face=\"verdana\">Esta dispon�vel 1 avatar...</font>";
		else
			$numero_avatares = "N�o � poss�vel determinar o n�mero de avatares dispon�veis";

echo "	
	<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" 
	\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">  
	<html xmlns=\"http://www.w3.org/1999/xhtml\">
	
	
	<!--JQuery-->
	<script type=\"text/javascript\" src=\"javascript/jquery-1.2.6.js\"></script>
	
	<link href=\"css/estilo.css\" rel=\"stylesheet\" type=\"text/css\" />
	<style>
		*.selected{border: 3px solid #737EB4;}
		#nenhum{font-size: 11px;font-family: verdana;}
		.bor{cursor: pointer;}
	</style>
	
	<script type=\"text/javascript\">
		
		$(document).ready(function(){
			
			$('.bor').click(function(){
				
				$('.bor').each(function(){
					
					if($(this).hasClass('selected')){
						$(this).removeClass('selected');
						$(this).width(50);
						$(this).height(50);
						}
				})
				
				if($(this).hasClass('selected')){
					
					$(this).removeClass('selected');
					
				}else{
					
					$(this).addClass('selected');
					
					$(this).width(44);
					$(this).height(44);
					
					}
							
			});
			
			$(window).load(function(){
				
				$('.bor').each(function(i){
					
					
					if(this.id == '$pre'){
						
						$(this).addClass('selected');
						
					}
					
				})
				
			})
			
			$('#nenhum').click(function(){
				
				$('.bor').each(function(){
					
					if($(this).hasClass('selected'))
						$(this).removeClass('selected');
						$(this).width(50);
						$(this).height(50);
						
				})
				
			})
			
		});	

	 function setText(str,ele){
  	
 	   		parent.document.getElementById(ele).value = str;
  			
  		}	
	</script>
	
	</head>
	<body>
	$numero_avatares<br />
	<a href=\"javascript:setText('imagens/avatar/$avatdefault','$idres');\" 
	id=\"nenhum\">Nenhum</a><br />
	" . listArqDirec( "imagens/avatar", $idres ). "
	</bod>
	</html>
	";





?>