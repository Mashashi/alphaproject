<?php

/**
 * Os conteúdos relacionados com pesquisas quer seja dentro so síte, fora do síte, 
 * administrativas e ou consideradas "normais" por parte de utilizadores sem 
 * previlégios especiais, são feitas aqui.
 * A variavel $_GET['modpsq'] indica o tio de pequisa que deve ser feito.
 * De notar que a listagem de utilizadores devolve uma página XML que em seguida 
 * será tratada, pela função javascript getXml.
 *     
 * @see javascript/javascript.js getXml()
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */
$alpha_root_path = defined( 'IN_PHPAP' ) ? "" : "../";
/**
 * Incluir o ficheiro de configuração config.php.
 *  
 */
include_once ( $alpha_root_path."config.php" );

/**
 * Incluir a classe para fazer a variável de acesso a base de dados bd.php.
 * 
 *   	
 */
include_once ( $alpha_root_path."bd.php" );

/**
 * Incluir fucoes_avan.php.
 *  
 */
include_once ( $alpha_root_path."funcoes_avan.php" );

/**
 * Incluir a classe pesq_rap no ficheiro pesq_rap.php
 *  
 */
include_once ( "pesq_rap.php" );

if ( ! isset($_SESSION['user']) )
	session_start();


switch ( $_GET['modpsq'] )
//modpaq indica o modo de pesquisa
{

	case 1:
		//Caso o modo de pesquisa seja para listagem de utilizadores.
		header( 'Content-type: application/xml; charset="utf-8"', true );
		//Este header define que a página daqui resultante deverá ser tratada como 
		//uma página XML.
		
		if ( 
		$_SESSION['estat_carac'][10]
		&& validParamInput($_POST['iden'], -1, 10) 
		&& validParamInput($_POST['listp'],-1, 10) 
		&& validParamInput($_POST['idai'], -1, 1) 
		&& validParamInput($_POST['idaf'],-1, 151) )
		{
		//echo "ola";	
			$_POST['idai'] = clearGarbadge( rawurldecode($_POST['idai']), false, false);

			if ( empty($_POST['idai']) ) $_POST['idai'] = 0;

			$_POST['idaf'] = clearGarbadge( rawurldecode($_POST['idaf']), false, false);

			if ( empty($_POST['idaf']) ) $_POST['idaf'] = 0;


			printListUtil( $_POST['iden'], $_POST['listp'], $_POST['idai'], $_POST['idaf'],
				$_POST['excui'], $_POST['excuo'], $_POST['rever'], $_POST['ordem'],
				$_POST['nick'], $_POST['excusus'], $_POST['numcart'] );

		}
		break;








	case 2:
		if ( isset($_SESSION['id_user']) )
		{
			//pesquisar por nome quado se quer eviar uma PM
			pesquisarPorNome( $_GET['user'] );
			
        }	
		break;







	case 3: break;
	
	default: break;

}



/**
 * validParamInput()
 * 
 * Ver se é um parametro válido para a pesquisa isto é se é um número que esta inicializado
 * e esta entre ]$ini,$fini[.
 *   
 * @param integer $param
 * @param integer $ini
 * @param integer $fini
 *   
 * @return boolean
 */
function validParamInput( $param, $ini, $fini )
{
	$bool = false;

	if ( isset($param) )
	{

		if ( empty($param) )
			$bool = true;

		else
			if ( is_numeric($param) )
			{

				if ( $param > -1 && $param < 151 )
				{

					$bool = true;

				}

			}


	}


	return $bool;

}












/**
 * pesquisarPorNome()
 * 
 * Pesquisar por um nome de utilizador.
 *   
 * @param String $user 
 * @return void
 */
function pesquisarPorNome( $user )
{

	global $host;
	global $user_bd;
	global $pass_bd;
	global $db;

	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" 
		\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">  
		<html xmlns=\"http://www.w3.org/1999/xhtml\">
		<head>
			
			<link href=\"../css/estilo.css\" rel=\"stylesheet\" type=\"text/css\" />
			
			<script type=\"text/javascript\">

			function refresh(username)
			{
				opener.document.forms['mennew'].menenconinp.value = username;
				opener.focus();
				window.close();
			}

			</script>

		<title>Alpha Project :: Pesquisar</title>	
		<style>
			img {display:none;}
		</style>
		</head>
		<body>
		<table width=\"300\">
		<form name=\"psqutil\" id=\"psqutil\" action=\"pesquisar.php\" method=\"get\">
		<tr>
			<td class=\"perfilgehead\" colspan=\"2\">Encontrar um utilizador</td>
		</tr>
		<tr>
		
		";

	if ( ! isset($user) || empty($user) )
	{

		echo "<td colspan=\"2\">";

		fazerErro( "Por favor forneça um nome ou um valor aproximado ao 
			do utilizador desejado." );

		echo "</tr></td>";

	}
	else
	{
		echo "<td>";
		
		$user = clearGarbadge( rawurldecode($user), false, false);

		$pesq_rap = new pesq_rap( "Utilizador", $host, $user_bd, $pass_bd, $db );
		
		$result = $pesq_rap->psqUtilNick($user, "");

		echo "<select style=\"font-size:12px;\" name=\"userlist\">
			<option value=\"\">Com " . floor( (count($result)/2) ) .
			" registos encontrados</option>";
			
		for ( $i = 1; $i < count($result); $i+=2 )
			echo "<option value=\"$result[$i]\">$result[$i]</option>";


		echo "
		</select>
		</td>
		<td>
		<input type=\"button\" value=\"Seleccionar\" class=\"font11 forms\"  id=\"clipuser\"
		onclick=
	\"javascript:refresh(this.form.userlist.options[this.form.userlist.selectedIndex].value);\"
	 	/>
		</td>
		</tr>";

	}

	echo "
		
		<tr>
		<td>
		<input type=\"hidden\" class=\"forms\" name=\"modpsq\" value=\"2\" />
		<input type=\"text\" class=\"forms\" name=\"user\" id=\"user\" />
		</td>	
		<td>
			<input type=\"submit\" value=\"Pesquisar\" 
			class=\"font11 forms\" id=\"psqnometil\" />
			
		</td>
		</tr>
		<tr>
		<td class=\"perfilfooter\" colspan=\"2\"></td>
		</tr>
		</form>
		<table>
		</body>
		</html>";

}

/**
 * printListUtil()
 * 
 * Fazer uma pesquisa detalhada pela tabela registados.
 * 
 * Nota que um utilizador consirado inactivo é o que não faz login a mais de um ano.
 *     
 * @param $identifi
 * @param $listpor
 * @param $idadei
 * @param $idadef
 * @param $excinac    
 * @param $excoff
 * @param $rever
 * @param $ordem
 * @param $nick
 * @param $sus 
 * @param $numcart
 *       
 * @return void
 */
 
function  printListUtil( $identifi, $listpor, $idadei, $idadef, $excinac, $excoff, $rever,
	$ordem, $nick, $sus, $numcart )
{
	
	
	
	global $host;
	global $user_bd;
	global $pass_bd;
	global $db;

	$nick = clearGarbadge( rawurldecode($nick), false, false);
	//$numcart = clearGarbadge(rawurldecode($numcart),false);

	$identifi = clearGarbadge( rawurldecode($identifi), false, false);
	$listpor = clearGarbadge( rawurldecode($listpor), false, false);
	$ordem = clearGarbadge( rawurldecode($ordem), false, false);

	/*$excinac= clearGarbadge(rawurldecode($excinac),false, false);
	$rever= clearGarbadge(rawurldecode($rever),false, false);
	$sus= clearGarbadge(rawurldecode($sus),false, false);
	$excoff= clearGarbadge(rawurldecode($excoff),false, false);*/


	$flag = true;

	$ord = "";

	$bd = new bd();

	$bd->setLigar( $host, $user_bd, $pass_bd, $db );

	switch ( $identifi )
	{

		case 1:
			$query .= "`id_registo`";
			break;
		case 2:
			$query .= "`registo_nick`";
			break;
		case 3:
			$query .= "`registo_nome_pri`";
			break;
		case 4:
			$query .= "`registo_nome_ult`";
			break;
		case 5:
			$query .= "`registo_data`";
			break;
		case 6:
			$query .= "`registo_data_nas`";
			break;
		case 7:
			$query .= "`registo_data_ultima`";
			break;

		case 8:
			$query .= "`estatuto_nome`";
			break;

		default:
			$query .= "`id_registo` ";

	}

	$query .= ", ";

	switch ( $listpor )
	{

		case 1:
			$query .= "`id_registo` ";
			$ord = "`id_registo` ";
			break;
		case 2:
			$query .= "`registo_nick`";
			$ord = "`registo_nick`";
			break;
		case 3:
			$query .= "`registo_nome_pri` ";
			$ord = "`registo_nome_pri` ";
			break;
		case 4:
			$query .= "`registo_nome_ult`";
			$ord = "`registo_nome_ult`";
			break;
		case 5:
			$query .= "`registo_data`";
			$ord = "`registo_data`";
			break;
		case 6:
			$query .= "`registo_data_nas`";
			$ord = "`registo_data_nas`";
			break;
		case 7:
			$query .= "`registo_data_ultima`";
			$ord = "`registo_data_ultima`";
			break;

		case 8:
			$query .= " `estatuto_nome` ";
			$ord = " `estatuto_nome` ";
			break;

		default:
			$query .= " `id_registo` ";
			$ord = " `id_registo` ";

	}

	$query = "Select Distinct `id_registo`," . $query .
		" From `registo`,`estatuto` ";


	if ( $identifi == 8 || $listpor == 8 )
	{


		$query .= " Where `id_estatuto` = `estatuto_id_estatuto` ";

		$flag = false;

	}


	if ( $rever )
	{

		if ( isset($sus) && $flag )
		{

			$query .= " Where  `registo_is_activo` >= '" . date( "Ymd" ) . "' ";
			$flag = false;

		}
		else
			if ( isset($sus) )
				$query .= " And `registo_is_activo` >= '" . date( "Ymd" ) . "' ";


		if ( isset($excinac) && $flag )
		{

			//-10000000000 um ano
			$tempo = date( "YmdHis" );
			$query .= " Where `registo_data_ultima` < " . ( $tempo - 10000000000 );
			$flag = false;

		}
		else
			if ( isset($excinac) && ! $flag )
			{

				$tempo = date( "YmdHis" );
				$query .= " And `registo_data_ultima` < " . ( $tempo - 10000000000 );

			}


		/**************************
		***************************
		Entre ás idades
		***************************/
		if ( $idadei == $idadef && $idadei > 0 )
		{
			/*Funciona*/
			if ( $flag )
			{

				$query .= " Where DATE_FORMAT(NOW(), '%Y') - 
					DATE_FORMAT(`registo_data_nas`, '%Y') - 
					(DATE_FORMAT(NOW(), '00-%m-%d') < 
					DATE_FORMAT(`registo_data_nas`, '00-%m-%d'))  <> $idadef ";
				$flag = false;

			}
			else
				$query .= " And DATE_FORMAT(NOW(), '%Y') - 
					DATE_FORMAT(`registo_data_nas`, '%Y') - 
					(DATE_FORMAT(NOW(), '00-%m-%d') < 
					DATE_FORMAT(`registo_data_nas`, '00-%m-%d'))  <> $idadef ";


		}
		else
			if ( $idadei > 0 && $idadef > 0 && $idadei < $idadef )
			{

				if ( $flag )
				{

					$query .= " Where DATE_FORMAT(NOW(), '%Y') - 
					DATE_FORMAT(`registo_data_nas`, '%Y') - 
					(DATE_FORMAT(NOW(), '00-%m-%d') < 
					DATE_FORMAT(`registo_data_nas`, '00-%m-%d'))  <= $idadei Or  
					DATE_FORMAT(NOW(), '%Y') - 
					DATE_FORMAT(`registo_data_nas`, '%Y') - 
					(DATE_FORMAT(NOW(), '00-%m-%d') < 
					DATE_FORMAT(`registo_data_nas`, '00-%m-%d')) >= $idadef ";
					$flag = false;

				}
				else
					$query .= "  Ans DATE_FORMAT(NOW(), '%Y') - 
					DATE_FORMAT(`registo_data_nas`, '%Y') - 
					(DATE_FORMAT(NOW(), '00-%m-%d') < 
					DATE_FORMAT(`registo_data_nas`, '00-%m-%d'))  <= $idadei And  
					DATE_FORMAT(NOW(), '%Y') - 
					DATE_FORMAT(`registo_data_nas`, '%Y') - 
					(DATE_FORMAT(NOW(), '00-%m-%d') < 
					DATE_FORMAT(`registo_data_nas`, '00-%m-%d')) >= $idadef ";

			}
			else
				if ( $idadef > 0 && $idadei < 1 )
				{

					if ( $flag )
					{

						$query .= " Where DATE_FORMAT(NOW(), '%Y') - 
					DATE_FORMAT(`registo_data_nas`, '%Y') - 
					(DATE_FORMAT(NOW(), '00-%m-%d') < 
					DATE_FORMAT(`registo_data_nas`, '00-%m-%d'))  >= $idadef ";

						$flag = false;

					}
					else
					{

						$query .= " And DATE_FORMAT(NOW(), '%Y') - 
					DATE_FORMAT(`registo_data_nas`, '%Y') - 
					(DATE_FORMAT(NOW(), '00-%m-%d') < 
					DATE_FORMAT(`registo_data_nas`, '00-%m-%d'))  >= $idadef ";

					}


				}
				else
					if ( $idadei > 0 && $idadef < 1 )
					{

						if ( $flag )
						{

							$query .= " Where DATE_FORMAT(NOW(), '%Y') - 
					DATE_FORMAT(`registo_data_nas`, '%Y') - 
					(DATE_FORMAT(NOW(), '00-%m-%d') < 
					DATE_FORMAT(`registo_data_nas`, '00-%m-%d'))  <= $idadei ";
							$flag = false;

						}
						else
						{

							$query .= " And DATE_FORMAT(NOW(), '%Y') - 
					DATE_FORMAT(`registo_data_nas`, '%Y') - 
					(DATE_FORMAT(NOW(), '00-%m-%d') < 
					DATE_FORMAT(`registo_data_nas`, '00-%m-%d'))  <= $idadei ";

						}

					}
		/**************************
		***************************
		Entre ás idades
		***************************/


		if ( isset($excoff) )
		{

			if ( $flag )
			{

				$query .= " Where `registo_online` Is null ";
				$flag = false;

			}
			else
				$query .= " And `registo_online` Is null ";

		}


	}
	else
	{
			
		if ( isset($sus) && $flag )
		{

			$query .= " Where  `registo_is_activo` < '" . date( "Ymd" ) . "' ";
			$flag = false;

		}
		else if ( isset($sus) )
				$query .= " And `registo_is_activo` < '" . date( "Ymd" ) . "' ";
				
	
		
		if ( isset($excinac) && $flag )
		{

			$tempo = date( "YmdHis" );
			$query .= " Where `registo_data_ultima` > " . ( $tempo - 10000000000 );
			$flag = false;

		} 
		else if( isset($excinac) )
		{
			$tempo = date( "YmdHis" );
			$query .= " And `registo_data_ultima` > " . ( $tempo - 10000000000 );	
			
		}	

		if ( isset($excoff) )
		{

			if ( $flag )
			{

				$query .= " Where `registo_online` Is Not null ";
				$flag = false;

			}
			else
				$query .= " And `registo_online` Is Not null ";

		}

		/**************************
		***************************
		Entre ás idades
		***************************/

		if ( $idadei == $idadef && $idadei > 0 )
		{

			if ( $flag )
			{

				$query .= " Where DATE_FORMAT(NOW(), '%Y') - 
					DATE_FORMAT(`registo_data_nas`, '%Y') - 
					(DATE_FORMAT(NOW(), '00-%m-%d') < 
					DATE_FORMAT(`registo_data_nas`, '00-%m-%d'))  = $idadef ";
				$flag = false;

			}
			else
				$query .= " And DATE_FORMAT(NOW(), '%Y') - 
					DATE_FORMAT(`registo_data_nas`, '%Y') - 
					(DATE_FORMAT(NOW(), '00-%m-%d') < 
					DATE_FORMAT(`registo_data_nas`, '00-%m-%d'))  = $idadef ";


		}
		else
			if ( $idadei > 0 && $idadef > 0 && $idadei < $idadef )
			{

				if ( $flag )
				{

					$query .= " Where DATE_FORMAT(NOW(), '%Y') - 
					DATE_FORMAT(`registo_data_nas`, '%Y') - 
					(DATE_FORMAT(NOW(), '00-%m-%d') < 
					DATE_FORMAT(`registo_data_nas`, '00-%m-%d'))  >= $idadei And  
					DATE_FORMAT(NOW(), '%Y') - 
					DATE_FORMAT(`registo_data_nas`, '%Y') - 
					(DATE_FORMAT(NOW(), '00-%m-%d') < 
					DATE_FORMAT(`registo_data_nas`, '00-%m-%d')) <= $idadef ";
					$flag = false;

				}
				else
					$query .= "  Ans DATE_FORMAT(NOW(), '%Y') - 
					DATE_FORMAT(`registo_data_nas`, '%Y') - 
					(DATE_FORMAT(NOW(), '00-%m-%d') < 
					DATE_FORMAT(`registo_data_nas`, '00-%m-%d'))  >= $idadei And  
					DATE_FORMAT(NOW(), '%Y') - 
					DATE_FORMAT(`registo_data_nas`, '%Y') - 
					(DATE_FORMAT(NOW(), '00-%m-%d') < 
					DATE_FORMAT(`registo_data_nas`, '00-%m-%d')) <= $idadef ";

			}
			else
				if ( $idadef > 0 && $idadei < 1 )
				{

					if ( $flag )
					{

						$query .= " Where DATE_FORMAT(NOW(), '%Y') - 
					DATE_FORMAT(`registo_data_nas`, '%Y') - 
					(DATE_FORMAT(NOW(), '00-%m-%d') < 
					DATE_FORMAT(`registo_data_nas`, '00-%m-%d'))  <= $idadef ";

						$flag = false;

					}
					else
					{

						$query .= " And DATE_FORMAT(NOW(), '%Y') - 
					DATE_FORMAT(`registo_data_nas`, '%Y') - 
					(DATE_FORMAT(NOW(), '00-%m-%d') < 
					DATE_FORMAT(`registo_data_nas`, '00-%m-%d'))  <= $idadef ";

					}


				}
				else
					if ( $idadei > 0 && $idadef < 1 )
					{

						if ( $flag )
						{

							$query .= " Where DATE_FORMAT(NOW(), '%Y') - 
					DATE_FORMAT(`registo_data_nas`, '%Y') - 
					(DATE_FORMAT(NOW(), '00-%m-%d') < 
					DATE_FORMAT(`registo_data_nas`, '00-%m-%d'))  >= $idadei ";
							$flag = false;

						}
						else
						{

							$query .= " And DATE_FORMAT(NOW(), '%Y') - 
					DATE_FORMAT(`registo_data_nas`, '%Y') - 
					(DATE_FORMAT(NOW(), '00-%m-%d') < 
					DATE_FORMAT(`registo_data_nas`, '00-%m-%d'))  >= $idadei ";

						}

					}

		/**************************
		***************************
		Entre ás idades
		***************************/


	}

	if ( $flag && ! empty($nick) ){
		
		$query .= " Where `registo_nick` like '%$nick%' ";
		$flag = false;
		
	}else
		if ( ! empty($nick) )
			$query .= " And `registo_nick` like '%$nick%' ";


	if ( $flag && ! empty($numcart) ){
		
		$query .= " Where `registo_numero` like '%$numcart%' ";
		$flag = false;
		
	}else
		if ( ! empty($numcart) )
			$query .= " And `registo_numero` like '%$numcart%' ";


	$ordem == 1 ? $query .= " Order By $ord Asc " : $query .= " Order By $ord  Desc ";
	
	//echo $query;
	
	$query = $bd->submitQuery( $query );

	echo "<?xml version=\"1.0\"?><listaregistos>" . mysql_numrows( $query );

	for ( $c = 0; $c < mysql_numrows($query); $c++ )
	{

		echo "<registo>";

		for ( $d = 0; $d < 3; $d++ )
		{

			echo "<item>" . ( rawurlencode(mysql_result($query, $c, $d)) ) . "</item>";

		}

		echo "</registo>";

	}

	echo "</listaregistos>";
	
	

}

?>