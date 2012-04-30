<?php require_once("91f5167c34c400758115c2a6826ec2e3/clases/funciones.php");

$dato = new funciones();

$_GET = $dato->getVariables($_GET['route']);
//$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if ($_GET) {

    if (isset($_GET[catalogo])) {
        include_once('catalogoBusqueda.php');
    } elseif (isset($_GET[producto])) {
        include_once('catalogoProducto.php');
    }
} else {
    //echo 'No hay variables GET';
    header('Location: ' . $dato->urlBaseSitio);
}
?>