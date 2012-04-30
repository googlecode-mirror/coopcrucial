function registrarUsuario(){
    location.href = "usuarios.php?accion=2";
}

function verPerfil(){
    location.href = "usuarios.php?accion=9";
}

function recordar(){
    location.href = "usuarios.php?accion=8";
}

function validarDocumento(){
    if($("#numeroDocumento").val()!=""){
        $.ajax({
            type: "POST",
            url: "usuarios.php",
            data: "accion=6&doc="+$("#numeroDocumento").val()
        }).done(function( msg ) {
            if(msg==0){
                $("#avisoDoc").css("display","");
                $("#numeroDocumento").attr("value","");
            }
            else if(msg==1)
                $("#avisoDoc").css("display","none");
            else if(msg==2)
                alert("El documento no es un numero. Verifiquelo");
        });
    }
}

function validarUsuario(){
    if($("#usuario").val()!=""){
        $.ajax({
            type: "POST",
            url: "usuarios.php",
            data: "accion=7&usuario="+$("#usuario").val()
        }).done(function( msg ) {
            if(msg==0){
                $("#avisoUsuario").css("display","");
                $("#usuario").attr("value","");
            }
            else if(msg==1)
                $("#avisoUsuario").css("display","none");
        });
    }
}

function consultarCiudades(){
    $.ajax({
        type: "POST",
        url: "usuarios.php",
        data: "accion=4&idPais="+$("#n_pais").val()
    }).done(function( msg ) {
        document.getElementById("divCiudad").innerHTML=msg;
    });
}

function iniciarSesion(){
    if($("#nombreUsuario").val()=="")
        alert("Ingrese el nombre de usuario.");
    else if($("#contrasenia").val()=="")
        alert("Ingrese la contraseña.");
    else{
        var datos = $("#frm_sesion").serialize();
        location.href = "usuarios.php?accion=0&"+datos;
    }
}

function cerrarSesion(){
    location.href = "usuarios.php?accion=1";
}

function cargarProducto(idProducto){
    location.href = "productos.php?accion=1&idProducto="+idProducto;
}

function cargarImagen(idImagenProducto){
    $.ajax({
        type: "POST",
        url: "productos.php",
        data: "accion=2&idImagenProducto="+idImagenProducto
    }).done(function( msg ) {
        document.getElementById("divImagenAmpliada").innerHTML=msg;
    });
}

function buscar(){
    var palabraClave = document.getElementById("buscador").value;
    if(palabraClave == "")
        alert("Debe ingresar alguna palabra clave que desee buscar.")
    else
        location.href = "productos.php?accion=3&palabraClave="+palabraClave;
}

function buscarPorCategorias(categoria){
    location.href = "productos.php?accion=4&idCat="+categoria;
}

function buscarPorPrecios(key){
    location.href = "productos.php?accion=4&precio="+key;
}

function buscarPorMarcas(marca){
    location.href = "productos.php?accion=4&marca="+marca;
}

function buscarPorCalificaciones(key){
    location.href = "productos.php?accion=4&calificacion="+key;
}

function agregarCarritoCantidad(idProducto){
    if($("#cantidad").val()=="")
        agregarCarrito(idProducto, 1);
    else
        agregarCarrito(idProducto, $("#cantidad").val());
}

function agregarCarrito(idProducto, cantidad){
    location.href = "productos.php?accion=5&id="+idProducto+"&cant="+cantidad
}

function cargarCarrito(paso){
    if(paso==1){
        location.href = "productos.php?accion=7&paso="+paso;
    }
    else if(paso==2){
        var cant = document.getElementsByName("cantidades[]");
        var ventas = document.getElementsByName("idsVentas[]");
        var arrayCant = "";
        var arrayVentas = "";
        for (i = 0; i < cant.length; i++) {
            if(i==(cant.length-1)){
                arrayCant += cant[i].value;
                arrayVentas += ventas[i].value;
            }
            else{
                arrayCant += cant[i].value+",";
                arrayVentas += ventas[i].value+",";
            }
        }
        location.href = "productos.php?accion=7&paso="+paso+"&cantidades="+arrayCant+"&ids="+arrayVentas;
    }
    else if(paso==3){
        if($("#idDireccion").val()=="")
            alert("Debe seleccionar una direccion.");
        else
            location.href = "productos.php?accion=7&paso="+paso+"&idDireccion="+$("#idDireccion").val();
    }
    else if(paso==4){
        location.href = "productos.php?accion=9";
    }
}

function cargarDireccion(idDireccion){
    $("#idDireccion").attr("value", idDireccion);
}

function divCarrito(){
    $("#divCarrito").toggle();
}

function agregarComentario(idProducto){
    $.ajax({
        type: "POST",
        url: "productos.php",
        data: "accion=6&idProducto="+idProducto+"&comentario="+$("#comentario").val()
    }).done(function(msg) {
        alert(msg);
        location.reload();
    });
}

function mostrarComentarios(){
    $("#divEspecificaciones").css("display","none");
    $("#divCaracteristicas").css("display","none");
    $("#divComentarios").css("display","");
}

function mostrarEspecificaciones(){
    $("#divCaracteristicas").css("display","none");
    $("#divComentarios").css("display","none");
    $("#divEspecificaciones").css("display","");
}

function mostrarCaracteristicas(){
    $("#divEspecificaciones").css("display","none");
    $("#divComentarios").css("display","none");
    $("#divCaracteristicas").css("display","");
}

function guardarUsuario(){
    if($("#nombre").val()=="")
        alert("Ingrese el nombre.");
    else if($("#apellidos").val()=="")
        alert("Ingrese el o los apellidos.");
    else if($("#tipoDocumento").val()=="")
        alert("Seleccione un tipo de documento.");
    else if($("#numeroDocumento").val()=="")
        alert("Ingrese el número de documento.");//TODO Validar que sea unico
    else if($("#datepicker").val()=="")
        alert("Seleccione su fecha de nacimiento.");
    else if($("#correo").val()=="")
        alert("Ingrese el correo electrónico.");
    else if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test($("#correo").val())))
        alert("Este no es un correo valido.");
    else if($("#correo1").val()=="")
        alert("Ingrese la confirmación del correo electrónico.");
    else if($("#correo").val()!=$("#correo1").val())
        alert("No coinciden los correos electrónicos.");
    else if($("#cel").val()=="")
        alert("Ingrese el numero celular.");
    else if($("#telefono").val()=="")
        alert("Ingrese el teléfono.");
    else if($("#pais").val()=="")
        alert("Seleccione un pais.");
    else if($("#ciudad").val()=="")
        alert("Seleccione una ciudad.");
    else if($("#direccion").val()=="")
        alert("Ingrese la dirección.");
    else if($("#usuario").val()=="")
        alert("Ingrese el nombre de usuario.");//TODO Validar que sea unico
    else if($("#pwd").val()=="")
        alert("Ingrese la contraseña.");
    else if($("#pwd1").val()=="")
        alert("Ingrese la confirmación de la contraseña.");
    else if($("#pwd").val()!=$("#pwd1").val())
        alert("No coinciden las contraseñas.");
    else{
        var datos = $("#frm_registro").serialize().split("&");
        var terminos = datos[19].split("=");
        if(terminos[1]!=1)
            alert("Para guardar, debe aceptar los términos y condiciones.");
        else{
            $.ajax({
                type: "POST",
                url: "usuarios.php",
                data: "accion=3&"+$("#frm_registro").serialize()
            }).done(function(msg) {
                document.getElementById("divRegistro").innerHTML=msg;
            });
        }
    }
}

function opciones(op){
    if(op==1){
        $("#divPuerta").css("display","none");
        $("#divSede").css("display","");
    }
    else if(op==2){
        $("#divSede").css("display","none");
        $("#divPuerta").css("display","");
    }
}

function nuevaDireccion(){
    $("#divDireccionPredeterminada").toggle();
    $("#divNuevaDireccion").toggle();
}

function guardarDireccion(){
    if($("#telefono").val()=="")
        alert("Ingrese el telefono.");
    else if($("#pais").val()=="")
        alert("Seleccione un pais.");
    else if($("#ciudad").val()=="")
        alert("Seleccione una ciudad.");
    else if($("#direccion").val()=="")
        alert("Ingrese la direccion.");
    else{
        $.ajax({
            type: "POST",
            url: "usuarios.php",
            data: "accion=5&"+$("#frm_direccion").serialize()
        }).done(function(msg) {
            alert(msg);
            location.reload();
        });
    }
}

function cargarDireccionDiv(idDireccion){
    $.ajax({
        type: "POST",
        url: "usuarios.php",
        data: "accion=11&idDireccion="+idDireccion
    }).done(function(msg) {
        document.getElementById("divDireccionPredeterminada").innerHTML=msg;
        $("#divNuevaDireccion").css("display","none");
        $("#divDireccionPredeterminada").css("display","");
    });
}

function eliminarDireccion(idDireccion){
    if(confirm("Esta seguro que desea eliminar esta direccion?")){
        $.ajax({
            type: "POST",
            url: "usuarios.php",
            data: "accion=12&idDireccion="+idDireccion
        }).done(function(msg) {
            alert(msg);
            location.reload();
        });
    }
}

function editarDireccion(idDireccion){
    $.ajax({
        type: "POST",
        url: "usuarios.php",
        data: "accion=13&idDireccion="+idDireccion
    }).done(function(msg) {
        document.getElementById("divNuevaDireccion").innerHTML=msg;
        $("#divDireccionPredeterminada").css("display","none");
        $("#divNuevaDireccion").css("display","");
    });
}

function eliminarRegistro(idProducto){
    $.ajax({
        type: "POST",
        url: "productos.php",
        data: "accion=8&idProducto="+idProducto
    }).done(function(msg) {
        alert(msg);
        location.href = "productos.php?accion=7&paso=1";
    });
}

function imprimir(div){
    var ficha=document.getElementById(div);
    var ventimp=window.open(' ','popimpr');
    ventimp.document.write(ficha.innerHTML);
    ventimp.document.close();
    ventimp.print();
    ventimp.close();
}

function cargarCategorias(categoria){
    if ($('#divCategorias').is(':hidden') || $('#divUsos').is(':hidden')){
        $.ajax({
            type: "POST",
            url: "productos.php",
            data: "accion=10&cat="+categoria
        }).done(function(msg) {
            if(categoria == 1){
                document.getElementById("divCategorias").innerHTML=msg;
                if ($('#divCategorias').is(':hidden')){
                    $("#divUsos").css("display","none");
                    $("#divCategorias").css("display","");
                } else{
                    $("#divUsos").css("display","none");
                    $("#divCategorias").css("display","none");
                }
            }
            else if(categoria == 2){
                document.getElementById("divUsos").innerHTML=msg;
                if ($('#divUsos').is(':hidden')){
                    $("#divCategorias").css("display","none");
                    $("#divUsos").css("display","");
                }else{
                    $("#divCategorias").css("display","none");
                    $("#divUsos").css("display","none");
                }
            }
        });
    }
    else
        $("#divCategorias").css("display","none");
}

function toolTip(op, idCategoria){
    if(op==1)
        $("#tooltip"+idCategoria).css("display","");
    else if(op==2)
        $("#tooltip"+idCategoria).css("display","none");
}

function editar(campo){
    $("#atr"+campo).toggle("slow");
}

function Productos(categoria, id){
    if(categoria == "categoria" || categoria == "uso")
        location.href = "productos.php?accion=11&cat="+categoria+"&id="+id;
}

function editarCampo(nombre, id){
    if($("#n_"+nombre).val() == "")
        alert("El campo "+nombre+" esta vacio.");
    else if($("#n_"+nombre).val() == "pais"){
        if($("#n_ciudad").val() == "")
            alert("Seleccione la ciudad.");
        else if($("#n_ciudad").val() != "")
            location.href = "usuarios.php?accion=10&campo=ciudad&valor="+$("#n_ciudad").val()+"&id="+id;
    }
    else
        location.href = "usuarios.php?accion=10&campo="+nombre+"&valor="+$("#n_"+nombre).val()+"&id="+id;
}

function divsPerfil(div){
    switch (div) {
        case 1:
            $("#divDirecciones").css("display","none");
            $("#divHistorial").css("display","none");
            $("#divPerfil").css("display","");
            break;
        case 2:
            $("#divPerfil").css("display","none");
            $("#divHistorial").css("display","none");
            $("#divDirecciones").css("display","");
            break;
        case 3:
            $("#divPerfil").css("display","none");
            $("#divDirecciones").css("display","none");
            $("#divHistorial").css("display","");
            break;
        default:
            break;
    }

}