<?php

require_once('CConexion.php');
require_once('colaborativas.php');

class CRegistros{

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

    //Obtener listado registros
    public function obtenerListadoRegistros() {

        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `registro` ORDER BY idRegistro ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);


        if ($numFilas > 0) {
            do {
                echo '<tr>
                    <td>' . $fila[idRegistro] . '</td>
                    <td>' . $fila[nombre] . '</td>
                    <td>' . $fila[valor] . '</td>
                    <td><a href="registrosModificar.php?id=' . $fila[idRegistro] . '">Modificar</a>
                    </td></tr>';
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Modificar registro
    public function modificarRegistro() {
        $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES);
        $valor = $_POST['valor'];
        $id = $_POST['id'];
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "UPDATE `registro` SET  `nombre` =  '$nombre', `valor` = $valor WHERE  `registro`.`idRegistro` = $id;";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        header('Location: ../registros.php');
    }

    //Eliminar categoria
    public function eliminarCategoria() {

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
        
        header('Location: ../categorias.php');
    }

    //Obtener especifico registro
    public function obtenerEspecificoRegistro($id, $campo) {

        $this->conectar();

        $id = (int) $id;

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `registro` WHERE idRegistro = $id";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);

        if ($numFilas > 0) {
            return $fila[$campo];
        }
    }

    //Especifico consecutivo registro
    public function obtenerConsecutivoRegistro() {
        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `registro` ORDER BY idRegistro DESC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultado);
        return $fila[idRegistro] + 1;
    }

    //Enviar correo certificado usuario
    public function enviarCorreoCertificadoUsuario() {
        $idCurso = $_POST['idCurso'];

        $idCursoEncriptado = md5($this->obtenerEspecificoEcursosUsuario($idCurso, $this->obtenerSessionUsuarioDaimlerel(), "id"));

        $objConexion = new CConexion();
        $urlSitio = $objConexion->obteneUrlBaseSitio();
        //$urlSitio = "http://localhost/daimlerel";

        $UN_SALTO = "\r\n";
        $DOS_SALTOS = "\r\n\r\n";

        $destinatario = $_POST['correo'];
        $titulo = "Certificado Curso Daimler";
        $mensaje = '<html><head></head><body>';
        $mensaje .="</body></html>";
        /* $mensaje="<html><head></head><body bgcolor=\"#0000ff\">";
          $mensaje .="<font face=\"Arial\" size=6>Prueba HTML </font>";
          $mensaje .="</body></html>"; */
        $responder = $_POST['correo'];
        $remite = "ciudadanodaimler@daimler.com.co";
        $remitente = "Daimler";

        $separador = "_separador_de_trozos_" . md5(uniqid(rand()));

        $cabecera = "Date: " . date("l j F Y, G:i") . $UN_SALTO;
        $cabecera .= "MIME-Version: 1.0" . $UN_SALTO;
        $cabecera .= "From: " . $remitente . "<" . $remite . ">" . $UN_SALTO;
        $cabecera .= "Return-path: " . $remite . $UN_SALTO;
        $cabecera .= "Reply-To: " . $remite . $UN_SALTO;
        $cabecera .="X-Mailer: PHP/" . phpversion() . $UN_SALTO;
        $cabecera .= "Content-Type: multipart/mixed;" . $UN_SALTO;
        $cabecera .= " boundary=$separador" . $DOS_SALTOS;

        // Parte primera -Mensaje en formato HTML
        # Separador inicial
        $texto = "--$separador" . $UN_SALTO;
        # Encabezado parcial
        $texto .="Content-Type: text/html; charset=\"ISO-8859-1\"" . $UN_SALTO;
        $texto .="Content-Transfer-Encoding: 7bit" . $DOS_SALTOS;
        # Contenido de esta parte del mensaje
        $texto .= $mensaje;

        # Separador de partes

        $adj1 = $UN_SALTO . "--$separador" . $UN_SALTO;

        // Parte segunda -Fichero adjunto nº 1
        # Encabezado parcial
        //$urlSitio91f5167c34c400758115c2a6826ec2e3/recursos/certificados/$idCursoEncriptado.pdf

        $adj1 .="Content-Type: application/octet-stream; name=\"../recursos/certificados/$idCursoEncriptado.pdf\"" . $UN_SALTO;
        $adj1 .="Content-Disposition: inline; filename=\"$idCursoEncriptado.pdf\"" . $UN_SALTO;
        $adj1 .="Content-Transfer-Encoding: base64" . $DOS_SALTOS;

        # lectura  del fichero adjunto
        $fp = fopen("../recursos/certificados/$idCursoEncriptado.pdf", "r");
        $buff = fread($fp, filesize("../recursos/certificados/$idCursoEncriptado.pdf"));
        fclose($fp);
        # codificación del fichero adjunto
        $adj1 .=chunk_split(base64_encode($buff));

        # Separador de partes
        // Unión de las diferentes partes para crear
        // el cuerpo del mensaje

        $mensaje = $texto . $adj1;

        //$mensaje=$texto;
        // envio del mensaje

        if (mail($destinatario, $titulo, $mensaje, $cabecera)) {
            echo "si";
        }
    }

    //coprobar tipo variable y redireccionar
    public function comprobarTipoVariableYredireccionar($variable) {

        $this->conectar();

        $objConexion = new CConexion();


        if (ctype_digit($variable)) {
            //echo "numero";
        } else {
            header("Location: " . $objConexion->obteneUrlBaseSitio());
            //echo "cadena";
        }
    }
}

?>