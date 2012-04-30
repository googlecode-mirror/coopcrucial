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
<title>Inicio</title>

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
                <a id="btnVerSitioWeb" href="#" target="_blank">Ver sitio web</a>&nbsp;|
                                <a id="btnLogOut" href="#">Logout</a>
                            </div>
        </div>
        
        <!--Menu Navegacion-->
        <?php include_once('menuNavegacion.php');?>
        <!--Fin Menu Navegacion-->
        
        <div id="content">
            <!-- edit sub navigation and quick links here -->
            <div id="sub_nav">
                               
                 
            </div>
            
            <div id="left">
                
                <!-- begin all content here -->                
                <h1 class="dashboard">Inicio</h1>
                                <p>Bienvenido al sitio administrativo de CoopCrucial</p>
            </div>
            
        <!-- edit sidebar items here --></div>
        
        <div id="footer">
            <!-- edit footer items here -->
            <div id="copyright">Copyright © 2012 </div>
            <div id="footer_links">

            </div>
        </div>
        
    </div>



</body>
</html>