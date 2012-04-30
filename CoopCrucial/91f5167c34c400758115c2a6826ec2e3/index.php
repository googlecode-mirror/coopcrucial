<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Administrador</title>

        <!--Estilo-->
        <link type="text/css" rel="stylesheet" href="estilo/admin_css.css" />
        <!--Efecto-->
        <script type="text/javascript" src="js/jquery-1.4.4.js"></script>
        <!--Efecto Session-->
        <script type="text/javascript" src="js/efectoSession.js" ></script>


    </head>

    <body>
        <!-- top lines for style -->
        <div id="top_green"></div>
        <div id="top_dark"></div>

        <div id="wrapper">

            <div id="header">
                <div id="logo"></div>
                <div id="user_links">
                    <a id="btnVerSitioWeb" href="#" target="_blank">Ver sitio web</a>

                </div>
            </div>

            <!-- edit navigation items here -->
            <div id="nav">
                <ul>
                    <li class="current"><a href="#">Inicio</a></li>

                </ul>
            </div>

            <div id="content">
                <!-- edit sub navigation and quick links here -->
                <div id="sub_nav">

                </div>

                <div id="left">

                    <!-- begin all content here -->
                    <h1 class="dashboard">Login</h1>
                    <p>Por favor ingrese su nombre de usuario y contraseña</p>
                    <form name="login" action="" method="post">
                        <label for="user">Usuario</label> <input type="text" id="usuario" name="usuario" value="" class="medium">
                            <br/>
                            <label for="pass">Contraseña</label> <input type="password" id="password" name="password" value="" class="medium"/>
                            <br/>
                            <br/>
                            <input type="submit" id="ingresar"  name="ingresar" value="ingresar" class="submit"/>
                    </form>


                </div>

                <!-- edit sidebar items here -->
                <div id="sidebar">
                </div>
            </div>

            <!--Menu Footer-->
            <?php include_once('footer.php'); ?>
            <!--Fin Menu Footer-->

        </div>



    </body>
</html>