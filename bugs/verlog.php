<?
/**
 * Serve o propósito de apresentar os bugs submetidos.
 * Nota que é usada a função crypt() em modo CRYPT_BLOWFISH.
 *   
 * @author Rafael Campos
 * @package alphaproject_biblioteca
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */

/**
 * Incluir as configurações
 *  
 */
require_once("../config.php");

//Esconder
if( isset($_GET["fechar"]) ){

	setcookie("autorizado", 0, time()+10, null, null, true);
	
	header("Location: ".$_SERVER["PHP_SELF"]);
	
}

//Variáveis
$aprovado = postLogAndCompare($_POST["usertry"], $_POST["passtry"]);
$actual = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<!--
<script type="text/javascript">
	document.forms["formtry"].elements["usertry"].value = "";
	document.forms["formtry"].elements["usertry"].value = "";
</script>
-->
</head>

<body>
<?
	/*$pass = crypt($pass, CRYPT_BLOWFISH);
	echo $pass."<br />";*/
	
	if( @include_once("entradas.php") ){
		
		if( $aprovado || $_COOKIE["autorizado"]){
		
			echo "
				<div style=\"border:gray 1px solid\">
				<table>
				<tr><td>Host</td><td>Data</td><td>Descrição</td></tr>
				";
				
			while( current($event) ){
				
				echo "<tr>";
				
				$actual = explode("::",current($event));
				
				echo "<td>$actual[0]</td><td>$actual[1]</td><td>$actual[2]</td>";
				
				echo "</tr>";
				
				next($event);
				
			}
				
			echo "
				<tr><td><a href=\"?fechar=1\">Esconder</a></td></tr>
				</table>
				</div>
				";
		
		}
		
	}  
		
		echo "
			<form action=\"".$_SERVER["PHP_SELF"]."\" method=\"post\" name=\"formtry\" id=\"formtry\">
				<label for=\"usertry\">
				<input type=\"text\" name=\"usertry\" value=\"\" id=\"usertry\" />
				</label>
				<label for=\"passtry\">
				<input type=\"text\" name=\"passtry\" value=\"\" id=\"passtry\" />
				</label>
				<p><input type=\"submit\" name=\"submitdata\" id=\"submitdata\" value=\"Ir para a fabrica dos doces\" /></p>
			</form>
		";
	
	

?>
</body>
<?
//
/**
 * Função responsável pela impressão em caso dos dados de login estarem correctos ou não 
 * caso contrário.
 * 
 * @global String Password de acesso
 * @global Nome de utilizador  
 * 
 * @param String $usertry
 * @param String $passtry 
 *     
 * @return boolean  
 */
function postLogAndCompare($usertry, $passtry){
	
	
	
	global $pass_log;
	global $user_log;
	
	$speaker = false;
	
	$passtry = crypt($passtry, CRYPT_BLOWFISH);

	if($passtry == $pass && $usertry == $user)
	{
		setcookie("autorizado", 1, time()+$tempexp);
		
		$speaker = true;
	}
	
	return $speaker;
	
}
?>
</html>