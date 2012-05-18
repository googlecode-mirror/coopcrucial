<?php

require_once('CConexion.php');
require_once('colaborativas.php');

class CBarraHorizontal {

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

    //Obtener listado barra horizontal
    public function obtenerListadoBarraHorizontal() {

        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `barra_horizontal` ORDER BY idBarraHorizontal ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);


        if ($numFilas > 0) {
            do {
                echo '<tr>
                    <td>' . $fila[idBarraHorizontal] . '</td>
                    <td>' . $fila[porcentaje] . '</td>
                    <td>' . $fila[titulo] . '</td>
                    <td>' . ($fila[mostrado]==1?"Si":"No"). '</td>
                    <td><a href="barraHorizontalModificar.php?id=' . $fila[idBarraHorizontal] . '">Modificar</a> |
                        <a class="btnEliminarElementoLista" title="' . $fila[idBarraHorizontal] . '" name="idBarraHorizontal"  href="barra_horizontal">Eliminar</a>
                        <input type="hidden" id="carpetaImagenBorrar" value="barra_horizontal/" />
                    </td></tr>';
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Crear Barra Horizontal
    public function crearBarraHorizontal() {
        $porcentaje = htmlspecialchars($_POST['porcentaje'], ENT_QUOTES);
        $titulo = htmlspecialchars($_POST['titulo'], ENT_QUOTES);
        $descripcion = htmlspecialchars($_POST['descripcion'], ENT_QUOTES);
        $mostrado = $_POST['mostrado'];
        $id = $this->obtenerConsecutivoBarraHorizontal();
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "INSERT INTO `barra_horizontal` (`idBarraHorizontal`, `porcentaje`, `titulo`, `descripcion`, `mostrado`) VALUES ($id, '$porcentaje', '$titulo', '$descripcion', $mostrado)";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        header('Location: ../barraHorizontal.php');
    }

    //Modificar barra horizontal
    public function modificarBarraHorizontal() {
        $porcentaje = htmlspecialchars($_POST['porcentaje'], ENT_QUOTES);
        $titulo = htmlspecialchars($_POST['titulo'], ENT_QUOTES);
        $descripcion = htmlspecialchars($_POST['descripcion'], ENT_QUOTES);
        $mostrado = $_POST['mostrado'];
        $id = (int)$_POST['id'];
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "UPDATE `barra_horizontal` SET `idBarraHorizontal` = '$id', `porcentaje` = '$porcentaje', `titulo` = '$titulo', `descripcion` = '$descripcion', `mostrado` = $mostrado;";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        header('Location: ../barraHorizontal.php');
    }

    //Eliminar categoria
    public function eliminarBarraHorizontal() {

        $this->conectar();
        //Si existe carpeta con imagenes
        if ($carpetaImagenBorrar != "") {
            //echo $msg = "$carpetaImagenBorrar".$id;
            //Eliminar carpeta
            eliminarRecursivoContenidoDeDirectorio("../recursos/$carpetaImagenBorrar" . $id . "/");
        }
        //Elimnar Registro
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "DELETE FROM `$tabla` WHERE id = $id";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());

        header('Location: ../barraHorizontal.php');
    }

    //Obtener especifico barra horizontal
    public function obtenerEspecificoBarraHorizontal($id, $campo) {
        $this->conectar();
        $id = (int) $id;
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `barra_horizontal` WHERE idBarraHorizontal = $id";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);
        if ($numFilas > 0) {
            return $fila[$campo];
        }
    }

    //Especifico consecutivo Barra Horizontal
    public function obtenerConsecutivoBarraHorizontal() {
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `barra_horizontal` ORDER BY idBarraHorizontal DESC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultado);
        return $fila[idBarraHorizontal] + 1;
    }
}

?>