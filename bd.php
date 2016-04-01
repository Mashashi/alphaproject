<?php

/**
 * Por esta classe s�o feitas todas as liga��es a base de dados.
 *   
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 */

/**
 * Muito �til todas as valid��es/excep��es podem ser aqui declaradas.
 * O que melhora em muito a modul�ridade do s�te, e consequentemente torna-se 
 * de mais f�cil 
 * manuten��o.
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
	 * Tem como fun��o poder inicializar a liga��o a instancia��o.
	 * Este m�todo n�o faz nada apenas foi introduzido para que n�o 
	 * houvessem duvidas quanto a sua
	 * utiliza��o na continua��o do trabalho.  
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
	 * Respons�vel por iniciar uma liga��o a bd "personalizada".
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
	 * Terminar liga��o.
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
	 * Esta fun��o devolve a query requesitada.
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
	 * Obter a liga��o corrente.
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
	 * Obter a liga��o a base de dados corrente.
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