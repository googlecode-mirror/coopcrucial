<?php
require_once 'conexionBD.php';

function consultarImagenHome() {
    $mdb2 = conectar();
    $imagen = new ImagenHome($mdb2['dsn']);
    $imagen->setSelect("imagen");
    $imagen->addSelect("url");
    $imagen->setOrder("rand()");
    $imagen = $imagen->getAll();
    return $imagen[0];
}

function consultarOfertaEspecial() {
    $mdb2 = conectar();
    $imagen = new OfertaEspecial($mdb2['dsn']);
    $imagen->setSelect("imagen");
    $imagen->addSelect("url");
    $imagen->setOrder("rand()");
    $imagen = $imagen->getAll();
    return $imagen[0];
}

?>
