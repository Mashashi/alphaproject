<?php

/**
 * Toda a gestão de mensagens privadas passa por aqui.
 * Inserção, remoção, impressão de interface de caixa de entrada, de caixa de saída e  
 * nova menssagem privada.
 * 
 * As variáveis que controlam o fluxo dos dados são: 
 * <ol>
 * 	
 *  <li>
 *  <code>$_GET['elem']==11</code>: 
 *  Imprime a caixa de entrada
 *  </li>
 * 	
 *  <li>
 *  <code>$_GET['elem']==11</code> e 
 *  <code>$_GET['mod']==1</code>:
 *  Imprime a caixa de saída
 *  </li>
 *  
 * 	<li>
 *  <code>$_GET['elem']==11</code> e 
 *  <code>$_GET['nova']==1</code>: 
 *  Imprime nova mensagem
 *  </li>
 * 	
 *  <li>
 *  <code>$_GET['elem'] == 11</code> e 
 *  <code>$_GET['nova'] == 1</code> e 
 *  <code>$_GET['to']</code>: 
 *  Imprime nova mensagem com o campo para já preeenchido 
 *  pelo nick cujo ID é <code>$_GET['to']</code>
 *  </li>
 *  
 *  <li>
 *  <code>$_POST['elem'] == 11</code> e 
 *  <code>$_POST['nova'] == 1</code> e 
 *  <code>$_POST['menenconinp']</code> e 
 *  <code>$_POST['menassun']</code> e 
 *  <code>$_POST['mentext']</code>:
 *  Envia uma nova menssagem para <code>$_POST['menenconinp']</code> com o assunto 
 *  <code>$_POST['menassun']</code> e o texto <code>$_POST['mentext']</code>
 *  </li>     
 * 
 * 
 * 	<li>
 *  <code>$_POST['elem'] == 11</code> e 
 *  <code>$_POST['checkapg']</code>: 
 *  Apaga as mensagens cujo o ID esta no array <code>$_POST['checkapg']</code>
 *  </li>  
 * 
 * </ol>  
 * 

 * <b>Nota:</b> <i>Para as acções acima descritas poderem ser efectuadas 
 * foi também verificado 
 * se o utilizadortem sessão iniciada</i>
 *   
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 * @todo A possiblidade de apagar as mensagens na caixa de saída sem que elas 
 * desapareçam para a pessoa a que foi enviada a mensagem. 
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

/**
 * Incluir fucoes_avan.php.
 *  
 */
include_once ( "funcoes_avan.php" );

if ( ! isset($_SESSION['user']) )
	session_start();

$bd = ligarBD();

validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );

if ( isset($_SESSION['user']) )
{

	if ( isset($_POST['checkapg']) )
	{
		//Apagar uma mensagem
		$array = $_POST['checkapg'];

		$query = "Delete From `mensagem` Where ";

		$array = array_values( $array );

		for ( $i = 0; $i < sizeof($array); $i++ )
		{

			$validmens = $bd->submitQuery( "Select Count(*) From `mensagem` 
				Where `mensagem_destinatario` = " . $_SESSION['id_user'] .
				" And `id_mensagem` = 
				$array[$i]" );

			$num = mysql_result( $validmens, 0, 0 );

			if ( $num != 1 )
			{
				$query = "";
				break;
			}

			if ( $i != 0 )
			{
				$query .= " Or ";
			}

			if ( $array[$i] != 0 )
				$query .= " `id_mensagem` = $array[$i] ";

		}

		$query = $bd->submitQuery( $query );

		if ( $query )
		{

			echo 7;

		}
		else
		{

			echo rawurlencode( "Ops, de momento não é possível atender ao seu pedido" );

		}

		die();

	}


	if ( isset($_GET['mens']) && is_numeric($_GET['mens']) )
	{
		if( ! defined( 'IN_PHPAP' ) ) die();
		//Impressão de uma mensagem individual
		if ( isset($_GET['mod']) )
		{

			$query = $bd->submitQuery( "Select 
				`registo_id_registo`, `registo_estatuto_id_estatuto`,`mensagem_destinatario`
				,DATE_FORMAT(`mensagem_data`, '%d-%m%-%Y %H:%i:%s'),`mensagem_corpo`
				,`mensagem_assunto`,`id_mensagem`
				From `mensagem` Where `id_mensagem` = " . $_GET['mens'] .
				" And `registo_id_registo` = " . $_SESSION['id_user']  );

			if ( mysql_numrows($query) == 1 )
			{
				echo "<div class=\"local\"><a href=\"?elem=11&amp;mod=1\">Mensagens :: 
				Caixa de saída
				</a> » " . mysql_result( $query, 0, 5 ) . "</div>";
			}
		}
		else
		{

			$query = $bd->submitQuery( "Select 
				`registo_id_registo`, `registo_estatuto_id_estatuto`,`mensagem_destinatario`
				,DATE_FORMAT(`mensagem_data`, '%d-%m%-%Y %H:%i:%s'),`mensagem_corpo`
				,`mensagem_assunto`,`id_mensagem`
				From `mensagem` Where `id_mensagem` = " . $_GET['mens'] .
				" And `mensagem_destinatario` = " . $_SESSION['id_user'] );

			if ( mysql_numrows($query) == 1 )
			{
				echo "<div class=\"local\"><a href=\"?elem=11\">Mensagens :: Caixa de entrada
				</a> » " . mysql_result( $query, 0, 5 ) . "</div>";
			}
		}


		if ( mysql_numrows($query) == 1 )
		{

			$de = mysql_result( $query, 0, 0 );
			$para = mysql_result( $query, 0, 2 );

			$assi = "";

			$dados = $bd->submitQuery( "Select `registo_nick`,`registo_ass` From `registo` 
			Where `id_registo` = $de" );

			$de = mysql_result( $dados, 0, 0 );

			$assi = mysql_result( $dados, 0, 1 );

			$para = mysql_result( $bd->submitQuery("Select `registo_nick` From `registo` 
			Where `id_registo` = $para"), 0, 0 );

			$data = mysql_result( $query, 0, 3 );
			$assunto = mysql_result( $query, 0, 5 );
			$text = mysql_result( $query, 0, 4 );

			echo "
			<table width=\"590\" style=\"height: 50px;border: 1px solid #FF9900;\">
				<tr >
					<td colspan=\"2\" class=\"perfilgehead\"></td>
				</tr>
				<tr >
					<td width=\"90\" class=\"linempe\" style=\"text-align:right;\"><b>De:</b></td>
					<td><b>$de</b></td>
				</tr>
				<tr>
					<td width=\"90\"  class=\"linempe\" style=\"text-align:right;\">
					<b>Para:</b></td>
					<td><b>$para</b></td>
				</tr>
				<tr>
					<td width=\"90\"  class=\"linempe\" style=\"text-align:right;\">
					<b>Data &amp; Hora:</b></td>
					<td>$data</td>
				</tr>
				<tr>
					<td width=\"90\" class=\"linempe\" style=\"text-align:right;\">
					<b>Assunto:</b></td>
					<td class=\"linempd\">$assunto</td>
				</tr>
				<tr>
					<td colspan=\"2\" style=\"padding:5px;\">" . nl2br( $text ) . "</td>
				</tr>
				<tr>
					<td colspan=\"2\" style=\"padding:5px;height:auto;\" 
					class=\"perfilfooter\">$assi</td>
				</tr>
			</table>
			";
		}
		else
		{

			include_once ( "home.php" );

		}

	}
	else
		if ( isset($_POST['nova']) && $_POST['nova'] == 2 )
		{
			//Inserção na tabela mensagens de uma nova entrada
				
			$output = newMesage( 
			rawurldecode($_POST['menenconinp']), 
			false ,
			rawurldecode($_POST['menassun']), 
			rawurldecode($_POST['mentext']) 
			);
			
			switch($output){
				
				case 0: die( rawurlencode("O utilizador não existe.") );
						break;
						
				case 2: die( rawurlencode("O texto da mensagem contém uma ou mais palavras com mais de 50 caractéres.") );
						break;
						
				case 3: die( rawurlencode("Erro ao enviar mensagem :X") );
						break;
						
				case 4: die( rawurlencode("O assunto tem de conter mais caractéres para além de espaços em branco.") );
						break;
						
				case 5: die( rawurlencode("O texto tem de conter mais caractéres para além de espaços em branco.") );
						break;
						
				case 6: die( rawurlencode("Erro ao enviar mensagem :X") );
						break;
						
				case 7: die( rawurlencode("Erro ao enviar mensagem :X") );
						break;
				
				default: die( rawurlencode("Mensagem enviada com sucesso!") ); 
				
			}

		}
		else
			if ( isset($_GET['nova']) && $_GET['nova'] == 1 )
			{
				if( ! defined( 'IN_PHPAP' ) ) die();
				//Impressão do conteúdo nova mensagem
				$to = "";

				if ( isset($_GET['to']) && is_numeric($_GET['to']) )
				{

					$to = clearGarbadge( $_GET['to'], false, false);

					$qyery = $bd->submitQuery( "Select `registo_nick` 
					From `registo` Where `id_registo` = $to" );

					if ( mysql_numrows($qyery) == 1 )
					{
						$to = mysql_result( $qyery, 0, 0 );
					}
					else
					{
						$to = "";
					}

				}

				$toolbar = drawToolBar( "mentext", "", true);

				echo "
			<div class=\"local\">Mensagens :: Nova</div>
			<table width=\"590\">
				<form name=\"mennew\" id=\"mennew\">
				<tr>
					<td width=\"100\" style=\"text-align:right;\">Utilizador:</td>
					<td width=\"100\">
						<input type=\"hidden\" value=\"2\" name=\"nova\" />
						<input type=\"text\" maxlength=\"15\" style=\"font-size: 11px;\" 
						name=\"menenconinp\" value=\"$to\" id=\"menenconinp\" class=\"forms\" />
					</td>
					<td>
						<input type=\"button\" style=\"font-size: 11px;float: left;\" 
						name=\"menencon\" 
						value=\"Encontrar utilizador\" 
						id=\"menencon\" class=\"forms\" />
					</td>
				</tr>
				<tr>
					<td width=\"100\" style=\"text-align:right;\">Assunto:</td>
					<td colspan=\"2\">
						<input type=\"text\" maxlength=\"49\" 
						style=\"font-size: 11px;width: 300px;\" 
						name=\"menassun\" id=\"menassun\" class=\"forms\" />
					</td>
				</tr>
				<tr>
					<td width=\"100\" style=\"text-align:right;\">Corpo da mensagem:</td>
					<td colspan=\"2\">
					<table height=\"15\" class=\"widthtoolbox\"
					style=\"background-image: URL('imagens/bg.gif');
							background-repeat: repeat-x;\">
						<tr>
							<td>
							$toolbar
							<input type=\"button\" value=\"Enviar mensagem\" class=\"forms\"
							style=\"font-size:11px;\" name=\"mensubt\" id=\"mensubt\" />
							</td>
						</tr>
					</table>	
						<textarea name=\"mentext\" 
						style=\"font-size: 11px;width: 500px;height: 200px;\" 
						id=\"mentext\" class=\"forms\"></textarea>
					</td>
				</tr>
				</form>
			</table>";


			}
			else
			{
				
				if( ! defined( 'IN_PHPAP' ) ) die();
				//Listagem de mensagens caixa de saída e entrada
				
				
				$pagi = clearGarbadge( $_GET['pagi'], false, false);

				$pagf = clearGarbadge( $_GET['pagf'], false, false);

				if ( ! is_numeric($pagi) || ! is_numeric($pagf) || $pagi < 0 || $pagf < 0 )
				{

					$pagi = 0;
		
					$pagf = 5;

				}
				
				
				
				
				
				$whos = "De";

				$query = "";

				if ( isset($_GET['mod']) && $_GET['mod'] == 1 )
				{

					echo "<div class=\"local\">Mensagens :: Caixa de Saída</div>";

					$whos = "Para";

					$query = $bd->submitQuery( "Select 
			`id_mensagem`,DATE_FORMAT(`mensagem_data`, '%d-%m%-%Y %H:%i:%s')
			,`mensagem_destinatario`,`mensagem_assunto`,`registo_id_registo`
			From `mensagem` Where `registo_id_registo` = " . $_SESSION['id_user'] 
			. " Limit $pagi,$pagf" );
				
					$mod = "&amp;mod=1";

					if ( ! $query )
						die();

					$head = "
			<td colspan=\"2\"><center>Caixa de saida</center></td>
			<td colspan=\"2\"><center><a href=\"?elem=11\">Caixa de entrada</a></center></td>";


				}
				else
				{
					$bd->submitQuery( "Update `registo` Set `registo_data_ultima` = '" 
					. date("YmdHis") ."' Where `id_registo` = " . $_SESSION['id_user'] );

					echo "<div class=\"local\">Mensagens :: Caixa de Entrada</div>
			<div class=\"titulotopico\">
			<a href=\"?elem=11&amp;nova=1\">
			<img src=\"imagens/t_new.png\" alt=[Nova PM] border=\"0\" 
			title=\"New Private Message\" />
			</a></div><div class=\"float-divider\"></div>";

					$mod = "";

					$query = $bd->submitQuery( "Select 
			`id_mensagem`,DATE_FORMAT(`mensagem_data`, '%d-%m%-%Y %H:%i:%s')
			,`mensagem_destinatario`,`mensagem_assunto`,`registo_id_registo`
			From `mensagem` Where `mensagem_destinatario` = " . $_SESSION['id_user'] 
			. " Order By `id_mensagem` Desc Limit $pagi,$pagf" );

					if ( ! $query )
						die();

					$head = "
			<td colspan=\"2\"><center>Caixa de entrada</center></td>
			<td colspan=\"2\"><center><a href=\"?elem=11&amp;mod=1\">Caixa de saida</a>
			</center></td>
			";

				}

				$result = "";

				for ( $i = 0; $i < mysql_numrows($query); $i++ )
				{
					$result .= "<tr id=\"trmen$i\" class=\"overviewhover\">";
					if ( isset($_GET['mod']) )
					{

						$queryatri = mysql_result( $bd->submitQuery(
						"Select `registo_nick` From `registo` 
						Where `id_registo` = " . mysql_result($query, $i, 2)), 0, 0 );

					}
					else
					{

						$queryatri = mysql_result( $bd->submitQuery("Select `registo_nick` From `registo` 
					Where `id_registo` = " . mysql_result($query, $i, 4)), 0, 0 );

					}
					$result .= "<td width=\"150\" class=\"borrigline\">
			<a href=\"?elem=11$mod&amp;mens=" . mysql_result( $query, $i, 0 ) . "\">
			" . mysql_result( $query, $i, 3 ) . "</a></td>";
					$result .= "<td class=\"borrigline\">" . mysql_result( $query, $i, 1 ) . "</td>";
					$result .= "<td class=\"borrigline\"><a href=\"?elem=10&amp;perfil=" .
						mysql_result( $query, $i, 4 ) . "\">
			$queryatri</a></td>";
					if ( ! isset($_GET['mod']) )
						$result .= "<td><input type=\"checkbox\" id=\"$i\" name=\"checkapg[$i]\" 
			class=\"meecheckk\" style=\"cursor:pointer;\"
			value=\"" . mysql_result( $query, $i, 0 ) . "\" />
			</td>";
					$result .= "</tr>";

				}


				echo "
		<table style=\"width: 100%;border-left:2px solid #FF9900;margin-left:2px;\">
		<form name=\"menpriform\" id=\"menpriform\">
		<tr>
			$head
		<tr>
		<tr>
			<td width=\"250\">Assunto</td>
			<td>Data</td>
			<td>$whos</td>";

				if ( ! isset($_GET['mod']) )
					echo "<td>Marca</td>";

				echo "<tr>
		$result";


				if ( ! isset($_GET['mod']) )
					echo "
					<tr>
					<td colspan=\"4\">
					<table style=\"width: 100%;\">
					<tr>
					<td>
					<input type=\"button\" style=\"font-size:11px\" name=\"apgmema\" 
					class=\"forms\" id=\"apgmema\"
					value=\"Apagar marcadas\" />
					</td>
					<td>
					<input type=\"button\" style=\"font-size:11px\" name=\"assitoma\" 
					class=\"forms\" value=\"Assinalar todas\"  id=\"assitoma\" />
					</td>
					<td>
					<input type=\"button\" style=\"font-size:11px\" name=\"destoma\" 
					class=\"forms\" id=\"destoma\"
					value=\"Desmarcar todas\" />
					</td>
					</tr>
					</table>
					</td>
					</tr>";

				echo "</form></table>";
				
	
	
	if( isset($_GET['mod']) )
		$query_count_spam = 
		" `registo_id_registo` "; 
	else
		$query_count_spam = 
		" `mensagem_destinatario` " ;
		
	$query_count_spam = floor( (mysql_result(
	$bd->submitQuery(
	"Select Count(*) From `mensagem` Where ".$query_count_spam . " = ".$_SESSION['id_user']
	), 0, 0)) / 6 );
	
	if($query_count_spam > 0){
	
	//Divisão do spam por páginas
	echo "<div class=\"listpags\" style=\"float: left;margin-left: 1px;\">";
	//Número de páginas totais	
	
	//Página actual
	$pag_actual = floor ( $pagi / $pagf );

	if ( $pag_actual > 0 && isset($_GET['mod']) )
		echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a href=\"?elem=11&amp;pagi=" 
		. ( $pagi -  5 ) . "&amp;pagf=5&amp;mod=1\" 
		title=\"Recuar para página anterior\">&lt;</a></div>";
	else
		if ( $pag_actual > 0 )
			echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a href=\"?elem=11&amp;pagi=" 
			. ( $pagi - 5 ) . "&amp;pagf=5\" title=\"Recuar para página anterior\">&lt;</a></div>";

	echo "<div class=\"pags\" style=\"margin-left: 0px;\">$pag_actual de $query_count_spam</div>";

	if ( $query_count_spam > 0 && isset($_GET['mod']) && $pag_actual < $query_count_spam )
		echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a href=\"?elem=11&amp;pagi=" . ( $pagi +  5 ) . "&amp;pagf=5&amp;mod=1\" title=\"Avançar para a próxima página\">&gt;</a></div>";
	else
		if ( $query_count_spam > 0 && $pag_actual < $query_count_spam )
			echo "<div class=\"pags\" style=\"margin-left: 0px;\"><a href=\"?elem=11&amp;accao=14&amp;pagi=" . ( $pagi + 5 ) . "&amp;pagf=5\" title=\"Avançar para a próxima página\">&gt;</a></div>";


	
	
	echo "</div>";			
	}
				
	}

}
else
{

	include_once ( "home.php" );

}

?>