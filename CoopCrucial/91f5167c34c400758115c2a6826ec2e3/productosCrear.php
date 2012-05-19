<?php
require_once("clases/funciones.php");
//Objeto
$dato = new funciones();
$dato->obtenerSessionUsuario();
$dato->autentificarSessionUsuario();
//include_once("FCKeditor/fckeditor.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Crear Producto</title>
        <!--Estilo-->
        <link type="text/css" rel="stylesheet" href="estilo/admin_css.css" />
        <style type="text/css" >
            fieldset{width: 160px;};
        </style>
        <!--Efecto-->
        <script type="text/javascript" src="js/jquery-1.4.4.js"></script>
        <!--Efecto Session-->
        <script type="text/javascript" src="js/efectoSession.js" ></script>
        <!--Editor-->
        <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
        <script type="text/javascript">
            var contImagenes = 2;
            var contCaracteristicas = 2;
            var contEspecificaciones = 2;
            function agregarImagen(){
                if(contImagenes>1 && contImagenes<9){
                    $("#divImagenes").append("<label>Imagen "+contImagenes+"</label><input type='file' name='imagenes[]' />");
                    contImagenes++;
                }
                else
                    alert("No puede agregar mas de 8 imágenes.");
            }
            function agregarCaracteristica(){
                $("#divCaracteristicas").append("<fieldset>"+
                    "<legend>Caracteristica "+contCaracteristicas+"</legend>"+
                    "<label>Nombre</label>"+
                    "<input type='text' name='nombreCaracteristica[]' class='other'/>"+
                    "<label>Descripcion</label>"+
                    "<textarea name='descripcionCaracteristica[]'  cols='100' rows='10'  class='other' style='width: 550px; height: 100px; max-width: 550px; max-height: 100px; min-width: 550px; min-height: 100px;'></textarea>"+
                    "</fieldset><br/>");
                contCaracteristicas++;
            }
            function agregarEspecificacion(){
                $("#divEspecificaciones").append("<fieldset>"+
                    "<legend>Especificacion "+contEspecificaciones+"</legend>"+
                    "<label>Titulo</label>"+
                    "<input type='text' name='tituloEspecificacion[]' class='other'/>"+
                    "<label>Descripcion</label>"+
                    "<textarea name='descripcionEspecificacion[]'  cols='100' rows='10'  class='other' style='width: 550px; height: 100px; max-width: 550px; max-height: 100px; min-width: 550px; min-height: 100px;'></textarea>"+
                    "</fieldset><br/>");
            }
            function agregarUso(){
                if($("#usosSelect").val()=="")
                    alert("Seleccione una catedoria de uso.");
                else{
                    $.ajax({
                        type: "POST",
                        url: "clases/funciones.php",
                        data: "opcion=16&idUso="+$("#usosSelect").val(),
                        success: function(data){
                            document.getElementById("divUsos").innerHTML=data;
                        }
                    });
                }
            }
        </script>
    </head>
    <body>
        <!-- top lines for style -->
        <div id="top_green"></div>
        <div id="top_dark"></div>
        <div id="wrapper">
            <div id="header">
                <div id="logo"></div>
                <div id="user_links">
                    <a id="btnVerSitioWeb" href="#" target="_blank">Ver sitio web</a>&nbsp;|
                    <a id="btnLogOut" href="#">Logout</a>
                </div>
            </div>
            <!--Menu Navegacion-->
<?php include_once('menuNavegacion.php'); ?>
            <!--Fin Menu Navegacion-->
            <div id="content">
                <!-- edit sub navigation and quick links here -->
                <div id="sub_nav">
                    <ul></ul>
                </div>
                <div id="left">
                    <!-- begin all content here -->
                    <h1 class="dashboard">Productos:  Crear producto</h1>
                    <p></p>
                    <div class="div_header">Form Fields</div>
                    <div class="div_content">
                        <form action="clases/funciones.php" method="post" id="formularioEnvio" enctype="multipart/form-data">
                            <label>Nombre</label>
                            <input type="text" name="nombre" maxlength="200"  />
                            <label>Descripcion</label>
                            <textarea id="descripcionCorta" name="descripcion" cols="40" rows="10"></textarea>
                            <label>Garantia</label>
                            <input type="text" name="garantia" class="other" maxlength="20"  />
                            <label>Marca</label>
                            <input type="text" name="marca" class="other" maxlength="30"  />
                            <label>Precio</label>
                            <input type="text" name="precio" class="other" maxlength="15"  />
                            <label>Precio Web</label>
                            <input type="text" name="precioWeb" class="other" maxlength="15"  />
                            <label>Tags</label>
                            <input type="text" name="tags" class="other" maxlength="50"  />
                            <label>Destacado&nbsp;<input type="checkbox" name="destacado" class="other" value="1" /></label>
                            <label>Recomendado&nbsp;<input type="checkbox" name="recomendado" class="other" value="1" /></label>
                            <label>Categoria</label>
                            <select name="idCategoria" style="width: auto;">
                                <option value="">---</option>
<?php $dato->obtenerListadoCategoriasProducto(); ?>
                            </select>
                            <label>Usos</label>
                            <select id="usosSelect" name="idUso[]" style="width: auto;">
                                <option value="">---</option>
<?php $dato->obtenerListadoUsosProducto(); ?>
                            </select><a href="javascript:void(0);" onclick="agregarUso();">Agregar</a>
                            <div id="divUsos" style="width: 150px; height: 100px; border: 1px solid #000;"></div>
                            <label onclick="javascript:$('#divImagenes').slideToggle('slow');" style="cursor: pointer;"><strong>IMAGENES</strong> (Deben tener un tamaño de 280 x 200 px)</label>
                            <div id="divImagenes" style="display: none;">
                                <label>Imagen 1</label>
                                <input type="file" name="imagenes[]" />
                                <a href="javascript:void(0);" onclick="agregarImagen();">Agregar Imagen</a>
                            </div>
                            <label onclick="javascript:$('#divCaracteristicas').slideToggle('slow');" style="cursor: pointer; margin-top: 15px;"><strong>CARACTERISTICAS</strong></label>
                            <div id="divCaracteristicas" style="display: none;">
                                <fieldset>
                                    <legend>Caracteristica 1</legend>
                                    <label>Nombre</label>
                                    <input type="text" name="nombreCaracteristica[]" class="other"/>
                                    <label>Descripcion</label>
                                    <textarea name="descripcionCaracteristica[]" cols="100" rows="10" style="width: 550px; height: 100px; max-width: 550px; max-height: 100px; min-width: 550px; min-height: 100px;"></textarea>
                                </fieldset><br/>
                                <a href="javascript:void(0);" onclick="agregarCaracteristica();">Agregar Caracter&iacute;stica</a>
                            </div>
                            <label onclick="javascript:$('#divEspecificaciones').slideToggle('slow');" style="cursor: pointer; margin-top: 15px;"><strong>ESPECIFICACIONES</strong></label>
                            <div id="divEspecificaciones" style="display: none;">
                                <fieldset>
                                    <legend>Especificacion 1</legend>
                                    <label>Titulo</label>
                                    <input type="text" name="tituloEspecificacion[]" class="other"/>
                                    <label>Descripcion</label>
                                    <textarea name="descripcionEspecificacion[]" cols="100" rows="10" style="width: 550px; height: 100px; max-width: 550px; max-height: 100px; min-width: 550px; min-height: 100px;"></textarea>
                                </fieldset><br/>
                                <a href="javascript:void(0);" onclick="agregarEspecificacion();">Agregar Especificaci&oacute;n</a>
                            </div>
                            <input type="hidden" name="opcion" value="8" />
                            <p><input type="submit" class="submit" value="Guardar"/></p>
                        </form>

                    </div>
                    <div class="div_bottom"></div>

                    <br class="clear"/>

                </div>

                <!-- Menu navegacion derecho -->
                <div id="sidebar">
                    <div class="sidebar_header"></div>
                    <div class="sidebar_content">
                        <ul>
                            <li><a href="productos.php">Consultar productos</a></li>
                        </ul>
                    </div>
                </div>
                <!-- Fin Menu navegacion derecho -->
            </div>
            <!--Menu Footer-->
<?php include_once('footer.php'); ?>
            <!--Fin Menu Footer-->
        </div>
    </body>
</html>