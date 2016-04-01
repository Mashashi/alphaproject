 <?php 
/**
 * Imprimir a interface de op��es de um filme.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 */

 if( defined( 'IN_PHPAP' ) ){
 
 validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
 
 if ( $_SESSION['estat_carac'][6] ){
 	/**
	 * Requerir as fun��es nativas dos filmes.
     */
	require_once("filmes/filme_funcoes.php");
	
	
	
	if ($_GET ["opcao"]==1 ){
	//Imprimir a interface inserir filmes
	
		echo "
		<div class=\"local\"><a href=\"?elem=2\">".$_SESSION['estat_nome']."</a> 
		� <a href=\"?elem=2&accao=4\">Gerir filmes</a> � Inserir filme</div>
		<div class=\"gestaopremain\">
		
		<table valign=\"top\" style=\"text-align:left; float:left;\">
		
		<tr>
		
		<td>"; 
		
		require_once("filme_ins.php");
		
		echo "</td></div></tr></table></div>";
    
	} else if($_GET["opcao"] == 2){
	//Imprirmir a interface de pesquisa de filmes administrativa
		echo "
		<div class=\"local\"><a href=\"?elem=2\">".$_SESSION['estat_nome']."</a> 
		� <a href=\"?elem=2&accao=4\">Gerir filmes</a> 
		� Apagar, Editar, Listar &amp; Procurar filmes</div>
		
		<div class=\"gestaopremain\">
		
		<table valign=\"top\" style=\"text-align:left; float:left;\">
		
		<tr>
		
		<td>"; 
		
		require_once("filme_lpea.php");
		
		echo "<br /></td></div></tr></table></div>";
	
	} else if($_GET["opcao"] == 3){
		// Incluir o ficheiro respons�vel pela gest�o das requesi��es
		echo "
		<div class=\"local\"><a href=\"?elem=2\">".$_SESSION['estat_nome']."</a> 
		� <a href=\"?elem=2&accao=4\">Gerir filmes</a> 
		� ".(isset($_GET['req'])?"Gerir requesi��o":"Gerir requesi��es")."</div>
		<div class=\"gestaopremain\">
		
		<table valign=\"top\" style=\"text-align:left; float:left;\">
		
		<tr>
		
		<td>"; 
		
		require_once("filme_gerir_req.php");
		
		if( isset($_GET['req']) )
			
			echo "<p><br /></p><a href=\"?elem=2&amp;accao=4&amp;opcao=3\">Listar as requesi��es</a>";
		
			echo "<br /></td></div></tr></table></div>";
	
	} else {
		//Incluir a interface principal de gest�o de filmes
		echo "
		<div class=\"local\"><a href=\"?elem=2\">".$_SESSION['estat_nome']."</a> 
		� Gerir filmes</div>
		<div class=\"gestaopremain\">
		
		<table valign=\"top\" style=\"text-align:left; float:left;\">
		
		<tr>
		
		<td>";
		
		echo "<div id=\"gestaopremainarea\"><div id=\"gestaopremainareanome\">Op��es</div> 
	
		<a href=\"".$_SERVER["PHP_SELF"]."?elem=2&amp;accao=4&amp;opcao=1\"><div 
		id=\"gestaopremainareaprevit\" > 
		Inserir filmes 
		</div></a>
					
					
		<a href=\"".$_SERVER["PHP_SELF"]."?elem=2&amp;accao=4&amp;opcao=2\"><div 
		id=\"gestaopremainareaprevit\" > 
		Apagar, Editar, Listar &amp; Procurar filmes
		</div></a>
		
		<a href=\"".$_SERVER["PHP_SELF"]."?elem=2&amp;accao=4&amp;opcao=3\"><div 
		id=\"gestaopremainareaprevit\" > 
		Gerir requesis�es 
		</div></a>";	
		
		
		echo "</td></div></tr></table></div>";
	
	}
	
} else {
	
	include_once("home.php");
	
}

} 
 
 ?> 