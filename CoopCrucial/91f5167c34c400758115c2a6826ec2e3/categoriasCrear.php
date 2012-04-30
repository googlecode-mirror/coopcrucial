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
        <title>Crear Categoria</title>

        <!--Estilo-->
        <link type="text/css" rel="stylesheet" href="estilo/admin_css.css" />
        <!--Efecto-->
        <script type="text/javascript" src="js/jquery-1.4.4.js"></script>
        <!--Efecto Session-->
        <script type="text/javascript" src="js/efectoSession.js" ></script>
        <!--Editor-->
        <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
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
                    <h1 class="dashboard">Categorias:  Crear categoria</h1>
                    <p></p>
                    <div class="div_header">Form Fields</div>
                    <div class="div_content">
                        <form action="clases/funciones.php" method="post" id="formularioEnvio" enctype="multipart/form-data">
                            <p/>
                            <label>Nombre</label>
                            <input type="text" name="nombre" maxlength="200"  />
                            <label>Titulo Descripcion</label>
                            <input type="text" name="titulo" maxlength="200"  />
                            <label>Descripcion</label>
                            <textarea name="descripcion" cols="100" rows="10" style="width: 550px; height: 100px; max-width: 550px; max-height: 100px; min-width: 550px; min-height: 100px;"></textarea>
                            <label>Tipo</label>
                            <select name="tipoCategoria" style="width: auto;">
                                <option value="">---</option>
                                <option value="Tipo">Tipo</option>
                                <option value="Uso">Uso</option>
                            </select>
                            <label>Imagen  (Debe tener un tamaño de  280 x 200 px)</label>
                            <input type="file" name="imagen" />
                            <label>Oferta (%)</label>
                            <input type="text" name="oferta" class="other" maxlength="2" style="width: 50px;" />
                            <input type="hidden" name="opcion" value="6" />
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
                            <li><a href="categorias.php">Consultar Categorias</a></li>
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