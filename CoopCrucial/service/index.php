<?php

session_start();
require_once '../util/smarty.php';
require_once '../DAPHP/productoDA.php';
require_once '../DAPHP/variosDA.php';
require_once '../DAPHP/imagenDA.php';

$smarty->assign("carrito", consultarCarrito());
$smarty->assign("imagenHome", consultarImagenHome());
$smarty->assign("ofertaEspecial", consultarOfertaEspecial());
$smarty->assign("barraHorizontal", consultarBarraHorizontal());
$smarty->assign("destacados", consultarProductosDestacados());
$smarty->assign("recomendados", consultarProductosRecomendados());
$smarty->assign("masComprados", consultarProductosMasComprados());
$smarty->display("home.html");
?>
