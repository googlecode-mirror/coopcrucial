<?php

require_once 'conexionBD.php';

function consultarProducto($idProducto) {
    $mdb2 = conectar();
    $producto = new Producto($mdb2['dsn']);
    $producto->addSelect(TABLA_IMAGEN_PRODUCTO . ".nombre AS imagen");
    $producto->addSelect(TABLA_CATEGORIA . ".nombre AS categoria");
    $producto->setWhere("idProducto = $idProducto");
    $producto->setJoin(TABLA_IMAGEN_PRODUCTO, TABLA_PRODUCTO . ".idProducto = " . TABLA_IMAGEN_PRODUCTO . ".idProducto");
    $producto->addJoin(TABLA_CATEGORIA, TABLA_PRODUCTO . ".idCategoria = " . TABLA_CATEGORIA . ".idCategoria");
    $producto->setGroup("idProducto");
    $producto = $producto->getAll();
    return $producto[0];
}

function consultarProductosDestacados() {
    $mdb2 = conectar();
    $productos = new Producto($mdb2['dsn']);
    $productos->addSelect(TABLA_IMAGEN_PRODUCTO . ".nombre AS imagen");
    $productos->setWhere("destacado = 1");
    $productos->setJoin(TABLA_IMAGEN_PRODUCTO, TABLA_PRODUCTO . ".idProducto = " . TABLA_IMAGEN_PRODUCTO . ".idProducto");
    $productos->setLimit(0, 2);
    $productos->setGroup("idProducto");
    $productos = $productos->getAll();
    return $productos;
}

function consultarProductosRecomendados() {
    $mdb2 = conectar();
    $productos = new Producto($mdb2['dsn']);
    $productos->addSelect(TABLA_IMAGEN_PRODUCTO . ".nombre AS imagen");
    $productos->setWhere("recomendado = 1");
    $productos->setJoin(TABLA_IMAGEN_PRODUCTO, TABLA_PRODUCTO . ".idProducto = " . TABLA_IMAGEN_PRODUCTO . ".idProducto");
    $productos->setLimit(0, 6);
    $productos->setGroup("idProducto");
    $productos = $productos->getAll();
    return $productos;
}

function consultarProductosMasComprados() {
    $mdb2 = conectar();
    $productos = new Producto($mdb2['dsn']);
    $productos->addSelect(TABLA_IMAGEN_PRODUCTO . ".nombre AS imagen");
    $productos->setJoin(TABLA_IMAGEN_PRODUCTO, TABLA_PRODUCTO . ".idProducto = " . TABLA_IMAGEN_PRODUCTO . ".idProducto");
    $productos->setOrder("vecesComprado DESC");
    $productos->setLimit(0, 6);
    $productos->setGroup("idProducto");
    $productos = $productos->getAll();
    return $productos;
}

function buscadorProductos($palabraClave) {
    $mdb2 = conectar();
    $productos = new Producto($mdb2['dsn']);
    $productos->addSelect(TABLA_IMAGEN_PRODUCTO . ".nombre AS imagen");
    $productos->setWhere(TABLA_PRODUCTO . ".nombre LIKE '%" . $palabraClave . "%' OR descripcion LIKE '%" . $palabraClave . "%' OR tags LIKE '%" . $palabraClave . "%'");
    $productos->setJoin(TABLA_IMAGEN_PRODUCTO, TABLA_PRODUCTO . ".idProducto = " . TABLA_IMAGEN_PRODUCTO . ".idProducto", inner);
    $productos->setLimit(0, 6);
    $productos->setGroup(TABLA_PRODUCTO . ".idProducto");
    return $productos->getAll();
}

function buscadorPorCategorias($idCat) {
    $mdb2 = conectar();
    $productos = new Producto($mdb2['dsn']);
    $productos->addSelect(TABLA_IMAGEN_PRODUCTO . ".nombre AS imagen");
    $productos->setWhere("idCategoria = $idCat");
    $productos->setJoin(TABLA_IMAGEN_PRODUCTO, TABLA_PRODUCTO . ".idProducto = " . TABLA_IMAGEN_PRODUCTO . ".idProducto", inner);
    $productos->setLimit(0, 6);
    $productos->setGroup(TABLA_PRODUCTO . ".idProducto");
    return $productos->getAll();
}

function buscadorPorPrecios($precio) {
    $mdb2 = conectar();
    $productos = new Producto($mdb2['dsn']);
    $productos->addSelect(TABLA_IMAGEN_PRODUCTO . ".nombre AS imagen");
    if ($precio == 0)
        $productos->setWhere("precioWeb >= 0 AND precioWeb < 50000");
    elseif ($precio > 0 && $precio < 16)
        $productos->setWhere("precioWeb >= " . ($precio * 50000) . " AND precioWeb < " . (($precio + 1) * 50000));
    else
        $productos->setWhere("precioWeb >= 800000");
    $productos->setJoin(TABLA_IMAGEN_PRODUCTO, TABLA_PRODUCTO . ".idProducto = " . TABLA_IMAGEN_PRODUCTO . ".idProducto", inner);
    $productos->setLimit(0, 6);
    $productos->setGroup(TABLA_PRODUCTO . ".idProducto");
    return $productos->getAll();
}

function buscadorPorMarcas($marca) {
    $mdb2 = conectar();
    $productos = new Producto($mdb2['dsn']);
    $productos->addSelect(TABLA_IMAGEN_PRODUCTO . ".nombre AS imagen");
    $productos->setWhere("marca = '$marca'");
    $productos->setJoin(TABLA_IMAGEN_PRODUCTO, TABLA_PRODUCTO . ".idProducto = " . TABLA_IMAGEN_PRODUCTO . ".idProducto", inner);
    $productos->setLimit(0, 6);
    $productos->setGroup(TABLA_PRODUCTO . ".idProducto");
    return $productos->getAll();
}

function buscadorPorCalificaciones($calificacion) {
    $mdb2 = conectar();
    $productos = new Producto($mdb2['dsn']);
    $productos->addSelect(TABLA_IMAGEN_PRODUCTO . ".nombre AS imagen");
    $productos->setWhere("calificacion = $calificacion");
    $productos->setJoin(TABLA_IMAGEN_PRODUCTO, TABLA_PRODUCTO . ".idProducto = " . TABLA_IMAGEN_PRODUCTO . ".idProducto", inner);
    $productos->setLimit(0, 6);
    $productos->setGroup(TABLA_PRODUCTO . ".idProducto");
    return $productos->getAll();
}

function rangosPreciosProductos() {
    $mdb2 = conectar();
    $rangoPrecios = array();
    //Menos de 50.000
    $productos = new Producto($mdb2['dsn']);
    $productos->setSelect("precioWeb");
    $productos->setWhere("precioWeb >= 0 AND precioWeb < 50000");
    $productos = $productos->getAll();
    if (count($productos) > 0)
        array_push($rangoPrecios, "<div style='float: left; width: 70%;'><a href='javascript:void(0);' onclick=\"buscarPorPrecios(0);\">Menos de $50.000</a></div><div align='right' style='float: left; width: 30%;'>(" . count($productos) . ")</div>");
    $i = 0;
    $li = 50000;
    $ls = 100000;
    while ($i < 15) {
        $productos = new Producto($mdb2['dsn']);
        $productos->setSelect("precioWeb");
        $productos->setWhere("precioWeb >= " . $li . " AND precioWeb < " . $ls);
        $productos = $productos->getAll();
        if (count($productos) > 0)
            array_push($rangoPrecios, "<div style='float: left; width: 70%;'><a href='javascript:void(0);' onclick=\"buscarPorPrecios(" . ($i + 1) . ");\">$" . $li . " - " . "$" . $ls . "</a></div><div align='right' style='float: left; width: 30%;'>(" . count($productos) . ")</div>");
        $i++;
        $li += 50000;
        $ls += 50000;
    }
    //Mas de 800.000
    $productos = new Producto($mdb2['dsn']);
    $productos->setSelect("precioWeb");
    $productos->setWhere("precioWeb >= 800000");
    $productos = $productos->getAll();
    if (count($productos) > 0)
        array_push($rangoPrecios, "<div style='float: left; width: 70%;'><a href='javascript:void(0);' onclick=\"buscarPorPrecios(16);\">Mas de 800.000</a></div><div align='right' style='float: left; width: 30%;'>(" . count($productos) . ")</div>");
    return $rangoPrecios;
}

function marcasProductos() {
    $marcas = array();
    $productos = new Producto($mdb2['dsn']);
    $productos->setSelect("marca");
    $productos->setGroup("marca");
    $productos = $productos->getAll();
    foreach ($productos as $p) {
        $marca = new Producto($mdb2['dsn']);
        $marca->setSelect("marca");
        $marca->setWhere("marca = '" . $p['marca'] . "'");
        $marca = $marca->getAll();
        if (count($marca) > 0)
            array_push($marcas, "<div style='float: left; width: 70%;'><a href='javascript:void(0);' onclick=\"buscarPorMarcas('" . $p['marca'] . "');\">" . $p['marca'] . "</a></div><div align='right' style='float: left; width: 30%;'>(" . count($marca) . ")</div>");
    }
    return $marcas;
}

function calificacionesProductos() {
    $calificaciones = array();
    $i = 0;
    while ($i < 5) {
        $productos = new Producto($mdb2['dsn']);
        $productos->setSelect("calificacion");
        $productos->setWhere("calificacion = " . ($i + 1));
        $productos = $productos->getAll();
        if (count($productos) > 0)
            array_push($calificaciones, "<div style='float: left; width: 70%;'><a href='javascript:void(0);' onclick=\"buscarPorCalificaciones(" . ($i + 1) . ");\">" . ($i + 1) . " Estrellas</a></div><div align='right' style='float: left; width: 30%;'>(" . count($productos) . ")</div>");
        $i++;
    }
    return $calificaciones;
}

function agregarCarrito($idProducto, $cantidad) {
    if (!isset($_SESSION['usuario']['idUsuario']) || $_SESSION['usuario']['idUsuario'] == "")
        return array("bool" => false, "msj" => "No tiene sesion iniciada.");
    $mdb2 = conectar();
    $carrito = new Venta($mdb2['dsn']);
    $carrito->setSelect("idVenta");
    $carrito->setWhere("idUsuario = " . $_SESSION['usuario']['idUsuario']);
    $carrito->addWhere("idProducto = $idProducto");
    $carrito->addWhere("carrito = 1");
    $carrito = $carrito->getAll();
    if (count($carrito) > 0)
        return array("bool" => true, "msj" => "Este producto ya se encuentra en el carrito.", "idVenta" => $carrito[0]['idVenta']);
    $nuevaVenta = new Venta($mdb2['dsn']);
    $nuevaVenta->useResult('object');
    $nVenta = $nuevaVenta->newEntity();
    $nVenta->idProducto = $idProducto;
    $nVenta->idUsuario = $_SESSION['usuario']['idUsuario'];
    $nVenta->cantidad = $cantidad;
    $nVenta->carrito = 1;
    $nVenta->idDireccion = null;
    $idVenta = $nVenta->save();
    if (is_numeric($idVenta)) {
        return array("bool" => true, "msj" => "El producto se ha agregado al carrito.", "idVenta" => $idVenta);
    }
    return array("bool" => false, "msj" => "No se pudo agregar al carrito.");
}

function consultarCarrito() {
    if (!isset($_SESSION['usuario']['idUsuario']) || $_SESSION['usuario']['idUsuario'] == "")
        return 0;
    $mdb2 = conectar();
    $carrito = new Venta($mdb2['dsn']);
    $carrito->setSelect(TABLA_PRODUCTO . ".nombre AS nombre");
    $carrito->addSelect(TABLA_PRODUCTO . ".precioWeb AS precio");
    $carrito->setWhere("idUsuario = " . $_SESSION['usuario']['idUsuario']);
    $carrito->addWhere("carrito = 1");
    $carrito->setJoin(TABLA_PRODUCTO, TABLA_VENTA . ".idProducto = " . TABLA_PRODUCTO . ".idProducto", inner);
    $cantidadCarrito = $carrito->getCount();
    $productosCarrito = $carrito->getAll();
    $total = 0;
    foreach ($productosCarrito as $p) {
        $total += $p['precio'];
    }
    return array("cantidad" => $cantidadCarrito, "productos" => $productosCarrito, "total" => $total);
}

function consultarProductoAgregado($idVenta) {
    $mdb2 = conectar();
    $producto = new Venta($mdb2['dsn']);
    $producto->setSelect("idVenta");
    $producto->addSelect("cantidad");
    $producto->addSelect(TABLA_PRODUCTO . ".idProducto AS idProducto");
    $producto->addSelect(TABLA_PRODUCTO . ".nombre AS nombre");
    $producto->addSelect(TABLA_PRODUCTO . ".precioWeb AS precioWeb");
    $producto->addSelect(TABLA_IMAGEN_PRODUCTO . ".nombre AS imagen");
    $producto->addSelect(TABLA_IMAGEN_PRODUCTO . ".idImagenProducto AS idImagen");
    $producto->setWhere("carrito = 1");
    $producto->setJoin(TABLA_PRODUCTO, "venta.idProducto = " . TABLA_PRODUCTO . ".idProducto", inner);
    $producto->addJoin(TABLA_IMAGEN_PRODUCTO, TABLA_PRODUCTO . ".idProducto = " . TABLA_IMAGEN_PRODUCTO . ".idProducto", inner);
    $producto->setGroup("idVenta");
    $producto = $producto->get($idVenta);
    $producto['precioWeb'] = $producto['precioWeb'] * $producto['cantidad'];
    return $producto;
}

function consultarEspecificaciones($idProducto) {
    $mdb2 = conectar();
    $especificaciones = new Especificacion($mdb2['dsn']);
    $especificaciones->setSelect("titulo");
    $especificaciones->addSelect("descripcion");
    $especificaciones->setWhere("idProducto = $idProducto");
    $cantidadEspecificaciones = $especificaciones->getCount();
    $especificaciones = $especificaciones->getAll();
    return array("cantidad" => $cantidadEspecificaciones, "especificaciones" => $especificaciones);
}

function consultarCaracteristicas($idProducto) {
    $mdb2 = conectar();
    $caracteristicas = new Caracteristica($mdb2['dsn']);
    $caracteristicas->setSelect("nombre");
    $caracteristicas->addSelect("descripcion");
    $caracteristicas->setWhere("idProducto = $idProducto");
    $cantidadCaracteristicas = $caracteristicas->getCount();
    $caracteristicas = $caracteristicas->getAll();
    return array("cantidad" => $cantidadCaracteristicas, "caracteristicas" => $caracteristicas);
}

function cargarProductosCarrito() {
    $mdb2 = conectar();
    $productos = new Producto($mdb2['dsn']);
    $productos->setSelect("idProducto");
    $productos->addSelect("nombre");
    $productos->addSelect("precioWeb");
    $productos->addSelect(TABLA_IMAGEN_PRODUCTO . ".nombre AS imagen");
    $productos->addSelect(TABLA_IMAGEN_PRODUCTO . ".idImagenProducto AS idImagen");
    $productos->addSelect(TABLA_VENTA . ".idVenta AS idVenta");
    $productos->addSelect(TABLA_VENTA . ".cantidad AS cantidad");
    $productos->setWhere(TABLA_VENTA . ".carrito = 1");
    $productos->setJoin(TABLA_IMAGEN_PRODUCTO, "producto.idProducto = " . TABLA_IMAGEN_PRODUCTO . ".idProducto", inner);
    $productos->addJoin(TABLA_VENTA, "producto.idProducto = " . TABLA_VENTA . ".idProducto", inner);
    $productos->setGroup("idProducto");
    $productos = $productos->getAll();
    $total = 0;
    for ($i = 0; $i < count($productos); $i++) {
        $productos[$i]['precioWeb'] = $productos[$i]['precioWeb'] * $productos[$i]['cantidad'];
        $total += $productos[$i]['precioWeb'];
    }
    return array("productos" => $productos, "total" => $total);
}

function eliminarRegistro($idProducto) {
    $mdb2 = conectar();
    $carrito = new Venta($mdb2['dsn']);
    $carrito->setSelect("idVenta");
    $carrito->setWhere("idUsuario = " . $_SESSION['usuario']['idUsuario']);
    $carrito->addWhere("idProducto = $idProducto");
    $carrito->addWhere("carrito = 1");
    $carrito = $carrito->getAll();
    if (sizeof($carrito) > 0) {
        $idVenta = $carrito[0]['idVenta'];
        $carrito = new Venta($mdb2['dsn']);
        return $carrito->remove($idVenta, "idVenta");
    }
    return false;
}

function cantidadesCarrito($ventas, $cantidades) {
    $mdb2 = conectar();
    if (is_array($ventas) && is_array($cantidades)) {
        for ($i = 0; $i < sizeof($ventas); $i++) {
            $arrayData = array("idVenta" => $ventas[$i], "cantidad" => $cantidades[$i]);
            $venta = new Venta($mdb2['dsn']);
            $venta->save($arrayData);
        }
        return true;
    }
    return false;
}

function compraCarrito() {
    $mdb2 = conectar();
    $ventas = new Venta($mdb2['dsn']);
    $ventas->setSelect('idVenta');
    $ventas->addSelect(TABLA_PRODUCTO . '.idProducto AS idProducto');
    $ventas->addSelect(TABLA_PRODUCTO . '.vecesComprado AS vecesComprado');
    $ventas->setWhere("idUsuario = " . $_SESSION['usuario']['idUsuario']);
    $ventas->addWhere("carrito = 1");
    $ventas->setJoin(TABLA_PRODUCTO, "venta.idProducto = " . TABLA_PRODUCTO . ".idProducto", inner);
    $ventas = $ventas->getAll();
    for ($i = 0; $i < sizeof($ventas); $i++) {
        $arrayData = array("idProducto" => $ventas[$i]["idProducto"], "vecesComprado"=>($ventas[$i]["vecesComprado"]+1));
        $producto = new Producto($mdb2['dsn']);
        $producto->save($arrayData);
        $arrayData = array("idVenta" => $ventas[$i]['idVenta'], "carrito" => 0, "fecha" => date("Y-m-d H:i:s"));
        $venta = new Venta($mdb2['dsn']);
        $venta->save($arrayData);
    }
}

function productos($cat, $id) {
    $mdb2 = conectar();
    $productos = new Producto($mdb2['dsn']);
    $productos->addSelect(TABLA_IMAGEN_PRODUCTO . ".nombre AS imagen");
    $productos->setJoin(TABLA_IMAGEN_PRODUCTO, TABLA_PRODUCTO . ".idProducto = " . TABLA_IMAGEN_PRODUCTO . ".idProducto", inner);
    if ($cat == "categoria")
        $productos->setWhere(TABLA_PRODUCTO . ".idCategoria = $id");
    elseif ($cat == "uso") {
        $productos->setWhere(TABLA_USO_PRODUCTO . ".idUso = $id");
        $productos->addJoin(TABLA_USO_PRODUCTO, TABLA_PRODUCTO . ".idProducto = " . TABLA_USO_PRODUCTO . ".idProducto", inner);
    }
    $productos->setLimit(0, 6);
    $productos->setGroup(TABLA_PRODUCTO . ".idProducto");
    return $productos->getAll();
}

?>
