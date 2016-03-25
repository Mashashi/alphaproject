<?php

/**
 * Página que informa ao utilizador a importância da sua expriência de navegação 
 * para o Administrador.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */
/**
 * Garantir que o ficheiro se encontra incluido no index.php.
 */
if( ! defined( 'IN_PHPAP' ) ) die();

/**
 * Incluir o ficheiro de configuração config.php.
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
					<p>Olá,</p>
					Caros utilizadores a existencia desta página não é vã...<br />
					
					<p>Pedimos a colaboração dos associados para que façam uma pequena 
					contribuição ao reportarem desde os mais pequenos erros, até aqueles 
					que possam
					apresentar uma ameaça de segurança.</p>
					
					<p>Embora tenhamos testado o comportamento da página sob várias condições
					e inumeras vezes existe sempre a possiblidade da ocorrência de falhas no
					sistema.</p>
					
					<p>Toda a informação sob a dita falha pode ser pouca portanto aconselhamos-te
					a tirar um <i>print-screen</i> e um pequeno texto, a descrever o 
					<b>bug</b>, neste texto poderam ser opcionalmente se assim o desejares 
					sugestões para a sua resolução, o tipo de erro que é php, css, javascript, 						 etc.
					</p>
					
					<p><a href=\"mailto:$emailadmin\" class=\"underline\">Reportar bug...</a></p>
					
					Voluntários para o desenvolvimento do projecto também são bem-vindos ;)</p>
					
					<p>Se pretendes de alguma maneira juntar-te a este programa clica no link 
					a baixo, com o papel que pertendes desempenhar, juntamente com um resumo 
					da tua expriência pessoal nessa área. Ajuda a Eça a avançar.<p>
					
					<p><a href=\"mailto:$emailadmin\" class=\"underline\">
					Sim, desejo associar-me a este programa!
					</a></p>
					
					Ou submete a tua sugestão/problema de uma forma mais concisa:<br />
					
					<p><textarea id=\"env_rel_bug_txt\" name=\"env_rel_bug_txt\"></textarea>
					
					<input type=\"button\" value=\"Enviar\" class=\"forms\" 
					id=\"env_rel_bug\" name=\"env_rel_bug\"/></p>
					
					<font color=\"brown\"><i>Com os melhores comprimentos, a gerência.</i></font>
			</td>
		</tr>
		</table>";

}


?>