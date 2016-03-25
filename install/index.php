<?php

/**
 * Ficheiro reponsável pela instalação e correcta configuração do síte alphaproject. 
 *    
 * @author Turma I1
 * @package alphaproject_base 
 * @copyright Copyright (c) 2008, 11º I1 Eça de Queirós 
 * @version 1.0
 */
 
 require_once ( "install_lang.php" );
 
 
 
 function nextSideItem($flag, $secFlag){
	
	if( $secFlag > 2 ) $secFlag = 1;
	
	$submenu = "itemsubmenuu";
	
	if($flag == $secFlag)
		$submenu = "whiter bold itemsubmenuu";
	else if($flag > $secFlag && $flag < 3)
		$submenu = "next itemsubmenuu";
	
	return $submenu;
	
 }
 
 require_once ( "../config.php" );
 
 global $host;
 global $user_bd;
 global $pass_bd;
 global $db;
 
 $lang = new install_lang( $_GET['lang'] );
 
	$texto = "";
	$submenu = "";
	$title = "";
	
	$mod = $_GET['mod'];
	$sub = $_GET['sub'];
	
	switch ($mod){
		
		case 2: 
				
				switch ($sub){
				    
					case 2: $texto = $lang->getLangElem("InstText").
							"
							<br style=\"margin-bottom: 300px;\" />	
							";
							
							$title = $lang->getLangElem("Inst");
							$local = "?mod=2&sub=2&lang=";
							break;
					
					
					default: 
							$texto = $lang->getLangElem("AntesDeProsseguirAInstalacao")
							."
							<br style=\"margin-bottom: 300px;\" />
							";
							$title = $lang->getLangElem("AntesDeInstalar");
							$local = "?mod=2&sub=1&lang=";
							break;
							
				}
		
		
				$submenu = "<div class=\"";
					
				$submenu .= 
				$_GET['sub'] != 2 /*&& $_GET['sub'] != 3
				&& $_GET['sub'] != 4 && $_GET['sub'] != 5
				&& $_GET['sub'] != 6*/
				? "whiter bold itemsubmenuu" : "next itemsubmenuu";
				
				$submenu .= "\">".$lang->getLangElem("AntesDeInstalar")."
					</div><div class=\"";
				
				$submenu .= nextSideItem($_GET['sub'],2);
					
				$submenu .= "\">".$lang->getLangElem("Inst")."</div>";
				break;
		
		default:
		
				switch ($sub){
							
					case 2: $texto = $lang->getLangElem("Licensa");
				    		$title = "General Public License";
							$local = "?mod=1&sub=2&lang=";
							break;
				    		
				    case 3: $texto = $lang->getLangElem("Suporte");
							$title = $lang->getLangElem("SuporteTitulo");
							$local = "?mod=1&sub=3&lang=";
							break;
					
					default: 
							
							$texto = $texto = $lang->getLangElem("Intro");
							$title = $lang->getLangElem("IntroTitulo");
							$local = "?mod=1&sub=1&lang=";
							break;
							
				}
				 
				$submenu = "<a href=\"?mod=1&amp;sub=1";
				
				$submenu .= 
				isset($_GET['lang']) ? "&amp;lang=".$_GET['lang'] : "";
				
				$submenu .= "\"><div class=\"";
					
				$submenu .= 
				$_GET['sub'] != 2 && $_GET['sub'] != 3 ? "bold itemsubmenu" : "itemsubmenu";
				
				$submenu .= "\">".$lang->getLangElem("IntroTitulo")."</div></a>";
					
				$submenu .=	"<a href=\"?mod=1&amp;sub=2";
				
				$submenu .= 
				isset($_GET['lang'])
				? "&amp;lang=".$_GET['lang'] : "";
				
				$submenu .="\"><div class=\"";
					
				$submenu .= 
				$_GET['sub'] == 2
				? "bold itemsubmenu" : "itemsubmenu";
				
				$submenu .=	"\">
					".$lang->getLangElem("LicenTitulo")."
					</div>
					</a>
					<a href=\"?mod=1&amp;sub=3";
				
				$submenu .= 
				isset($_GET['lang']) ? "&amp;lang=".$_GET['lang'] : "";	
					
				$submenu .="\"><div class=\"";
					
				$submenu .= 
				$_GET['sub'] == 3
				? "bold itemsubmenu" : "itemsubmenu";
					
				$submenu .=	"\">".$lang->getLangElem("SuporteTitulo")."</div></a>";
				
				break;
		
	}
	
	
	


?>

<!DOCTYPE HTML PUBLIC 
"-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		
		<title><? echo $title; ?></title>
		
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	
		<!--Autores-->
		<meta name="author" content="11_12_TurmaI1_2008_2009" />
		
		<!--JQuery-->
		<script type="text/javascript" src="../javascript/jquery-1.2.6.js"></script>
		
		<!--JQuery-->
		<script type="text/javascript" src="javascript_install.js"></script>
		
		<!--Style CSS-->
		<link href="style_install.css" rel="stylesheet" type="text/css" />
		
		<link rel="shortcut icon" href="../imagens/favicon.png" />
		
	<script type="text/javascript">
		
		$(document).ready(function(){
			
					<?
		if ($_GET['lang'] == 1 || $_GET['lang'] == 2 || $_GET['lang'] == 3 || $_GET['lang'] == 4 )
		echo "document.getElementById(\"selectlang\").selectedIndex =  ".($_GET['lang']-1).";";
					?>
			
			})
		
	</script>
	<!--scroll="no"-->
	</head>
	<body bgcolor="#DBD7D1">
	
		<!--<input type="button" class="nextstep" value="Próximo passo" />-->
		
		<table id="container">
		  
		  <tr>	
			<td>
			<img style="float: left;" src="imagens/install.png" />
			<div id="installpanel"><? echo $lang->getLangElem("PainelInstalar"); ?><br />
			
			<select style="font-size: 11px;width: 159px;" id="selectlang"
			onchange="javascript: changeLang();">
				<option value="<? echo $local; ?>1">Português</option>
				<option value="<? echo $local; ?>2">Inglish</option>
				<option value="<? echo $local; ?>3">Alemão</option>
				<option value="<? echo $local; ?>4">Francês</option>
			</select>
			
			</div>
			</td>
		  </tr>
		  
		  <tr>	
			<td>
			
			<table style="margin-left: 8px;" cellspacing="0">
			<tr>
			<td>
			<div class="<? echo $_GET['mod'] != 2 || !isset($_GET['mod']) ? "tab" : "tabb"; ?>">
			<a href="?mod=1<? 
			echo isset($_GET['lang']) ? "&amp;lang=".$_GET['lang'] : "";
			?>"><? echo $lang->getLangElem("Propriadades"); ?></a>
			</div>
			</td>
			<td>
			<div class="<? echo $_GET['mod'] == 2 ? "tab" : "tabb"; ?>" style="float: right;">
			<a href="?mod=2<? 
			echo isset($_GET['lang']) ? "&amp;lang=".$_GET['lang'] : "";
			?>"><? echo $lang->getLangElem("Instalar"); ?></a></div>
			</td>
			</tr>
			</table>
			
			<div id="border-top"></div>
			
			<div id="contendstotal">
				<div id="submenu"> <? echo $submenu; ?> </div>
				<div id="contends"> <? echo $texto; ?> </div>
			</div>
			
			<div id="border-bottom"></div>
			
			<div id="credits"><? echo $lang->getLangElem("Creditos"); ?></div>
			
			</td>
		  </tr>
		  
		</table>
		
		
	</body>
</html>