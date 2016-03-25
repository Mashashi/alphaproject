<?php

/**
 * Por esta classe sуo feitas todas as ligaчѕes a base de dados.
 *   
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11К, 12К; I1 Eчa de Queirѓs
 * @version 1.0
 */

/**
 * Muito њtil todas as validчѕes/excepчѕes podem ser aqui declaradas.
 * O que melhora em muito a modulсridade do sэte, e consequentemente torna-se 
 * de mais fсcil 
 * manutenчуo.
 * 
 * @package alphaproject_biblioteca 
 */
class bd
{

	private $ligacao = "";

	private $basedados = "";

	/**
	 * bd::__construct()
	 * 
	 * Tem como funчуo poder inicializar a ligaчуo a instanciaчуo.
	 * Este mщtodo nуo faz nada apenas foi introduzido para que nуo 
	 * houvessem duvidas quanto a sua
	 * utilizaчуo na continuaчуo do trabalho.  
	 * 
	 * @param boolean $debug  
	 * @return void
	 */
	function __construct()
	{
	}


	/**
	 * bd::__toString()
	 * 
	 * Devolve o nome da base de dados.
	 *   
	 * @return String
	 */
	function __toString()
	{
		return @mysql_result( $this->submitQuery( "SELECT DATABASE()" ) ,0,0 );
	}


	/**
	 * bd::setLigar()
	 * 
	 * Responsсvel por iniciar uma ligaчуo a bd "personalizada".
	 *  
	 * @param String $host
	 * @param String $user
	 * @param String $pass
	 * @param String $bd
	 *  
	 * @return void
	 */
	public function setLigar( $host, $user, $pass, $bd )
	{
		
		$this->ligacao = @mysql_pconnect( $host, $user, $pass ) 
		or die( header( 'Location: '.substr( $_SERVER['PHP_SELF'],0 , 
		( strrpos ($_SERVER['PHP_SELF'],"/") ) ).'/install') );
		
		$this->basedados = @mysql_select_db( $bd, $this->ligacao ) 
		or die(  header( 'Location: '.substr( $_SERVER['PHP_SELF'],0 , 
		( strrpos ($_SERVER['PHP_SELF'],"/") ) ).'/install') );
		
	}


	/**
	 * bd::setDesligar()
	 * 
	 * Terminar ligaчуo.
	 *   
	 * @return void
	 */
	/*public function setDesligar()
	{

		if ( $this->ligacao != "" )
		{

			mysql_close( $this->ligacao ) or die();

			$this->ligacao != "";

		}
	}*/


	/**
	 * bd::submitQuery()
	 * 
	 * Esta funчуo devolve a query requesitada.
	 *   
	 * @param mixed $query
	 * 
	 * @return mysql_query
	 */
	public function submitQuery( $query )
	{

		$data = "";

		if ( $this->ligacao != "" )
			$data = @mysql_query( $query ) or die();
		else
			die();

		return $data;

	}


	/**
	 * bd::getLigacao()
	 * 
	 * Obter a ligaчуo corrente.
	 *   
	 * @return mysql_pconnect
	 */
	public function getLigacao()
	{

		$liga = "";

		if ( $this->ligacao != "" )
			$liga = $this->ligacao;

		else
			die();

		return $liga;

	}


	/**
	 * bd::getBaseDados()
	 * 
	 * Obter a ligaчуo a base de dados corrente.
	 *   
	 * @return mysql_select_db
	 */
	public function getBaseDados()
	{

		$base = "";

		if ( $this->ligacao != "" )
			$base = $this->basedados;

		else
			die();

		return $base;

	}


}

?>