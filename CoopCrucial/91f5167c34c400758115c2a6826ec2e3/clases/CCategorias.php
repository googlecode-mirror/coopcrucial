<?php

require_once('CConexion.php');
require_once('colaborativas.php');

class CCategorias {

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

//Obtener listado categorias
    public function obtenerListadoCategorias() {
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `categoria` ORDER BY idCategoria ASC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);
        if ($numFilas > 0) {
            do {
                echo '<tr>
                    <td>' . $fila[idCategoria] . '</td>
                    <td>' . $fila[nombre] . '</td>
                    <td>' . $fila[imagen] . '</td>
                    <td><a href="categoriasModificar.php?id=' . $fila[idCategoria] . '">Modificar</a> |
                        <a class="btnEliminarElementoLista" title="' . $fila[idCategoria] . '" name="idCategoria"  href="categoria">Eliminar</a>
                        <input type="hidden" id="carpetaImagenBorrar" value="categoria/" />
                    </td></tr>';
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Obtener listado usos
    public function obtenerListadoUsos() {
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `uso` ORDER BY idUso ASC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);
        if ($numFilas > 0) {
            do {
                echo '<tr>
                    <td>' . $fila[idUso] . '</td>
                    <td>' . $fila[nombre] . '</td>
                    <td>' . $fila[imagen] . '</td>
                    <td><a href="usosModificar.php?id=' . $fila[idUso] . '">Modificar</a> |
                        <a class="btnEliminarElementoLista" title="' . $fila[idUso] . '" name="idUso"  href="uso">Eliminar</a>
                        <input type="hidden" id="carpetaImagenBorrar" value="uso/" />
                    </td></tr>';
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

//Crear categorias
    public function crearCategoria() {
        $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES);
        $titulo = htmlspecialchars($_POST['titulo'], ENT_QUOTES);
        $descripcion = htmlspecialchars($_POST['descripcion'], ENT_QUOTES);
        $tipoCategoria = $_POST['tipoCategoria'];
        $imagen = comprobarArchivo("imagen");
        if (isset($_POST['oferta']))
            $oferta = (int) $_POST['oferta'];
        else
            $oferta = "";
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        if ($tipoCategoria == "Tipo") {
            $id = $this->obtenerConsecutivoCategoria();
            $sentencia = "INSERT INTO `categoria` (`idCategoria`, `nombre`, `oferta`, `tituloDescripcion`, `descripcion`) VALUES ('$id', '$nombre', '$oferta', '$titulo', '$descripcion');";
        } elseif ($tipoCategoria == "Uso") {
            $id = $this->obtenerConsecutivoUso();
            $sentencia = "INSERT INTO `uso` (`idUso`, `nombre`, `oferta`, `tituloDescripcion`, `descripcion`) VALUES ('$id', '$nombre', '$oferta', '$titulo', '$descripcion');";
        }
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());

//Crear directorio
        mysql_select_db($this->database_conn, $this->conn);
        if ($tipoCategoria == "Tipo") {
            mkdir("../recursos/categoria/$id");
            if ($imagen != "") {
                $imagen = guardarImagen("../recursos/categoria/$id/", "imagen");
                $sentencia = "UPDATE `categoria` SET `imagen` = '$imagen' WHERE `categoria`.`idCategoria` = $id;";
                $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
            }
        } elseif ($tipoCategoria == "Uso") {
            mkdir("../recursos/uso/$id");
            if ($imagen != "") {
                $imagen = guardarImagen("../recursos/uso/$id/", "imagen");
                $sentencia = "UPDATE `uso` SET `imagen` = '$imagen' WHERE `uso`.`idUso` = $id;";
                $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
            }
        }
        header('Location: ../categorias.php');
    }

//Modificar categoria
    public function modificarCategoria() {

        $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES);
        $titulo = htmlspecialchars($_POST['titulo'], ENT_QUOTES);
        $descripcion = htmlspecialchars($_POST['descripcion'], ENT_QUOTES);
        $tipoCategoria = $_POST['tipoCategoria'];
        $imagen = comprobarArchivo("imagen");
        if (isset($_POST['oferta']))
            $oferta = (int) $_POST['oferta'];
        else
            $oferta = "";
        $id = (int) $_POST['id'];
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        if ($tipoCategoria == "Tipo")
            $sentencia = "UPDATE `categoria` SET  `nombre` =  '$nombre', `oferta` = '$oferta', `tituloDescripcion` = '$titulo', `descripcion` = '$descripcion' WHERE  `categoria`.`idCategoria` = $id;";
        elseif ($tipoCategoria == "Uso")
            $sentencia = "UPDATE `uso` SET  `nombre` =  '$nombre', `oferta` = '$oferta', `tituloDescripcion` = '$titulo', `descripcion` = '$descripcion' WHERE  `uso`.`idUso` = $id;";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());

        mysql_select_db($this->database_conn, $this->conn);
        if ($tipoCategoria == "Tipo") {
            if ($imagen != "") {
                elimarArchivo("../recursos/categoria/$id/", $this->obtenerEspecificoCategoria($id, "imagen"));
                $imagen = guardarImagen("../recursos/categoria/$id/", "imagen");
                $sentencia = "UPDATE `categoria` SET  `imagen` =  '$imagen' WHERE `categoria`.`idCategoria` =$id;";
                $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
            }
        } elseif ($tipoCategoria == "Uso") {
            if ($imagen != "") {
                elimarArchivo("../recursos/uso/$id/", $this->obtenerEspecificoUso($id, "imagen"));
                $imagen = guardarImagen("../recursos/uso/$id/", "imagen");
                $sentencia = "UPDATE `uso` SET  `imagen` =  '$imagen' WHERE `uso`.`idUso` =$id;";
                $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
            }
        }
        header('Location: ../categorias.php');
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

//Obtener especifico Categoria
    public function obtenerEspecificoCategoria($id, $campo) {

        $this->conectar();

        $id = (int) $id;

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `categoria` WHERE idCategoria = $id";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);

        if ($numFilas > 0) {
            return $fila[$campo];
        }
    }

//Obtener especifico Uso
    public function obtenerEspecificoUso($id, $campo) {

        $this->conectar();

        $id = (int) $id;

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `uso` WHERE idUso= $id";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);

        if ($numFilas > 0) {
            return $fila[$campo];
        }
    }

//Especifico consecutivo Categoria
    public function obtenerConsecutivoCategoria() {
        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `categoria` ORDER BY idCategoria DESC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultado);
        return $fila[idCategoria] + 1;
    }

//Especifico consecutivo Uso
    public function obtenerConsecutivoUso() {
        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `uso` ORDER BY idUso DESC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultado);
        return $fila[idUso] + 1;
    }

}

?>