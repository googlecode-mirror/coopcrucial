<?php

require_once('CConexion.php');
require_once('colaborativas.php');

class CImagenDestacado {

    public $hostname_conn;
    public $database_conn;
    public $username_conn;
    public $password_conn;
    public $conn;

    //CONECTAR A LA BASE DE DATOS
    public function conectar() {

        $conexion = new CConexion();

        $this->hostname_conn = $conexion->obteneHostName();
        $this->database_conn = $conexion->obteneDataBase();
        $this->username_conn = $conexion->obteneUserName();
        $this->password_conn = $conexion->obtenePassword();
        $this->conn = mysql_pconnect($this->hostname_conn, $this->username_conn, $this->password_conn) or trigger_error(mysql_error(), E_USER_ERROR);
    }

    //Obtener listado imagen destacado
    public function obtenerListadoImagenDestacado() {
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `imagen_destacado` ORDER BY idImagenDestacado ASC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);
        if ($numFilas > 0) {
            do {
                echo '<tr>
                    <td>' . $fila[idImagenDestacado] . '</td>
                    <td>' . $fila[imagen] . '</td>
                    <td>' . $fila[url] . '</td>
                    <td><a class="btnEliminarElementoLista" title="' . $fila[idImagenDestacado] . '" name="idImagenDestacado"  href="imagen_destacado">Eliminar</a>
                        <input type="hidden" id="carpetaImagenBorrar" value="imagen_destacado/" />
                    </td></tr>';
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Crear Imagen Destacado
    public function crearImagenDestacado() {
        $imagen = comprobarArchivo("imagen");
        $url = htmlspecialchars($_POST['url'], ENT_QUOTES);
        $id = $this->obtenerConsecutivoImagenDestacado();
        if ($imagen != "") {
            $this->conectar();
            $imagen = guardarImagen("../recursos/imagenDestacado/", "imagen");
            mysql_select_db($this->database_conn, $this->conn);
            $sentencia = "INSERT INTO `imagen_destacado` (`idImagenDestacado`, `imagen`, `url`) VALUES ('$id', '$imagen', '$url');";
            $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        }
        header('Location: ../imagenDestacado.php');
    }

    //Especifico consecutivo Imagen Destacado
    public function obtenerConsecutivoImagenDestacado() {
        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `imagen_destacado` ORDER BY idImagenDestacado DESC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultado);
        return $fila[idImagenDestacado] + 1;
    }

}

?>