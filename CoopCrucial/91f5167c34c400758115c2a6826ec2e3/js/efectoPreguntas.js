$(document).ready(function(){

	$("#BtnAgregarPregunta").click(function(event){
	
		event.preventDefault();

		
		popUpInformativo("popUp")
		
		
	});
	
	$(".BtnModificarPregunta").click(function(event){
	
		event.preventDefault();
		
		var idPregunta = $(this).attr("href");

		$("#framePreguntas").attr({
			src: "cursosPreguntasModificar.php?id="	+ idPregunta
		});
		
		popUpInformativo("popUp2")
		
		
	});
	
	$(".BtnModificarRespuesta").click(function(event){
	
		event.preventDefault();
		
		var idRespuesta = $(this).attr("href");

		$("#framePreguntas").attr({
			src: "cursosRespuestasModificar.php?id="	+ idRespuesta
		});
		
		popUpInformativo("popUp2")
		
		
	});
	
	$(".BtnModificarSlide").click(function(event){
	
		event.preventDefault();
		
		var idRespuesta = $(this).attr("href");

		$("#framePreguntas").attr({
			src: "cursosSlidesModificar.php?id="	+ idRespuesta
		});
		
		popUpInformativo("popUp2")
		
		
	});
	
	

});



	//Pop up Informativo 
	function popUpInformativo(nombrePopUp)
	{
				
		//Consigue valores de la ventana del navegador    
		var w = $(window).width();    
		var h = $(window).height();
		
		//Fondo Transparente
		$("#fondoTransparentePopUp").css("width",w +"px"); 
		$("#fondoTransparentePopUp").css("height",h +"px"); 
		
		$("#fondoTransparentePopUp").css("left",0 + "px"); 
		$("#fondoTransparentePopUp").css("top",0 + "px");
		
		$("#fondoTransparentePopUp").fadeIn('slow');
		
		//Consigue valores de la ventana del navegador   
		var anchoPopUp = $("#" + nombrePopUp).width();
		var altoPopUp = $("#" + nombrePopUp).height();
		
		 //Centra el popup       
		 w = (w/2) - (anchoPopUp /2);    
		 h = (h/2) - (altoPopUp/2);    
		 $("#" + nombrePopUp).css("left",w + "px");    
		 $("#" + nombrePopUp).css("top",h + "px");
		 
		 $("#" + nombrePopUp).fadeIn('slow'); 
		 
	
		//alert("Ancho: " + w + "Alto: " + h);
	}
	
	//Cerrar popup respuestas preguntas concurso
	function cerrarPopUpRespuestasPreguntasConcurso(valor)
	{
	
		if(valor = "popUp2")
		{
			document.location.reload();
		}
		else
		{
			$("#"+valor).hide();
			$("#fondoTransparentePopUp").hide();			
		}


	}