<?php

require_once 'conexionBD.php';

function consultarCategorias() {
    $mdb2 = conectar();
    $productos = array();
    $cat = new Categoria($mdb2['dsn']);
    $cat->setSelect("idCategoria");
    $cat->addSelect("nombre");
    $cat = $cat->getAll();
    foreach ($cat as $k => $p) {
        $pro = new Producto($mdb2['dsn']);
        $pro->setSelect(TABLA_CATEGORIA . ".nombre AS categoria");
        $pro->setWhere(TABLA_PRODUCTO . ".idCategoria = " . $p['idCategoria']);
        $pro->setJoin(TABLA_CATEGORIA, TABLA_PRODUCTO . ".idCategoria= " . TABLA_CATEGORIA . ".idCategoria", inner);
        $pro = $pro->getAll();
        if (count($pro) > 0)
            array_push($productos, "<div style='float: left; width: 70%;'><a href='javascript:void(0);' onclick=\"buscarPorCategorias('" . $p['idCategoria'] . "');\">" . $p['nombre'] . "</a></div><div align='right' style='float: left; width: 30%;'>(" . count($pro) . ")</div>");
    }
    return $productos;
}

function cargarCategorias($categoria) {
    $mdb2 = conectar();
    if ($categoria == 1) {
        $categorias = new Categoria($mdb2['dsn']);
        $categorias->addSelect("idCategoria AS id");
        $categorias->setLimit(0, 6);
        $categorias = $categorias->getAll();
        for ($i = 0; $i < count($categorias); $i++) {
            $categorias[$i]["categoria"] = "categoria";
        }
        return $categorias;
    } elseif ($categoria == 2) {
        $usos = new Uso($mdb2['dsn']);
        $usos->addSelect("idUso AS id");
        $usos->setLimit(0, 8);
        $usos = $usos->getAll();
        for ($i = 0; $i < count($usos); $i++) {
            $usos[$i]["categoria"] = "uso";
        }
        return $usos;
    } return false;
}

function cargarCategoriasInternas($idProducto) {
    $mdb2 = conectar();
    $tipos = new Categoria($mdb2['dsn']);
    $tipos->setSelect("idCategoria AS id");
    $tipos->addSelect("nombre");
    $tipos->addSelect("imagen");
    $tipos->addSelect("tituloDescripcion");
    $tipos->addSelect("descripcion");
    $tipos->setWhere(TABLA_PRODUCTO . ".idProducto = $idProducto");
    $tipos->setJoin(TABLA_PRODUCTO, "categoria.idCategoria = " . TABLA_PRODUCTO . ".idCategoria", inner);
    $tipos = $tipos->getAll();
    for ($i = 0; $i < count($tipos); $i++) {
        $tipos[$i]["categoria"] = "categoria";
    }
    $usos = new UsoProducto($mdb2['dsn']);
    $usos->setSelect("idUso AS id");
    $usos->addSelect("uso.nombre AS nombre");
    $usos->addSelect("imagen");
    $usos->addSelect("tituloDescripcion");
    $usos->addSelect("uso.descripcion AS descripcion");
    $usos->setWhere(TABLA_PRODUCTO . ".idProducto = $idProducto");
    $usos->setJoin(TABLA_PRODUCTO, "uso_producto.idProducto = " . TABLA_PRODUCTO . ".idProducto", inner);
    $usos->addJoin(TABLA_USO, "uso_producto.idUso = " . TABLA_USO . ".idUso", inner);
    $usos = $usos->getAll();
    for ($i = 0; $i < count($usos); $i++) {
        $usos[$i]["categoria"] = "uso";
    }
    return array_merge($tipos, $usos);
}

?>
