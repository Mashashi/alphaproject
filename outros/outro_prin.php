 <?php 
/**
 * Imprimir a interface dos itens outros.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 */


 if ( $_SESSION['estat_carac'][8] ){

	if ($_GET ["opcao"]==1 ){
	
		echo "
		<div class=\"local\"><a href=\"?elem=2\">".$_SESSION['estat_nome']."</a> 
		� <a href=\"?elem=2&accao=8\">Gerir itens</a> 
		� Inserir outro item</div>
		
		<div class=\"gestaopremain\">
		
		<table valign=\"top\" style=\"text-align:left; float:left;\">
		
		<tr>
		
		<td>"; 
		/**
		 * Requerir as fun��es nativas dos outros itens.
		 */
		require_once("outro_ins.php");
		
		echo "</td></div></tr></table></div>";
    
	} else if($_GET["opcao"] == 2){
	
		echo "
		<div class=\"local\"><a href=\"?elem=2\">".$_SESSION['estat_nome']."</a> 
		� <a href=\"?elem=2&accao=8\">Gerir itens</a> 
		� Apagar, Editar, Listar &amp; Procurar outros itens</div>
 	
		<div class=\"gestaopremain\">
		
		<table valign=\"top\" style=\"text-align:left; float:left;\">
		
		<tr>
		
		<td>"; 
		
		require_once("outro_lpea.php");
		
		echo "</td></div></tr></table></div>";
	
	} else if($_GET["opcao"] == 3){
		
		echo "
		<div class=\"local\"><a href=\"?elem=2\">".$_SESSION['estat_nome']."</a> 
		� <a href=\"?elem=2&amp;accao=8\">Gerir outros</a> � ".(isset($_GET['req'])?"Gerir requesi��o":"Gerir requesi��es")."</div>
		
		<div class=\"gestaopremain\">
		
		<table valign=\"top\" style=\"text-align:left; float:left;\">
		
		<tr>
		
		<td>"; 
		
		require_once("outro_gerir_req.php");
		
		if( isset($_GET['req']) )
			
			echo "<p><br /></p>
			<a href=\"?elem=2&amp;accao=8&amp;opcao=3\">Listar as requesi��es</a>";
		
			echo "<br /></td></div></tr></table></div>";
	
	} else {
		
		echo "
		<div class=\"local\"><a href=\"?elem=2\">".$_SESSION['estat_nome']."</a> 
		� Gerir outros itens</div>
		
		<div class=\"gestaopremain\">
		
		<table valign=\"top\" style=\"text-align:left; float:left;\">
		
		<tr>
		
		<td>";
		
		echo "<div id=\"gestaopremainarea\"><div id=\"gestaopremainareanome\">Op��es</div> 
	
		<a href=\"".$_SERVER["PHP_SELF"]."?elem=2&amp;accao=8&amp;opcao=1\"><div 
		id=\"gestaopremainareaprevit\" > 
		Inserir outro item
		</div></a>
					
					
		<a href=\"".$_SERVER["PHP_SELF"]."?elem=2&amp;accao=8&amp;opcao=2\"><div 
		id=\"gestaopremainareaprevit\" > 
		Apagar, Editar, Listar &amp; Procurar outros itens
		</div></a>
		
		<a href=\"".$_SERVER["PHP_SELF"]."?elem=2&amp;accao=8&amp;opcao=3\"><div 
		id=\"gestaopremainareaprevit\" > 
		Gerir requesis�es 
		</div></a>";	
		
		
		echo "</td></div></tr></table></div>";
	
	}
	
} else {
	
	include_once("home.php");
	
}
 
 
 ?> 