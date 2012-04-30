<?php require_once("clases/funciones.php"); ?>
<?php
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
        <title>Modificar Producto</title>

        <!--Estilo-->
        <link type="text/css" rel="stylesheet" href="estilo/admin_css.css" />
        <!--Efecto-->
        <script type="text/javascript" src="js/jquery-1.4.4.js"></script>
        <!--Efecto Session-->
        <script type="text/javascript" src="js/efectoSession.js" ></script>
        <!--Editor-->
        <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
        <!--Efecto Yox-->
        <script type="text/javascript" src="js/yoxview/yoxview-init.js" ></script>
        <script type="text/javascript" src="js/efectoYox.js"></script>



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
                    <ul>
                    </ul>

                </div>

                <div id="left">

                    <!-- begin all content here -->
                    <h1 class="dashboard">Productos:  Modificar producto</h1>
                    <p></p>
                    <div class="div_header">Form Fields</div>
                    <div class="div_content">
                        <form action="clases/funciones.php" method="post" id="formularioEnvio" enctype="multipart/form-data">
                            <label>Nombre</label>
                            <input type="text" name="nombre" maxlength="200" value="<?php echo $dato->obtenerEspecificoProducto($_GET['id'], "nombre"); ?>"  />
                            <label>Descripcion</label>
                            <textarea id="descripcionCorta" name="descripcion" cols="40" rows="10"><?php echo $dato->obtenerEspecificoProducto($_GET['id'], "descripcion"); ?></textarea>
                            <label>Garantia</label>
                            <input type="text" name="garantia" class="other" maxlength="20" value="<?php echo $dato->obtenerEspecificoProducto($_GET['id'], "garantia"); ?>"  />
                            <label>Marca</label>
                            <input type="text" name="marca" class="other" maxlength="30" value="<?php echo $dato->obtenerEspecificoProducto($_GET['id'], "marca"); ?>" />
                            <label>Precio</label>
                            <input type="text" name="precio" class="other" maxlength="15" value="<?php echo $dato->obtenerEspecificoProducto($_GET['id'], "precio"); ?>" />
                            <label>Precio Web</label>
                            <input type="text" name="precioWeb" class="other" maxlength="15" value="<?php echo $dato->obtenerEspecificoProducto($_GET['id'], "precioWeb"); ?>" />
                            <label>Tags</label>
                            <input type="text" name="tags" class="other" maxlength="50" value="<?php echo $dato->obtenerEspecificoProducto($_GET['id'], "tags"); ?>" />
                            <label>Destacado&nbsp;<input type="checkbox" name="destacado" class="other" value="1" <?php if($dato->obtenerEspecificoProducto($_GET['id'], "destacado")==1) echo"checked"; ?>/></label>
                            <label>Recomendado&nbsp;<input type="checkbox" name="recomendado" class="other" value="1" <?php if($dato->obtenerEspecificoProducto($_GET['id'], "recomendaciones")==1) echo"checked"; ?> /></label>
                            <label>Categoria</label>
                            <select name="idCategoria" style="width: auto;">
                                <option value="">---</option>
                                <?php $dato->obtenerListadoCategoriasProducto(); ?>
                            </select>
                            <label onclick="javascript:$('#divImagenes').slideToggle('slow');" style="cursor: pointer;"><strong>IMAGENES</strong> (Deben tener un tamaño de 280 x 200 px)</label>
                            <div id="divImagenes" style="display: none;">
                                <?php $dato->obtenerListadoImagenesProducto($_GET['id']); ?>
                                <a href="javascript:void(0);" onclick="agregarImagen();">Agregar Imagen</a>
                            </div>
                            <label onclick="javascript:$('#divCaracteristicas').slideToggle('slow');" style="cursor: pointer; margin-top: 15px;"><strong>CARACTERISTICAS</strong></label>
                            <div id="divCaracteristicas" style="display: none;">
                                <label>Caracteristica 1</label>
                                <input type="text" name="caracteristicas[]" class="other"/>
                                <a href="javascript:void(0);" onclick="agregarCaracteristica();">Agregar Caracter&iacute;tica</a>
                            </div>
                            <label onclick="javascript:$('#divEspecificaciones').slideToggle('slow');" style="cursor: pointer; margin-top: 15px;"><strong>ESPECIFICACIONES</strong></label>
                            <div id="divEspecificaciones" style="display: none;">
                                <fieldset>
                                    <legend>Especificacion 1</legend>
                                    <label>Titulo</label>
                                    <input type="text" name="tituloEspecificacion[]" class="other"/>
                                    <label>Descripcion</label>
                                    <input type="text" name="descripcionEspecificacion[]" class="other"/>
                                </fieldset><br/>
                                <a href="javascript:void(0);" onclick="agregarEspecificacion();">Agregar Especificaci&oacute;n</a>
                            </div>
<!--                            <label>Imagen  (Debe tener un tamaño de  280 x 200 px)</label>
                            <input type="file" name="imagen" />

                            <div class="yoxview"><a class='yoxviewLink' href="recursos/categoria/<?php echo $_GET['id']; ?>/<?php echo $dato->obtenerEspecificoCategoria($_GET['id'], "imagen"); ?>" title="<?php echo $dato->obtenerEspecificoCategoria($_GET['id'], "imagen"); ?>"><?php echo $dato->obtenerEspecificoCategoria($_GET['id'], "imagen"); ?></a>
                            </div>-->
                            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
                            <input type="hidden" name="opcion" value="9" />
                            <p><input type="submit" class="submit" value="Guardar"/></p>
                                   <!-- <p style="clear: both;">
                            </p><h2>Eliminar este elemento</h2>

                            <input type="button" class="submit" value="Click para eliminar" onclick="delete_item('1', '1')">
                            <p></p>-->
                        </form>

                    </div>
                    <div class="div_bottom"></div>

                    <br class="clear"/>


                </div>

                <!-- Menu navegacion derecho -->
                <!--<div id="sidebar">
                        <div class="sidebar_header"></div>
        <div class="sidebar_content">
            <ul>
                        <li><a href="crearSubSeccion.php">Nueva sub sección</a></li>

                    </ul>
            </div>

                    </div>-->
                <!-- Fin Menu navegacion derecho -->



            </div>

            <div id="footer">
                <!-- edit footer items here -->
                <div id="copyright">Copyright © 2012 </div>
                <div id="footer_links">

                </div>
            </div>

        </div>



    </body>
</html>