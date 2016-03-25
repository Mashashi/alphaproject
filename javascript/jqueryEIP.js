$(document).ready(function(){
$(".editaraqui").each(function(i){
setClickable(this, this.id);
})
});
function setClickable(obj, i) {
$(obj).click(function() {
var textarea = '<div><textarea rows="4" cols="60">'+br2nl($(this).html())+'</textarea>';
var button	 = '<div><input type="button" value="Salvar" class="saveButton" /> Ou <input type="button" value="Cancelar" class="cancelButton" /></div></div>';
var revert = $(obj).html();
$(obj).after(textarea+button).remove();
$('.saveButton').click(function(){saveChanges(this, false, revert, i);});
$('.cancelButton').click(function(){saveChanges(this, revert, revert, i);});
})

.mouseover(function() {
$(obj).addClass("editable");
})
.mouseout(function() {
$(obj).removeClass("editable");
});
}//end of function setClickable
/*
obj - Elemento DOM
*/
function saveChanges(obj, cancel, revert, n) {

var t = $(obj).parent().siblings(0).val();

if(!strWordCount(t," ",24)){

	alert("O texto a editar não pode conter palavras com mais de 24 caractres!");
	cancel = revert;	
	
}
if(Trim(t) == ""){
	
	alert("O texto a editar não pode conter só espaços em branco ou estar vazio!");
	cancel = revert;
	
}

if(!cancel && Trim(t) != "") {

$.post("faq.php",{
  content: escape(t),
  n: n
},function(txt){
	if(txt.length > 0)	
		alert(unescape(txt))
		
});
}else {
var t = cancel;
}
if(t=='') t='(Clica para adicionar texto)';
$(obj).parent().parent().after('<div class=\"editaraqui\" id="'+n+'">'+nl2br(t)+'</div>').remove();
setClickable($("#"+n), n);
}	