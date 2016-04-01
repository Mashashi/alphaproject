<?php

/**
 * Por esta classe s�o feitas as pesquisas r�pidas.
 *   
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 */

/**
 * Saber se est� ou n�o incluido no index.php
 * 
 */
$alpha_root_path = defined( 'IN_PHPAP' ) ? "" : "../";
 
/**
 * Incluir a classe para fazer a vari�vel de acesso a base de dados bd.php.
 *   	
 */
 include_once ( $alpha_root_path."bd.php" );

/**
 * Classe utilizada para concretizar pesquisas rapidas pelo s�te.
 * 
 *   
 * @package alphaproject_biblioteca  
 */
class pesq_rap
{
	
	private $psqPor = "";
	
	private $bd = "";
	
	private $lastQuery = "";
	
	/**
 	 * pesq_rap::__construct()
 	 * 
 	 * M�todo instanciador desta classe.
	 *   
	 * @param String $psqPor T�tulo da pesquisa
	 * @param String $host
	 * @param String $user_bd
	 * @param String $pass_bd
	 * @param String $db   
	 * 
	 * @return void  	
	 */
	public function __construct( $psqPor, $host, $user_bd, $pass_bd, $db )
	{
		
		$this->psqPor = $psqPor;
		
		$this->bd = new bd();
		
		$this->bd->setLigar( $host, $user_bd, $pass_bd, $db );
		
	}
	
	/**
 	 * pesq_rap::__toString()
 	 * 
 	 * Obter uma representa��o textual da classe, mais precisamente o t�tulo.   
	 * 
	 * @return String  	
	 */
	public function __toString(){
		
		return $this->psqPor;
		
	}
	
	/**
 	 * pesq_rap::psqUtilNick()
 	 * 
	 * Pesquisar utilizadores
 	 *  
	 * @param String $nom Par�metro de pesquisa nick de utilizador.
	 * @param String $adi Par�metros adicionais.
	 *      
	 * @return String	
	 */
	public function psqUtilNick($nick, $adi){
		
		$nick = explode(" ",$nick);
		
		$queryTxt = "Select `id_registo`,`registo_nick` From `registo` 
		Where `registo_nick` ";
			
		for($i = 0; $i < count($nick); $i++)
			
			$queryTxt .= " Like '%$nick[$i]%' Or `registo_nick` ";
		
		$queryTxt = substr($queryTxt,0,strlen($queryTxt)-19)." $adi";
		
		return $this->psqBody($queryTxt);
		
	}
	
	/**
 	 * pesq_rap::psqTopiNom()
 	 * 
 	 * Pesquisar t�picos.
	 *   
	 * @param String $nom Par�metro de pesquisa, nome do t�pico.
	 * @param String $adi Par�metros adicionais.
	 * 
	 * @return mixed array   	
	 */
	public function psqTopiNom($nom, $adi){
		
		$nom = explode(" ",$nom);
		
		$queryTxt = "Select `id_post`,`post_titulo` From `post` 
		Where `post_titulo` ";
			
		for($i = 0; $i < count($nom); $i++)
			
			$queryTxt .= " Like '%$nom[$i]%' Or `post_titulo` ";
		
		$queryTxt = substr($queryTxt,0,strlen($queryTxt)-18);
		
		$queryTxt .= " And `post_activo` = 1 And `post_prin` = 1  $adi";
		
		return $this->psqBody($queryTxt);
		
	}
	
	/**
 	 * pesq_rap::psqPostNom()
 	 * 
 	 * Pesquisar respostas.
	 *   
	 * @param String $nom Par�metro de pesquisa, texto da resposta.
	 * @param String $adi Par�metros adicionais.
	 * 
	 * @return mixed array   	
	 */
	public function psqPostNom($nom, $adi){
		
		$nom = explode(" ",$nom);
		
		$queryTxt = "Select `id_post`,`post_titulo` From `post` 
		Where `post_texto` ";
			
		for($i = 0; $i < count($nom); $i++)
			
			$queryTxt .= " Like '%$nom[$i]%' Or `post_texto` ";
		
		$queryTxt = substr($queryTxt,0,strlen($queryTxt)-17);
		
		$queryTxt .= " And `post_activo` = 1 And `post_prin` = 0 $adi";
		
		return $this->psqBody($queryTxt);
		
	}
	
	/**
 	 * pesq_rap::psqFilme()
 	 * 
 	 * Pesquisar respostas.
	 *   
	 * @param String $nom Par�metro de pesquisa, nome do elemento do tipo filme.
	 * @param String $adi Par�metros adicionais.
	 * 
	 * @return mixed array   	
	 */
	public function psqFilme( $nom, $adi ){
		
		$nom = explode(" ",$nom);
		
		switch($flag){
		
			case 1: $campo = "`filme_etiqueta`";
			break;
			
			default: $campo = "`filme_nome`";
			
		}
		
		$queryTxt = "Select `geral_id_geral`,$campo From `filme` 
		Where `filme_nome` ";
			
		for($i = 0; $i < count($nom); $i++)
			
			$queryTxt .= " Like '%$nom[$i]%' Or `filme_nome` ";
		
		$queryTxt = substr( $queryTxt,0,strlen($queryTxt)-17 )." $adi";
		
		
		return $this->psqBody($queryTxt);
		
	}
	
	/**
 	 * pesq_rap::psqAlbum()
 	 * 
 	 * Pesquisar elementos do tipo �lbum.
	 *   
	 * @param String $nom Par�metro de pesquisa, nome do elemento do tipo �lbum.
	 * @param String $adi Par�metros adicionais.
	 * 
	 * @return mixed array   	
	 */
	public function psqAlbum( $nom, $adi ){
		
		$nom = explode(" ",$nom);
		
		switch($flag){
		
			case 1: $campo = "`album_etiqueta`";
			break;
			
			default: $campo = "`album_nome`";
			
		}
		
		$queryTxt = "Select `geral_id_geral`, $campo From `album` 
		Where `album_nome` ";
			
		for($i = 0; $i < count($nom); $i++)
			
			$queryTxt .= " Like '%$nom[$i]%' Or `album_nome` ";
		
		$queryTxt = substr( $queryTxt,0,strlen($queryTxt)-17 )." $adi";
		
		return $this->psqBody($queryTxt);
		
	}
	
	/**
 	 * pesq_rap::psqOutro()
 	 * 
 	 * Pesquisar em elementos do tipo outro.
	 *   
	 * @param String $nom Par�metro de pesquisa, nome do elemento do tipo outro.
	 * @param String $adi Par�metros adicionais.
	 * 
	 * @return mixed array   	
	 */
	public function psqOutro( $nom, $adi ){
		
		$nom = explode(" ",$nom);
		
		switch($flag){
		
			case 1: $campo = "`outro_etiqueta`";
			break;
			
			default: $campo = "`outro_nome`";
			
		}
		
		$queryTxt = "Select `geral_id_geral`, $campo From `outro` 
		Where `outro_nome` ";
			
		for($i = 0; $i < count($nom); $i++)
			
			$queryTxt .= " Like '%$nom[$i]%' Or `outro_nome` ";
		
		$queryTxt = substr( $queryTxt,0,strlen($queryTxt)-17 )." $adi";
		
		return $this->psqBody($queryTxt);
		
	}
	
	/**
 	 * pesq_rap::psqBody()
 	 * 
 	 * Suporta no m�ximo dois campos por pesquisa.
 	 * Fun��o comum a {@link psqAnunNom($nom, $adi)} & {@link psqUtilNick($nick, $adi)}.
	 *   
	 * @param String $queryTxt A query a ser executada.
	 *    
	 * @return mixed array	
	 */
	private function psqBody($queryTxt){
		
		$this->lastQuery = $queryTxt;
		
		$resposta = array();
		
		$count = 0;
		
		$query = $this->bd->submitQuery($queryTxt);
		
		while($row = mysql_fetch_array($query)){
			
			$resposta[$count++] = $row[0];
			
			$resposta[$count++] = $row[1];
			
		}
		
		mysql_free_result($query);
		
		return $resposta;	
		
	}
	
	/**
 	 * pesq_rap::getLastQuery()
 	 * 
 	 * Capturar a �ltima query executada.
	 * 
	 * @return mixed array   	
	 */
	public function getLastQuery(){
		
		return $this->lastQuery;	
		
	}
	
}

?>