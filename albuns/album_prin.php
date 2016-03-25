 <?php 
/**
 * Menu de opções de gestão dos álbuns
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */
 
 if(  defined( 'IN_PHPAP' ) ) {
 
 validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
 
 if ( $_SESSION['estat_carac'][7]  ){
 	/**
 	 * Incluir as funções direccionadas para os álbuns independetemente do contexto
 	 * em que a página foi apresentada.
	 *   
 	 */
	require_once("album_funcoes.php");

	if ($_GET ["opcao"]==1 ){
	
		echo "
		<div class=\"local\"><a href=\"?elem=2\">".$_SESSION['estat_nome']."</a> 
		» <a href=\"?elem=2&accao=6\">Gerir álbuns</a> » Inserir álbuns</div>
		
		<div class=\"gestaopremain\">
		
		<table valign=\"top\" style=\"text-align:left; float:left;\">
		
		<tr>
		
		<td>"; 
		
		include_once("album_ins.php");
		
		echo "</td></div></tr></table></div>";
    
	} else if($_GET["opcao"] == 2){
	
		echo "
		<div class=\"local\"><a href=\"?elem=2\">".$_SESSION['estat_nome']."</a> 
		» <a href=\"?elem=2&accao=6\">Gerir álbuns</a> 
		» Apagar, Editar, Listar &amp; Procurar álbuns</div>
		
		<div class=\"gestaopremain\">
 	
		<table valign=\"top\" style=\"text-align:left; float:left;\">
		
		<tr>
		
		<td>"; 
		
		require_once("album_lpea.php");
		
		echo "</td></div></tr></table></div>";
	
	} else if($_GET["opcao"] == 3){
		
		echo "
		<div class=\"local\"><a href=\"?elem=2\">".$_SESSION['estat_nome']."</a> 
		» <a href=\"?elem=2&accao=6\">Gerir álbuns</a> 
		» ".(isset($_GET['req'])?"Gerir requesição":"Gerir requesições")."</div>
		
		<div class=\"gestaopremain\">
		
		<table valign=\"top\" style=\"text-align:left; float:left;\">
		
		<tr>
		
		<td>"; 
		
		require_once("album_gerir_req.php");
		
		if( isset($_GET['req']) )
			
			echo "<p><br /></p>
			<a href=\"?elem=2&amp;accao=6&amp;opcao=3\">Listar as requesições</a>";
		
			echo "</td></div></tr></table></div>";
	
	} else {
		
		echo "
		<div class=\"local\"><a href=\"?elem=2\">".$_SESSION['estat_nome']."</a> 
		» Gerir álbuns</div>
		
		<div class=\"gestaopremain\">
		
		<table valign=\"top\" style=\"text-align:left; float:left;\">
		
		<tr>
		
		<td>";
		
		echo "<div id=\"gestaopremainarea\"><div id=\"gestaopremainareanome\">Opções</div> 
	
		<a href=\"".$_SERVER["PHP_SELF"]."?elem=2&amp;accao=6&amp;opcao=1\"><div 
		id=\"gestaopremainareaprevit\" > 
			Inserir álbuns 
		</div></a>
					
					
		<a href=\"".$_SERVER["PHP_SELF"]."?elem=2&amp;accao=6&amp;opcao=2\"><div 
		id=\"gestaopremainareaprevit\" > 
			Apagar, Editar, Listar &amp; Procurar álbuns
		</div></a>
		
		<a href=\"".$_SERVER["PHP_SELF"]."?elem=2&amp;accao=6&amp;opcao=3\"><div 
		id=\"gestaopremainareaprevit\" > 
			Gerir requesisões 
		</div></a>";	
		
		
		echo "</td></div></tr></table></div>";
	
	}
	
} else {
	
	include_once("home.php");
	
}
 
}
 
 ?> 