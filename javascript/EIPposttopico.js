$(document).ready(function(){
	$(classesEdit).each(function(i){setClickablePostTop (this, this.id, i);});
});
				function setClickablePostTop (obj, id, i){
					
					$(obj).mouseover(function() {
						$(obj).addClass("editable");
					});
					
					$(obj).mouseout(function() {
						$(obj).removeClass("editable");
					});
		
					$(obj).click(function() {
					
					if(i % 2 != 0){
					
					var textarea = '<div style="width:300px;">'+drawToolBar( "caixa23", tt, ts )+'<textarea rows="4" cols="60" name="caixa23" id="caixa23">'+br2nl($(obj).html())+'</textarea>';
					
					} else {
					
					var textarea = '<div style="width:300px;"><textarea rows="4" cols="60" name="caixa23" id="caixa23">'+br2nl($(obj).html())+'</textarea>';
						
					}
					
					var button = '<div><input type="button" value="Salvar"'
					+'class="saveButton" /> Ou <input type="button" value="Cancelar"'
					+' class="cancelButton" /></div>';
					
					if(i % 2 != 0)
					button += '<p>Nota: Se o post for editado o video que eventualmente contém será apagado!</p>';
					
					button += '</div>';
					
					var revert = $(obj).html();
					
					$(obj).after(textarea+button).remove();
	
					$('.saveButton').click(function(){
						
						saveChangesPostTop(this, false, revert, i, id);
					});
					
					$('.cancelButton').click(function(){
					
						saveChangesPostTop(this, revert, revert, i, id);
						
					});
	
					});	
					
				}
				
				function saveChangesPostTop(obj, cancel, revert, n, id) {
					
					
					
					if(n % 2 != 0){
						
						var t = $(obj).parent().siblings().eq(4).val();
					
					}else{
						
						
						var t = $(obj).parent().siblings().eq(0).val();
						
					}
					
					if(!cancel){
						
						
						
						var reg = new RegExp("\ |\n", "ig");
						
						if( !strWordCount(t,reg,24) ){

						alert("O texto a editar não pode conter palavras com mais de"
						+" 24 caractéres!");
				
						//cancel = revert;	
	
						}
						
						if(Trim(t) == ''){
	
							alert("O texto a editar não pode conter só espaços em branco"+
							" ou estar vazio!");
						
							//cancel = revert;
	
						}

					}
					
					
					if(strWordCount(t,reg,24) || cancel){
					
					if(!cancel) {
						
						$.post("editoppost.php",{
  						content: escape(t),
  						tp: n,
  						id: id
						},function(txt){
							if(txt.length > 0)	
								alert(unescape(txt));
		
						});
					} else { var t = cancel; }
					
					if(t=='') 
						t='(Clica para adicionar texto)';
					
					if(n<3) classs = 'editaraquitopico';else classs = 'editaraquipost';
					
					
					$(obj).parent().parent().after(
						'<div class="'+classs+'" id="'+id+'">'+nl2br(t)+'</div>'
						).remove();
					
					setClickablePostTop($("#"+id), id ,n);
					
					}
					
				}