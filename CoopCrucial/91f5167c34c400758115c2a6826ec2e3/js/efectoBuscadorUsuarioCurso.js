$(document).ready(function(){

	$("#BtnBuscarCurso").click(function(){
	
		//alert("urso " + $("#curso").val())
	
		$.ajax({
			type: "POST",
			url: "clases/funciones.php",
			data: "opcion=20&idCurso="+ $("#curso").val(),
			success: function(msg){
					//alert(msg)
					
					$("#ContenedorResultadosCursosUsuarios").html("");
					$("#ContenedorResultadosCursosUsuarios").html(msg);
				
			}
		});
		
	});
	
	
});