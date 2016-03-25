<?
/**
 * Impressão de um relatório do síte.
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 * @todo Podia-se incluir aqui também as estátisticas dos elementos 
 */

if( !isset($_SESSION['id_user']) ) session_start();


/**
 * Incluir a classe para a apresntação e formatação dos dados ao estilo .pdf.
 */
require_once("fpdf.php");
/**
 * Incluir funções básicas.
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
$pdf->SetTitle("Relatório.");
 
//assunto
$pdf->SetSubject("Relatório - Estatuto ".$_SESSION['estat_nome']);
 
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


$titulo = "Relatório - Estatuto ".$_SESSION['estat_nome'];

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
	$common_buffer .= "\n\n\nRelatório Detalhado:\n\n\n";

if( $_SESSION['estat_carac'][0] ){
	
	$common_buffer = 
	"\nUsers c/ mais tópicos criados:  ";
	
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
	
	$pdf->MultiCell(0, 5, "\n\nÁreas:\n\n", 0, 1);
	
	$query = $bd->submitQuery( "Select `id_area`,`area_nome` From `area`" );
	
	//$pdf->MultiCell(0, 5, "Contagem tópicos", 1, 1);
	//$pdf->MultiCell(0, 5, "Contagem respostas", 1, 1);
	
	$header = 
	array('Nome da área','Contagem tópicos','Contagem respostas','Contagem tópicos a spam');
	
	$somas[0] = "Total:";
	$somas[1] = 0;
	$somas[2] = 0;
	$somas[3] = 0;
	
	for($e = 0; $e < mysql_num_rows($query); $e++){
			//Erro quando a linha é muito grande o texto não coincide com o layout do doc
			//wordwrap a funcionar mas o fpdf não aceita o \n
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
	
	$header = array('Nome da área', 'Contagem respostas a spam', 'Tópicos trancados');
	
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
	"\nÁrea c/ mais respostas: ".mysql_result( $bd->submitQuery( "
	SELECT DISTINCT `area_nome`, COUNT( * ) AS Sum From `area` Join `post` On
	`id_area` = `topico_area_id_area` Where `post_prin` = 0 And `post_activo` = 1
	GROUP BY CRC32( `registo_id_registo` )
	ORDER BY sum DESC
	LIMIT 0 , 1 " ),0 , 0 );*/
	
	/*$common_buffer .= 
	"\nÁrea c/ mais tópicos: ".mysql_result( $bd->submitQuery( "Select Count(*) 
	From `post` Where `post_prin` = 0 And `post_activo` = 0" ),0 , 0 );*/
	
}

if($_SESSION['estat_carac'][3]){
	
	$common_buffer .= 
	"\n\nRequerir filmes:\n\n";
	
}

if($_SESSION['estat_carac'][4]){
	
	$common_buffer .= 
	"\n\nRequerir álbuns:\n\n";
	
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
	"\n\nGerir álbum:\n\n";
	
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


/*******definindo o rodapé*************************/
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

REFERÊNCIAS :

FPDF - >Esta é o construtor da classe. Ele permite que seja definido o formato da página, a orientação e a unidade de medida usada em todos os métodos (exeto para tamanhos de fonte).

utilizacao : FPDF([string orientation [, string unit [, mixed format]]])

SetFont -> Define a fonte que será usada para imprimir os caracteres de texto. É obrigatória a chamada, ao menos uma vez, deste método antes de imprimir o texto ou o documento resultante não será válido.

utilizacao : SetFont(string family [, string style [, float size]])

SetTitle - >Define o título do documento.

utilizacao : SetTitle(string title)

SetSubject -> Define o assunto do documento

utilizacao : SetSubject(string subject)

SetX - >Define a abscissa da posição corrente. Se o valor passado for negativo, ele será relativo à margem direita da página.

utilizacao : SetX(float x)

SetY - > Move a abscissa atual de volta para margem esquerda e define a ordenada. Se o valor passado for negativo, ele será relativo a margem inferior da página.

utilizacao : SetY(float y)

Cell - > Imprime uma célula (área retangular) com bordas opcionais, cor de fundo e texto. O canto superior-esquerdo da célula corresponde à posição atual. O texto pode ser alinhado ou centralizado. Depois de chamada, a posição atual se move para a direita ou para a linha seguinte. É possível pôr um link no texto.

Se a quebra de página automática está habilitada e a pilha for além do limite, uma quebra de página é feita antes da impressão.

utilizacao - >Cell(float w [, float h [, string txt [, mixed border [, int ln [, string align [, int fill [, mixed link]]]]]]])

Ln - > Faz uma quebra de linha. A abscissa corrente volta para a margem esquerda e a ordenada é somada ao valor passado como parâmetro.

utilizacao ->Ln([float h])

MultiCell - > Este método permite imprimir um texto com quebras de linha. Podem ser automática (assim que o texto alcança a margem direita da célula) ou explícita (através do caracter \n). Serão geradas tantas células quantas forem necessárias, uma abaixo da outra.

O texto pode ser alinhado, centralizado ou justificado. O bloco de células podem ter borda e um fundo colorido.

utilizacao : MultiCell(float w, float h, string txt [, mixed border [, string align [, int fill]]])

Image ->Coloca uma imagem na página - tipos suportados JPG PNG

utilizacao : Image(string file, float x, float y [, float w [, float h [, string type [, mixed link]]]])

*/

//}

?>