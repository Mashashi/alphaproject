<?php

/**
 * Por esta classe são feitas as pesquisas rápidas.
 *   
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

/**
 * Saber se está ou não incluido no index.php
 * 
 */
$alpha_root_path = defined( 'IN_PHPAP' ) ? "" : "../";
 
/**
 * Incluir a classe para fazer a variável de acesso a base de dados bd.php.
 *   	
 */
 include_once ( $alpha_root_path."bd.php" );

/**
 * Classe utilizada para concretizar pesquisas rapidas pelo síte.
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
 	 * Método instanciador desta classe.
	 *   
	 * @param String $psqPor Título da pesquisa
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
 	 * Obter uma representação textual da classe, mais precisamente o título.   
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
	 * @param String $nom Parâmetro de pesquisa nick de utilizador.
	 * @param String $adi Parâmetros adicionais.
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
 	 * Pesquisar tópicos.
	 *   
	 * @param String $nom Parâmetro de pesquisa, nome do tópico.
	 * @param String $adi Parâmetros adicionais.
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
	 * @param String $nom Parâmetro de pesquisa, texto da resposta.
	 * @param String $adi Parâmetros adicionais.
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
	 * @param String $nom Parâmetro de pesquisa, nome do elemento do tipo filme.
	 * @param String $adi Parâmetros adicionais.
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
 	 * Pesquisar elementos do tipo álbum.
	 *   
	 * @param String $nom Parâmetro de pesquisa, nome do elemento do tipo álbum.
	 * @param String $adi Parâmetros adicionais.
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
	 * @param String $nom Parâmetro de pesquisa, nome do elemento do tipo outro.
	 * @param String $adi Parâmetros adicionais.
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
 	 * Suporta no máximo dois campos por pesquisa.
 	 * Função comum a {@link psqAnunNom($nom, $adi)} & {@link psqUtilNick($nick, $adi)}.
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
 	 * Capturar a última query executada.
	 * 
	 * @return mixed array   	
	 */
	public function getLastQuery(){
		
		return $this->lastQuery;	
		
	}
	
}

?>