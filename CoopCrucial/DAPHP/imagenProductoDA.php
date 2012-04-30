<?php
require_once 'conexionBD.php';

function consultarImagenesProducto($idProducto) {
    $mdb2 = conectar();
    $imagenes = new ImagenProducto($mdb2['dsn']);
    $imagenes->setWhere("idProducto = $idProducto");
    $imagenes = $imagenes->getAll();
    return $imagenes;
}

function cargarImagenProducto($idImagenProducto) {
    $mdb2 = conectar();
    $imagenes = new ImagenProducto($mdb2['dsn']);
    $imagenes->setWhere("idImagenProducto = $idImagenProducto");
    $imagenes = $imagenes->getAll();
    $imagen = $imagenes[0];
    return "<img class='etalage_source_image' src='../91f5167c34c400758115c2a6826ec2e3/recursos/producto/".$imagen['idProducto']."/".$imagen['nombre']."' title='".$imagen['nombre']."'/>
            <img class='etalage_thumb_image' src='../91f5167c34c400758115c2a6826ec2e3/recursos/producto/".$imagen['idProducto']."/".$imagen['nombre']."' width='100%' height='300'/>";
}

?>
