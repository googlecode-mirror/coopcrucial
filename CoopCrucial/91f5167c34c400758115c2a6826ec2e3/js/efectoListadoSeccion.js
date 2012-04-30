$(document).ready(function(){
    //Eliminar item de la base de datos
    eliminarItemBaseDatos();
	
});

//Eliminar item de la base de datos
function eliminarItemBaseDatos(){
    $(".btnEliminarElementoLista").click(function(event){
        event.preventDefault();
        var tabla = $(this).attr("href");
        var idElemento = $(this).attr("title");
        var nameId = $(this).attr("name");
        var carpetaImagenBorrar = $("#carpetaImagenBorrar").val();
		
        if(confirm("Esta seguro?")){
            $.ajax({
                type: "POST",
                url: "clases/funciones.php",
                data: "opcion=5&tabla="+ tabla + "&id=" + idElemento + "&carpetaImagenBorrar=" + carpetaImagenBorrar+"&nameId="+nameId,
                success: function(msg){

                    //alert(msg)
                    switch(tabla){
                        case "categoria":
                            document.location.href="categorias.php";
                            break;
                        case "producto":
                            document.location.href="productos.php";
                            break;
                    }
                }
            });
        }
    });
}