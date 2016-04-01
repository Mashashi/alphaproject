<?php

/**
 * P�gina que informa ao utilizador a import�ncia da sua expri�ncia de navega��o 
 * para o Administrador.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 */
/**
 * Garantir que o ficheiro se encontra incluido no index.php.
 */
if( ! defined( 'IN_PHPAP' ) ) die();

/**
 * Incluir o ficheiro de configura��o config.php.
 *  
 */
include_once ( "config.php" );


if ( ! isset($_SESSION['user']) )
	session_start();

if ( isset($_SESSION['user']) )
{

	echo "
		<table id=\"topgebug\">
		<tr>
			<td><img src=\"imagens/bug.jpg\"></td>
			<td width=\"450\">
					<p>Ol�,</p>
					Caros utilizadores a existencia desta p�gina n�o � v�...<br />
					
					<p>Pedimos a colabora��o dos associados para que fa�am uma pequena 
					contribui��o ao reportarem desde os mais pequenos erros, at� aqueles 
					que possam
					apresentar uma amea�a de seguran�a.</p>
					
					<p>Embora tenhamos testado o comportamento da p�gina sob v�rias condi��es
					e inumeras vezes existe sempre a possiblidade da ocorr�ncia de falhas no
					sistema.</p>
					
					<p>Toda a informa��o sob a dita falha pode ser pouca portanto aconselhamos-te
					a tirar um <i>print-screen</i> e um pequeno texto, a descrever o 
					<b>bug</b>, neste texto poderam ser opcionalmente se assim o desejares 
					sugest�es para a sua resolu��o, o tipo de erro que � php, css, javascript, 						 etc.
					</p>
					
					<p><a href=\"mailto:$emailadmin\" class=\"underline\">Reportar bug...</a></p>
					
					Volunt�rios para o desenvolvimento do projecto tamb�m s�o bem-vindos ;)</p>
					
					<p>Se pretendes de alguma maneira juntar-te a este programa clica no link 
					a baixo, com o papel que pertendes desempenhar, juntamente com um resumo 
					da tua expri�ncia pessoal nessa �rea. Ajuda a E�a a avan�ar.<p>
					
					<p><a href=\"mailto:$emailadmin\" class=\"underline\">
					Sim, desejo associar-me a este programa!
					</a></p>
					
					Ou submete a tua sugest�o/problema de uma forma mais concisa:<br />
					
					<p><textarea id=\"env_rel_bug_txt\" name=\"env_rel_bug_txt\"></textarea>
					
					<input type=\"button\" value=\"Enviar\" class=\"forms\" 
					id=\"env_rel_bug\" name=\"env_rel_bug\"/></p>
					
					<font color=\"brown\"><i>Com os melhores comprimentos, a ger�ncia.</i></font>
			</td>
		</tr>
		</table>";

}


?>