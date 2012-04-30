<?php

require_once 'conexionBD.php';

function consultarComentarios($idProducto) {
    $mdb2 = conectar();
    $comentarios = new Comentario($mdb2['dsn']);
    $comentarios->setSelect(TABLA_USUARIO.".usuario AS usuario");
    $comentarios->addSelect("comentario");
    $comentarios->setWhere("idProducto = $idProducto");
    $comentarios->setJoin(TABLA_PRODUCTO,TABLA_COMENTARIO.".idProducto = " . TABLA_PRODUCTO.".idProducto",inner);
    $comentarios->addJoin(TABLA_USUARIO,TABLA_COMENTARIO.".idUsuario = " . TABLA_USUARIO.".idUsuario",inner);
    $comentarios->setOrder("idComentario DESC");
    $cantidadComentarios = $comentarios->getCount();
    $comentarios = $comentarios->getAll();
    return array("cantidad"=>$cantidadComentarios,"comentarios"=>$comentarios);
}

function agregarComentario($idProducto, $comentario){
    if (!isset($_SESSION['usuario']['idUsuario']) || $_SESSION['usuario']['idUsuario'] == "")
        return "No tiene sesion iniciada.";
    $mdb2 = conectar();
    $nuevoComentario = new Comentario($mdb2['dsn']);
    $nuevoComentario->useResult('object');
    $nComentario = $nuevoComentario->newEntity();
    $nComentario->idProducto = $idProducto;
    $nComentario->idUsuario = $_SESSION['usuario']['idUsuario'];
    $nComentario->comentario = $comentario;
    $nComentario->fecha = date("Y/m/d");
    $idComentario = $nComentario->save();
    if (is_numeric($idComentario)) {
        return "Comentario guardado.";
    }
    return "No se pudo agregar el comentario.";
}
?>
