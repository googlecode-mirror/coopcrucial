<?php

class MDB_QueryToolExt extends MDB_QueryTool {

    function __construct($dsn) {
        global $conexionDB;
        $this->sequenceName = $this->table;
        $this->tableSpec = array(
            array('name' => $this->table, 'shortName' => $this->table),
        );
        parent::__construct($dsn, array('autoConnect' => false), 1);

        if (!$conexionDB) {
            $this->connect($dsn);
            $conexionDB = $this->db;
        }
        else
            $this->db = $conexionDB;
    }

}

define('TABLA_CATEGORIA', 'categoria');
define('TABLA_USO', 'uso');
define('TABLA_USO_PRODUCTO', 'uso_producto');
define('TABLA_PRODUCTO', 'producto');
define('TABLA_ESPECIFICACION', 'especificacion');
define('TABLA_CARACTERISTICA', 'caracteristica');
define('TABLA_IMAGEN_PRODUCTO', 'imagen_producto');
define('TABLA_COLOR_PRODUCTO', 'color_producto');
define('TABLA_COLOR', 'color');
define('TABLA_IMAGEN_HOME','imagen_home');
define('TABLA_IMAGEN_OFERTA_ESPECIAL','oferta_especial');
define('TABLA_USUARIO','usuario');
define('TABLA_PREFERENCIA_USUARIO','preferencia_usuario');
define('TABLA_PREFERENCIA','preferencia');
define('TABLA_VENTA','venta');
define('TABLA_COMENTARIO','comentario');
define('TABLA_PAIS','pais');
define('TABLA_CIUDAD','ciudad');
define('TABLA_DIRECCION','direccion');
define('TABLA_REGISTRO','registro');

class Categoria extends MDB_QueryToolExt{
    var $table = TABLA_CATEGORIA;
    var $primaryCol = 'idCategoria';
}

class Uso extends MDB_QueryToolExt{
    var $table = TABLA_USO;
    var $primaryCol = 'idUso';
}

class UsoProducto extends MDB_QueryToolExt{
    var $table = TABLA_USO_PRODUCTO;
    var $primaryCol = 'idUsoProducto';
}

class Producto extends MDB_QueryToolExt {
    var $table = TABLA_PRODUCTO;
    var $primaryCol = 'idProducto';
}

class Especificacion extends MDB_QueryToolExt {
    var $table = TABLA_ESPECIFICACION;
    var $primaryCol = 'idEspecificacion';
}

class Caracteristica extends MDB_QueryToolExt {
    var $table = TABLA_CARACTERISTICA;
    var $primaryCol = 'idCaracteristica';
}

class ImagenProducto extends MDB_QueryToolExt {
    var $table = TABLA_IMAGEN_PRODUCTO;
    var $primaryCol = 'idImagenProducto';
}

class ColorProducto extends MDB_QueryToolExt {
    var $table = TABLA_COLOR_PRODUCTO;
    var $primaryCol = 'idColorProducto';
}

class Color extends MDB_QueryToolExt {
    var $table = TABLA_COLOR;
    var $primaryCol = 'idColor';
}

class ImagenHome extends MDB_QueryToolExt {
    var $table = TABLA_IMAGEN_HOME;
    var $primaryCol = 'idImagenHome';
}

class OfertaEspecial extends MDB_QueryToolExt {
    var $table = TABLA_IMAGEN_OFERTA_ESPECIAL;
    var $primaryCol = 'idOfertaEspecial';
}

class Usuario extends MDB_QueryToolExt {
    var $table = TABLA_USUARIO;
    var $primaryCol = 'idUsuario';
}

class Preferencia extends MDB_QueryToolExt {
    var $table = TABLA_PREFERENCIA;
    var $primaryCol = 'idPreferencia';
}

class PreferenciaUsuario extends MDB_QueryToolExt {
    var $table = TABLA_PREFERENCIA_USUARIO;
    var $primaryCol = 'idPreferenciaUsuario';
}

class Venta extends MDB_QueryToolExt {
    var $table = TABLA_VENTA;
    var $primaryCol = 'idVenta';
}

class Comentario extends MDB_QueryToolExt {
    var $table = TABLA_COMENTARIO;
    var $primaryCol = 'idComentario';
}

class Pais extends MDB_QueryToolExt {
    var $table = TABLA_PAIS;
    var $primaryCol = 'idPais';
}

class Ciudad extends MDB_QueryToolExt {
    var $table = TABLA_CIUDAD;
    var $primaryCol = 'idCiudad';
}

class Direccion extends MDB_QueryToolExt {
    var $table = TABLA_DIRECCION;
    var $primaryCol = 'idDireccion';
}

class Registro extends MDB_QueryToolExt {
    var $table = TABLA_REGISTRO;
    var $primaryCol = 'idRegistro';
}

?>