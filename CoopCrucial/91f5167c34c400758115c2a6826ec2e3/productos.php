<?php require_once("clases/funciones.php"); ?>
<?php
//Objeto
$dato = new funciones();
$dato->obtenerSessionUsuario();
$dato->autentificarSessionUsuario();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Productos</title>

        <!--Estilo-->
        <link type="text/css" rel="stylesheet" href="estilo/admin_css.css" />
        <!--Efecto-->
        <script type="text/javascript" src="js/jquery-1.4.4.js"></script>
        <!--Efecto Session-->
        <script type="text/javascript" src="js/efectoSession.js" ></script>
        <!--Efecto Lstado seccion-->
        <script type="text/javascript" src="js/efectoListadoSeccion.js" ></script>
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
                    <h1 class="dashboard">Productos: Listado de productos</h1>
                    <p></p>
                    <div class="div_header">&nbsp;</div>
                    <div class="div_content">
                        <table id="mytable" class="admin_table">
                            <thead>
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Precio</th>
                                    <th scope="col">Precio web</th>
                                    <th scope="col">Comprado</th>
                                    <th scope="col">Categoria</th>
                                    <th scope="col">Acciones</th></tr>
                            </thead>
                            <tbody class="ui-sortable">
                                <?php $dato->obtenerListadoProductos(); ?>
                            </tbody>
                        </table>
                        <p>
                       <!--<input class="submit" type="button" value="Guardar orden" id="table_button">-->
                        </p>
                    </div>
                    <div class="div_bottom"></div>
                </div>

                <!-- Menu navegacion derecho -->
                <div id="sidebar">
                    <div class="sidebar_header"></div>
                    <div class="sidebar_content">
                        <ul>
                            <li><a href="productosCrear.php">Nuevo Producto </a></li>

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