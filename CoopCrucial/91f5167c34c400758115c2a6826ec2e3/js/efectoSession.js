$(document).ready(function(){
					   
	clickIngreso();
	
	cerrarSession();
	
	modificarDatosUsuario();
	
	visitarSitioWeb();
	
});
//Click ingreso administrador
function clickIngreso()
{
	$("#ingresar").click(function(event){
		event.preventDefault();
		
		$("#cargador").css("display","inline");
		
		var usuario = $("#usuario").val();
		var password = $("#password").val();
		
		$.ajax({
  			type: "POST",
   			url: "clases/funciones.php",
   			data: "opcion=1&usuario="+ usuario + "&password="+ password,
   			success: function(msg){
			
			if(msg != "No")
			{
				//alert( msg );
				//iniciarSession(usuario);
				$.ajax({
					type: "POST",
					url: "clases/funciones.php",
					data: "opcion=2&usuario="+ usuario,
					success: function(msg){
		
						document.location.href="inicio.php";
					}
				});
				
				
			}
    		
			$("#cargador").css("display","none");
  			}
 		});

	});
}
//Iniciar session usuario
function iniciarSession(usuario)
{
		$.ajax({
  			type: "POST",
   			url: "clases/funciones.php",
   			data: "opcion=2&usuario="+ usuario,
   			success: function(msg){


  			}
 		});
}

//Cerrar session usuario
function cerrarSession()
{
	
	$("#btnLogOut").click(function(event){
	
		event.preventDefault();
	
	
		$.ajax({
  			type: "POST",
   			url: "clases/funciones.php",
   			data: "opcion=3",
   			success: function(msg){
				
				document.location.href ="index.php";


  			}
 		});
		
		
	});
}


function modificarDatosUsuario()
{
	$("#modificarUsuario").click(function(event){
		event.preventDefault();

		$("#cargador").css("display","inline");
		var usuario = $("#usuario").val();
		var password = $("#password").val();
		$.ajax({
			type:"POST",
			url: "clases/funciones.php",
			data: "opcion=4&usuario=" + $("#usuario").val() +"&password="+ $("#password").val(),
			success: function(msg){
				$("#cargador").css("display","none");
				if(msg != "no")
				{
					alert(msg);
				}
				else
				{
					alert("No se han podido modificar tus datos");
				}
			}
		});
		
		
	});
}

function visitarSitioWeb()
{
	$("#btnVerSitioWeb").click(function(event){
		
		event.preventDefault();
		
		window.open("http://nabica.com.co/clientes/daimlerel/")
	});
}
