<?php
/**
 * Permite que sejam geridos blocos de código na página home.   
 *  
 * @author Rafael Campos
 * @package alphaproject_biblioteca 
 * @copyright Copyright (c) 2008, 2009; 11º, 12º; I1 Eça de Queirós
 * @version 1.0
 */
 
 include_once ( "funcoes.php" );
 
 if ( ! isset($_SESSION['user']) ) session_start();

 validarKey( $_SESSION['id_user'], $_SESSION['id_estat'] , $_SESSION['key_session'] );
 
 if( isset($_SESSION['estat_carac']) ){
 
 if ( array_search ( false , $_SESSION['estat_carac'] ) === false ){
	
	if( isset( $_POST['del_block'] ) && is_numeric( $_POST['del_block'] ) &&  $_POST['del_block'] > 0 ){
		
		$bd = ligarBD();
		
		$bd->submitQuery("Delete From `bloco` Where `id_bloco` = ".$_POST['del_block']); 
		
		if(mysql_affected_rows() == 1){
		
			die(rawurlencode("O bloco foi apagado com sucesso."));
		
		} else {
		
			die(rawurlencode("Este bloco já não existe."));
	
		}
		
	} if( isset($_POST['id_bloco']) && is_numeric($_POST['id_bloco']) && isset( $_POST['block_code'] ) 
	&& isset( $_POST['block_des'] ) 
	&& isset( $_POST['block_act'] ) && is_bool((bool)$_POST['block_act'] ) ){
		
		
		$id_bloco =  clearGarbadge($_POST['id_bloco'],false,false);
		
		$bloco_act =  clearGarbadge($_POST['block_act'],false,false);
		
		$bloco_act = ($bloco_act == "true")?"1":"0";
		
		$bloco_code = str_replace("'", "\'", utf8_decode ($_POST['block_code']) );
		
		$bloco_des = str_replace("'", "\'", utf8_decode  ($_POST['block_des']) );
		
		$bd = ligarBD();
		
		//echo "Update `bloco` Set `bloco_codigo` = '$bloco_code', `bloco_date_up` =  '".date('YmdHis')."',`bloco_nome` = '$bloco_des',  `bloco_active` = $bloco_act Where `id_bloco` = $id_bloco";
		
		$bd->submitQuery("Update `bloco` Set `bloco_codigo` = '$bloco_code', `bloco_date_up` =  '".date('YmdHis')."',`bloco_nome` = '$bloco_des',  `bloco_active` = '$bloco_act' Where `id_bloco` = $id_bloco"); 
		
		if( mysql_affected_rows() == 1 ){
		
			die(rawurlencode("O update ao seu bloco de código foi feito com sucesso."));
		
		} else {
		
			die(rawurlencode("De momento não é possível atender ao pedido."));
		
		}
		
	} else if( isset( $_POST['block_code'] ) && isset( $_POST['block_des'] ) 
	&& isset( $_POST['block_act'] ) && is_bool((bool)$_POST['block_act'] ) ){
		
		
		
		
		
		//Falta validação
		$block_code = str_replace("'", "\'", utf8_decode ($_POST['block_code']));
		//Falta validação
		$block_des = str_replace("'", "\'", utf8_decode ($_POST['block_des']));
		
		$block_act = clearGarbadge($_POST['block_act'], false, false);
		
		$bd = ligarBD();
		
		$ordem = mysql_result($bd->submitQuery("Select Max(`bloco_ordem`) From `bloco`"),0,0);
		
		if ( $ordem == null ) 
			$ordem = 1;
		else 
			$ordem++;
		
		if( $bd->submitQuery("
			
			INSERT INTO `bloco`(
			`id_bloco` ,
			`bloco_ordem` ,
			`bloco_codigo` ,
			`bloco_date_up` ,
			`bloco_nome` ,
			`bloco_active` 
			)
			VALUES (
				NULL , $ordem, '$block_code', '".date('YmdHis')."', '$block_des', $block_act
			) 
		") ){
		
			die( rawurlencode("Código php \"$block_des\" submetido com sucesso !") );
		
		}
		
	} else {
		
		echo "
		<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">  
		<html xmlns=\"http://www.w3.org/1999/xhtml\">
		<head>
		
			<title>Inserir um bloco de código</title>
			
			<script src=\"javascript/codepress.js\" type=\"text/javascript\"></script>
			
			<script type=\"text/javascript\" src=\"javascript/jquery-1.2.6.js\"></script>
			
			<script type=\"text/javascript\">
				$(document).ready(function(){
				
				$('#submit_code').click(function(){
					
						var data_tosend = 'block_code='+encodeURIComponent( myCpWindow2.getCode() )+'&block_des='+encodeURIComponent ( myCpWindow1.getCode() )+'&block_act='+$('#block_act').attr( 'checked' );
						
						if( $('#id_bloco').val() > 0 ){
						
							data_tosend += '&id_bloco='+$('#id_bloco').val();
						
						}
						
						if(window.confirm('Tem a certeza que deseja aguardar alterações a este bloco?')){
						$.ajax({
						
							type: 'POST',
							url: 'blocks.php',
							data: data_tosend,
							dataType: 'html',
							success: function(txt){
							
								$('#new_module').html( unescape(txt) );
							
							},error: function(){ 
							
								$('#new_module').html( 'Não foi possível atender ao seu pedido' );
							
							}, beforeSend:
							function (){
							
								$('#new_module').html( '<img src=\"imagens/indicator.gif\" />' );
							
							}
					
						});
						}
						
						
					});
					
					
					
					$('#del_block').click(function(){
						
						if(window.confirm('Tem a certeza que deseja apagar este bloco?')){
						$.ajax({
						
							type: 'POST',
							url: 'blocks.php',
							data: 'del_block='+$('#id_bloco').val(),
							dataType: 'html',
							success: function(txt){
							
								$('#new_module').html( unescape(txt) );
							
							},error: function(){ 
							
								$('#new_module').html( 'Não foi possível atender ao seu pedido' );
							
							}, beforeSend:
							function (){
							
								$('#new_module').html( '<img src=\"imagens/indicator.gif\" />' );
							
							}
						
						});
						}
					});
					
					
					$('#copy_code').click(function(){
						
						window.clipboardData.setData('Text', myCpWindow2.getCode());
						
					});

				});
			</script>
			
			<style>
			body{
				
				font-family: verdana;
				font-size: 11px;
				margin: auto;
				margin-top: 10px;
				width: 1007px;
				height: auto;
			}
			</style>
		</head>
		<body>
		
		";
		
		
		$bloco_id = 0;
		
		$bloco_nome = "";
		
		$bloco_codigo = "";
		
		$bloco_update = "";
		
		$bloco_activo = "checked=\"checked\"";
		
		
		
		if( isset($_GET['index_id']) && is_numeric($_GET['index_id']) ){
		
		
			$bloco_id = clearGarbadge($_GET['index_id'],false,false);
			
			if( existeNaBd("bloco", "id_bloco", $bloco_id)  ){
			
			$bd = ligarBD();
			
			$bloco = $bd->submitQuery("Select * From `bloco` Where `id_bloco` = $bloco_id ");
			
				if( mysql_numrows($bloco) == 1){
				
					$bloco_id = mysql_result($bloco,0,0);
					
					$bloco_nome = mysql_result($bloco,0,4);
					
					$bloco_codigo = mysql_result($bloco,0,2);
					
					$bloco_update = mysql_result($bloco,0,3);
					
					$bloco_activo = mysql_result($bloco,0,5);
					
					if($bloco_activo == 0){
					
						$bloco_activo = "";
					
					} else {
					
						$bloco_activo = "checked=\"checked\"";
					
					}
				}
			
			} else {
			
				echo "<h1>Esse bloco de código não existe.</h1>";
			
			}
			
		}
		
		
		
		echo "
		<p><label for=\"block_des\">Descrição bloco de código<br />
			<textarea name=\"block_des\" style=\"height:50px;width:600px;\" id=\"myCpWindow1\" style=\"height: 50px\" class=\"codepress text linenumbers-on\">$bloco_nome</textarea>
		</label></p>
		
		<p><label for=\"block_cod\">Código do bloco<br />
			<textarea id=\"myCpWindow2\" name=\"block_cod\" style=\"height:400px;width:600px;\" class=\"codepress php linenumbers-on\">$bloco_codigo</textarea>
		</label></p>
		
		<p>
		<label for=\"block_act\">Bloco activo
			<input type=\"checkbox\" name=\"block_act\" id=\"block_act\" value=\"1\"
			title=\"Define se está ou não este bloco visível\" $bloco_activo />
		</label>
		</p>
		
		<input type=\"hidden\" value=\"$bloco_id\" id=\"id_bloco\" />
		
		<input type=\"button\"  name=\"submit_code\"  id=\"submit_code\" value=\"Guardar bloco\" /> ";
		
		
		if( $bloco_id > 0 ){
		
			echo "<input type=\"button\" name=\"del_block\" class=\"forms\" id=\"del_block\" value=\"Apagar bloco\" />";
		
		}
		
		
		echo " <input type=\"button\"  name=\"copy_code\"  id=\"copy_code\" value=\"Copiar código\" />
		
		<div id=\"new_module\"></div>
		
		</body>
		</html>
		";
		
	}
 }
 
 }
?>