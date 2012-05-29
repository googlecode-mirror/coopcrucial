<?php

session_start();

require_once '../util/smarty.php';
require_once '../DAPHP/productoDA.php';
require_once '../DAPHP/usuarioDA.php';
require_once '../DAPHP/variosDA.php';
require_once '../DAPHP/imagenProductoDA.php';
require_once '../DAPHP/categoriaDA.php';
require_once '../DAPHP/comentarioDA.php';

extract($_REQUEST);

switch ($accion) {
    case 1:
        $smarty->assign("carrito", consultarCarrito());
        $producto = consultarProducto($idProducto);
        $smarty->assign("producto", $producto);
        switch ($producto['tipoTalla']) {
            case 1:
                $tallas = array("No tiene" => "No tiene");
                break;
            case 2:
                $tallas = array("S" => "S", "M" => "M", "L" => "L", "XL" => "XL");
                break;
            case 3:
                $tallas = array();
                for ($i = 35; $i < 46; $i++) {
                    $tallas[$i] = $i;
                }
                break;
            case 4:
                $tallas = array("Unica" => "Unica");
                break;
            default:
                $tallas = array();
                break;
        }
        $smarty->assign("tallas", $tallas);
        $colorArray = $producto['colores'];
        $colorArray = explode(",", $colorArray);
        $colores = array();
        for ($i = 0; $i < count($colorArray); $i++) {
            $colores[$colorArray[$i]] = $colorArray[$i];
        }
        $smarty->assign("colores", $colores);
        $smarty->assign("imagenes", consultarImagenesProducto($idProducto));
        $smarty->assign("categoriasInternas", cargarCategoriasInternas($idProducto));
        $smarty->assign("recomendados", consultarProductosRecomendados());
        $smarty->assign("comentarios", consultarComentarios($idProducto));
        $smarty->assign("especificaciones", consultarEspecificaciones($idProducto));
        $smarty->assign("caracteristicas", consultarCaracteristicas($idProducto));
        $smarty->assign("linkRedes", urlencode("http://nabica.com.co" . $_SERVER['REQUEST_URI']));
        $smarty->assign("idProducto", $idProducto);
        if (!isset($calificacion))
            $smarty->assign("calificacion", 0);
        else
            $smarty->assign("calificacion", $calificacion);
        $smarty->display("productoInterna.html");
        break;
    case 2:
        $img = cargarImagenProducto($idImagenProducto);
        echo "<div id='etalage' style='display: block;'>
             $img
             </div>";
        break;
    case 3:
        if (isset($palabraClave) && $palabraClave != "") {
            $smarty->assign("carrito", consultarCarrito());
            $smarty->assign("productos", buscadorProductos($palabraClave));
            $smarty->assign("categorias", consultarCategorias());
            $smarty->assign("rangosPrecios", rangosPreciosProductos());
            $smarty->assign("marcas", marcasProductos());
            $smarty->assign("calificaciones", calificacionesProductos());
            $smarty->assign("palabraClave", $palabraClave);
            $smarty->display("productosListado.html");
        }
        else
            header("Location: ../");
        break;
    case 4:
        if (isset($idCat) && $idCat != "")
            $smarty->assign("productos", buscadorPorCategorias($idCat));
        elseif (isset($precio) && $precio != "")
            $smarty->assign("productos", buscadorPorPrecios($precio));
        elseif (isset($marca) && $marca != "")
            $smarty->assign("productos", buscadorPorMarcas($marca));
        elseif (isset($calificacion) && $calificacion != "")
            $smarty->assign("productos", buscadorPorCalificaciones($calificacion));
        else
            header("Location: ../");
        $smarty->assign("carrito", consultarCarrito());
        $smarty->assign("categorias", consultarCategorias());
        $smarty->assign("rangosPrecios", rangosPreciosProductos());
        $smarty->assign("marcas", marcasProductos());
        $smarty->assign("calificaciones", calificacionesProductos());
        $smarty->display("productosListado.html");
        break;
    case 5:
        /*session_unset();
        die ("listo");*/
        if ($talla == "undefined" && $color == "undefined") {
            $resp = consultarTipoTalla($id);
            $resp = agregarCarrito($id, $cant, $resp['talla'], $resp['color']);
        }
        else
            $resp = agregarCarrito($id, $cant, $talla, $color);
        /*var_dump($resp);
        die;*/
        $smarty->assign("msj", $resp['msj']);
        if ($resp['bool'])
            $smarty->assign("producto", consultarProductoAgregado($resp['idVenta']));
        $smarty->assign("carrito", consultarCarrito());
        $smarty->assign("recomendados", consultarProductosRecomendados());
        $smarty->assign("masComprados", consultarProductosMasComprados());
        $smarty->display("carrito.html");
        break;
    case 6:
        $resp = agregarComentario($idProducto, $comentario);
        echo $resp;
        break;
    case 7:
        switch ($paso) {
            case 1:
                $resp = cargarProductosCarrito();
                $smarty->assign("productos", $resp['productos']);
                $smarty->assign("total", $resp['total']);
                break;
            case 2:
                if (isset($_SESSION['usuario']['idUsuario']) && $_SESSION['usuario']['idUsuario'] != "") {
                    $cantidades = explode(",", $cantidades);
                    $ventas = explode(",", $ids);
                    $bool = cantidadesCarrito($ventas, $cantidades);
                    $resp = cargarDirecciones();
                    $smarty->assign("paises", consultarPaises());
                    $smarty->assign("direccion", $resp['direccion']);
                    $smarty->assign("direcciones", $resp['direcciones']);
                }
                break;
            case 3:
                agregarDireccion($idDireccion);
                $resp = cargarProductosCarrito();
                $smarty->assign("productos", $resp['productos']);
                $smarty->assign("total", $resp['total']);
                break;
            default:
                break;
        }
        $smarty->assign("paso", $paso);
        $smarty->assign("carrito", consultarCarrito());
        $smarty->display("carritoInterna.html");
        break;
    case 8:
        $resp = eliminarRegistro($idProducto);
        if ($resp)
            echo "Producto eliminado del carrito.";
        else
            echo "No se pudo eliminar el producto del carrito.";
        break;
    case 9:
        compraCarrito();
        header("Location: ../");
        break;
    case 10:
        $categorias = cargarCategorias($cat);
        $resp = "";
        if (is_array($categorias)) {
            foreach ($categorias as $c) {
                $resp .= "<div align='center' style='width: 100px; height: 120px; float: left;'>
                        <img src='../91f5167c34c400758115c2a6826ec2e3/recursos/" . $c['categoria'] . "/" . $c['id'] . "/" . $c['imagen'] . "' onclick=\"Productos('" . $c['categoria'] . "', '" . $c['id'] . "');\" style='float: left; cursor: pointer;' width='100' height='100'/>
                        <label>" . $c['nombre'] . "</label>
                      </div>";
            }
        }
        echo $resp;
        break;
    case 11:
        if (isset($cat) && ($cat == "categoria" || $cat == "uso") && isset($id)) {
            $smarty->assign("carrito", consultarCarrito());
            $smarty->assign("productos", productos($cat, $id));
            $smarty->assign("categorias", consultarCategorias());
            $smarty->assign("rangosPrecios", rangosPreciosProductos());
            $smarty->assign("marcas", marcasProductos());
            $smarty->assign("calificaciones", calificacionesProductos());
            $smarty->display("productosListado.html");
        }
        else
            header("Location: ../");
        break;
    default:
        header("Location: ../");
        break;
}
?>
