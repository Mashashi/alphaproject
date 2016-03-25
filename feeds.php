<?php
/**
 * Ficheio onde é incluido e usado o mapeiRSS.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

$path_rss_fetch = "magpierss/rss_fetch.inc";

$url = "http://www.publico.clix.pt/rss.ashx?idCanal=10";
/**
 * Requerir o ficheiro através do qual se vai chamar as funções para ler os
 * URL conforme o código da notíciam, $_GET['tipo_noticia'], utilizando
 * para isto funções como a fetch_rss(). 
 */
require_once($path_rss_fetch); 

switch($_GET['tipo_noticia']){
	
	case 1: 
	$url = "http://www.publico.clix.pt/rss.ashx?idCanal=11"; //Internacional
	break;
	case 2: 
	$url = "http://www.publico.clix.pt/rss.ashx?idCanal=12"; //Politica
	break;
	case 3:
	$url = "http://www.publico.clix.pt/rss.ashx?idCanal=13"; //Ciências
	break;
	case 4: 
	$url = "http://www.publico.clix.pt/rss.ashx?idCanal=56"; //Desporto
	break;
	case 5: 
	$url = "http://www.publico.clix.pt/rss.ashx?idCanal=14"; //Cultura
	break;
	case 6: 
	$url = "http://www.publico.clix.pt/rss.ashx?idCanal=57"; //Economia
	break;
	/*case 7: 
	$url = "http://www.publico.clix.pt/rss.ashx?idCanal=58"; //Educação
	break;*/
	/*case 8: 
	$url = "http://www.publico.clix.pt/rss.ashx?idCanal=59"; //Local
	break;*/
	case 9: 
	$url = "http://www.publico.clix.pt/rss.ashx?idCanal=61"; //Media e tecnologia
	break;
	case 10: 
	$url = "http://www.publico.clix.pt/rss.ashx?idCanal=62"; //Sociadade
	break;
	/*case 11: 
	$url = "http://www.publico.clix.pt/rssdr.asp"; //Diário da república
	break;*/
	case 12: 
	$url = "http://www.publico.clix.pt/rss.ashx?idCanal=2100"; //Ecoesfera
	break;
	default: $url = "http://www.publico.clix.pt/rss.ashx?idCanal=10";
	break;
	
}


$rss = fetch_rss($url);

echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">  
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<style>
body{
	
	font-family: verdana;
	font-size: 11px;
	
}
td{
	
	padding: 2px;
	
}
a:link {
	
	text-decoration: none;
	color: #0033CC;
	
	}
	
a:visited {
	
	text-decoration: none;
	color: #0033CC;
	
	}
	
a:hover {
	
	text-decoration: none;
	color: #0033CC;
	
	}
a:active {
	
	text-decoration: none;
	color: #0033CC;
	
	}
img{
	
	border: 1px solid black;
	
}
</style>
<script>
function redirectParent(to){ parent.window.document.location.href = to; }
</script>
</head>
<body>
<a href=\"?tipo_noticia=0\">Geral</a>,
<a href=\"?tipo_noticia=1\">Internacional</a>,
<a href=\"?tipo_noticia=2\">Politica</a>,
<a href=\"?tipo_noticia=3\">Ciencias</a>,
<a href=\"?tipo_noticia=4\">Desporto</a>,
<a href=\"?tipo_noticia=5\">Cultura</a>,
<a href=\"?tipo_noticia=6\">Economia</a>,
<!--<a href=\"?tipo_noticia=7\">Educação</a>,-->
<!--<a href=\"?tipo_noticia=8\">Local</a>,-->
<a href=\"?tipo_noticia=9\">Media e tecnologia</a>,
<a href=\"?tipo_noticia=10\">Sociadade</a>,
<!--<a href=\"?tipo_noticia=11\">Diário da república</a>,-->
<a href=\"?tipo_noticia=12\">Ecosfera</a>.

<table><tr><td><b>".$rss->channel['title']."</b></td></tr>";

$count = 0;

foreach ($rss->items as $item ) {

	$title = substr($item['title'],0);
	
	$url   = "\"javascript:redirectParent('".$item['link']."');\"";
	
	if($count % 2 == 0)
		echo 
	"<tr style=\"background-color: #E0E0DF;\"><td>
	<img src=\"imagens/marcador.jpg\" alt=\"Marcador\" /><br />
	<a href=\"#\" onclick=$url>$title</a></li></td>";
	else
		echo 
	"<tr style=\"background-color: #F0F0EF;\"><td>
	<img src=\"imagens/marcador.jpg\" alt=\"Marcador\" /><br />
	<a href=\"#\" onclick=$url>$title</a></li></td>";
	
	$count++;
		
}

echo "</table>
</body>
</html>";

?>