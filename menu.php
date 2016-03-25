<?php

/**
 * Aqui é feita a impressão do menu principal no lado esquerdo do ecrã.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

if( ! defined( 'IN_PHPAP' ) ) die();

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

echo "
 Menu
 <hr />";

/*Área personalizada conforme estatuto*/
if ( isset($_SESSION['user']) ){

	//array_search(1,$_SESSION['estat_carac'])>-1 Ver se o utilizador tem no estatuto
	//alguma área de gestão
	//if ( array_search(1, $_SESSION['estat_carac']) > -1 )
	
	echo "<div class=\"area\"><b><a href=\"?elem=2\" title=\"A tua área\">" .
	$_SESSION['estat_nome'] . "</a></b></div><div class=\"descricao\">
	Área reservada ao <strong>" . $_SESSION['estat_nome'] .
	"</strong></div><br />";
	
}
/*Área personalizada conforme estatuto*/

echo "<div class=\"area\"><b><a href=\"?elem=3\">FAQ</a></b></div>
 <div class=\"descricao\">Perguntas feitas frequentemente.</div><br />";

 if ( isset($_SESSION['user']) || $autorver ){
 
 echo "
 
 <div class=\"area\"><b><a href=\"?elem=8\">Fórum</a></b></div>
 <div class=\"descricao\">Discussão biblioteca.</div><br />
 
 ";

	//listarAreasNome();
	//echo "<div class=\"float-divider\"></div>";
	
	if ( isset($_SESSION['user']) )
	{
		echo "
		<div class=\"area\" style=\"background-image: none;\">
		<b style=\"cursor: pointer;\">
		<input type=\"text\"  style=\"width:176px;\" 
		class=\"scientificCalc forms\" />
		</b></div>
		<div class=\"descricao\">
		Uma calculadora.</div><br />";
		
		echo "
	 <div class=\"area\"><a href=\"?elem=9\">Mais acerca do projecto</a></b></div>
 	 <div class=\"descricao\">
	 Submete bugs, junta-te ao programa, envia-nos sugestões, etc.
	 </div>
	 <br />";

	}

}
else
	fazerErro( "Para ver a pagina por completo é preciso estar registado." );
		echo "
		<script type=\"text/javascript\">
		if( navigator.appName.indexOf(\"Netscape\") < 0 && navigator.appName.indexOf(\"Firefox\") < 0  )
		document.write('<center><!-- Inicio do botao do Firefox --><a href=\"http://br.mozdev.org/\"><img src=\"imagens/firefox-logo.png\" style=\"border-style:none;\" title=\"Mozilla Firefox 3\" alt=\"[Firefox]\" /></a><!-- Fim do botao do Firefox --></center>');
		</script>
		";
	
echo "
<center><p onclick=\"javascript:add_bookmark();\"
style=\"
background-image:URL('imagens/favorite.png');
background-repeat: no-repeat;
cursor: pointer;
width: 50px;
height: 50px;
\" title=\"Adicionar aos favoritos\">
</p></center>
";

?>