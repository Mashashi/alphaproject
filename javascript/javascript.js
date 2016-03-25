/***************************************** 
 *  @name      Javascript Alpha Project  
 *  @version   1.0                		 
 *  @author    Rafael Campos I1 11º, 12º ANO          
 *  @year      2008, 2009                     
 *****************************************/

/*var plugins = {
	hasAcrobat:function() {
		if (!window.ActiveXObject) return false;
		try { if (new ActiveXObject('AcroPDF.PDF')) return true;}
		catch (e) {}
		try { if (new ActiveXObject('PDF.PdfCtrl')) return true;}
		catch (e) {}
		return false;
	},
	hasFlash: function() {
		if (!window.ActiveXObject) return false;
		try {if (new ActiveXObject('ShockwaveFlash.ShockwaveFlash')) return true;}
	    	catch (e) { return false;}
	},
	hasJava: function() {
		return (!navigator.javaEnabled());
	},
	hasQuickTime: function() {
		if (!window.ActiveXObject) return false;
		try { if (new ActiveXObject('QuickTime.QuickTime')) return true;}
		catch (e) {}
		try {if(new ActiveXObject('QuickTimeCheckObject.QuickTimeCheck')) return true;}
		catch (e) {};
		return false;
	},
	hasRealPlayer: function() {
		if (!window.ActiveXObject) return false;
	    	var definedControls = [
			'rmocx.RealPlayer G2 Control',
			'rmocx.RealPlayer G2 Control.1',
			'RealPlayer.RealPlayer(tm) ActiveX Control (32-bit)',
			'RealVideo.RealVideo(tm) ActiveX Control (32-bit)',
			'RealPlayer'
		];
		for (var i = 0; i < definedControls.length; i++) {
			try {if(new ActiveXObject(definedControls[i])) return true;}
			catch (e) {continue;}
		}
		return false;
	},
	hasShockwave: function() {
		if (!window.ActiveXObject) return false;
	    	try {if(new ActiveXObject(’SWCtl.SWCtl’)) return true;}
		catch (e) {return false;}
	},
	hasWMP: function() {
		if (!window.ActiveXObject) return false;
	  	try {if(new ActiveXObject(’WMPlayer.OCX’)) return true;}
		catch (e) { return false;}
	}
}
*/

/*function hasQuickTime(){
	
		if (!window.ActiveXObject) return false;
		
		try { 
			if (new ActiveXObject('QuickTime.QuickTime')) return true;
		}catch (e) {
			
			try { 
				if(new ActiveXObject('QuickTimeCheckObject.QuickTimeCheck')) return true;
			}catch (e) {
				return false;
			};
			
		}
		
}
function evalSound(soundobj) {
	if(hasQuickTime()==true){
  		var thissound = eval("document."+soundobj);
  		thissound.Play();
	}	
}*/

/*String.prototype.br2nl =
  function() {
    return this.replace(/\<br(\s*\/|)\>/gi ,"\n");
  };

String.prototype.nl2br =
  function() {
    return this.replace(/\r\n|\r|\n/g ,"<br />");
 };*/  


function nl2br(str){ return str.replace(/\r\n|\r|\n/g ,"<br />"); }

function br2nl(str){ return str.replace(/\<br(\s*\/|)\>/gi ,"\n"); }



//Função que faz o preload da página
function waitPreloadPage() { //DOM
if (document.getElementById){
document.getElementById('prepage').style.visibility='hidden';
}else{
if (document.layers){ //NS4
document.prepage.visibility = 'hidden';
}
else { //IE4
document.all.prepage.style.visibility = 'hidden';
}
}
}

/*Confirmar uma opção*/
function confirmOp(params,mens,posmens){
if (window.confirm(mens))
{
	location.href=params;
	if(posmens.length>0)
		window.alert(posmens);
}	
}

//Texto seleccionado com cor no Firefox ou netscape
if( navigator.appName.indexOf("Netscape") > -1 || navigator.appName.indexOf("Firefox") > -1  )
	document.write( '<style>::-moz-selection{background:#F89D0D;color:#FFFFFF;}</style>');

//A função serialize do jQuery nas linhas foi modificada de modo a receber escape_chars 
//(Valor boolean) que quando a true faz o encode e a false faz encodeURIComponent.

/*Adicionar o texto no elemento responseid fazendo a cópia do conteúdo de id*/
function addTextToolBar(id, responseid){

	/*
	var text = getText(responseid);
	
	var cursorPosition = document.getElementById(responseid).createTextRange().boundingWidth;
	
	var cursorPosition = document.body.createTextRange().move("character");
	
	alert(cursorPosition);
	
	setText( text.substr( 0, cursorPosition )+document.getElementById(id).options[document.getElementById(id).selectedIndex].value+text.substr( cursorPosition , text.length ), responseid);
	*/
	setText(getText(responseid)
	+document.getElementById(id).options
	[document.getElementById(id).selectedIndex].value,responseid);
	
	document.getElementById(id).selectedIndex = 0;
			
}

/*Obter multiplos values de uma listbox*/
function getMultipleAll(ob) { 
			
	var arSelected = new Array();
			
	for(var i = ob.length-1; i > -1; i--) 

			arSelected.push(escape( ob.options[i].value) );
			
	return arSelected;
			
}

/*Fazer o explode de uma string $item com o delimitador $delimiter*/
function explode($delimiter, $item) {
	
	var tempArray = new Array();
	var Count=0;
	var tempString = new String($item);

	while ( tempString.indexOf($delimiter) > 0 ) {
	
		tempArray[Count] = tempString.substr( 0, tempString.indexOf($delimiter) );
		
		tempString = tempString.substr( tempString.indexOf($delimiter)+1,tempString.length-tempString.indexOf($delimiter)+1 );
		
		Count++;
		
	}

	tempArray[Count]=tempString;
	
return tempArray;

}  




/*Verifica se há uma palavara com menos de $num caracteres num $text em 
que cada palavra é delimitada por $delimitador ignora tags html*/
function strWordCount($text,$delimitador,$num){
	
	$text = stripHTML($text);
	
	$flag = true;
		
	$ver = $text.split($delimitador);
	
	for(var $i = 0; $i < $ver.length ; $i++){
		
		if($ver[$i].length > $num ){
			
			$flag = false;
			
		}
		
	}
	
	return $flag;
}




/*Retorna uma string sem tags HTML e sem a tag de video do youtube [youtube][/youtube]*/
function stripHTML(oldString) {

    if(oldString != null){
   
		oldString = oldString.replace(/(<([^>]+)>)/ig,"");
		
		oldString = oldString.replace(/(\[youtube\].*\[\/youtube\])/ig,"vid");
		
	}
	
   return oldString;
   
}


/*Obter multiplos values de uma listbox*/
function getMultiple(ob) { 
			
	var arSelected = new Array();
			
	while (ob.selectedIndex != -1) { 
				
		if (ob.selectedIndex != -1) 
			
			arSelected.push( ob.options[ob.selectedIndex].value );
					
			ob.options[ob.selectedIndex].selected = false;
				
		} 
			
	return arSelected;
			
}
		


/*Faz a contagem decresente em que o utilizador será dado como offline*/
function moveRelogio(time){
			
			
			if(time < 1){
				
				msg = "Dado como offline";
				
				document.getElementById('timeleftt').innerHTML = msg;
			
			}else {
				
				msg = time + " minutos";
				
				time--;
				
				try{
				
					document.getElementById('timelefttt').innerHTML = msg;
					setTimeout("moveRelogio("+time+")", 60000);
					
				}catch(e){ }
				
				

    		}
			
		}

/******************************
*******************************
*******************************
*Funções jQuery****************
*******************************
*******************************
*******************************/
$(document).ready(function(){
    
	try{
	//Função responsável pela maneira como se podem organizar as músicas de um álbum
    $("#tracks_alb").tableDnD({
		
		onDragClass: "myTrackDragClass",
		
		onDrop: function(table, row) {
            
		    /*$("#tracks_alb tr:even").css("background-color", "#FFCC00");
            $("#tracks_alb tr:odd").css("background-color", "#FFFFFF");*/
            
			var rows = table.tBodies[0].rows;
            
			var posicoes = "";
			
			var ids = "";
			
			for (var i = 0; i < rows.length; i++) {
                
				ids += rows[i].id.substring(0,rows[i].id.indexOf("#"))+" ";
						
				posicoes += 
				rows[i].id.substring( (rows[i].id.indexOf("#")+1), rows[i].id.length )+" ";
                 
            }
	        
			posicoes = posicoes.substring(0, posicoes.length-1);
	        ids = ids.substring(0, ids.length-1);
	        
	        
	        
			/*alert(posicoes);
	        alert(ids);
			helper = explode(" ", posicoes).sort(function(a,b){return a - b});
	        for (var i = 0; i < rows.length; i++) { 
			$("#tracks_alb tr").get(i).id = helper[i];
            }*/
	        
			$.ajax({
				type: "POST",
				url: "albuns/album_upd_sup_gen_trilh.php",
				data: "id=3&id_album="+getText("album_id_ord")+"&contend="+escape(posicoes)
				+"&ids="+escape(ids),
				dataType: "html",
				success: function(txt){
  		 			
					if(txt.length > 0) alert(unescape(txt));
				
				},
				error: 
				function(){ alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.'); }
			
			});
					
	    }
	    
	});
	} catch(e){}
	
	/*$("#tracks_alb tr").mousedown(function(){
		$(this).css( "background-color", "red" );
	});
	$("#tracks_alb tr").mouseup(function(){
		$(this).css( "background-color", "white" );
	});*/
	
	//$("#tracks_alb tr:even').css( 'background-color', 'orange' )");

	//Calculadora
	try{
		$('.scientificCalc').calculator({
		//layout: $.calculator.scientificLayout, 
		closeText: 'Fechar',
		useText: 'Usar',
		eraseText: 'Apagar',
		buttonStatus: 'Calculadora'
		});
	}catch(e){}
	
    /*Menu drop down*/
    try{
		
		jQuery('.basic').accordion({
		
			//active: false, 
			header: '.trogle', 
			//navigation: true, 
			event: 'mouseover', 
			//fillSpace: true, 
			animated: 'bounceslide' 
			
			});
			
			var accordions = jQuery('.basic');
			
			jQuery('#switch select').change(function() {
				accordions.accordion("activate", this.selectedIndex-1 );
			});
			jQuery('#close').click(function() {
				accordions.accordion("activate", -1);
			});
			jQuery('#switch2').change(function() {
				accordions.accordion("activate", this.value);
			});
			jQuery('#enable').click(function() {
				accordions.accordion("enable");
			});
			jQuery('#disable').click(function() {
				accordions.accordion("disable");
			});
			jQuery('#remove').click(function() {
				accordions.accordion("destroy");
				wizardButtons.unbind("click");
			});
	}catch(e){}
	/*Menu drop down*/	
    
    
    
    
    
    
	/*Abre o logo da biblioteca quando o rato esta sobre este*/
    $("#toglelibri").mouseover(function() {
		
		if($("#libri").is(":hidden")){
			
			$("#libri").css("visibility","visible");
			$("#libri").slideDown("slow");
			
		}

	});
	
    /*Recolhe o logo da biblioteca quando o rato deixa de estar sobre este*/
	$("#libri").mouseout(function() {
		
		if( !$("#libri").is(":hidden") )
			$("#libri").slideUp();

	});
    
	/*Nota o método serializa do jquery foi alterado para opcionalmente serilize(true) no
	serialize(false) faz encodeURIComponent */
    function showValues(id){
		  //window.alert($(id).serialize(true))		
	      return $(id).serialize(true);
		
	}
	
    /*Submeter uma nova FAQ*/
    $("#submitnewfaq").click(
		function(){
			
			log('faq.php','POST',showValues('#adifaq'),true);
		
		});
	
	/*Inserir um novo utilizador*/
	$("#inserirutilsub").click(
		function(){
			//alert(showValues('#inserirutilreg'));
			log('inserirutil.php','POST',showValues('#inserirutilreg'),true);
		
		});
	
	/*Confirmar a acção apagar área.*/
	/*$("#delareafor").click(
		
		function(){
			
			
			if(!confirm("Tem a certeza que deseja apagar está área?")){
				
				location.href = "?elem=";
				
			}
		
		});*/
	
	/*Função responsável pela pesquisa de utilizadores chama a função javascript getXml()*/
	$("#listarutils").click(
		
		function(){
			
			//is_numeric('idai')//Verifica se a idade inicial é numerica
			//is_numeric('idaf')//Verifica se a final inicial é numerica
			
			//Verifica se a idade inicial é menor ou igual a idade final
			if(is_menor('idai','idaf')){
				getXml('pesquisas/pesquisar.php?modpsq=1','POST',showValues('#listarutil'),
				document.getElementById('idsaidautil'));
			
			}
		});
		
	/*Ver se um campo é numérico*/
	function is_numeric(id){
	
		if(!isFinite(getText(id))){
			
			setText('',id);
			
			window.alert('Só valores númericos de 1 a 120 são permitidos!');
			
		}
	
	}
	
	/*Verificar se o val1 é maior do que val2*/
	function is_menor(val1,val2){
		
		var flag = true;
		
		if( isFinite(getText(val2)) && isFinite(getText(val1))){
			if( Val(getText(val1)) > Val(getText(val2)) ){
				
				setText('',val1);
			
				setText('',val2);
			
				window.alert("A idade inicial tem de ser menor ou igual a idade final :S");
				
				flag = false;
			}
		}
		
		return flag;
		
	}
	
	/*Função responsável pela validação da password e nome de utilizador.
	Nota que quando o login acontece com sucesso é retornado 1 e a form é submetida
	e as variáveis com o nome de utilizador e password são submtidas atráves da função
	php validarUser($user,$pass) em funcoes.php*/
	/*$("#fazerlogin").click(
		
		function(){

			log('login.php','POST',showValues('#loginform'),true);
		
		});*/
	
	/*Limpar o campos de login quando o utilizador clica neles*/
	/*$("#userlogin, #passlogin").click(
		
		function(){
		
			setText('',this.id);
			
		});*/
		
	/*Meter os campos de login sempre que não estam preechidos
	como texto Utilizador e Password*/
	/*$("#userlogin, #passlogin").blur(
		
		function(){
			
			if(getText(this.id) == ""){
				
				if(this.id == "userlogin")
				
					setText('Utilizador',this.id);
				
				else
					
					setText('Password',this.id);
				
			}
		});*/
	
	/*Introduzir um novo post*/
	$("#subpost").click(function(){
			//alert(showValues('#submitpost'));
			
			log ('postar.php','POST', "assunto="+encodeURIComponent($("#assunto").val())+"&coment=1&topico="+$("#topico").val()+"&area="+$("#area").val()+"&texto="+encodeURIComponent($("#texto").val()),true);
			
		});
	
	/*Adiciona a cor vermelha a uma linha da coluna do tópico degestão FAQ em editar e remover
	ver também a função php printGerirFaq() em funcoes_avan.php*/
	$(".overcheck").click(
		function(){
			if(this.checked)
				$("#trover"+this.id).addClass("overviewmarked");
			else
				$("#trover"+this.id).removeClass("overviewmarked");
		});
	
	/*Adiciona a cor vermelha a uma linha da coluna na caixe de entrada  mensagensem 
	editar e remover ver também a função mp.php*/
	$(".meecheckk").click(
		
		function(){
			
			if(this.checked)
				$("#trmen"+this.id).addClass("overviewmarked");
			else
				$("#trmen"+this.id).removeClass("overviewmarked");
				
		});
	
	/*Adiciona uma coloração vermelha na linha em apagar e editar estatuto
	ao carregar na checkbox com o nome de class apagestatuto ver também o ficheiro estat.php
	em funcoes_avan.php*/
	$(".apagestatuto").click(function(){
			$(".apagestatuto").each(
			function(i){
				if(this.checked)
					$("#tr"+i).addClass("overviewmarked");
				else
					$("#tr"+i).removeClass("overviewmarked");
			})	
		});
	
	
	/*Enviar para o ficheiro faq.php o valor das FAQ cuja checkbos foi sleccionada
	ver também a função php printGerirFaq() em funcoes_avan.php*/
	$("#apagarfaqs").click(
		
		function(){
			
			/*Nota o fieldSerialize não foi modificado*/
			if($('#editfaqres :checkbox').fieldSerialize().length == 0){
				
				window.alert("Selecciona as FAQs a apagar!");
				
			}
			
			log('faq.php','POST',$('#editfaqres :checkbox').fieldSerialize()+"&apagar=1",true);
			
			
		});
	
	/*Funções para editar o utilizador esta acção acontece quando o utilizador 
	clica na checkbox com a label reverso.*/
	$("#reverr").click(function(){
			
			if(this.checked){
				$("#tagrever").html("Incluir apenas:");
				$("#tagreverr").html("Utilizadores fora da faixa etária:");
			}else{
			
				$("#tagrever").html("Excluir:");
				$("#tagreverr").html("Utilizadores dentro da faixa etária:");
			}	
		});
	
	/*Qando houver clique no butão editar abrir o pop up*/
	$("#editutiloncli").click( function(){ editarUtil(601,423) });
	
	/*Abrir o popup para a edição do utilizador*/
	function editarUtil(width,heigth){
		
		if (navigator.appName=="Microsoft Internet Explorer")
			heigth += 28;
			
		params = document.getElementById('idsaidautil').value;
		
		if( params > 0 ){
		
		atribs = 'toolbar=no,location=no,top='+((screen.height/2)-(heigth/2))+',left='+
		((screen.width/2)-(width/2))+',status=no,menubar=no,scrollbars=no,resizable=no,width='+width+',height='+heigth;
			
		window.open('gerir.php?accao=1&upidedit='+params,'page1',atribs);
		
		}else
		
			window.alert("Por favor seleccione um utilizador!");
			
	}
	
	/*Fazer a decoração underline hover para os elementos que tenham atribuida a class underline*/
	$(".underline").hover(
		function(){
			this.style.textDecoration = 'underline';
		},function(){
			this.style.textDecoration = 'none';
		});
	
	/*Editar as permições do estatuto com o clique na checkbox ver também o ficheiro 
	estat.php e a função printGerirEstatuto() em funcoes_avan.php*/
	$(".checkestatper").change(function(){
			
			var valor = 0;
			
			if(this.checked)
				valor = 1;
				
				log ('estat.php','POST','switch='+valor+'&elemeestatcheck='+this.id,true);
				
		});
	
	/*Editar o nome do utilizador quando houver uma 
	modificação no valor da inputbox, ver também o ficheiro 
	estat.php e a função printGerirEstatuto() em funcoes_avan.php*/
	$(".formestatuto").change(
		function (){
			
			log ('estat.php','POST','nome='+escape(this.value)+'&id='+this.id,true);
      	
		  });
		  
	/*Apagar um estatuto, ver também o ficheiro 
	estat.php e a função printGerirEstatuto() em funcoes_avan.php*/	  
	$("#apgestatat").click(function (e){
		
		
			
			var params = "";
			
			$(".apagestatuto").each(function(i){
				
				if(this.checked){
					
					$(this).addClass("overview");
					
					params += this.id+"="+this.value+"&";
					
				}
			});
			
			  params = params.substring(0,params.length-1);
			  
			  
			  
			  if(params.length > 0){
			  	
				if( window.confirm("Queres mesmo apagar este\s estatuto?") ){
			  		
					log ('estat.php','POST',params,true);
			  	}
			  	
			  }else
			  	
				  window.alert("Seleccione o/os estatuto/os a apagar!");
      		
			  	
		  });
		  	
	/*Adicionar um coloração para quando o rato esta em cima do elemento que tem como class
	overview over e retirar esse coloração quando o rato deixa de estrar sobre esse elemento
	A coloração de momento é (laranja) usa-se em principalmente em tabelas*/
	$(".overviewhover").hover(
		function (){
        	$(this).addClass("overview");
      	}, function () {
        	$(this).removeClass("overview");
      	});	
		
	
		
	$(".movtopto_area_submit").click(
		function (){
			
			$(".topico_mov_area").eq( $(".movtopto_area_submit").index(this) ).fadeOut("slow");
			
			movTo = $(".movtopto_area").eq( $(".movtopto_area_submit").index(this) ).val();
			
			iDthisTop = $(".mov_top_id").eq( $(".movtopto_area_submit").index(this) ).val();
			
			data = "id_area="+movTo+"&id_top="+iDthisTop;
			//alert(movTo+" "+iDthisTop);
			
			$.ajax({
   			type: "POST",
  			url: "mov_top.php",
   			data: data,
   			dataType: "html",
			success: function(txt){
  		 		
				if(txt.length > 0)
					window.alert(unescape(txt));
					
   			},
   			error: function(){ 
			   	window.alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.');
				   }
				   
			}/*, beforeSend:
				function (){
					
					$(".statusreq").html(
					"<img src=\"imagens/indicator.gif\" alt=\"A carregar...\" title=\"A carregar\" />");
					
			}*/);
			
      	});	
	
	$(".movtopto_area").change(
		function (){
        	
			//alert( $(".movtopto_area").eq( $(".movtopto_area").index(this) ).label() );
			
      	});	
		
		
    /*Adicionar uma coloração e inverter a cor da letra
	Isto é de momento a classe esta definida para passar a cor da letra para branco
	e o fundo para dourado, esta class é usada maioritariamente em butões do tipo apagar
	nas administração geral do síte.*/
	$(".homedes").hover(
		
		function (){
        	$(this).removeClass("homedes");
			$(this).addClass("homeitem");
      	}, function () {
        
			$(this).removeClass("homeitem");
			$(this).addClass("homedes");
	});
	
	/*Função responsável por mostrar ou esconder o menu de pesquisa rápida
	ver também pesquisa.php e menu.php*/
	$("#startpesquisar").click(
		
		function(e){
			
			if ($(".pesquisar:first").is(":visible")) {
				
				$(".pesquisar").hide("fast");
				
			  } else {
			  	
				var w = document.documentElement;
				
				$(".pesquisar").css("left",(e.clientX-w.scrollLeft));
				
				$(".pesquisar").css("top",(e.clientY+w.scrollTop));
				
				$(".pesquisar").show("fast");
				
				$(".pesquisar").css("visibility","visible");
				
      		}
				
			});
	
	
	$("#ferrareq").click(
		
		function(e){
			
			if ($(".ferraopen:first").is(":visible")) {
				
				$(".ferraopen").hide("fast");
				
			  } else {
			  	
				var w = document.documentElement;
				
				$(".ferraopen").css("left",(e.clientX-w.scrollLeft));
				
				$(".ferraopen").css("top",(e.clientY+w.scrollTop));
				
				$(".ferraopen").show("fast");
				
				$(".ferraopen").css("visibility","visible");
				
      		}
				
			});
	
	
	
	
	//Função responsável por enviar os dados para estat.php pedindo a criação de um novo estatuto
	//ver também estat.php e .
	$("#estatcriestat").click(function(){
				
				var params = showValues('#newestatutoutil');
				
				if(Trim(getText('esno')) != "")
					log('estat.php','POST',params,true);
				else
					window.alert("O nome do estatuto esta por preencher!");
				
				
      		}
		);  
	
	$("#assitoma").click(function (){
		
		$(".meecheckk").each(function (){
			
			if(!this.checked)
				$("#trmen"+this.id).addClass("overviewmarked");
			
			this.checked = true;
			
		});
		
	});
	
	$("#destoma").click(function (){
		
		$(".meecheckk").each(function (){ 
			
			if(this.checked)
				$("#trmen"+this.id).removeClass("overviewmarked");
			
			this.checked = false;
			
			});
		
	});
	
	/*Apagar uma mensagem, ver também mp.php*/
	$("#apgmema").click(function (){
		
		var flag = 0;
		
		$(".meecheckk").each(function(){
			
			if(flag != 2) flag = 1;
			
			if(this.checked == true) flag = 2;
				
		});
		
		if(flag == 2){
			
			log('mp.php','POST',showValues('#menpriform'),true);
		
		}else{
			
			if(flag == 1)
				window.alert("Por favor selecione a/as mensagens a apagar.");
			else
				window.alert("Não tens mensagens na tua caixa de entrada.");
		}
	});
	
	$(".marcafrase").click(function(){
			
			if(this.checked)
				$("#row"+this.id).addClass("overviewmarked");
			else
				$("#row"+this.id).removeClass("overviewmarked");
		
	});
	
	/*Introduzir as tags no campo em de texto conforme o index da tag seleccionda, isto a quando 
	o envio de uma PM ver também mp.php*/
	$("#mentags").change(function (){
		 
		setText(getText("mentext")
		+document.getElementById("mentags").options[document.getElementById("mentags").selectedIndex].value,"mentext");
		document.getElementById("mentags").selectedIndex = 0;
		
	});
	
	
	
	/*Abrir uma janela de pequisa para encontrar um nome de utilizador a quando 
	o envio de uma mensagem*/
	$("#menencon").click(function (){
		width = 300;
		height = 172;
		
		if (navigator.appName=="Microsoft Internet Explorer")
			height += 23;
		
		atribs = 'toolbar=no,location=no,top='+((screen.height/2)-(height/2))+',left='+
		((screen.width/2)-(width/2))+',status=no,menubar=no,scrollbars=no,resizable=no,width='+width+',height='+height;
		
		
		window.open('pesquisas/pesquisar.php?modpsq=2&user='+escape(getText('menenconinp')),
		'page2',atribs);
		
	});
	
	/*Inserir uma nova mensgem privada*/
	$("#mensubt").click(function (){
		
		log('mp.php','POST',showValues("#mennew"),true);
		
	});
	
	
	/*Introduzir uma nova frase, ver também frase.php e a função printGerirFrases() 
	em funcoes_avan.php*/
	$("#introfrase").click(function (){
		
		log('frases.php','POST',showValues("#insrinewfra"),true);

	});
	
	
	/*Apagar uma frase, ver também frase.php e a função printGerirFrases() em funcoes_avan.php*/
	$("#apgfrase").click(function (){
		
		flag = 0;
		
		$(".marcafrase").each(function(){
			
			if(flag != 2 && this.visible) flag = 1;
			if(this.checked == true) flag = 2;
			
		});
		
		if(flag == 2)
			log('frases.php','POST',showValues('#editdelfrase') ,true);
		else if(flag == 1)
			window.alert("Selecciona a/as frase/es a apagar!");
		else if(flag == 0)
			window.alert("Não existem frases para apagar.");
			
		});
	
	//Mudar a visualização de spam posts para spam tópicos
	$("#changetospam").click(function(){
		
		if(this.checked)
			location.href = "?elem=2&accao=14&mod="+this.value;
		else
			location.href = "?elem=2&accao=14";
			
	});
	
	
	//Apagar as mensagens de spam marcadas
	$("#apgspammarc").click(function(){
		
		if(showValues('#spamadmin').length > 0)
		
			log('gerir_spam.php','POST',showValues('#spamadmin')+"&mod=0",true);
	
		else
			
			window.alert("Selecciona os posts spam a apagar!")	
		
	});
	
	//Restaurar as mensagens de spam marcadas
	$("#repspammarc").click(function(){
		
		if(showValues('#spamadmin').length > 0)
		
			log('gerir_spam.php','POST',showValues('#spamadmin')+"&mod=1",true);
	
		else
			
			window.alert("Selecciona os posts spam a repor.");
		
	});
	
	//Apagar um tópico dado como spam
	$("#apgspammarctop").click(function(){
		
		if(showValues('#spamadmin').length > 0)
		
			log('gerir_spam.php','POST',showValues('#spamadmin')+"&mod=2",true);
	
		else
			
			window.alert("Selecciona os tópicos spam a apagar.");
		
	});
	
	//Repor um tópico dado como spam
	$("#repspammarctop").click(function(){
		
		if(showValues('#spamadmin').length > 0)
		
			log('gerir_spam.php','POST',showValues('#spamadmin')+"&mod=3",true);
	
		else
			
			window.alert("Selecciona os tópicos spam a repor.");
		
	});
	
	/*var browserName=navigator.appName
	var browserVersion=navigator.appVersion*/
	
	/*lastBlock = $("#aa0");
    maxWidth = 140;
    minWidth = 130;
	
	Menu deslizante para as áreas
    $(".slide li a").hover(
		function(){
        	
			$(lastBlock).animate({width: minWidth+"px"}, { queue:false, duration:400 });
			$(this).animate({width: maxWidth+"px"}, { queue:false, duration:400});
			lastBlock = this;
      		
	  	}, function() {
				// Impedir o erro handler not found 
		});*/
    
	$(".trancadotop").click(function(){
		
		var flag = 1;
		
		if( this.checked ) flag = 0;

		$.post("editoppost.php",{
  				
				flag: flag,
				id: this.id
				
		} , function(txt){
				
				if(txt.length > 0)	alert(unescape(txt));
		
		});
		
		if(this.checked){
			
			try{
				
				document.submitpost.subpost.disabled = true;
	        	document.submitpost.prepost.disabled = true;
	        	document.submitpost.assunto.disabled = true;
	        	document.submitpost.texto.disabled = true;
	        	document.submitpost.selecttoolb.disabled = true;
	        	
	        	
			}catch(e){
				
				
			}
			
		} else {
			
			try{
				
				document.submitpost.subpost.disabled = false;
	        	document.submitpost.prepost.disabled = false;
	        	document.submitpost.assunto.disabled = false;
	        	document.submitpost.texto.disabled = false;
	        	document.submitpost.selecttoolb.disabled = false;
				
			}catch(e){
				
				
			}
			
		}
		
	});






/*Início - Funções de gestão dos filmes*/

	$("#apg_rel").click(function(){
		
		var listRel = document.getElementById("rel_fil");
		
		if(listRel.selectedIndex != -1){

			for(var i = listRel.length - 1; i >= 0; i--){
			
				if(listRel.options[i].selected){ listRel.remove(i); }
			
			}
			
		}else window.alert("Seleccione o realizador a excluir.");
			
		
	});
	
	$("#new_rel").click(function(){
		
		
		var  rel = prompt ("Nome do realizador (Até 70 caractéres):","");
		
		if( rel.length > 0){
		
		if( rel != null  ){
			
			if(rel.length < 71){
			
				/*var Op = document.createElement("OPTION")
				Op.value = rel
				Op.text = rel*/
				
				cont = document.getElementById("rel_fil").options.length;
				newOption = new Option(rel, rel);
				document.getElementById("rel_fil").options[cont] = newOption;	
			
			} else 
			
			alert("A contagem de caractéres excede o permitido de 70 em "
			+(rel.length-70)+" caractéres");
				
		}
		
		} else alert("O nome do realizador não pode estar em branco.")
	});
	
	$("#apg_som").click(function(){
		
		if( $("#tip_som_fil").val() > 0 ){
			
			if(confirm("Tem a certeza que deseja apagar este tipo de som?")){
			
			$.ajax({
   			type: "POST",
  			url: "filmes/filme_upd_gen_sup_som.php",
   			data: "id="
			+(  document.getElementById("tip_som_fil")
			.options[document.getElementById("tip_som_fil").selectedIndex].value )+"&flag=2",
   			dataType: "html",
			success: function(txt){
  		 		
				for(var i = document.getElementById("tip_som_fil").length-1; i > -1 ;i--){
					//alert(document.getElementById("sup_fil").options[i].value+"=="+txt)
					if( document.getElementById("tip_som_fil").options[i].value == txt)
						document.getElementById("tip_som_fil").remove(i);
						
				}
				
   			},
   			error: function(){ 
			   	alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.');
				   }
				   
			});
			
			}
			
			
		} else window.alert("Seleccione o tipo de som a apagar.");
		
	});
	
	$("#del_sup").click(function(){
		
	if( $("#sup_fil").val() > 0 ){
	
			if(confirm("Tem a certeza que deseja apagar este suporte?")){
			$.ajax({
   			type: "POST",
  			url: "filmes/filme_upd_gen_sup_som.php",
   			data: "id="
			+(  document.getElementById("sup_fil")
			.options[document.getElementById("sup_fil").selectedIndex].value )
			+"&flag=3",
   			dataType: "html",
			success: function(txt){
  		 		
				for(var i = document.getElementById("sup_fil").length-1; i > -1 ;i--){
					//alert(document.getElementById("sup_fil").options[i].value+"=="+txt)
					if( document.getElementById("sup_fil").options[i].value == txt)
						document.getElementById("sup_fil").remove(i);
						
				}
				
   			},
   			error: function(){ 
			   	alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.');
				   }
				   
			});
			}
			
		} else window.alert("Seleccione o suporte a apagar.");
		
	});
	
	$("#apg_gen").click(function(){
		
		if( document.getElementById("gen_fil").selectedIndex > 0 ){
		
		if(confirm("Tem a certeza que deseja apagar este gênero?")){
		$.ajax({
   			type: "POST",
  			url: "filmes/filme_upd_gen_sup_som.php",
   			data: "id="
			+(  document.getElementById("gen_fil")
			.options[document.getElementById("gen_fil").selectedIndex].value )
			+"&flag=1",
   			dataType: "html",
			success: function(txt){
  		 		
				for(var i = document.getElementById("gen_fil").length-1; i > 0 ;i--){
					
					if( document.getElementById("gen_fil").options[i].value == txt)
						document.getElementById("gen_fil").remove(i);
						
				}
				
   			},
   			error: function(){ 
			   		alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.'); }
 		})
 		}
		} else window.alert("Seleccione o/os gêneros a apagar.");
 		
	});
	
	$("#new_gen").click(function(){
	
		var gen = prompt("Introduza o nome do novo gênero (Até 70 caractéres):","");
		
		if(gen != null){
			
		if( gen.length < 71 ){
		
		if( gen.length > 0 ){

		$.ajax({
   			type: "POST",
  			url: "filmes/filme_upd_gen_sup_som.php",
   			data: "id=1&contend="+escape(gen),
   			dataType: "xml",
			success: function(xml){
				
				if( $(xml).find('option').find('name').text() != ""  ){
  		 			
					var value = unescape( $(xml).find('option').find('name').text() );
  		 			var id = $(xml).find('option').find('id').text();
  		 		
					cont = document.getElementById("gen_fil").options.length;
					newOption = new Option(value, id);
					document.getElementById("gen_fil").options[cont] = newOption;
				
				} else
				alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.');
				
   			},
   			error: function(XMLHttpRequest, textStatus, errorThrown){ 
			   	alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.'+textStatus); }
 		
		 });
 		
		
		} else alert ("O nome do género não pode estar em branco.");
		
		} else alert ("O nome do género excede os 70 caractéres permitidos.");
		
		}				
	});
	
	
	$("#gen_fil").change(function(){
		
		/*encodeURIComponent();
		
		alert("hello");
		
		$.ajax({
   			type: "POST",
  			url: "filmes/filme_get_nums.php",
   			data: "id=1&contend="+escape(gen),
   			dataType: "xml",
			success: function(xml){
				
				$("#")
				
   			},
   			error: function(XMLHttpRequest, textStatus, errorThrown){ 
			   	alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.'+textStatus); }
 		
		 });*/
		
	});
	
	$("#new_som").click(function(){
		
		var som = prompt("Novo tipo de som (Até 70 caractéres):","");
		
		if(som != null){
			
		if( som.length < 71 ){
		
		if( som.length > 0 ){	
		
			
		$.ajax({
   			type: "POST",
  			url: "filmes/filme_upd_gen_sup_som.php",
   			data: "id=2&contend="+escape(som),
   			dataType: "xml",
			success: function(xml){
  		 		if( $(xml).find('option').find('name').text() != ""  ){
  		 			
  		 			value = unescape( $(xml).find('option').find('name').text() );
  		 			id = $(xml).find('option').find('id').text();
  		 		
					cont = document.getElementById("tip_som_fil").options.length;
					newOption = new Option(value, id);
					document.getElementById("tip_som_fil").options[cont] = newOption;
				
				} else
				alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.');
				
   			},
   			error: function(){ 
			   	alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.'); }
 		});
 		
		 } else alert ("O nome do som não pode estar em branco.");
 		
		} else alert ("O nome do som excede os 70 caractéres permitidos.");
		
		}
	});
	
	$("#new_sup").click(function(){
		
		var sup = prompt("Novo suporte (Até 50 caractéres. O caracter '|' não é permitido):","");
		
		if(sup != null){
			
		if( sup.indexOf("|") < 0 ){
		
		if( sup.length < 51 ){
			
		if( sup.length > 0 ){
			
		$.ajax({
   			type: "POST",
  			url: "filmes/filme_upd_gen_sup_som.php",
   			data: "id=3&contend="+escape(sup),
   			dataType: "xml",
			success: function(xml){
  		 		
  		 		if( $(xml).find('option').find('name').text() != ""  ){
  		 			
  		 			value = unescape( $(xml).find('option').find('name').text() );
  		 			id = $(xml).find('option').find('id').text();
  		 		
					cont = document.getElementById("sup_fil").options.length;
					newOption = new Option(value, id);
					document.getElementById("sup_fil").options[cont] = newOption;
				
				} else
				alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.');
				
   			},
   			error: function(){ 
			   	alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.'); }
 		})
 		
		} else alert ("O nome do suporte não pode estar em branco.");
		
		} else alert ("O nome do suporte excede os 50 caractéres permitidos.");
		
		} else alert ("O nome do suporte não pode conter o caractér '|'.");
		
		}	
	});
	
	

	
	
	
	$("#ins_fil").click(function(){

		
		relSelected = getMultipleAll(document.getElementById("rel_fil"));
		
		var dados = "";
		
		for(var i = 0; i < relSelected.length; i++ )
			dados += "rel_fil["+i+"] = "+escape( relSelected[i] )+"&";	
				
		dados += "nom_fil="+escape( getText("nom_fil") )+"&";
		dados += "eti_fil="+getText("eti_fil")+"&";
		dados += "sin_fil="+escape( getText("sin_fil") )+"&";
		dados += "ano_fil="+getText("ano_fil")+"&";
		dados += "dur_fil="+getText("dur_fil")+"&";
		dados += "req_fil="+document.getElementById("req_fil").checked+"&";
		dados += "slo_fil="+escape( getText("slo_fil") )+"&";
		dados += "tip_som_fil="+getText("tip_som_fil")+"&";
		dados += "gen_fil="+getText("gen_fil")+"&";
		dados += "class_imdb_fil="+getText("class_imdb_fil")+"&";
		dados += "num_copi_film="+escape( getText("data_filme_copi") );
		
		//alert(dados);
		$.ajax({
   			type: "POST",
  			url: "filmes/filme_ins.php",
   			data: dados,
   			dataType: "html",
			success: function(txt){
  		 		
  		 		if(txt.length > 0)	
				   alert(unescape(txt));
				
   			},
   			error: 
			function(){ alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.'); }
			
 		});
		
			
	});
	
	
	$("#apg_film").click(function(){
		
		var query = "";
		
		$(".mark").each(function(i)
			{
				
				if(this.checked){
					
					query += "apg_fil["+i+"]="+this.id+"&";
					
					this.checked = false;
					
					$('#trf'+this.id).fadeOut("slow");
					
				}
				
			});
		
		if ( query != "" ) {
			
			$.ajax({
				type: "POST",
				url: "filmes/filme_lpea.php",
				data: query,
				dataType: "html",
				success: function(txt){
  		 		
					if(txt.length > 0) alert(unescape(txt));
				
				},
				error: 
				function(){ alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.'); }
			
			})
			
		} else alert("Seleccione os filmes a apagar.");
			
	});
	
	$("#new_sup_sub").click(function(){
		
		/*alert(
		getText("data_filme_copi").indexOf( getText("sup_fil") )
		+"-->"+ ( (getText("data_filme_copi").indexOf( getText("sup_fil") ) ) % 3));*/
		//alert( getText("data_filme_copi").indexOf( getText("sup_fil") ) )
		
		if( getText("sup_fil") != "" ){
		
		flag = true;
		
		elems = explode("|", getText("data_filme_copi"));
		
		for(i = 2; i < elems.length ;i+=3){
			
			if( elems[i] == getText("sup_fil") ) flag = false;
			
		}
		
		if( flag ){
		
		var flag = 0;
		
		if ( document.getElementById("char_sup_film").selectedIndex < 0 )
			flag = 1;
		else if ( document.getElementById("sup_fil").selectedIndex < 0 )
			flag = 2;
		else if( getText("num_cop_fil") == "")
			flag = 3;
		
		
		if( flag == 0 ) {
		
		txt = getText("num_cop_fil")+"|"+
		document.getElementById("char_sup_film").options[document.getElementById("char_sup_film").selectedIndex].value;
		
		txth = txt+"|"+
		document.getElementById("sup_fil").options[document.getElementById("sup_fil")
		.selectedIndex].value/*+(document.getElementById("copi_mos_film").length+1)*/;
	
		txts = txt+"|"+
		document.getElementById("sup_fil").options[document.getElementById("sup_fil")
		.selectedIndex].text;
			
		newOption = new Option(txts, txth);
		
		document.getElementById("copi_mos_film").options[document
		.getElementById("copi_mos_film").options.length] = newOption;
		
		if( getText("data_filme_copi") != "" ){
				
			if(getText("data_filme_copi") != "") txth = "|"+txth;
				
			setText( getText("data_filme_copi")+txth, "data_filme_copi");
			
		} else setText( txth, "data_filme_copi" );
		
		//alert( getText("data_filme_copi") );
		
		} else {
			
			
			switch(flag){
				
				case 1: flag = "Os elementos do suporte não foram preenchidos.";
						break;
				case 2: flag = "Seleccione um suporte para o número de cópias do filme.";
						break;
				default: flag = "O número de cópias não foi preenchido.";
				 		break;
				
			}
			
			alert (flag);
			
		}
		
		} else alert("Não pode inserir duas entradas com o mesmo suporte.");
		
		} else alert("Introduza um novo suporte.");
		
	});
	
	
	$("#del_sup_sub").click(function(){
		
		if( document.getElementById("copi_mos_film").selectedIndex > -1) {
		
		var replace = getText("data_filme_copi").replace(
		"|"+document.getElementById("copi_mos_film").options[document
		.getElementById("copi_mos_film").selectedIndex].value+"|", "|" ); 
		
		if( replace == getText("data_filme_copi") ){
			
			replace = getText("data_filme_copi").replace(
			"|"+document.getElementById("copi_mos_film").options[document
			.getElementById("copi_mos_film").selectedIndex].value, "" ); 
		
			if( replace == getText("data_filme_copi") ){
				
				replace = getText("data_filme_copi").replace(
				document.getElementById("copi_mos_film").options[document
				.getElementById("copi_mos_film").selectedIndex].value+"|", "" ); 
				
				if( replace == getText("data_filme_copi") ){
			
					replace = getText("data_filme_copi").replace(
					document.getElementById("copi_mos_film").options[document
					.getElementById("copi_mos_film").selectedIndex].value, "" ); 
					
				}
			
			}
				
		}
		
		setText(replace,"data_filme_copi");
		
		document.getElementById("copi_mos_film").remove(document
		.getElementById("copi_mos_film").selectedIndex);
		
		} else 
		alert(
		"Seleccione o número de cópias com o suporte desejado e  o número de suportes a apagar."
		);
			
	});
	
	function newpicture(pic, newpicSrc) { pic.src = newpicSrc; }
	
	$(".votestar").hover(function () {
		
			switch( $(".votestar").index(this) ){
				
				case 1: $("#text_pos_vot").html("Nada de especial"); 
				break;
				
				case 2: $("#text_pos_vot").html("Vale a pena"); 
				break;
				
				case 3: $("#text_pos_vot").html("Muito giro");
				break;
				
				case 4: $("#text_pos_vot").html("Espetacular");
				break;
				
				default: $("#text_pos_vot").html("Fraco");
				
			}
		
		if( getText("film_id_fil") != ""){
			
			
			
			for(i = 0; i <= $(".votestar").index(this); i++){
				
				newpicture( $(".votestar").get(i) , 'imagens/star.png');
			
			}
			
			for(i = 4; i > $(".votestar").index(this); i--){
				
				newpicture( $(".votestar").get(i), 'imagens/star2.png');
			
			}
			
		}
		
	}, function (){
		
		$("#text_pos_vot").html("Obrigado pelo voto!");
		
		if( getText("film_id_fil") != ""){
			
			$("#text_pos_vot").html("<br /><br />");
			
			for(i = 4; i > getText("film_classi_fil")-1; i--){
					
				newpicture( $(".votestar").get(i), 'imagens/star2.png');
			
			}
			
			for(i = 0; i <= getText("film_classi_fil")-1; i++){
					
				newpicture( $(".votestar").get(i), 'imagens/star.png');
			
			}
			
			
		}
		
      });
	
	$(".votestar").click(function () {
        
        if(getText("film_id_fil") != ""){
        
		$.post("filmes/filme_votar.php",{
			
			id: getText("film_id_fil"),
			voto: $(".votestar").index(this)
				
		}, function(txt){
				
			if( txt.length > 0 ){
				
				if( !isNaN(txt) ){
					
					setText("","film_id_fil");
					
					for(i = 0; i < txt; i++){
				
						
						newpicture( $(".votestar").get(i) , 'imagens/star.png');
			
					}
					
					for(i = 4; i >= txt; i--){
						
						newpicture( $(".votestar").get(i) , 'imagens/star2.png');
			
					}
					
					$("#text_pos_vot").html ("<br />Obrigado pelo voto!");
					
				} else alert(txt)
				
			} else alert("De momento não é possível atender ao seu pedido.");
			
		});
		
		}
		
	});
	
	
	
	
	$("#req_button").click(function () {
	   	
	   	value="id_film="+getText("id_film_h")+"&dat_dis="+$("#dat_min_lev_req").val()
		   +"&sup_fil="+$("#sup_fil").val();
	   	
		$.ajax({
			type: "POST",
			url: "filmes/filme_req.php",
			data: value,
			dataType: "html",
			success: function(txt){
				
				$("#statusreq").html("");		
				
				if( txt.length > 0 ){
				
				if( txt == 1 ){
					
						$("#requesicaoform").html("Receberá uma PM a confirmar as condições em que se deve dirigir à biblioteca para levantar o filme.");
					
				} else window.alert( unescape( txt ) );
				
				} else window.alert("De momento não é possível atender ao seu pedido.");
					
					},
					error: 
				function(){ 
					$("#statusreq").html("");
					window.alert(
					'Lamentamos mas de momento não é possível efectuar a requesição.'); 
				
				}, beforeSend:
				function (){
					
					$("#statusreq").html(
					"<img src=\"imagens/indicator.gif\" "+
					+"alt=\"A carregar...\" title=\"A carregar\" />");
					
				}
				});
		
	}); 

/*Fim - Funções de gestão dos filmes*/

	$("#env_rel_bug").click(function () {
		
		$("#env_rel_bug_txt").attr("disabled", "disabled");
		$("#env_rel_bug").attr("disabled", "disabled");
		
		$.post("log_bugs.php",{
			
			msg: escape( $("#env_rel_bug_txt").val() )
				
		});
		
	}); 

	
	$(".newvidyou").click(function () {
		
		var val = prompt("Endereço do video do youtube:","");
		
		//var myExpReg = new 
		//RegExp("^http://(br|www)?(\.)?youtube\.com/watch\?v=(a-zA-Z0-9_-)*$");
		//&& myExpReg.exec(val) != null
		
		if(val != null ){
			
			val = "[youtube]"+val+"[/youtube]";
		
			setText( $("#"+$( "#rox"+this.id ).val()).val()+val , $( "#rox"+this.id ).val() );
		
		}
		
	});
	
	$("#upd_fil").click(function () {
		
		relSelected = getMultipleAll(document.getElementById("rel_fil"));
		//alert(escape( relSelected[1]) );
		$.post("filmes/filme_editar.php",{
			
			titulo: escape( $("#nom_fil").val() ),
			etiqueta: $("#eti_fil").val(),
			sinopse: escape( $("#sin_fil").val() ),
			ano: $("#ano_fil").val(),
			duracao: $("#dur_fil").val(),
			requesitavel: document.getElementById("req_fil").checked,
			slogan: escape( $("#slo_fil").val() ),
			tipo_som: $("#tip_som_fil").val(),
			'realizadores[]': relSelected,
			gen_filme: $("#gen_fil").val(),
			class_film: escape( $("#class_imdb_fil").val() ),
			id_filme: $("#apg_fil").val(),
			num_copi_film: escape( getText("data_filme_copi") )
			
		}, function(txt){
			
			if( txt.length > 0 ){
				
				alert( unescape( txt ) );
				
			} else alert("De momento não é possível atender ao seu pedido.");
			
		});
		
	});
	
	
	
	
	
$("#edit_req").click(function () {
	
		$.post("filmes/filme_gerir_req.php",{
			
			dat_min: escape( $("#dat_req").val() ),
			dat_lev: $("#dat_levant").val(),
			id_film: $("#id_req").val(),
			id_util: $("#id_util").val(),
			sup_fil: $("#sup_fil").val()
			
		}, function(txt){
			
			if( txt.length > 0 ){
				
				alert( unescape( txt ) );
				
			} else alert("De momento não é possível atender ao seu pedido.");
			
		});
		
	});
	

	
	$("#apg_req").click(function () {
		
		//alert("heyman");
		
		$.post("filmes/filme_gerir_req.php",{
			
			id_req: escape( $("#id_req").val() )
			
		}, function(txt){
			
			if( txt.length > 0 ){
				
				if( txt  == "1"){
					
					window.alert("A requesição foi apagada com sucesso.");
					location.href = "index.php?elem=2&accao=4&opcao=3";
					
				} else alert( unescape( txt ) );				
				
			} else alert("De momento não é possível atender ao seu pedido.");
			
		});
		
	});
	
	/*Início - Algumas operações que dizem respeito aos álbuns*/
	
	$("#tracks_alb_user tr:even").css("background-color", "#FFCC00");
		
	$("#tracks_alb tr:even").css("background-color", "#FFCC00");
	
	$("#ins_new_alb").click(function(){
		
		dados = showValues("#album_ins");
		dados = dados.substr(dados.indexOf("name_tri_alb"), dados.length);
				
		dados += "&nom_alb="+escape( getText("nome_alb") )+"&";
		dados += "eti_alb="+getText("etiq_alb")+"&";
		dados += "sin_alb="+escape( getText("sin_alb") )+"&";
		dados += "ano_alb="+getText("ano_alb")+"&";
		dados += "req_alb="+document.getElementById("req_alb").checked+"&";
		dados += "num_copi_alb="+escape( getText("data_filme_copi") );
		
		$.ajax({
   			type: "POST",
  			url: "albuns/album_ins.php",
   			data: dados,
   			dataType: "html",
			success: function(txt){
  		 		
  		 		if(txt.length > 0)	
				   alert(unescape(txt));
				
   			},
   			error: 
			function(){ alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.'); }
			
 		});
		
			
	});
	
	$("#new_trilh_alb").click(function () {
		
		/*values =
		showValues("#album_ins");
		values = values.substr(values.indexOf("name_tri_alb"), values.length);
		alert( values );*/
		
		var lastRow = $("#trilh_alb_table tr:last").clone();
		
		var id = $("#trilh_alb_table tr").size()-1;
		
		//alert(id);
		
		$("[name]:first", lastRow).attr("name", "name_tri_alb["+id+"]");
		$("[name]:eq(1)", lastRow).attr("name", "time_tri_alb["+id+"]");
		$("[name]:eq(2)", lastRow).attr("name", "acerc_tri_alb["+id+"]");
		$("[id]", lastRow).attr("id", "time_tri_alb"+id );
		$("[name]:last", lastRow).attr("name", "sel_alb_track_gen["+id+"]");
		$("[id]:last", lastRow).attr("id", "sel_alb_track_gen["+id+"]");
		
		//alert(lastRow.html());
		
		$("#trilh_alb_table").append(lastRow);
		
		$( "#time_tri_alb"+id ).mask("99:99:99");
		
	});
		
	
	$("#new_sup_alb").click(function(){
		
		var sup = prompt("Novo suporte (Até 70 caractéres. O caracter '|' não é permitido):","");
		
		if(sup != null){
			
		if( sup.indexOf("|") < 0 ){
		
		if( sup.length < 71 ){
			
		if( sup.length > 0 ){
		
		$.ajax({
   			type: "POST",
  			url: "albuns/album_upd_sup_gen_trilh.php",
   			data: "id=1&contend="+escape(sup),
   			dataType: "xml",
			success: function(xml){
  		 		
				if( $(xml).find('option').find('name').text() != ""  ){
  		 			
					value = unescape( $(xml).find('option').find('name').text() );
  		 			id = $(xml).find('option').find('id').text();
  		 			
					cont = document.getElementById("sup_fil").options.length;
					newOption = new Option(value, id);
					document.getElementById("sup_fil").options[cont] = newOption;
				
				} else
				alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.');
				
   			},
   			error: 
			function(){ alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.'); }
			
 		});
		} else alert ("Por favor preencha o nome desejado do suporte.");
		} else alert ("O suporte excede os 70 caracteres permitidos.");
		} else alert ("O caracter | não pode ser utilizado.");
		}		
	
	});
	
	
	
	$("#del_sup_alb").click(function(){	
	
		
		if( $("#sup_fil").val() > 0 ){
			
			if(confirm("Tem a certeza que deseja apagar este suporte?")){
			
			$.ajax({
   			type: "POST",
  			url: "albuns/album_upd_sup_gen_trilh.php",
   			data: "id="+(  document.getElementById("sup_fil").options
			[document.getElementById("sup_fil").selectedIndex].value )+"&flag=1",
   			dataType: "html",
			success: function(txt){
				for(var i = document.getElementById("sup_fil").length-1; i > -1 ;i--){
					//alert(document.getElementById("sup_fil").options[i].value+"=="+txt)
					if( document.getElementById("sup_fil").options[i].value == txt)
						document.getElementById("sup_fil").remove(i);
						
				}
   			},
   			error: function(){
			   		alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.');
				   }
				   
			});
			
			}
			
		} else window.alert("Seleccione o suporte a apagar.");
	
	});
	
	
	
	$("#new_trilh_alb_gen").click(function(){	
		
		genero = prompt("Introduza o nome do gênero pretendido até 100 caracteres:","");
		
		if( genero != null ) {
		
		genero = escape(genero);
		
		//alert(genero);
		
		if(genero.length > 0){
			
		if( genero.length < 101 ){
			
			$.ajax({
   			type: "POST",
  			url: "albuns/album_upd_sup_gen_trilh.php",
   			data: "id=2&contend="+genero,
   			dataType: "xml",
			success: function(xml){
				
				/*newOption = new Option("asdsa", "1");
				cont = $(".alb_tril_gen_update").get(1).options.length;
				$(".alb_tril_gen_update").get(1).options[cont] = newOption;*/
				
				if( $(xml).find('option').find('name').text() != ""  ){
  		 			
					value = unescape( $(xml).find('option').find('name').text() );
  		 			id = $(xml).find('option').find('id').text();
					
					
					for(var i = $(".alb_tril_gen_update").length-1; i > -1 ;i--){
						
						cont = $(".alb_tril_gen_update").get(i).options.length;
						$(".alb_tril_gen_update").get(i).options[cont] = new Option(value, id);
						
					}	
				
				
				} else
				alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.');
				
				
   			},
   			error: function(){
				alert("heelo");
			   		alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.');
				   }
				   
			});
			
		} else window.alert("O nome do gênero excede os 100 caracteres permitidos.");
	
		} else window.alert("O nome do gênero tem de estar preenchido.");
		
		}
		
	});
	
	
	$("#del_trilh_alb_gen").click(function(){
	
		if (document.getElementById("ref_gen_upda").options.length > 0) {
		
		if(confirm("Tem a certeza que deseja apagar este gênero musical?")){
		
		genero = document.getElementById("ref_gen_upda").options[document.getElementById("ref_gen_upda").selectedIndex].value;
			
		$.ajax({
   			type: "POST",
  			url: "albuns/album_upd_sup_gen_trilh.php",
   			data: "id="+genero+"&flag=2",
   			dataType: "html",
			success: function(txt){
				
				for(var i = $(".alb_tril_gen_update").length-1; i > -1 ;i--){
						
					for(var e = $(".alb_tril_gen_update").get(i).options.length-1; e > -1 ;e--){
							
						if( $(".alb_tril_gen_update").get(i).options[e].value == txt)
							$(".alb_tril_gen_update").get(i).remove(e);
						
					}
						
				}	
					
   			},
   			error: function(){
			   		alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.');
				   }
				   
 		});
 		}
		
		} else window.alert("Não exsitem gêneros musicais a apagar.");
 		
	});
	
	
	
	$("#apg_alb").click(function(){
		
		var query = "";
		
		$(".mark").each(function(i){
				
				if(this.checked){
					
					query += "apg_alb["+i+"]="+this.id+"&";
					
					this.checked = false;
					
					$('#trf'+this.id).fadeOut("slow");
					
				}
				
			});
		
		
		query = query.substring(0, query.length-1);
		
		if ( query != "" ) {
			
			$.ajax({
				type: "POST",
				url: "albuns/album_lpea.php",
				data: query,
				dataType: "html",
				success: function(txt){
  		 		
					if(txt.length > 0) window.alert(unescape(txt));
					
				},
				error: 
				function(){ alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.'); }
			
			})
			
		} else alert("Seleccione os filmes a apagar.");
			
	});
	
	
	
	$(".votestaralb").click(function () {
        
        if( getText("album_id_alb") != ""){
    

		$.post("albuns/album_votar.php",{
			
			id: getText("album_id_alb"),
			voto: $(".votestaralb").index(this)
				
		}, function(txt){
				
			if( txt.length > 0 ){
				
				if( !isNaN(txt) ){
					
					setText("","album_id_alb");
					
					for(i = 0; i < txt; i++){
				
						
						newpicture( $(".votestaralb").get(i) , 'imagens/star.png');
			
					}
					
					for(i = 4; i >= txt; i--){
						
						newpicture( $(".votestaralb").get(i) , 'imagens/star2.png');
			
					}
					
					$("#text_pos_vot").html ("Obrigado pelo voto!");
					
				} else alert(txt)
				
			} else alert("De momento não é possível atender ao seu pedido.");
			
		});
		
		}
		
	});
	
	
	$(".votestaralb").hover(function () {
		
			switch( $(".votestaralb").index(this) ){
				
				case 1: $("#text_pos_vot").html("Nada de especial"); 
				break;
				
				case 2: $("#text_pos_vot").html("Vale a pena"); 
				break;
				
				case 3: $("#text_pos_vot").html("Muito giro");
				break;
				
				case 4: $("#text_pos_vot").html("Espetacular");
				break;
				
				default: $("#text_pos_vot").html("Fraco");
				
			}
		
		if( getText("album_id_alb") != ""){
			
			
			
			for(i = 0; i <= $(".votestaralb").index(this); i++){
				
				newpicture( $(".votestaralb").get(i) , 'imagens/star.png');
			
			}
			
			for(i = 4; i > $(".votestaralb").index(this); i--){
				
				newpicture( $(".votestaralb").get(i), 'imagens/star2.png');
			
			}
			
		}
		
	}, function (){
		
		$("#text_pos_vot").html("Obrigado pelo voto!");
		
		if( getText("album_id_alb") != ""){
			
			$("#text_pos_vot").html("<br />");
			
			for(i = 4; i > getText("album_classi_alb")-1; i--){
					
				newpicture( $(".votestaralb").get(i), 'imagens/star2.png');
			
			}
			
			for(i = 0; i <= getText("album_classi_alb")-1; i++){
					
				newpicture( $(".votestaralb").get(i), 'imagens/star.png');
			
			}
			
			
		}
		
      });
      
    /*$(".overchecktrilh").click(
		function(){
			if(this.checked)
				$("#"+this.id).addClass("overviewmarked");
			else
				$("#"+this.id).removeClass("overviewmarked");
	});*/
      
    $("#apg_trilhas_alb").click(function(){
			
		values = showValues("#apg_trilh_fo");
		
		//alert(values+" "+getText("album_id_ord"));
		
		$(".overchecktrilh").each(function(i){
			if(this.checked){
				//this.checked = false;
				$("#tracks_alb tr:eq("+i+")").fadeOut("slow");
			}
		});
		
		//$("#tracks_alb tr:even").css("background-color", "#FFCC00");
		
		if ( values != "" ) {
		
			$.ajax({
				type: "POST",
				url: "albuns/album_upd_sup_gen_trilh.php",
				data: values+"&flag=3&id="+getText("album_id_ord"),
				dataType: "html",
				success: function(txt){
  		 		
					if(txt.length > 0) window.alert( unescape(txt) );
					
				},
				error: 
				function(){ alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.'); }
			});
			
		} else alert("Seleccione as trilhas do álbum a apagar.");
		
	});
	
	$("#ins_trilh_edit").click(function(){
		
		 	/*var newRow    = document.createElement( 'tr' );
            var groupCell   = document.createElement( 'td' );
            var roleCell    = document.createElement( 'td' );
            var requestCell = document.createElement( 'td' );
            var cancelCell  = document.createElement( 'td' ); 
			newRow.setAttribute("id", '');			
			newRow.appendChild( groupCell );
            newRow.appendChild( roleCell );
            newRow.appendChild( requestCell );
            newRow.appendChild( cancelCell );*/ 				
		
		nome = getText("name_new_trilh");
		
		if(nome != ""){
			
			temp_trilh = getText("time_tri_alb");
			
			if(temp_trilh != ""){
				
				acerca = getText("acerc_tri_alb");
				
				genero_txt = document.getElementById("ref_gen_upda").options
				[document.getElementById("ref_gen_upda").selectedIndex].text;
				
				value = "contend="+escape( nome )+"&time_tri="+escape( temp_trilh )
				+"&id=4&id_alb="
				+getText("album_id_ord")+"&acerca="+escape( acerca )+
				"&gen_trilh="+getText("ref_gen_upda");
				
				$.ajax({
					type: "POST",
					url: "albuns/album_upd_sup_gen_trilh.php",
					data: value,
					dataType: "html",
					success: function(txt){
						
						id = txt.substring(0,txt.indexOf("#"));
						ord = txt.substring(txt.indexOf("#")+1, txt.length);
						
						//alert(id+" "+ord);
						if(txt.length > 0){
							
						if( isNaN(id) || isNaN(ord) || id.length < 1 || ord.length < 1 ) 
							window.alert(unescape(txt));
						else{
							
							$("#tracks_alb").append('<tr id="'+txt+'"><td>'+
							'<input type="checkbox" name="apg_trlh_a['+
							$("#tracks_alb tr").length+']' +
							'id="apg_trlh_a['+
							$("#tracks_alb tr").length+']" value="'+id+'" ' + 
							'class=\"overchecktrilh\" /></td>' +
           					'<td>'+nome+'</td>' +
            				'<td>'+temp_trilh+'</td>' +
        					'<td>'+genero_txt+'</td>' +
            				'<td>'+acerca+'</td></tr>');
							
							
							$("#tracks_alb tr:even").css("background-color", "#FFCC00");
							
							$("#tracks_alb").tableDnD({
		
								onDragClass: "myTrackDragClass",
		
								onDrop: function(table, row) {
								var rows = table.tBodies[0].rows;
								var posicoes = "";
								var ids = "";
								for (var i = 0; i < rows.length; i++) {
								ids += rows[i].id.substring(0,rows[i].id.indexOf("#"))+" ";
								posicoes += 
								rows[i].id.substring( (rows[i].id.indexOf("#")+1)
								, rows[i].id.length )+" ";
            					}
								posicoes = posicoes.substring(0, posicoes.length-1);
	        					ids = ids.substring(0, ids.length-1);
								$.ajax({
									type: "POST",
									url: "albuns/album_upd_sup_gen_trilh.php",
									data: "id=3&id_album="+getText("album_id_ord")
									+"&contend="+escape(posicoes)
									+"&ids="+escape(ids),
									dataType: "html",
									success: function(txt){
										if(txt.length > 0) alert(unescape(txt));
									},
									error: 
								function(){ 
								alert(
								'Lamentamos mas houve um erro ao tentar efectuar o pedido.'
								); 
								}
								});
	    						}
							});
							}
							
							}
					},
					error: 
				function(){ 
					window.alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.'); }
				});
				
				
			} else window.alert("A duração da trilha não foi preenchida.");
			
		} else window.alert("O nome da trilha não foi preenchido.");
		
	});
	
	$("#req_button_alb").click(function(){
		
		value = getText("dat_min_lev_req");
		
		if(value != ""){
		
		value = "dat_dis="+escape(value)+"&sup_alb="+getText("sup_alb")+"&id_album="
		+getText("album_id_ord");
		
		$.ajax({
			type: "POST",
			url: "albuns/album_req.php",
			data: value,
			dataType: "html",
			success: function(txt){
						
				if(txt == 1)
					$("#requesicaoform").html(
					"Receberá uma PM a confirmar as condições em que se deve dirigir à biblioteca para levantar o álbum.");
					else 
						$("#statusreq").html("");
						window.alert(unescape(txt));
					},
					error: 
				function(){ 
					$("#statusreq").html("");
					window.alert(
					'Lamentamos mas de momento não é possível efectuar a requesição.');
				
					 
				}, beforeSend:
				function (){
					
					$("#statusreq").html(
					"<img src=\"imagens/indicator.gif\" "+
					+"alt=\"A carregar...\" title=\"A carregar\" />");
					
				}
				});
		
		} else window.alert("A data não é válida.");
		
		
	});
	
	$("#apg_req_alb").click(function(){
		
		$.post("albuns/album_gerir_req.php",{
			
			id_req: escape( $("#id_req").val() )
			
		}, function(txt){
			
			if( txt.length > 0 ){
				
				if( txt  == "1"){
					
					window.alert("A requesição foi apagada com sucesso.");
					location.href = "index.php?elem=2&accao=6&opcao=3";
					
				} else alert( unescape( txt ) );				
				
			} else alert("De momento não é possível atender ao seu pedido.");
			
		});
			
		
	});
	
	$("#edit_req_alb").click(function(){
		
		
		$.post("albuns/album_gerir_req.php",{
			
			dat_min: escape( $("#dat_req").val() ),
			dat_lev: $("#dat_levant").val(),
			id_album: $("#id_req").val(),
			id_util: $("#id_util").val(),
			sup_alb: $("#sup_alb").val()
			
		}, function(txt){
			
			if( txt.length > 0 ){
				
				alert( unescape( txt ) );
				
			} else alert("De momento não é possível atender ao seu pedido.");
			
		});
			
		
	});
	
	//Editar dados relativos a um álbum
	$("#upd_alb").click(function(){
		
		$.post("albuns/album_editar.php",{
			
			titulo: escape( $("#nom_alb").val() ),
			etiqueta: $("#eti_alb").val(),
			ano: $("#ano_alb").val(),
			id_album: $("#apg_alb").val(),
			requesitavel: document.getElementById("req_alb").checked,
			sinopse : escape( $("#sin_alb").val() ),
			num_copi_alb : escape( $("#data_filme_copi").val() )
			
		}, function(txt){
			
			if( txt.length > 0 ){
			
				alert( unescape( txt ) );
				
			} else alert("De momento não é possível atender ao seu pedido.");
			
		});
		
	});
	/*Fim - Algumas operações que dizem respeito aos álbuns*/
	
	
	
	
	
	
	/*Início - Algumas operações que dizem respeito aos outros*/
	$("#apg_outro").click(function(){
		
		var query = "";
		
		$(".mark").each(function(i)
			{
				
				if(this.checked){
					
					query += "apg_outro["+i+"]="+this.id+"&";
					
					this.checked = false;
					
					$('#trf'+this.id).fadeOut("slow");
					
				}
				
			});
		
		if ( query != "" ) {
			alert(query);
			
			$.ajax({
				type: "POST",
				url: "outros/outro_lpea.php",
				data: query,
				dataType: "html",
				success: function(txt){
  		 		
					if(txt.length > 0) alert(unescape(txt));
				
				},
				error: 
				function(){ alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.'); }
			
			})
			
		} else alert("Seleccione os filmes a apagar.");
		
		
	});
	
	
	$("#new_sup_outro").click(function(){
	
		var sup = prompt("Novo suporte (Até 70 caractéres. O caracter '|' não é permitido):","");
		
		if(sup != null){
			
		if( sup.indexOf("|") < 0 ){
		
		if( sup.length < 71 ){
			
		if( sup.length > 0 ){
		
		$.ajax({
   			type: "POST",
  			url: "outros/outro_upd_dir_sup.php",
   			data: "id=1&contend="+escape(sup),
   			dataType: "xml",
			success: function(xml){
  		 		
				if( $(xml).find('option').find('name').text() != ""  ){
  		 			
					value = unescape( $(xml).find('option').find('name').text() );
  		 			id = $(xml).find('option').find('id').text();
  		 			
					cont = document.getElementById("sup_fil").options.length;
					newOption = new Option(value, id);
					document.getElementById("sup_fil").options[cont] = newOption;
				
				} else
				alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.');
				
   			},
   			error: 
			function(){ alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.'); }
			
 		});
		} else alert ("Por favor preencha o nome desejado do suporte.");
		} else alert ("O suporte excede os 70 caracteres permitidos.");
		} else alert ("O caracter | não pode ser utilizado.");
		}	
		
	
	});
	
	$("#del_sup_outro").click(function(){
	
		/*alert(document.getElementById("sup_fil").options[document.getElementById("sup_fil").selectedIndex].value);*/
		
		if(confirm("Tem a certeza que deseja apagar este suporte?")){
		$.ajax({
   			type: "POST",
  			url: "outros/outro_upd_dir_sup.php",
   			data: "id="
			+(  document.getElementById("sup_fil")
			.options[document.getElementById("sup_fil").selectedIndex].value )
			+"&flag=1",
   			dataType: "html",
			success: function(txt){
  		 		
				for(var i = document.getElementById("sup_fil").length-1; i > -1 ;i--){
					
					if( document.getElementById("sup_fil").options[i].value == txt)
						document.getElementById("sup_fil").remove(i);
						
				}
				
   			},
   			error: function(){ 
			   	alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.');
				   }
				   
 		});
		}
	
	});
	
	
	
	$("#inser_dir_aut").click(function(){
	
		var sup = prompt("Novo suporte (Até 70 caractéres):","");
		
		if(sup != null){
		
		if( sup.length < 71 ){
			
		if( sup.length > 0 ){
		
		$.ajax({
   			type: "POST",
  			url: "outros/outro_upd_dir_sup.php",
   			data: "id=2&contend="+escape(sup),
   			dataType: "xml",
			success: function(xml){
  		 		
				if( $(xml).find('option').find('name').text() != ""  ){
  		 			
					value = unescape( $(xml).find('option').find('name').text() );
  		 			id = $(xml).find('option').find('id').text();
  		 			
					cont = document.getElementById("dir_sel").options.length;
					newOption = new Option(value, id);
					document.getElementById("dir_sel").options[cont] = newOption;
				
				} else
				alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.');
				
   			},
   			error: 
			function(){ alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.'); }
			
 		});
		} else alert ("Por favor preencha o nome desejado do suporte.");
		} else alert ("O suporte excede os 70 caracteres permitidos.");
		}	
		
	
	});
	
	
	$("#apg_dir_aut").click(function(){
		
		if(document.getElementById("dir_sel").options[document.getElementById("dir_sel").selectedIndex].value > 0){
		
		if(confirm("Tem a certeza que deseja apagar este direito?")){
		
		$.ajax({
   			type: "POST",
  			url: "outros/outro_upd_dir_sup.php",
   			data: "id="
			+(  document.getElementById("dir_sel")
			.options[document.getElementById("dir_sel").selectedIndex].value )
			+"&flag=2",
   			dataType: "html",
			success: function(txt){
  		 		
				for(var i = document.getElementById("dir_sel").length-1; i > -1 ;i--){
					
					if( document.getElementById("dir_sel").options[i].value == txt)
						document.getElementById("dir_sel").remove(i);
						
				}
				
   			},
   			error: function(){ 
			   	alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.');
				   }
				   
 		});
		
		}
		
		}
		
	
	});
	
	
	
	$("#ins_new_outr").click(function(){
		//dados = showValues("#album_ins");
		//dados = dados.substr( dados.indexOf("name_tri_alb"), dados.length );
		dados = "";		
		dados += "nom_outro="+escape( getText("nome_outro") );
		dados += "&eti_outro="+getText("etiq_outro");
		dados += "&sin_outro="+escape( getText("sin_outro") );
		dados += "&ano_outro="+getText("ano_outro");
		dados += "&req_outro="+document.getElementById("req_outro").checked;
		dados += "&dir_sel="+getText("dir_sel");
		dados += "&num_copi_outro="+escape( getText("data_filme_copi") );
		
		$.ajax({
   			type: "POST",
  			url: "outros/outro_ins.php",
   			data: dados,
   			dataType: "html",
			success: function(txt){
  		 		
  		 		if(txt.length > 0)	
				   alert(unescape(txt));
				
   			},
   			error: 
			function(){ alert('Lamentamos mas houve um erro ao tentar efectuar o pedido.'); }
			
 		});
	
	});
	
	
	
	$(".votestaroutr").click(function () {
        
        if( getText("outro_id_out") != ""){
    

		$.post("outros/outro_votar.php",{
			
			id: getText("outro_id_out"),
			voto: $(".votestaroutr").index(this)
				
		}, function(txt){
				
			if( txt.length > 0 ){
				
				if( !isNaN(txt) ){
					
					setText("","outro_id_out");
					
					for(i = 0; i < txt; i++){
				
						
						newpicture( $(".votestaroutr").get(i) , 'imagens/star.png');
			
					}
					
					for(i = 4; i >= txt; i--){
						
						newpicture( $(".votestaroutr").get(i) , 'imagens/star2.png');
			
					}
					
					$("#text_pos_vot").html ("Obrigado pelo voto!");
					
				} else alert(txt)
				
			} else alert("De momento não é possível atender ao seu pedido.");
			
		});
		
		}
		
	});
	
	
	$(".votestaroutr").hover(function () {
		
			switch( $(".votestaroutr").index(this) ){
				
				case 1: $("#text_pos_vot").html("Nada de especial"); 
				break;
				
				case 2: $("#text_pos_vot").html("Vale a pena"); 
				break;
				
				case 3: $("#text_pos_vot").html("Muito giro");
				break;
				
				case 4: $("#text_pos_vot").html("Espetacular");
				break;
				
				default: $("#text_pos_vot").html("Fraco");
				
			}
		
		if( getText("outro_id_out") != ""){
			
			
			
			for(i = 0; i <= $(".votestaroutr").index(this); i++){
				
				newpicture( $(".votestaroutr").get(i) , 'imagens/star.png');
			
			}
			
			for(i = 4; i > $(".votestaroutr").index(this); i--){
				
				newpicture( $(".votestaroutr").get(i), 'imagens/star2.png');
			
			}
			
		}
		
	}, function (){
		
		$("#text_pos_vot").html("Obrigado pelo voto!");
		
		if( getText("outro_id_out") != ""){
			
			$("#text_pos_vot").html("<br />");
			
			for(i = 4; i > getText("outro_classi_out")-1; i--){
					
				newpicture( $(".votestaroutr").get(i), 'imagens/star2.png');
			
			}
			
			for(i = 0; i <= getText("outro_classi_out")-1; i++){
					
				newpicture( $(".votestaroutr").get(i), 'imagens/star.png');
			
			}
			
			
		}
		
      });
	


	$("#req_button_outr").click(function () {
	   	
	   	value = "id_outro="+getText("album_id_ord")+"&dat_dis="+$("#dat_min_lev_req").val()
		   +"&sup_outr="+$("#sup_outr").val();
	   	
		//alert(value);
		
		$.ajax({
			type: "POST",
			url: "outros/outro_req.php",
			data: value,
			dataType: "html",
			success: function(txt){
				
				$("#statusreq").html("");		
				
				if( txt.length > 0 ){
				
				if( txt == 1 ){
					
						$("#requesicaoform").html("Receberá uma PM a confirmar as condições em que se deve dirigir à biblioteca para levantar o outro item.");
					
				} else window.alert( unescape( txt ) );
				
				} else window.alert("1De momento não é possível atender ao seu pedido.");
					
					},
					error: 
				function(){ 
					$("#statusreq").html("");
					window.alert(
					'2Lamentamos mas de momento não é possível efectuar a requesição.'); 
				
				}, beforeSend:
				function (){
					
					$("#statusreq").html(
					"<img src=\"imagens/indicator.gif\" "+
					+"alt=\"A carregar...\" title=\"A carregar\" />");
					
				}
				});
		
	});
	
	
	
	$("#edit_req_outro").click(function () {
		
		$.post("outros/outro_gerir_req.php",{
			
			dat_min: escape( $("#dat_req").val() ),
			dat_lev: $("#dat_levant").val(),
			id_outro: $("#id_req").val(),
			id_util: $("#id_util").val(),
			sup_out: $("#sup_fil").val()
			
		}, function(txt){
			
			if( txt.length > 0 ){
				
				alert( unescape( txt ) );
				
			} else alert("De momento não é possível atender ao seu pedido.");
			
		});
		
	});
	
	
	$("#upd_outro").click(function () {
		//alert("alert");
		$.post("outros/outro_editar.php",{
			
			titulo: escape( $("#nom_outro").val() ),
			etiqueta: $("#eti_outro").val(),
			sinopse:  escape( $("#sin_outro").val() ),
			ano: $("#ano_outro").val(),
			requesitavel:  document.getElementById("req_outro").checked,
			id_outro: $("#apg_outro").val(),
			num_copi_alb: $("#data_filme_copi").val()
			
		}, function(txt){
			
			if( txt.length > 0 ){
				
				alert( unescape( txt ) );
				
			} else alert("De momento não é possível atender ao seu pedido.");
			
		});
		
	});
	
	
	$("#apg_req_out").click(function () {
		
		$.post("outros/outro_gerir_req.php",{
			
			id_req: escape( $("#id_req").val() )
			
		}, function(txt){
			
			if( txt.length > 0 ){
				
				if( txt  == "1"){
					
					window.alert("A requesição foi apagada com sucesso.");
					location.href = "index.php?elem=2&accao=8&opcao=3";
					
				} else alert( unescape( txt ) );				
				
			} else alert("De momento não é possível atender ao seu pedido.");
			
		});
		
	});
	/*Fim - Algumas operações que dizem respeito aos outros*/
	
	
	
	
	$("#apg_req_out").click(function () {
		
		$.post("outros/outro_gerir_req.php",{
			
			id_req: escape( $("#id_req").val() )
			
		}, function(txt){
			
			if( txt.length > 0 ){
				
				if( txt  == "1"){
					
					window.alert("A requesição foi apagada com sucesso.");
					location.href = "index.php?elem=2&accao=8&opcao=3";
					
				} else alert( unescape( txt ) );				
				
			} else alert("De momento não é possível atender ao seu pedido.");
			
		});
		
	});
	
	
	
	//Dar respeito
	$(".thumbs_down").hover(function () {
		
		this.src = "imagens/thumbs_down_houver.bmp";
		
	},function(){
		
		this.src = "imagens/thumbs_down.bmp";
		
	});
	
	$(".thumbs_up").hover(function () {
		
		this.src = "imagens/thumbs_up_houver.bmp";
		
	},function(){
		
		this.src = "imagens/thumbs_up.bmp";
		
	});
	

	
	$(".thumbs").click(function(){
		
		var pos = Math.floor( $(".thumbs").index(this)/2);
		$.post("respeito.php",{
			
			tipo_respeito: this.id,
			id_post: $(".hide_thumbs").eq( pos ).val()
			
		}, function(txt){
			//opacity:0.4;filter:alpha(opacity=40
			
			$( ".info_thumbs" ).eq( pos ).html( unescape(txt) ).css("opacity","100").css("filter","alpha(opacity=100)").css("display","block").fadeOut(5000);
			
		});
		
	});
	
	
	$(".sticky_change").click(function(){
		
		var pos = $(".sticky_change").index(this);
		
		$.ajax({
			type: "POST",
			url: "postar.php",
			data: "sticky_change="+$(".mov_top_id").eq( $(".sticky_change").index(this) ).val(),
			dataType: "html",
			success: function(txt){
				
				if(txt.length < 1){
					
					if( $(".sticky_change").eq( pos ).attr( "checked") )
						$(".icon_thread").eq( pos ).attr( "src", "imagens/sticky.png" );
					else
						$(".icon_thread").eq( pos ).attr( "src", "imagens/post.png" );
						
				} else window.alert( unescape( txt ) );
					
				},
				error: function(){ 
					
					window.alert("Ocurreu um erro");
					//$(".icon_thread").eq( pos ).html("Não é possível atender ao pedido");
				
				}, beforeSend:
				function (){
					
					$(".icon_thread").eq( pos ).attr( "src", "imagens/indicator.gif" );
					
				}
				
		});
		
	});
	
	/**/
	
	
	$(".edit_myBlock").click(function(){
	
		inserirBlock(700,"?index_id="+$(".edit_myBlock_id").eq( $(".edit_myBlock").index(this) ).val());
		
	});
	
	

	
	
	
});
	
	function inserirBlock(width,params){
	
		atribs = 'toolbar=no,location=yes,top=0,left='+((screen.width/2)-(width/2))+',status=no,menubar=no,scrollbars=no,resizable=no,width='+width+',height='+(screen.height*0.83984375);
			
		window.open('blocks.php'+params,'page1',atribs);
			
	}

	
	





	

/******************************
*******************************
*******************************
*Funções jQuery****************
*******************************
*******************************
*******************************/

/******************************
*******************************
*******************************
*Formatação dos input filds****
*******************************
*******************************
*******************************/



jQuery(function($){
   			
			try{
			
			   $("input[@name=data]").mask("99/99/9999");
			   
			   $("input[@name=profdtnas]").mask("99/99/9999");
			   
			   $("input[@name=class_imdb_fil]").mask("99.9"
			   ,{completed:function(){
			   		   
					   if( parseFloat( this.val() ) > 10 || parseFloat( this.val() ) < 0 ){
					   		
							alert(
							"Introduz um valor maior do que 0 e igual ou inferior a 10 sff.");
					
							setText("", "class_imdb_fil");
							
						}
				   
				   }});
			   
			   $("input[@name=ano_fil]").mask("9999");
			   
			   $("input[@name=ano_alb]").mask("9999");
			    
			   //$("input[@name=eti_fil]").mask("9999999999");
			   
			   //$("input[@name=etiq_outro]").mask("9999999999");
			   
			   //$("input[@name=etiq_alb]").mask("9999999999");
			   
			   $("input[@name=num_cop_fil]").mask("999");
			   
			   $("input[@name=idai]").mask("99");
			   
			   $("input[@name=idaf]").mask("99");
			   
			   $("input[@name=dur_fil]").mask("9999min");
			
			   //$("input[@name=dur_alb]").mask("9999min");
			   
			   $("input[@name=dat_min_lev_req]").mask("99/99/");
				 	
			   /*$("input[@name=dat_max_lev_req]" ).mask(time
			   ,{completed:function(){
			   		
					   if( parseInt( this.val() ) < parseInt( $("#dat_min_lev_req").val() ) ){
					   		
							alert("Introduz um valor entre 0 e 10 sff.");
					
							$("#dat_min_lev_req").val() = "";
							
							$("#dat_max_lev_req").val() = "";
							
						}
				   
				   }});*/
			   
			   $("input[@name=dat_req]").mask("99/99/9999");
			   
			   $("input[@name=ano_outro]").mask("9999");
			   
			   $("input[@name=dat_levant]").mask("99/99/9999");
			   
			   $("#time_tri_alb").mask("99:99:99");
			   
			   //$("#eti_alb").mask("9999999999");
				
			   //$("#eti_outro").mask("9999999999");
			   
		}catch(e){}
			
		});

		

/******************************
*******************************
*******************************
*Formatação dos input filds****
*******************************
*******************************
*******************************/

/******************************
*******************************
*******************************
*AJAX**************************
*******************************
*******************************
*******************************/
/*
Envia parametros post ou get para um ficheiro definido e pode fazer o alert 
do retorno da informação textual desse ficheiro, se responseid estiver activo
*/
function log( url, metodo, parametros, responseid ){  

var xmlHttp;

try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttp=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    try
      {
      xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
    catch (e)
      {
      alert("O teu browser não suporta AJAX!");
      return false;
      }
    }
  }
  
  
  
  xmlHttp.open(metodo,url,true);
  
  if( metodo.toLowerCase() == 'post')
  	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  
  xmlHttp.send(parametros);
  
  
  
  
  xmlHttp.onreadystatechange = function(){
    
	if( xmlHttp.readyState==4 && xmlHttp.status == 200 ){
        
		resposta = unescape( xmlHttp.responseText );
        
        /*Neste switch é feita uma verificação das respostas e confrome a flag númerica
		é feita a acção descrita em vez de ser apresentada a mensagem num alert()*/
        switch( resposta ){
			
			case "1": resposta = "";
					  document.forms['loginform'].submit(); 
					  break;
					  
			case "2": resposta = "";			  	
					  document.submitpost.subpost.disabled = true;
	                  document.submitpost.prepost.disabled = true;
	                  document.submitpost.assunto.disabled = true;
	                  document.submitpost.texto.disabled = true;
	                  document.submitpost.selecttoolb.disabled = true;
	                  location.href = location.href;
					  break;
			
			case "3": resposta = "";			  	
					  document.submitopico.subpost.disabled = true;
	                  document.submitopico.assunto.disabled = true;
	                  document.submitopico.texto.disabled = true;
					  document.submitopico.resposta.disabled = true;
					  document.submitopico.selecttags.disabled = true;
					  location.href = location.href;
					  break;
					  
			case "4": resposta = "";
				$(document).ready(function(){
					$(".apagestatuto").each(function(i){
						if(this.checked){
							this.checked = false;
							$('#tr'+i).fadeOut("slow");
						}
					});
				});
			break;
			
			case "5": resposta = "";
				$(document).ready(function(){
					$(".overcheck").each(function(i){
						if(this.checked){	
							this.checked = false;	
							$('#trover'+i).fadeOut("slow");
						}
					}); 
				});
			break;
			
			case "6": resposta = "Já existe um estatuto com esse nome.";
					  /*
					  //No caso de já existir um estatuto com aquele nome 
					  //falta meter esse campo com o nome antes da edição
					  alert(window.frames['estaiframe'].name)
					  alert(document.getElementById("estaiframe").name)
					  window.frames['estaiframe'].location = url;   
					  document.getElementById('estaiframe').src = "www.google.com"*/
					  break;

			case "7": resposta = "";
					$(document).ready(function(){
						$(".meecheckk").each(function(i){
							if(this.checked){
								this.checked = false;
								$('#trmen'+i).fadeOut("slow");
							}
						});
					}); 
					break;
					
			case "8": resposta = "";
					$(document).ready(function(){
						$(".marcafrase").each(function(i){
							if(this.checked){
								this.checked = false;
								$('#row'+i).fadeOut("slow");
							}
						});
					}); 
					break;
					
			case "9": resposta = "";
					$(document).ready(function(){
						$(".marcspamtoh").each(function(i){
							if(this.checked){
								this.checked = false;
								$('#rowspam'+i).fadeOut("slow");
							}
						});
					}); 
					break;
			
		}
		
			if (responseid == true && resposta.length != 0) window.alert(resposta);

		   
	  } else {
	  	
			if(xmlHttp.readyState==4 && xmlHttp.status != 200) 
				window.alert("De momento não é possível atender ao teu pedido :(");
		
		} 
    
	}
  
  }
  
  
  

/*Função esponsável pelos resultados obtidos na pesquisa de utilizadores*/  
function getXml(url,metodo,parametros,responseid){
//alert(url+" "+metodo+" "+parametros+" "+responseid);

/*Fix para o bug quando se faz clique no butão reset no ie*/ 
if(parametros.indexOf('listp')<0)
	parametros += "&listp=1";
if(parametros.indexOf('iden')<0)
	parametros += "&iden=1";
	
var xmlHttp;
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttp=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    try
      {
      xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
    catch (e)
      {
      alert("O teu browser não suporta AJAX!");
      return false;
      }
    }
  }  
  
  if(responseid.length >0){
  	
	for(var i = responseid.length; i >= 0;i--) responseid.remove(i);

  }
  	
  responseid.remove(0);
  
  responseid.remove(1);
  
  
   xmlHttp.open(metodo, url, true);
  
  xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  
  //alert(parametros)
  
  xmlHttp.send(parametros);
  
  
  
  xmlHttp.onreadystatechange = function() {
    
	var Op = document.createElement("OPTION");
    
	if(xmlHttp.readyState == 4 && xmlHttp.status == 200){
        
		/*texto = xmlHttp.responseText
		
		alert(texto)*/
		
        //alert(xmlHttp.responseXML.getElementsByTagName('listaregistos').item(0).childNodes.length);
        
		/**/var xmldoc = xmlHttp.responseXML;
		//Aceder a lista de registos
		var regi = xmldoc.getElementsByTagName('listaregistos').item(0);
		
		var contreg = 0;
		
		for (var iNode = 0; iNode < regi.childNodes.length; iNode++) {
			
			contreg = regi.firstChild.data;
			if(contreg > 1)
				contreg = '<b>'+regi.firstChild.data+'</b> registos encontrados';
			if(contreg == 1)
				contreg = '<b>1</b> registo encontrado';
			if(contreg == 0)
				contreg = 'Nenhum registo encontrado.';
			
			setTextDiv(contreg,'navtop'); 
			
			regiin = regi.childNodes.item(iNode);   
			
			var Op = document.createElement("OPTION");
			
			cnt = 0;
					
			for(var re = 0; re < regiin.childNodes.length; re++){
				     
				    var regiin2 = regiin.childNodes.item(re);
					
					
					
					
					for(var re2 = 0; re2 < regiin2.childNodes.length; re2++){
						
							var regiin3 = regiin2.childNodes.item(re2);
						
						if(cnt == 0)
							
							Op.value = unescape(regiin3.data);
							
						else
						
							Op.text += unescape(regiin3.data)+"|";
						
						
					}
					
		      		
					cnt++;
				
					try{
								
						responseid.options.add(Op);
								
					}catch(e){}
					
				
				}
                  
            }/**/
 
	  } else setTextDiv('<img src="imagens/indicator.gif" alt="[-->]" />A carregar...','navtop');
    
	}
  
  }
/******************************
*******************************
*******************************
*AJAX**************************
*******************************
*******************************
*******************************/

/*******************************************
********************************************
********************************************
*Limpar E Re-Escrver os campos do formlogin*
********************************************
********************************************
********************************************/    
  function setText(str,ele){
  	
 	   document.getElementById(ele).value = str;
  
  }
  
   function getText(ele){
  	
 	   return document.getElementById(ele).value;
  
  } 
/*******************************************
********************************************
********************************************
*Limpar E Re-Escrver os campos do formlogin*
********************************************
********************************************
********************************************/ 

/*******************************************
********************************************
********************************************
*Limpar E Re-Escrver os campos do formlogin*
********************************************
********************************************
********************************************/
  function setTextDiv(str,ele){ 
  
	document.getElementById(ele).innerHTML = str;
  
  }
  
  
	function getDiv(ele){
  	
 	   return document.getElementById(ele).innerHTML;
  
  	} 
/*******************************************
********************************************
********************************************
*Limpar E Re-Escrver os campos do formlogin*
********************************************
********************************************
********************************************/ 

/*******************************************
********************************************
*******************************************
*Trim **************************************
********************************************
********************************************
********************************************/

function Trim(str){return str.replace(/^\s+|\s+$/g,"");}

/*******************************************
********************************************
********************************************
*Trim **************************************
********************************************
********************************************
********************************************/

/*******************************************
********************************************
********************************************
*Converter para numérico *******************
********************************************
********************************************
********************************************/
	function Val(String){

		return parseFloat(String);

		}
/*******************************************
********************************************
********************************************
*Converter para numérico *******************
********************************************
********************************************
********************************************/

/*******************************************
********************************************
********************************************
*Previsualizar *****************************
********************************************
********************************************
********************************************/
/*Número na mensagem não funciona!*/
function preVisualizar(autor,numero,estat_nome,texto,titulo,assinatura,registadoa,numposts,avatar){
	
	
	if(!strWordCount(texto," ",50)){
		
		window.alert("O texto não pode conter palavras com mais de 50 caracteres :X");
		
	} else {
	
	var time = new Date();
	
	var data = 
	time.getDate()+'-'
	+(1+time.getMonth())+'-'
	+time.getFullYear()+' '
	+time.getHours()+':'+time.getMinutes()+':'+time.getSeconds();
	
	if(titulo.length==0){
		window.alert('O assunto não foi preenchido.');
	
	}else{
	
	if(texto.length==0){
		window.alert('O corpo da mensagem não foi preenchido.');
		
	}else{
	
	var texto = '<div class="geral"><div class="geraltop"><div class="geraltopleft">'+data+'</div><div class="geraltopright">'+numero+'</div></div><div class="geralbottom"><div class="geralbottomleft"><div class="geralbottomleftitens">'+autor+'</div><div class="geralbottomleftitens">Estado: <img src="imagens/msn2.gif" alt=\"[Online]\" /></div><div class="geralbottomleftitens">Estatuto: '+estat_nome+'</div><div class="geralbottomleftitens" style="height: 148px;overflow: hidden;"><img src="'+avatar+'" alt="[Avatar]"/></div><div class="geralbottomleftitens">Registado a: <br />'+registadoa+'</div><div class="geralbottomleftitens">Nº Posts: '+numposts+'</div></div><div class="head geralbottomright"><img src="imagens/topico.gif" alt="[Tópico]" />Re:<strong>'+titulo+'</strong><hr /></div><div class="postbody geralbottomright">'+texto+'<hr /><div class="assi">'+unescape(assinatura)+'</div></div><div class="footer geralbottomright"></div></div></div><div id="dividirgeral"></div>';
	
	setTextDiv(texto,'preview');
	
	}
	
 }

}
	
}
/*******************************************
********************************************
********************************************
*Previsualizar *****************************
********************************************
********************************************
********************************************/

/*******************************************
********************************************
********************************************
*Checkboxs *********************************
********************************************
********************************************
********************************************/
function canPost(){
	
	flag = 1;
	
	if(document.submitopico.resposta.checked)
		
		flag = 0;

	
	return flag;
	
}
/*******************************************
********************************************
********************************************
*Checkboxs *********************************
********************************************
********************************************
********************************************/

/*******************************************
********************************************
********************************************
*Citar *************************************
********************************************
********************************************
********************************************/
function quote(elem,nick){
	
	try{
		
		var str = 
		'<p>'+Trim(document.submitpost.texto.value)+'<code>Citando <b>'+Trim(getDiv(nick))+'</b></code><br /><i><strong>"'+getDiv(elem)+'"</strong></i></p>';
		
		setText(str,'texto');
		
	}catch(e){
		
		alert("Feche o menu de edição e volte a tentar novamente.");
		
	}
	
}
/*******************************************
********************************************
********************************************
*Citar *************************************
********************************************
********************************************
********************************************/

/*******************************************
********************************************
********************************************
*Novo tópico *******************************
********************************************
********************************************
********************************************/
function novoTopico(area,tooltiptags,tagstoshow,sticky){

ass = "encodeURIComponent( getText('assunto') )";

txt = "encodeURIComponent( getText('texto') )";

tool = drawToolBar('texto', tooltiptags, tagstoshow );
	
texto = '<form name="submitopico"><div class="geral"><div class="geraltop">'+
'<div class="geraltopleftpost\"><img border=\"0\" src=\"imagens/new.png\" alt="[Novo Tópico]"></div>'+
'<div id="geraltopleftpost" style="padding: 0px;border: 0px;margin-top: 2px;\"><div id="cont">'+
'<div class="geraltoppostinle" style=\"height: 18px;\">Assunto</div><div class="float-divider">'+
''+
'<div class="geraltoppostinle">Impedir Resposta? <input type="checkbox" id="resposta" name="resposta" /></div></div></div>'+
'<div id="geraltoppostinri"><input class="assuntostyle forms" id="assunto" type="text" maxlength="80" name="assunto" /></div></div></div>'+
'<div id="geraltop"><div class="imgtool geraltoppostinle" id="statpost" style="text-align:left;margin-left:2px;background-repeat: repeat-x;">'+
tool+'</div>';

if(sticky){

	texto += '<div class="geraltoppostinle" style="text-align:left;margin-left:2px;width:421px;">Sticky? <input type="checkbox" id="sticky" name="sticky" /></div>';

} else {

	texto += '<input type="hidden" value=\"0\" id="sticky" name="sticky" />';
	
}

texto +='<div id="geraltoppostinri" style="width: 421px;"><textarea id="texto" style="font-family: verdana; font-size: 11px; width: 417px;" rows="7" class=\"forms\" name="comentario">'+
'</textarea></div></div><div id="geraltop" style="text-align: center;"><input type="button" class="forms" id="subpost" name="submeter" value="Submeter" onclick="javascript:log(&#39;postar.php&#39;,&#39;POST&#39;,&#39;area='+area+'&assunto=&#39;+'+ass+'+&#39;&texto=&#39;+'+txt+'+&#39;&sticky=&#39;+document.getElementById(&#39;sticky&#39;).checked+&#39;&coment=&#39;+canPost(),true);" /></form>';



 setTextDiv(texto,'topicnew');
}
/*******************************************
********************************************
********************************************
*Novo tópico *******************************
********************************************
********************************************
********************************************/

/*******************************************
********************************************
********************************************
*Novo tópico *******************************
********************************************
********************************************
********************************************/
function getYouURL(id) {
	
	var val = prompt("Endereço do video do youtube:","");
		
	//var myExpReg = new RegExp("^http://(br|www)?(\.)?youtube\.com/watch\?v=(a-zA-Z0-9_-)*$");
	//&& myExpReg.exec(val) != null
		
	if(val != null ){
			
		val = "[youtube]"+val+"[/youtube]";
		
		setText( getText(id)+val , id );
		
	}

}

function drawToolBar( $responseid, $tooltiptags, $tagstoshow )
{
			
	$resposta = "";
	
	$id = "selecttags";
	 
	/*Math.floor(Math.random() * 100) 
	+" "+ Math.floor(Math.random() * 100) 
	+" "+ Math.floor(Math.random() * 100);
	*/

	$resposta = "<img src=\"imagens/dots.png\" alt=\"[:]\" /><select id=\""+$id+"\" onChange=\"javascript:addTextToolBar('"+$id+"','"+$responseid+"');\" class=\"heighttags\"><option>Tags permitidas</option>";

	for ( $i = 0; $i < $tagstoshow.length; $i++ )
	{
		
			$tip = $tooltiptags[$i];

		$resposta += "<option value=\""+$tagstoshow[$i]+"\">" + $tip + "</option>";

	}
	$resposta += "</select>";
	
	$resposta += '<br /><img style="cursor:pointer;" onclick="javascript:getYouURL(\''+$responseid+'\');" border="0" alt="Introduzir video do youtube" title="Introduzir video do youtube" src="imagens/youtube_2.png" />';
	
	return $resposta;
}


/*******************************************
********************************************
********************************************
*Novo tópico *******************************
********************************************
********************************************
********************************************/

/*******************************************
********************************************
********************************************
*Mensagens de confirmação ******************
********************************************
********************************************
********************************************/
function confirmSpam(params,mens,posmens){
if (window.confirm (mens))
{
location.href=params;
window.alert(posmens);
}	
}
/*******************************************
********************************************
********************************************
*Mensagens de confirmação ******************
********************************************
********************************************
********************************************/

/*******************************************
********************************************
********************************************
*Adicionar aos favoritos *******************
********************************************
********************************************
********************************************/
function add_bookmark(){
	var url = document.location;
	//window.external.IsSubscribed(url)
	//if(window.external.IsSubscribed(url)){
	
    var title = document.title;
    if (window.sidebar){ 
	window.sidebar.addPanel(title, url,"");
	}else if(window.opera && window.print){
		var mbm = document.createElement('a');
        mbm.setAttribute('rel','sidebar');
        mbm.setAttribute('href',url);
        mbm.setAttribute('title',title);
        mbm.click();
    }
    else if(document.all){ window.external.AddFavorite(url, title); }
	
	//}
	
}
/*******************************************
********************************************
********************************************
*Adicionar aos favoritos *******************
********************************************
********************************************
********************************************/


