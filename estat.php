<?php

/**
 * Ficheiro que responde aos pedidos por Ajax de actualização, remoção, 
 * ou inserção dos estatutos.
 * Imprime também a interface de remoção e edição de estatutos 
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
	 * Incluir funcoes.php.
	 *  
	 */
	include_once ( "funcoes.php" );
 
	if ( ! isset($_SESSION['user']) ) session_start();

	validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );

if ( isset($_SESSION['estat_carac'][11]) && $_SESSION['estat_carac'][11] )
{

	$bd = ligarBD();
	
	if ( isset($_POST['nome']) && isset($_POST['id']) )
	{
			
			$id = clearGarbadge( $_POST['id'], false, false );

			if ( ! is_numeric($id) )
				die();

			$nome = clearGarbadge( rawurldecode($_POST['nome']), false, false );
			
			/*
			if ( strpos($nome, " ") !== false )
			{echo rawurlencode( "Não pode haver espaços em branco no nome do estatuto." );
			die();}
			*/
			
			if ( strlen( trim($nome) ) == 0 ){
				echo rawurlencode( 
				"O nome do estatuto não pode estar vazio ou conter só espaços em branco." 
				);
				die();
			}
			
			$query = $bd->submitQuery( "Select count(*) From `estatuto` Where 
				`estatuto_nome` like '$nome' COLLATE latin1_general_cs" );


			if ( mysql_result($query, 0, 0) < 1 )
			{
				
				
				$query = $bd->submitQuery( "Update `estatuto` Set `estatuto_nome` = '$nome' 
					Where `id_estatuto` = $id" );
					
				if ( ! $query )
				{

					echo rawurlencode( "O estatuto de momento não pode ser alterado." );

				}
				else
					echo rawurlencode( "Nome do estatuto alterado com sucesso." );

			}
			else
				echo rawurlencode( "6" );


		

	}
	else
		if ( isset($_POST['elemeestatcheck']) && isset($_POST['switch']) )
		{

			$_POST['elemeestatcheck'] = clearGarbadge( $_POST['elemeestatcheck'], false, false );

			$campo = $_POST['elemeestatcheck']{0};

			$id = substr( $_POST['elemeestatcheck'], 1, strlen($_POST['elemeestatcheck'] - 1) );

			if ( ! is_numeric($id) )
				die();

			$switch = clearGarbadge( $_POST['switch'], false, false );

			if ( $switch != 1 && $switch != 0 )
				die();

			switch ( $campo )
			{

				case 'a':
					$campo = "`estatuto_gerir_area`";
					break;
				case 'b':
					$campo = "`estatuto_gerir_topi`";
					break;
				case 'c':
					$campo = "`estatuto_gerir_post`";
					break;
				case 'd':
					$campo = "`estatuto_gerir_filme`";
					break;
				case 'e':
					$campo = "`estatuto_gerir_album`";
					break;
				case 'f':
					$campo = "`estatuto_gerir_outro`";
					break;
				case 'g':
					$campo = "`estatuto_gerir_registo`";
					break;
				case 'h':
					$campo = "`estatuto_gerir_faq`";
					break;
				case 'i':
					$campo = "`estatuto_gerir_estatuto`";
					break;
				case 'j':
					$campo = "`estatuto_gerir_frases`";
					break;
				case 'k':
					$campo = "`estatuto_req_filme`";
					break;
				case 'l':
					$campo = "`estatuto_req_album`";
					break;
				case 'm':
					$campo = "`estatuto_req_outro`";
					break;
				default:
					die();

			}

			$query = $bd->submitQuery( "Update `estatuto` Set $campo = $switch 
			Where `id_estatuto` = $id" );

			if ( ! $query )
				echo rawurlencode( "De momento não é possível satisfazer o teu pedido :(" );

	


		}
		else
			if ( isset($_POST['del']) && isset($_SESSION['estat_carac'][11]) 
			&& $_SESSION['estat_carac'][11] )
			{

				/*Não deixar o utilizador apagar o estatuto se houver
				registos associados a esse estatuto*/

				$query = "Delete From `estatuto` Where ";

				$queryver = "";

				$del = $_POST['del'];

				$flag = true;

				foreach ( $del as $key => $out )
				{

					if ( ! $flag )
						$query .= " Or ";

					if ( $flag )
						$flag = false;

					$queryver = $bd->submitQuery( "
					Select count(*) From `registo` Where `estatuto_id_estatuto` = $del[$key]
					" );

					if ( mysql_result($queryver, 0, 0) > 0 )
					{

						$anet = "Foram encontrados ";
						$dep = " utilizadores associados a ";
						$ult = " terás de associar estes utilizadores a outro estatuto.";

						if ( mysql_result($queryver, 0, 0) == 1 )
						{

							$anet = "Foi encontrado ";
							$dep = " utilizador associado a ";
							$ult = " terás de associar este utilizador a outro estatuto.";

						}

						$queryestat = $bd->submitQuery( "
						Select `estatuto_nome` From `estatuto` Where `id_estatuto` 
						= $del[$key]
						" );


						echo rawurlencode( "$anet" . mysql_result($queryver, 0, 0) . "$dep" .
							mysql_result($queryestat, 0, 0) . ".\nPara apagar " 
							. mysql_result($queryestat, 0, 0) . "$ult" );

						die();

					}

					$query .= " `id_estatuto` = " . $del[$key];


				}

				$query = $bd->submitQuery( $query );

				if ( ! $query )
					echo rawurlencode( "De momento não é possível levar a acção apagar estatuto a cabo!" );
				else
					echo "4";

			


			}
			else
				if ( isset($_SESSION['estat_carac'][11]) && $_SESSION['estat_carac'][11] &&
					isset($_POST['estatnome']) )
				{

					$nomeestat = clearGarbadge( rawurldecode($_POST['estatnome']), false, false );

					if ( strlen($nomeestat) == 0 )
						die();

					$query = $bd->submitQuery( "Select count(*) From `estatuto` 
					Where `estatuto_nome` Like '$nomeestat' COLLATE latin1_general_cs" );

					if ( mysql_result($query, 0, 0) > 0 )
					{

						echo 
					rawurlencode( "O nome $nomeestat já se encontra atribuído a um estatuto!" );

						die();

					}

					$estatop = $_POST['estatutoop'];

					for ( $i = 0; $i < 13; $i++ )
					{

						if ( $estatop[$i] == null )
						{

							$estatop[$i] = 0;

						}


					}

					$campos[0] = 0; //`estatuto_gerir_area`
					$campos[1] = 0; //`estatuto_gerir_topi`
					$campos[2] = 0; //`estatuto_gerir_post`
					$campos[3] = 0; //`estatuto_gerir_filme`
					$campos[4] = 0; //`estatuto_req_filme`
					$campos[5] = 0; //`estatuto_gerir_album`
					$campos[6] = 0; //`estatuto_req_album`
					$campos[7] = 0; //`estatuto_gerir_outro`
					$campos[8] = 0; //`estatuto_req_outro`
					$campos[9] = 0; //`estatuto_gerir_faq`
					$campos[10] = 0; //`estatuto_gerir_registo`
					$campos[11] = 0; //`estatuto_gerir_estatuto`
					$campos[12] = 0; //`estatuto_gerir_frases`

					for ( $i = 0; $i < count($campos); $i++ )
					{

						for ( $e = 0; $e < count($campos); $e++ )
						{

							if ( $estatop[$i] == $e + 1 )
							{

								if ( $i < 0 )
									$campos[0] = 1;
								else
									$campos[$i] = 1;
							}

						}

					}

					$query = "
INSERT INTO `estatuto` (
`id_estatuto` ,
`estatuto_nome` ,
`estatuto_gerir_topi` ,
`estatuto_gerir_post` ,
`estatuto_gerir_area` ,
`estatuto_req_filme` ,
`estatuto_req_album` ,
`estatuto_req_outro` ,
`estatuto_gerir_filme` ,
`estatuto_gerir_album` ,
`estatuto_gerir_outro` ,
`estatuto_gerir_faq` ,
`estatuto_gerir_registo` ,
`estatuto_gerir_estatuto` ,
`estatuto_gerir_frases`
)
VALUES (
NULL , '$nomeestat', '$campos[1]', '$campos[2]', '$campos[0]', '$campos[4]', '$campos[6]'
, '$campos[8]', '$campos[3]', '$campos[5]', '$campos[7]', '$campos[9]', '$campos[10]'
, '$campos[11]', '$campos[12]'
);";


					$query = $bd->submitQuery( $query );

					if ( ! $query )
						echo rawurlencode( "De momento não é possível inserir o novo estatuto :'(" );

					else
						echo rawurlencode( "Novo estatuto inserido!" );

				

				}
				else
					if ( isset($_SESSION['estat_carac'][11]) && $_SESSION['estat_carac'][11] )
					{

						$query = $bd->submitQuery( "Select * From `estatuto`" );

						echo "
		<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" 
		\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">  
		<html xmlns=\"http://www.w3.org/1999/xhtml\">
			
			<!--JQuery-->
			<script type=\"text/javascript\" src=\"javascript/jquery-1.2.6.js\"></script>
			
			<!--Máscara de input-->
			
			
			
			<!--
			<script type=\"text/javascript\" src=\"javascript/acordion/chili-1.7.pack.js\">
			</script>
			<script type=\"text/javascript\" src=\"javascript/acordion/jquery.easing.js\">
			</script>
			<script type=\"text/javascript\" src=\"javascript/acordion/jquery.dimensions.js\">
			</script>
			<script type=\"text/javascript\" src=\"javascript/acordion/jquery.accordion.js\">
			</script>
			
			<script type=\"text/javascript\" src=\"javascript/jquery.pngFix.js\">
			</script>
			
			<script type=\"text/javascript\" src=\"javascript/jquery.maskedinput-1.1.3.js\">
			</script>
			<script type=\"text/javascript\" src=\"javascript/pluginpage.js\"></script>
			-->
			
			<!--Rotinas-->
			<script type=\"text/javascript\" src=\"javascript/javascript.js\"></script>
			
			

			
			<link href=\"css/estilo.css\" rel=\"stylesheet\" type=\"text/css\" />
			<style>
				td{font-size: 11px;border-right: 1px #FF9900 solid;padding: 5px;}
				.forms{font-size: 11px;}
				
				<!--Adicionar as tags dos elementos em que pngs se utilizam-->	
				<!--[if lt IE 7.]>
				<style type=\"text/css\">
					img, p, div { behavior: url(css/iepngfix.htc); }
				</style> 
				<![endif]-->
			</style>
		
		</head>
		<body>
		<table border=\"0\">
		<tr class=\"overviewhover\">
		<td class=\"homedes\" id=\"apgestatat\"><b>Apagar</b></td>
		<td>Nome estatuto
		<input type=\"button\" style=\"width: 83px;\" value=\"Mudar nome\" class=\"forms\" />
		</td>
		<td>Gerir Áreas</td>
		<td>Gerir Tópicos</td>
		<td>Gerir Posts</td>
		<td>Gerir Filme</td>
		<td>Gerir Álbum</td>
		<td>Gerir Outro</td>
		<td>Gerir Registo</td>
		<td>Gerir FAQ</td>
		<td>Gerir Estatuto</td>
		<td>Gerir Frases</td>
		<td>Requesitar Filme</td>
		<td>Requesitar Álbum</td>
		<td>Requesitar Outro</td>
		</tr>
		<form name=\"apagedieestat\" id=\"apagedieestat\">";

						for ( $i = 0; $i < mysql_numrows($query); $i++ )
						{

							$attribs = "";

							$id = mysql_result( $query, $i, 0 );

							for ( $b = 0; $b < 13; $b++ )
							{

								if ( mysql_result($query, $i, $b + 2) == 1 )
									$attribs[$b] = "checked=\"checked\"";
								else
									$attribs[$b] = "";

							}

							$attribs[13] = mysql_result( $query, $i, 1 );

							//nick 1;
							//gerir area 4;
							//gerir topico 2;
							//gerir post 3;
							//gerir filmes 8;
							//gerir álbuns 9;
							//gerir outros 10;
							//gerir registados 12;
							//gerir estatutos 13;
							//gerir faq 11;
							//gerir frase 14;
							//requesitar filmes 5;
							//requesitar álbuns 6;
							//requesitar outros 7;


		echo "<tr class=\"overviewhover\" id=\"tr$i\">
		<td>
		<input style=\"cursor: pointer;\" value=\"$id\" type=\"checkbox\" name=\"estatapag\" 
		class=\"apagestatuto\" id=\"del[$i]\" />
		</td>
		
		<td>
		<input type=\"text\" value=\"$attribs[13]\" maxlength=\"35\" name=\"estatnomeest\" 
		class=\"formestatuto\" id=\"$id\" />
		</td>
		
		<td>
		<input style=\"cursor: pointer;\" type=\"checkbox\" name=\"elemeestatcheck\" 
		$attribs[2] id=\"a$id\" class=\"checkestatper\" />
		</td>

		<td>
		<input  style=\"cursor: pointer;\"type=\"checkbox\" name=\"elemeestatcheck\" 
		$attribs[0] id=\"b$id\" class=\"checkestatper\" />
		</td>

		<td>
		<input style=\"cursor: pointer;\" type=\"checkbox\" name=\"elemeestatcheck\" 
		$attribs[1] id=\"c$id\" class=\"checkestatper\" />
		</td>

		<td><input  type=\"checkbox\" name=\"elemeestatcheck\" $attribs[6] id=\"d$id\" 
		class=\"checkestatper\" style=\"cursor: pointer;\" /></td>

		<td><input type=\"checkbox\" name=\"elemeestatcheck\" $attribs[7] id=\"e$id\" 
		class=\"checkestatper\" style=\"cursor: pointer;\" /></td>
		
		<td><input type=\"checkbox\" name=\"elemeestatcheck\" $attribs[8] id=\"f$id\" 
		class=\"checkestatper\" style=\"cursor: pointer;\" /></td>
		
		<td><input type=\"checkbox\" name=\"elemeestatcheck\" $attribs[10] id=\"g$id\" 
		class=\"checkestatper\" style=\"cursor: pointer;\" /></td>
		
		<td><input type=\"checkbox\" name=\"elemeestatcheck\" $attribs[9] id=\"h$id\" 
		class=\"checkestatper\" style=\"cursor: pointer;\" /></td>
		
		<td><input type=\"checkbox\" name=\"elemeestatcheck\" $attribs[11] id=\"i$id\" 
		class=\"checkestatper\" style=\"cursor: pointer;\" /></td>
		
		<td><input type=\"checkbox\" name=\"elemeestatcheck\" $attribs[12] id=\"j$id\" 
		class=\"checkestatper\" style=\"cursor: pointer;\" /></td>
		
		<td><input type=\"checkbox\" name=\"elemeestatcheck\" $attribs[3] id=\"k$id\" 
		class=\"checkestatper\" style=\"cursor: pointer;\" /></td>
		
		<td><input type=\"checkbox\" name=\"elemeestatcheck\" $attribs[4] id=\"l$id\" 
		class=\"checkestatper\" style=\"cursor: pointer;\" /></td>
		
		<td><input type=\"checkbox\" name=\"elemeestatcheck\" $attribs[5] id=\"m$id\" 
		class=\"checkestatper\" style=\"cursor: pointer;\" /></td>
		</tr>";

						}

					

						echo "
							</form>
							</table>
							<body>
							</html>
							";
							
					}
					

}

?>