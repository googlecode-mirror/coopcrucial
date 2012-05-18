<?php

require_once('CConexion.php');
require_once('colaborativas.php');

class CEliminacionRegistros {

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

    //Eliminar registro tabla
    public function eliminarRegistroTabla() {
        $tabla = $_POST['tabla'];
        $id = $_POST['id'];
        $nameId = $_POST['nameId'];
        $carpetaImagenBorrar = $_POST['carpetaImagenBorrar'];
        //Elimnar Registro
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "DELETE FROM `$tabla` WHERE $nameId = $id";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        //Si existe carpeta con imagenes
        if ($carpetaImagenBorrar != "") {
            //echo $msg = "$carpetaImagenBorrar".$id;
            //Eliminar carpeta
            eliminarRecursivoContenidoDeDirectorio("../recursos/$carpetaImagenBorrar" . $id . "/");
        }
    }

}

?>