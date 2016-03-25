		$(document).ready(function(){
			
					$(".itemsubmenu").hover(
						function (){
							
        					this.style.backgroundColor = 'white';
        					this.style.color = '#BC2A6E';
        					
      					}, function () {
      						
							this.style.backgroundColor = 'transparent';
        					this.style.color = '#0033CC';
        					
        					
      					})
      					
      				$(".tabb").hover(
						function (){
							
        					this.style.color = '#BC2A6E';
        					this.style.backgroundImage = 'URL("imagens/tabbb.jpg")';
        					
      					}, function () {
      						
        					this.style.color = '#0033CC';
        					this.style.backgroundImage = 'URL("imagens/tabb.jpg")';
        					
      					})
      				
					
					$(".nextstep").hover(
						function (){
							
        					this.style.color = '#BC2A6E';
        					this.style.border = '1px solid #BC2A6E';
        					
      					}, function () {
      						
        					this.style.color = 'black';
        					this.style.border = '1px solid black';
        					
      					})	
      				
			
			})
		
		function changeLang(){
			
			location.href = document.getElementById("selectlang").options[document.getElementById("selectlang").selectedIndex].value;
			
		}