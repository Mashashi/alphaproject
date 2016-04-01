<?php
/**  
 * Imprime uma imagem com um texto originado randomicamente.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 */

/**
 * Vari�veis de configura��o do ficheiro
 *  
 */
$width = 180;
$height = 50;

/** 
 * Incluir as fun��es mais avan�adas.
 */
include_once("funcoes_mais_avan.php");

// Salve como "img.php"
// Inicia sess�o.
/*if( !isset($_SESSION['id_user']) )*/
session_start();

// Tamb�m temos uma mensagem de erro nesse header quando a GD n�o esta instalada.
header ("Content-type: image/png");

//�rea da imagem.
$area = imagecreatetruecolor($width, $height);

// Cor de fundo.
$fundo = imagecolorallocate($area, 255, 255, 255);

$cor4 = imagecolorallocatealpha($area, rand(220,249), 
rand(183,203), rand(117,137), rand(0,60));

/*Daqui em diante temos o GD a trabalhar para mostrar o c�digo alfanum�rico e os riscos meio que rasurando o resultado do c�digo. Apesar que a inten��o � essa mesmo! Altere os valores e veja o que acontece com as formas e as cores!*/

// Desenha �rea
imagefill($area, 0, 0, $fundo);

$_SESSION['gd_code'] = genarateCod(4);

$_SESSION['used_gd_code'] = 0;

// O texto do c�digo.
imagettftext($area, 20, rand(-6,6), rand(8,15), 
($height*0.80) ,$cor4,'fonts/perfectdrown.ttf', $_SESSION['gd_code'] );

// Constroi
imagepng($area);

// Destr�i
imagedestroy($area);
?>