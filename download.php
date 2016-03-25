<?php
/**
 * Ficheiro de download. 
 *
 * Chamada:
 *    download.php?f=phptutorial.zip
 *
 * @author Rafael Campos
 * @package alphaproject_biblioteca
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */


/**
 * Permite hotlinking
 * 
 * O que é hotlinking
 * 
 * Muitas vezes tens uma imagem bonita ou interessante em seu site, e alguém 
 * resolve copiá-la para colocar em outro site. Técnicamente isso não te traz 
 * prejuízo algum. O problema começa quando o dono do outro site, em vez de 
 * copiar a imagem, simplesmente chama a sua imagem diretamente do seu servidor, 
 * fazendo um link direto. Isto é o hotlink. 
 *    
 * Vazio - Permite hotlinking 
 * 
 * Se não estiver vazio (Examplo: examplo.com) só vai permitir download quando este 
 * texto é referido
 *  
 */
define('ALLOWED_REFERRER', '');

/**
 * Pasta de download
 *  
 */
define('BASE_DIR','downloads/');

/**
 * Fazer ficheiro de log downloads?  true/false
 *  
 */
define('LOG_DOWNLOADS',true);
 
/**
 * Nome do ficheiro de log
 *  
 */
define('LOG_FILE',BASE_DIR.'downloads.log');

// Extensões permitidas
$allowed_ext = array (

  // archives
  'zip' => 'application/zip',

  // documents
  'pdf' => 'application/pdf',
  'doc' => 'application/msword',
  'xls' => 'application/vnd.ms-excel',
  'ppt' => 'application/vnd.ms-powerpoint',
  
  // executables
  'exe' => 'application/octet-stream',

  // images
  'gif' => 'image/gif',
  'png' => 'image/png',
  'jpg' => 'image/jpeg',
  'jpeg' => 'image/jpeg',

  // audio
  'mp3' => 'audio/mpeg',
  'wav' => 'audio/x-wav',

  // video
  'mpeg' => 'video/mpeg',
  'mpg' => 'video/mpeg',
  'mpe' => 'video/mpeg',
  'mov' => 'video/quicktime',
  'avi' => 'video/x-msvideo',
  'wmv' => 'video/wmv'
  
);



####################################################################
###  Nada de mudanças a baixo
####################################################################


if (ALLOWED_REFERRER !== ''
&& (!isset($_SERVER['HTTP_REFERER']) || strpos(strtoupper($_SERVER['HTTP_REFERER']),strtoupper(ALLOWED_REFERRER)) === false)
) {
  
  die();
}


set_time_limit(0);

if (!isset($_GET['f']) || empty($_GET['f'])) {
  die();
}


$fname = basename($_GET['f']);


function find_file ($dirname, $fname, &$file_path) {

  $dir = opendir($dirname);

  while ($file = readdir($dir)) {
    if (empty($file_path) && $file != '.' && $file != '..') {
      if (is_dir($dirname.'/'.$file)) {
        find_file($dirname.'/'.$file, $fname, $file_path);
      }
      else {
        if (file_exists($dirname.'/'.$fname)) {
          $file_path = $dirname.'/'.$fname;
          return;
        }
      }
    }
  }

} 


$file_path = '';
find_file(BASE_DIR, $fname, $file_path);

//O ficheiro não existe
if (!is_file($file_path)) {
  die();
}

// Tamanho do ficheiro em bytes
$fsize = filesize($file_path); 

// Extensão do ficheiro
$fext = strtolower(substr(strrchr($fname,"."),1));

// Ver se a extensão está habilitada
if (!array_key_exists($fext, $allowed_ext)) {
  //die("Not allowed file type."); 
  die();
}


if ($allowed_ext[$fext] == '') {
  $mtype = '';
  
  if (function_exists('mime_content_type')) {
    $mtype = mime_content_type($file_path);
  }
  else if (function_exists('finfo_file')) {
    $finfo = finfo_open(FILEINFO_MIME); 
    $mtype = finfo_file($finfo, $file_path);
    finfo_close($finfo);  
  }
  if ($mtype == '') {
    $mtype = "application/force-download";
  }
}
else {
  // get mime type defined by admin
  $mtype = $allowed_ext[$fext];
}

if (!isset($_GET['fc']) || empty($_GET['fc'])) {
  $asfname = $fname;
}
else {
  // Retirar caractéres inválidos
  $asfname = str_replace(array('"',"'",'\\','/'), '', $_GET['fc']);
  if ($asfname === '') $asfname = 'NoName';
}

// Set header
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Type: $mtype");
header("Content-Disposition: attachment; filename=\"$asfname\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: " . $fsize);

// Download
$file = @fopen($file_path,"rb");
if ($file) {
  while(!feof($file)) {
    print(fread($file, 1024*8));
    flush();
    if (connection_status()!=0) {
      @fclose($file);
      die();
    }
  }
  @fclose($file);
}

// log downloads
if (!LOG_DOWNLOADS) die();

$f = @fopen(LOG_FILE, 'a+');
if ($f) {
  @fputs($f, date("m.d.Y g:ia")."  ".$_SERVER['REMOTE_ADDR']."  ".$fname."\n");
  @fclose($f);
}

?>