$(document).ready(function(){

	$(".btnEliminarImagen").click(function(event){
	
		event.preventDefault();
		
		var ruta = $(this).attr("href");
		var imagen = $(this).attr("title");
		
	
		$.ajax({
			type: "POST",
			url: "clases/funciones.php",
			data: "opcion=43&ruta=" + ruta + "&imagen=" + imagen,
			success: function(msg){
			
				document.location.reload();

			}
		});				

		
	});
	
	//Eliminar imagen subSeccion
	$("#btnEliminarImagen").click(function(event){
	
		event.preventDefault();
		
		var ruta = $(this).attr("href");
		
		
	
		$.ajax({
			type: "POST",
			url: "clases/funciones.php",
			data: "opcion=44&ruta=" + ruta,
			success: function(msg){
			
				document.location.reload();

			}
		});				

		
	});
	
	//Eliminar banner ofertas
	$("#btnEliminarBannerOfertas").click(function(event){
	
		event.preventDefault();
		
		var ruta = $(this).attr("href");
		
		
	
		$.ajax({
			type: "POST",
			url: "clases/funciones.php",
			data: "opcion=45&ruta=" + ruta,
			success: function(msg){
			
				document.location.reload();

			}
		});				

		
	});
	
	//Eliminar banner promociones
	$("#btnEliminarBannerPromociones").click(function(event){
	
		event.preventDefault();
		
		var ruta = $(this).attr("href");
		
		
	
		$.ajax({
			type: "POST",
			url: "clases/funciones.php",
			data: "opcion=46&ruta=" + ruta,
			success: function(msg){
			
				document.location.reload();

			}
		});				

		
	});
	
	
	
	
	
	
});