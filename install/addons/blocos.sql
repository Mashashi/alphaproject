INSERT INTO `bloco` (`id_bloco`, `bloco_ordem`, `bloco_codigo`, `bloco_date_up`, `bloco_nome`, `bloco_active`) VALUES
(1, 1, 'echo "\r\n<object id=\\"player1\\" type=\\"application/x-shockwave-flash\\" \r\ndata=\\"intro/player_flv_maxi.swf\\" width=\\"294\\" height=\\"276\\">\r\n<noscript></noscript>\r\n<param name=\\"movie\\" value=\\"intro/player_flv_maxi.swf\\" />\r\n<param name=\\"allowFullScreen\\" value=\\"true\\" />\r\n<param name=\\"FlashVars\\" value=\\"configxml=intro/video_biblioteca.xml\\" />\r\n</object> \r\n<br />\r\n<a href=\\"download.php?f=video_biblioteca.wmv\\">Fazer download deste video</a>";', '2009-04-17 14:55:38', 'V�deo biblioteca', 1),
(2, 2, 'echo "\r\nBoas,<br />\r\n<p>Colega, professor ou docente. $benvindo</p>";', '2009-04-17 14:57:23', 'Sauda��es', 1),
(3, 3, '$queryregnome = "";\r\n\r\n$regnome = "";\r\n\r\n$tit = "";\r\n\r\n$querynom = "";\r\n\r\n$bd = ligarBD();\r\n\r\n//Fazer a query que dara os cinco primeiros topicos mais recentes\r\n$querynum = $bd->submitQuery( "\r\n	Select `id_topico`,`area_id_area` From `topico`, `post`\r\n	Where `id_topico` > \r\n	(Cast((Select Max(`id_topico`) From `topico`,`post` \r\n	Where `id_topico` = `topico_id_topico` And `post_activo` = 1 \r\n        And `post_prin` = 1) As Binary )-5) \r\n	And `id_topico` = `topico_id_topico` And `post_activo` = 1 And `post_prin` = 1 Order by `id_topico` Desc" );	\r\n\r\nfor ( $i = 0; $i < mysql_numrows($querynum); $i++ ){\r\n        \r\n	//Seleccionar os dados relativos ao post atrav�s\r\n	//da chave estrangeira do topico (topico_id_topico)\r\n	$querynom = $bd->submitQuery( "Select `post_titulo`\r\n	,`registo_id_registo`,DATE_FORMAT(`post_data_hora`, ''%d-%m-%Y'') \r\n        From `post` Where `topico_id_topico` = " \r\n        . mysql_result($querynum, $i, 0) ." And `post_prin` = 1" );\r\n\r\n	\r\n	if ( mysql_num_rows($querynom) > 0 )\r\n	{\r\n	\r\n		//Seleccionar dados relativos ao autor se o resultado da\r\n		//o n�mero de registos sa $querynum for superior a 0\r\n		$queryregnome = $bd->submitQuery( "\r\n		Select `registo_nick`,`id_registo` \r\n                From `registo` Where `id_registo` = " \r\n                .mysql_result($querynom, 0, 1) );\r\n		\r\n		\r\n		if( mysql_num_rows($queryregnome) == 1 )\r\n			$link = "?elem=10&amp;perfil=" \r\n                       . mysql_result( $queryregnome, 0, 1 );	\r\n		else \r\n			$link = ""; \r\n		\r\n		//Data em que o t�pico foi colocado\r\n		$data = mysql_result( $querynom, 0, 2 );\r\n		\r\n	}\r\n\r\n\r\n	if ( mysql_num_rows($queryregnome) == 1 )\r\n	{\r\n\r\n		//Se o n�mero de colunas que \r\n                //tem como resultado $queryregnome for igual a\r\n		//1 o autor foi identificado correctamente\r\n		if ( $queryregnome ) \r\n		$regnome = \r\n                 "<a href=\\"$link\\"><i>"\r\n                 .mysql_result( $queryregnome, 0, 0 )."</i></a>";\r\n		\r\n		mysql_freeresult( $queryregnome );\r\n		\r\n	}\r\n	else\r\n	{\r\n		//No caso de a condi��o a cima \r\n                //nao ser preenchida dar-se a, o autor como Bnido\r\n		$regnome = "<em><b>Autor banido</b></em>";\r\n		\r\n	}\r\n\r\n	$tit = mysql_result( $querynom, 0, 0 );\r\n\r\n	if ( strlen($tit) > 30 )\r\n		$tit = substr( $tit, 0, 30 ) . "...";\r\n\r\n	if ( mysql_numrows($querynom) > 0 )\r\n	{\r\n		\r\n		echo "<div style=\\"margin-top: 4px;\\"><b>\r\n                <a href =\\"?elem=8&amp;area=" \r\n                . mysql_result( $querynum, $i, 1 ) .\r\n		"&amp;topico=" . mysql_result( $querynum, $i, 0 ) \r\n                . "\\">" . $tit ."</a></b><br /> por $regnome a <i>$data</i> \r\n		</div>";\r\n\r\n	}\r\n	\r\n	/*if( mysql_num_rows($querynom) > 0 ){\r\n		mysql_freeresult($queryregnome);\r\n	}*/\r\n	\r\n	mysql_freeresult($querynom);\r\n	\r\n}\r\n\r\nmysql_freeresult($querynum);', '2009-04-17 15:11:28', 'T�picos recentes', 1),
(4, 4, '	$elem_nome = "";\r\n		\r\n	$accao = "";\r\n		\r\n	$elem_url_nome = "";\r\n	\r\n	$bd = ligarBD();\r\n	\r\n	$query_req_most = $bd->submitQuery( "\r\n	SELECT DISTINCT `id_geral`,`id_elemento`, COUNT( * ) \r\n        AS Sum From `geral` Join `requesicao` On\r\n	`geral_id_geral` = `id_geral`\r\n	GROUP BY CRC32( `id_requesicao` )\r\n	ORDER BY sum DESC\r\n	LIMIT 0 , 5 " );\r\n				\r\n			\r\n	//print_r( mysql_fetch_array($query_req_most) );\r\n	while($i < mysql_num_rows($query_req_most)){\r\n		\r\n		//for($i = 0; $i < mysql_num_rows($query_req_most); $i++)\r\n		\r\n		$i = mysql_num_rows($query_req_most);\r\n		\r\n		$id = mysql_result( $query_req_most, $i, 0 );\r\n		\r\n		$id_elem = mysql_result( $query_req_most, $i, 1 );\r\n	\r\n		switch ($id_elem){\r\n			\r\n			case 1: $elem_nome = \r\n                        $bd->submitQuery("Select `filme_nome` From `filme` \r\n			Where `geral_id_geral` = $id");\r\n			if( mysql_numrows($elem_nome) > 0 ){\r\n				$elem_nome = mysql_result($elem_nome,0,0);\r\n				$accao = 5;\r\n				$elem_url_nome = "filme";\r\n			} else {\r\n				$elem_nome = "<i>Filme n�o encontrado</i>";\r\n				$accao = -1;\r\n			}\r\n			\r\n			break;\r\n			\r\n			\r\n			\r\n			case 2: \r\n			$elem_nome = \r\n                        $bd->submitQuery("Select `album_nome` From `album` \r\n			Where `geral_id_geral` = $id");\r\n			if( mysql_numrows($elem_nome) > 0 ){\r\n				$elem_nome = mysql_result($elem_nome,0,0);\r\n				$accao = 7;\r\n				$elem_url_nome = "album";\r\n			} else {\r\n				$elem_nome = "<i>�lbum n�o encontrado</i>";\r\n				$accao = -1;\r\n			}\r\n			break;\r\n			\r\n			\r\n			\r\n			case 3: \r\n                        $elem_nome = \r\n                        $bd->submitQuery("Select `outro_nome` From `outro` \r\n			Where `geral_id_geral` = $id");\r\n			if( mysql_numrows($elem_nome) > 0 ){\r\n				$elem_nome = mysql_result($elem_nome,0,0);\r\n				$accao = 9;\r\n				$elem_url_nome = "outro";\r\n			} else {\r\n				$elem_nome = "<i>Outro item n�o encontrado</i>";\r\n				$accao = -1;\r\n			}\r\n			break;\r\n			\r\n			\r\n			\r\n			default: $elem_nome = "Sem correspond�ncia";\r\n			$accao = -1;\r\n			break;\r\n			\r\n			\r\n			\r\n		}\r\n		\r\n		//if( mysql_num_rows($query_req_most) > 0 ){\r\n			\r\n			if($accao > -1 && isset($_SESSION[''id_user'']) ){\r\n				echo "<div style=\\"margin-top: 4px;\\">\r\n				<a href=\\"?elem=2&amp;accao=$accao&amp;$elem_url_nome=$id\\">\r\n				" . seeNomElem( $id_elem, true ) . " \r\n				<b>$elem_nome</b></a>\r\n				 </div>";		\r\n			} else {\r\n				echo "<div style=\\"margin-top: 4px;\\">\r\n				" . seeNomElem( $id_elem, true ) . " \r\n				<b>$elem_nome</b>\r\n				 </div>";	\r\n			}\r\n		//}\r\n		$i++;\r\n\r\n	}\r\n\r\nmysql_freeresult($query_req_most );\r\n	', '2009-04-17 15:33:32', 'Mais requesitados', 1),
(23, 5, '       $bd = ligarBD();\r\n	\r\n	$querynewelem = $bd->submitQuery("\r\n	Select * From `geral` Where `id_geral` >\r\n	( Cast( ( Select Max(`id_geral`) From `geral`) \r\n	As Binary )-5) Order By `id_geral` Desc\r\n	");\r\n	\r\n	$id_elem = "";\r\n	\r\n	$id = "";\r\n	\r\n	$elem_nome = "";\r\n		\r\n	$accao = "";\r\n	\r\n	$i = 0;\r\n	\r\n	while( $i < mysql_num_rows($querynewelem) ){\r\n		\r\n		\r\n	\r\n		$elem_nome = "";\r\n		\r\n		$accao = "";\r\n		\r\n		$elem_url_nome = "";\r\n		\r\n		$id = mysql_result( $querynewelem, $i, 0 );\r\n		\r\n		$id_elem = mysql_result( $querynewelem, $i, 1 );\r\n	\r\n		switch ($id_elem){\r\n			\r\n			case 1: $elem_nome = $bd->submitQuery("Select `filme_nome` From `filme` \r\n			Where `geral_id_geral` = $id");\r\n			if( mysql_numrows($elem_nome) > 0 ){\r\n				$elem_nome = mysql_result($elem_nome,0,0);\r\n				$accao = 5;\r\n				$elem_url_nome = "filme";\r\n			} else {\r\n				$elem_nome = "<i>Filme n�o encontrado</i>";\r\n				$accao = -1;\r\n			}\r\n			\r\n			break;\r\n			\r\n			\r\n			\r\n			case 2: \r\n			$elem_nome = $bd->submitQuery("Select `album_nome` From `album` \r\n			Where `geral_id_geral` = $id");\r\n			if( mysql_numrows($elem_nome) > 0 ){\r\n				$elem_nome = mysql_result($elem_nome,0,0);\r\n				$accao = 7;\r\n				$elem_url_nome = "album";\r\n			} else {\r\n				$elem_nome = "<i>�lbum n�o encontrado</i>";\r\n				$accao = -1;\r\n			}\r\n			break;\r\n			\r\n			\r\n			\r\n			case 3: $elem_nome = $bd->submitQuery("Select `outro_nome` From `outro` \r\n			Where `geral_id_geral` = $id");\r\n			if( mysql_numrows($elem_nome) > 0 ){\r\n				$elem_nome = mysql_result($elem_nome,0,0);\r\n				$accao = 9;\r\n				$elem_url_nome = "outro";\r\n			} else {\r\n				$elem_nome = "<i>Outro item n�o encontrado</i>";\r\n				$accao = -1;\r\n			}\r\n			break;\r\n			\r\n			\r\n			\r\n			default: $elem_nome = "Sem correspond�ncia";\r\n			$accao = -1;\r\n			break;\r\n			\r\n			\r\n			\r\n		}\r\n		\r\n		//if( mysql_num_rows($querynewelem) > 0 ){\r\n			\r\n			if($accao > -1 && isset($_SESSION[''id_user'']) ){\r\n				echo "<div style=\\"margin-top: 4px;\\">\r\n				<a href=\\"?elem=2&amp;accao=$accao&amp;$elem_url_nome=$id\\">\r\n				" . seeNomElem( $id_elem, true ) . " \r\n				<b>$elem_nome</b></a>\r\n				 </div>";		\r\n			} else {\r\n				echo "<div style=\\"margin-top: 4px;\\">\r\n				" . seeNomElem( $id_elem, true ) . " \r\n				<b>$elem_nome</b>\r\n				 </div>";	\r\n			}\r\n		//}\r\n		\r\n		$i++;\r\n		\r\n	}	\r\n		\r\n		\r\n	mysql_freeresult($querynewelem);', '2009-04-17 16:23:03', 'Novas aquisi��es da biblioteca', 1),
(24, 6, '$bd = ligarBD(); \r\n\r\n$querynom = $bd->submitQuery( "Select `id_registo`, `registo_nick` From `registo` Where	`registo_online` Is Not Null Limit 0,9" );\r\n\r\nif ( mysql_numrows($querynom) > 0 )\r\n{\r\n\r\n for ( $i = 0; $i < mysql_numrows($querynom); $i++  )\r\n {\r\n//Ter� de se criar um documento para mostrar as informa��es relativas a um user\r\necho "<a href=\\"?elem=10&perfil=" . mysql_result( $querynom, 0, 0 ) . "\\">\r\n<font style=\\"font-size: " . rand( 10, 30 ) . "px;font-variant: small-caps;\\">" . mysql_result( $querynom, $i, 1 ) ."</font></a>";\r\n}\r\n\r\n\r\n}\r\necho "<table style=\\"border: 1px solid black;\\">\r\n<tr>\r\n<td>\r\n<a href=\\"?elem=10\\"><b>Todos</b></a>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td>\r\n<a href=\\"?elem=10&u=1\\"><b>Online</b></a>\r\n</td>\r\n</tr>\r\n</table>";\r\n\r\nmysql_freeresult($querynom);\r\n\r\n\r\n\r\n\r', '2009-04-22 12:59:36', 'Ver utilizadores\r\n\r\n\r\n\r\n\r', 1),
(25, 7, '$bd = ligarBD();\r\n\r\n$query = $bd->submitQuery( "Select `id_registo`,`registo_nick`,\r\nDATE_FORMAT(`registo_data`, ''%d-%m-%Y''),`registo_ass`\r\nFrom `registo` Where `id_registo` >= \r\n(Cast((Select Max(`id_registo`) From `registo`) As Binary )-4) \r\nOrder by `id_registo` Desc" );\r\n\r\n$ass = "";\r\n\r\nfor ( $i = 0; $i < mysql_numrows($query); $i++ )\r\n{\r\n\r\n	$ass = mysql_result( $query, $i, 3 );\r\n\r\n	if ( $ass == null || strlen(trim($ass)) == 0 )\r\n		$ass = "";\r\n	else\r\n		$ass = "<i>" . $ass . "</i><br/>";\r\n\r\n	echo "<div style=\\"margin-top: 4px;\\">\r\n       <a href=\\"?elem=10&amp;perfil=" . mysql_result( $query, $i, 0       ) . "\\"><b>" . mysql_result( $query, $i, 1 ) . "</b></a><br />" .  $ass . "Registado � <i>" . mysql_result( $query, $i, 2 ) . "</i></div>";\r\n\r\n}\r\n\r\nmysql_freeresult($query);\r\n', '2009-04-17 17:13:53', 'Novos utilizadores', 1),
(26, 8, 'echo "<iframe src=\\"feeds.php\\" name=\\"feeds\\" frameborder=\\"0\\" height=\\"200\\"></iframe>";\r\n\r\n\r\n\r\n\r', '2009-04-21 11:57:26', 'Feeds de n�t�cias\r\n\r\n\r\n\r\n\r', 0);