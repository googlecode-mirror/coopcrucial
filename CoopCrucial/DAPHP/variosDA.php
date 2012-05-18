<?php

require_once 'conexionBD.php';

function consultarPaises() {
    $mdb2 = conectar();
    $pais = new Pais($mdb2['dsn']);
    $pais->setSelect("idPais");
    $pais->addSelect("nombre");
    $pais = $pais->getAll();
    $paises = array();
    foreach ($pais as $p) {
        $paises[$p['idPais']] = $p['nombre'];
    }
    return $paises;
}

function consultarCiudades($idPais) {
    $mdb2 = conectar();
    $ciudad = new Ciudad($mdb2['dsn']);
    $ciudad->setSelect("idCiudad");
    $ciudad->addSelect("nombre");
    $ciudad->setWhere("idPais = '$idPais'");
    $ciudad = $ciudad->getAll();
    $ciudades = "<select id='n_ciudad' name='ciudad' style='width: auto;'>
                    <option value=''>---</option>";
    foreach ($ciudad as $c) {
        $ciudades .= "<option value='".$c['idCiudad']."'>".$c['nombre']."</option>";
    }
    return $ciudades."</select>";
}

function consultarValorBono(){
    $mdb2 = conectar();
    $valor = new Registro($mdb2['dsn']);
    $valor->setSelect("valor");
    $valor = $valor->get(1);
    return $valor['valor'];
}

function consultarBarraHorizontal(){
    $mdb2 = conectar();
    $barra = new BarraHorizontal($mdb2['dsn']);
    $barra->setLimit(0, 1);
    $barra = $barra->getAll();
    return $barra[0];
}

function consultarImagenDestacado(){
    $mdb2 = conectar();
    $destacado = new ImagenDestacado($mdb2['dsn']);
    $destacado->setLimit(0, 1);
    $destacado = $destacado->getAll();
    return $destacado[0];
}

?>
