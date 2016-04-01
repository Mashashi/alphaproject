<?
/**
 * Impress�o de um relat�rio do s�te.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11�, 12�; I1 E�a de Queir�s
 * @version 1.0
 * @todo Podia-se incluir aqui tamb�m as est�tisticas dos elementos 
 */

if( !isset($_SESSION['id_user']) ) session_start();


/**
 * Incluir a classe para a apresnta��o e formata��o dos dados ao estilo .pdf.
 */
require_once("fpdf.php");
/**
 * Incluir fun��es b�sicas.
 */
require_once("funcoes.php");

validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
 
//defininfo a fonte !
define('FPDF_FONTPATH','fpdf/font/');

//instancia a classe.. P=Retrato, mm =tipo de 
//medida utilizada no casso milimetros, tipo de folha =A4
$pdf = new FPDF("P","mm","A4");

//define a fonte a ser usada
$pdf->SetFont('arial','',10);
 
//define o titulo
$pdf->SetTitle("Relat�rio.");
 
//assunto
$pdf->SetSubject("Relat�rio - Estatuto ".$_SESSION['estat_nome']);
 
// posicao vertical no caso -1.. e o limite da margem
$pdf->SetY("-1");

//
$pdf->SetFont('arial','',8);

function BasicTable(array $header, $data)
{
	global $pdf;
    //Header
    foreach($header as $col)
        $pdf->Cell(39,7, $col ,1);
    $pdf->Ln();
	
	//wordwrap( mysql_result($query,$e,1), 5, "\n" )."\nr";
    //Data
    if(count($data) > 0){
    	foreach($data as $row)
    	{
        	foreach($row as $col)
            	$pdf->Cell(39,6,$col,1);
        	$pdf->Ln();
    	}
    }
}


$titulo = "Relat�rio - Estatuto ".$_SESSION['estat_nome'];

//escreve no pdf largura,altura,conteudo,borda,quebra de linha,alinhamento
$pdf->Cell(0,5,$titulo,0,0,'L');
 
$pdf->Cell(0,5,$_SERVER['PHP_SELF'],0,1,'R');
 
$pdf->Cell(0,0,'',1,1,'L');
 
$pdf->Ln(8);
 

 
//posiciona verticalmente 21mm
$pdf->SetY("21");
 
//posiciona horizontalmente 30mm
$pdf->SetX("30");
 
$pdf->Image("imagens/ecaacessibi.jpg", 8, 20, 70, 56);

$pdf->SetY("70");

$pdf->SetX("10");

$pdf->MultiCell(0, 5, str_replace( "<br />", "\n", commonInfo()), 0, 1);

$bd = ligarBD();

if( array_search(true, (array) $_SESSION['estat_carac'] ) > -1 )
	$common_buffer .= "\n\n\nRelat�rio Detalhado:\n\n\n";

if( $_SESSION['estat_carac'][0] ){
	
	$common_buffer = 
	"\nUsers c/ mais t�picos criados:  ";
	
	$query_most_topic_users = $bd->submitQuery( "
	SELECT DISTINCT `registo_nick`, COUNT( * ) AS Sum From `registo` Join `post` On
	`id_registo` = `registo_id_registo` Where `post_prin` = 1 And `post_activo` = 1
	GROUP BY CRC32( `registo_id_registo` )
	ORDER BY sum DESC
	LIMIT 0 , 5 ");
	
	while($row = mysql_fetch_row($query_most_topic_users)){
		
		$common_buffer .= $row[0].", ";
		
	}
	
	$common_buffer = substr($common_buffer,0,strlen($common_buffer)-2);
	
}

if( $_SESSION['estat_carac'][1] ){
	
	$common_buffer .= 
	"\n\nRespostas:\n\n
	Users c/ mais respostas criadas:  ";
	
	$query_most_topic_users = $bd->submitQuery( "
	SELECT DISTINCT `registo_nick`, COUNT( * ) AS Sum From `registo` Join `post` On
	`id_registo` = `registo_id_registo` Where `post_prin` = 0 And `post_activo` = 1
	GROUP BY CRC32( `registo_id_registo` )
	ORDER BY sum DESC
	LIMIT 0 , 5 ");
	
	while($row = mysql_fetch_row($query_most_topic_users)){
		
		$common_buffer .= $row[0].", ";
		
	}
	
	$common_buffer = substr($common_buffer,0,strlen($common_buffer)-2);
	
}

$pdf->MultiCell(0, 5, $common_buffer, 0, 1);

if($_SESSION['estat_carac'][2]){
	
	$pdf->MultiCell(0, 5, "\n\n�reas:\n\n", 0, 1);
	
	$query = $bd->submitQuery( "Select `id_area`,`area_nome` From `area`" );
	
	//$pdf->MultiCell(0, 5, "Contagem t�picos", 1, 1);
	//$pdf->MultiCell(0, 5, "Contagem respostas", 1, 1);
	
	$header = 
	array('Nome da �rea','Contagem t�picos','Contagem respostas','Contagem t�picos a spam');
	
	$somas[0] = "Total:";
	$somas[1] = 0;
	$somas[2] = 0;
	$somas[3] = 0;
	
	for($e = 0; $e < mysql_num_rows($query); $e++){
			//Erro quando a linha � muito grande o texto n�o coincide com o layout do doc
			//wordwrap a funcionar mas o fpdf n�o aceita o \n
			$data[$e][0] = wordwrap( mysql_result($query,$e,1), 10, "\n".$pdf->Ln() );
			
			$somas[1] += $data[$e][1] = 
			mysql_result( $bd->submitQuery( "Select Count( * ) 
			From `post` Where `topico_area_id_area` = ".mysql_result($query,$e,0)." 
			And `post_prin` = 1 And `post_activo` = 1" ), 0, 0);
			
			$somas[2] += $data[$e][2] = 
			mysql_result( $bd->submitQuery( "Select Count( * ) 
			From `post` Where `topico_area_id_area` = ".mysql_result($query,$e,0)."
			And `post_prin` = 0 And `post_activo` = 1" ), 0, 0);
			
			$somas[3] += $data[$e][3] = 
			mysql_result( $bd->submitQuery( "Select Count( * ) 
			From `post` Where `topico_area_id_area` = ".mysql_result($query,$e,0)."
			And `post_prin` = 1 And `post_activo` = 0" ), 0, 0);
			
			if($e == (mysql_num_rows($query)-1) ){
				
				$data[$e+1][0]= $somas[0];
				$data[$e+1][1]= $somas[1];
				$data[$e+1][2]= $somas[2];
				$data[$e+1][3]= $somas[3];
				
			}
			
	}
	
	BasicTable($header,$data);
	
	$data = array();
	
	$header = array('Nome da �rea', 'Contagem respostas a spam', 'T�picos trancados');
	
	$somas[0] = "Total:";
	$somas[1] = 0;
	$somas[2] = 0;
	
	for($e = 0; $e < mysql_num_rows($query); $e++){
		
			$data[$e][0] = mysql_result($query,$e,1);
			
			
			$somas[1] += $data[$e][1] = mysql_result( $bd->submitQuery( "
			Select Count( * )
			From `post` Where `topico_area_id_area` = ".mysql_result($query,$e,0)." 
			And `post_prin` = 0 And `post_activo` = 0 " ), 0, 0);
			
			/*
Select `topico_id_topico` As id_topico, IF(
((Select Count(*)
From `post` Where `topico_area_id_area` = 2 
And `post_prin` = 0 And `post_activo` = 0 And `topico_id_topico` = 2
And (Select `post_activo` From `post`
Where `topico_area_id_area` = 2 And `post_prin` = 1 And `topico_id_topico` = id_topico)) > 0),
(Select `id_post` From `post` Where `topico_area_id_area` = 2 
And `post_prin` = 0 And `post_activo` = 0),
("0")
) From `post`
			*/
			
			$somas[2] += $data[$e][2] = mysql_result( $bd->submitQuery( "Select Count( * ) 
			From `topico` Where `area_id_area` = ".mysql_result($query,$e,0)."
			And `topico_pode_comentar` = 0" ), 0, 0);
			
			if($e == (mysql_num_rows($query)-1) ){
				
				$data[$e+1][0]= $somas[0];
				$data[$e+1][1]= $somas[1];
				$data[$e+1][2]= $somas[2];
				
			}
			
	}
	
	BasicTable($header,$data);
	
	/*$common_buffer .= 
	"\n�rea c/ mais respostas: ".mysql_result( $bd->submitQuery( "
	SELECT DISTINCT `area_nome`, COUNT( * ) AS Sum From `area` Join `post` On
	`id_area` = `topico_area_id_area` Where `post_prin` = 0 And `post_activo` = 1
	GROUP BY CRC32( `registo_id_registo` )
	ORDER BY sum DESC
	LIMIT 0 , 1 " ),0 , 0 );*/
	
	/*$common_buffer .= 
	"\n�rea c/ mais t�picos: ".mysql_result( $bd->submitQuery( "Select Count(*) 
	From `post` Where `post_prin` = 0 And `post_activo` = 0" ),0 , 0 );*/
	
}

if($_SESSION['estat_carac'][3]){
	
	$common_buffer .= 
	"\n\nRequerir filmes:\n\n";
	
}

if($_SESSION['estat_carac'][4]){
	
	$common_buffer .= 
	"\n\nRequerir �lbuns:\n\n";
	
}

if($_SESSION['estat_carac'][5]){
	
	$common_buffer .= 
	"\n\nRequerir outro:\n\n";
	
}

if($_SESSION['estat_carac'][6]){
	
	$common_buffer .= 
	"\n\nGerir filme:\n\n";
	
}

if($_SESSION['estat_carac'][7]){
	
	$common_buffer .= 
	"\n\nGerir �lbum:\n\n";
	
}

if($_SESSION['estat_carac'][8]){
	
	$common_buffer .= 
	"\n\nGerir outro:\n\n";
	
}

if($_SESSION['estat_carac'][9]){
	
	$common_buffer .= 
	"\n\nGerir faq:\n\n";
	
}

if($_SESSION['estat_carac'][10]){
	
	$common_buffer .= 
	"\n\nGerir registo:\n\n";
	
}

if($_SESSION['estat_carac'][11]){
	
	$common_buffer .= 
	"\n\nGerir estatuto:\n\n";
	
}

if($_SESSION['estat_carac'][12]){
	
	$common_buffer .= 
	"\n\nGerir frases:\n\n";
	
}



 
//$pdf->MultiCell(0, 5, $common_buffer, 0, 1);


/*******definindo o rodap�*************************/
//posiciona verticalmente 270mm
$pdf->SetY("270");
 
//data actual
$data = date("d/m/Y");
 
$data = "Criado em ".$data;
 
$n_pag = $pdf->PageNo();
 
//imprime uma celula... largura,altura, texto,borda,quebra de linha, alinhamento
//$pdf->Cell( 0, 0, '', 1, 1, 'L' );

//imprime uma celula... largura,altura, texto,borda,quebra de linha, alinhamento
$pdf->Cell( 0, 5, $data, 0, 0, 'L');
 
//imprime uma celula... largura,altura, texto,borda,quebra de linha, alinhamento
$pdf->Cell( 0, 5, $n_pag, 0, 1, 'R' );

//imprime a saida do arquivo..
$pdf->Output("arquivo","I");
 
 
 

 
 
 
 
 
 
 
 
 
/*
************************************************************************

REFER�NCIAS :

FPDF - >Esta � o construtor da classe. Ele permite que seja definido o formato da p�gina, a orienta��o e a unidade de medida usada em todos os m�todos (exeto para tamanhos de fonte).

utilizacao : FPDF([string orientation [, string unit [, mixed format]]])

SetFont -> Define a fonte que ser� usada para imprimir os caracteres de texto. � obrigat�ria a chamada, ao menos uma vez, deste m�todo antes de imprimir o texto ou o documento resultante n�o ser� v�lido.

utilizacao : SetFont(string family [, string style [, float size]])

SetTitle - >Define o t�tulo do documento.

utilizacao : SetTitle(string title)

SetSubject -> Define o assunto do documento

utilizacao : SetSubject(string subject)

SetX - >Define a abscissa da posi��o corrente. Se o valor passado for negativo, ele ser� relativo � margem direita da p�gina.

utilizacao : SetX(float x)

SetY - > Move a abscissa atual de volta para margem esquerda e define a ordenada. Se o valor passado for negativo, ele ser� relativo a margem inferior da p�gina.

utilizacao : SetY(float y)

Cell - > Imprime uma c�lula (�rea retangular) com bordas opcionais, cor de fundo e texto. O canto superior-esquerdo da c�lula corresponde � posi��o atual. O texto pode ser alinhado ou centralizado. Depois de chamada, a posi��o atual se move para a direita ou para a linha seguinte. � poss�vel p�r um link no texto.

Se a quebra de p�gina autom�tica est� habilitada e a pilha for al�m do limite, uma quebra de p�gina � feita antes da impress�o.

utilizacao - >Cell(float w [, float h [, string txt [, mixed border [, int ln [, string align [, int fill [, mixed link]]]]]]])

Ln - > Faz uma quebra de linha. A abscissa corrente volta para a margem esquerda e a ordenada � somada ao valor passado como par�metro.

utilizacao ->Ln([float h])

MultiCell - > Este m�todo permite imprimir um texto com quebras de linha. Podem ser autom�tica (assim que o texto alcan�a a margem direita da c�lula) ou expl�cita (atrav�s do caracter \n). Ser�o geradas tantas c�lulas quantas forem necess�rias, uma abaixo da outra.

O texto pode ser alinhado, centralizado ou justificado. O bloco de c�lulas podem ter borda e um fundo colorido.

utilizacao : MultiCell(float w, float h, string txt [, mixed border [, string align [, int fill]]])

Image ->Coloca uma imagem na p�gina - tipos suportados JPG PNG

utilizacao : Image(string file, float x, float y [, float w [, float h [, string type [, mixed link]]]])

*/

//}

?>