<?php

require_once('CConexion.php');
require_once('colaborativas.php');

class CProductos {

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

    //Obtener listado productos
    public function obtenerListadoProductos() {

        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT `idProducto`, `producto`.`nombre`, `producto`.`descripcion`, `garantia`, `marca`, `precio`, `precioWeb`, `vecesComprado`, `categoria`.nombre as `categoria` FROM `producto` JOIN `categoria` USING (idCategoria) ORDER BY idProducto ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);

        if ($numFilas > 0) {
            do {
                echo '<tr>
                    <td>' . $fila[idProducto] . '</td>
                    <td>' . $fila[nombre] . '</td>
                    <td>' . $fila[precio] . '</td>
                    <td>' . $fila[precioWeb] . '</td>
                    <td>' . $fila[vecesComprado] . ' veces</td>
                    <td>' . $fila[categoria] . '</td>
                    <td><a href="productosModificar.php?id=' . $fila[idProducto] . '">Modificar</a> |
                        <a class="btnEliminarElementoLista" title="' . $fila[idProducto] . '" name="idProducto"  href="producto">Eliminar</a>
                        <input type="hidden" id="carpetaImagenBorrar" value="producto/" />
                    </td></tr>';
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Crear productos
    public function crearProducto() {
        $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES);
        $descripcion = htmlspecialchars($_POST['descripcion'], ENT_QUOTES);
        $garantia = htmlspecialchars($_POST['garantia'], ENT_QUOTES);
        $marca = htmlspecialchars($_POST['marca'], ENT_QUOTES);
        $precio = (int) $_POST['precio'];
        $precioWeb = (int) $_POST['precioWeb'];
        $tags = htmlspecialchars($_POST['tags']);
        if (isset($_POST['destacado']) && $_POST['destacado'] == 1)
            $destacado = (int) $_POST['destacado'];
        else
            $destacado = 0;
        if (isset($_POST['recomendado']) && $_POST['recomendado'] == 1)
            $recomendado = (int) $_POST['recomendado'];
        else
            $recomendado = 0;
        $idCategoria = (int) $_POST['idCategoria'];

        $id = $this->obtenerConsecutivoProducto();
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "INSERT INTO `producto` (`idProducto`, `nombre`, `descripcion`, `garantia`, `marca`, `precio`, `precioWeb`, `vecesComprado`, `tags`, `destacado`, `recomendado`, `numeroVotos`, `calificacion`, `idCategoria`) VALUES ('$id', '$nombre', '$descripcion', '$garantia', '$marca', '$precio', '$precioWeb', '0', '$tags', '$destacado', '$recomendado', '0', '1', '$idCategoria');";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());

        //Usos
        if (isset($_POST['idUso'])) {
            mysql_select_db($this->database_conn, $this->conn);
            for ($i = 0; $i < count($_POST['idUso']); $i++) {
                if ($_POST['idUso'][$i] != "") {
                    $idUso = (int) $this->obtenerConsecutivoUsoProducto();
                    $sentencia = "INSERT INTO `uso_producto` (`idUsoProducto`, `idProducto`, `idUso`) VALUES ('$idUso', '" . $id . "', '" . $_POST['idUso'][$i] . "');";
                    $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
                }
            }
        }

        //Guarda especificaciones
        if (isset($_POST['tituloEspecificacion']) && isset($_POST['descripcionEspecificacion'])) {
            mysql_select_db($this->database_conn, $this->conn);
            for ($i = 0; $i < count($_POST['tituloEspecificacion']); $i++) {
                if ($_POST['tituloEspecificacion'][$i] != "" && $_POST['descripcionEspecificacion'][$i] != "") {
                    $idEspecificacion = (int) $this->obtenerConsecutivoEspecificacion();
                    $sentencia = "INSERT INTO `especificacion` (`idEspecificacion`, `titulo`, `descripcion`, `idProducto`) VALUES ('$idEspecificacion', '" . htmlspecialchars($_POST['tituloEspecificacion'][$i]) . "', '" . htmlspecialchars($_POST['descripcionEspecificacion'][$i]) . "', '$id');";
                    $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
                }
            }
        }

        //Guarda caracteristicas
        if (isset($_POST['nombreCaracteristica']) && isset($_POST['descripcionCaracteristica'])) {
            mysql_select_db($this->database_conn, $this->conn);
            for ($i = 0; $i < count($_POST['nombreCaracteristica']); $i++) {
                if ($_POST['nombreCaracteristica'][$i] != "" && $_POST['descripcionCaracteristica'][$i] != "") {
                    $idCaracteristica = (int) $this->obtenerConsecutivoCaracteristica();
                    $sentencia = "INSERT INTO `caracteristica` (`idCaracteristica`, `nombre`, `descripcion`, `idProducto`) VALUES ('$idCaracteristica', '" . htmlspecialchars($_POST['nombreCaracteristica'][$i]) . "', '" . htmlspecialchars($_POST['descripcionCaracteristica'][$i]) . "', '$id');";
                    $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
                }
            }
        }

        //Crear directorio y guarda imagenes
        mkdir("../recursos/producto/$id");
        if (comprobarArchivos("imagenes")) {
            $imagenes = guardarImagenes("../recursos/producto/$id/", "imagenes");
            //Redimensionar imagen
            //$imagen2 = redimensionarImagen(103,108,"../recursos/noticias/$id/$imagen","../recursos/noticias/$id/pequena","pequena");
            mysql_select_db($this->database_conn, $this->conn);
            foreach ($imagenes as $img) {
                $idImagenProducto = $this->obtenerConsecutivoImagenProducto();
                $sentencia = "INSERT INTO `imagen_producto` (`idImagenProducto`, `nombre`, `idProducto`) VALUES ('$idImagenProducto', '$img', '$id');";
                $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
            }
        }

        header('Location: ../productos.php');
    }

    //Modificar categoria
    public function modificarProducto() {
        $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES);
        $descripcion = htmlspecialchars($_POST['descripcion'], ENT_QUOTES);
        $garantia = htmlspecialchars($_POST['garantia'], ENT_QUOTES);
        $marca = htmlspecialchars($_POST['marca'], ENT_QUOTES);
        $precio = (int) $_POST['precio'];
        $precioWeb = (int) $_POST['precioWeb'];
        $tags = htmlspecialchars($_POST['tags']);
        if (isset($_POST['destacado']) && $_POST['destacado'] == 1)
            $destacado = (int) $_POST['destacado'];
        else
            $destacado = 0;
        if (isset($_POST['recomendado']) && $_POST['recomendado'] == 1)
            $recomendado = (int) $_POST['recomendado'];
        else
            $recomendado = 0;
        $idCategoria = (int) $_POST['idCategoria'];
        $idProducto = (int) $_POST['idProducto'];

        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "UPDATE `producto` SET  `nombre` =  '$nombre', `descripcion` = '$descripcion', `garantia` = '$garantia', `marca`='$marca', `precio`='$precio', `precioWeb`='$precioWeb', `tags` = '$tags', `destacado`='$destacado', `recomendado`='$recomendado' WHERE  `producto`.`idProducto` = $idProducto;";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
//        var_dump($this->obtenerImagenesEspecificasProducto($idProducto, "nombre"));
//        die;
        /* if ($imagen != "") {

          elimarArchivo("../recursos/categoria/$id/", $this->obtenerEspecificoCategoria($id, "imagen"));
          $imagen = guardarImagen("../recursos/categoria/$id/", "imagen");

          mysql_select_db($this->database_conn, $this->conn);
          $sentencia = "UPDATE `categoria` SET  `imagen` =  '$imagen' WHERE  `categoria`.`idCategoria` =$id;";

          $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
          } */

        //Guarda nuevas imagenes
        if (comprobarArchivos("imagenes")) {
            $imagenes = guardarImagenes("../recursos/producto/$idProducto/", "imagenes");
            //Redimensionar imagen
            //$imagen2 = redimensionarImagen(103,108,"../recursos/noticias/$id/$imagen","../recursos/noticias/$id/pequena","pequena");
            mysql_select_db($this->database_conn, $this->conn);
            foreach ($imagenes as $img) {
                $idImagenProducto = $this->obtenerConsecutivoImagenProducto();
                $sentencia = "INSERT INTO `imagen_producto` (`idImagenProducto`, `nombre`, `idProducto`) VALUES ('$idImagenProducto', '$img', '$idProducto');";
                $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
            }
        }
        header('Location: ../productos.php');
    }

    //Obtener especifico producto
    public function obtenerEspecificoProducto($id, $campo) {
        $this->conectar();
        $id = (int) $id;
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `producto` WHERE idProducto = $id";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);
        if ($numFilas > 0) {
            return $fila[$campo];
        }
    }

    //Obtener imagen especifica producto
    public function obtenerImagenesEspecificasProducto($id, $campo) {
        $this->conectar();
        $id = (int) $id;
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `imagen_producto` WHERE idImagenProducto = $id";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);
        $imagenes = array();
        if ($numFilas > 0) {
            do {
                $imagenes[$fila['idImagenProducto']] = $fila['nombre'];
            } while ($fila = mysql_fetch_assoc($resultado));
            return $imagenes;
        }
    }

    //Obtener listado categorias de producto
    public function obtenerListadoCategoriasProducto() {
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `categoria` ORDER BY idCategoria ASC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);
        if ($numFilas > 0) {
            do {
                echo "<option value='" . $fila[idCategoria] . "'>" . $fila[nombre] . "</option>";
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Obtener listado categorias por usos de producto
    public function obtenerListadoUsosProducto() {
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `uso` ORDER BY idUso ASC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);
        if ($numFilas > 0) {
            do {
                echo "<option value='" . $fila[idUso] . "'>" . $fila[nombre] . "</option>";
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Obtener listado imagenes de producto
    public function obtenerListadoImagenesProducto($idProducto) {
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `imagen_producto` WHERE `idProducto`='$idProducto' ORDER BY idImagenProducto ASC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);
        if ($numFilas > 0) {
            do {
                echo"<label>Imagen 1</label>
                     <input type='file' name='imagenes[]' />
                     <div class='yoxview'>
                        <a class='yoxviewLink' href='recursos/producto/" . $idProducto . "/" . $fila['nombre'] . "' title='" . $fila['idImagenProducto'] . "'>" . $fila['nombre'] . "</a>
                        <a href='javascript:void(0);' onclick='eliminarImagenProducto(" . $idProducto . "," . $fila['idImagenProducto'] . ");'>Eliminar</a>
                     </div><br/>";
                //TODO metodos imagen
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Eliminar una imagen del producto
    public function eliminarImagenProducto($p, $i) {
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "DELETE FROM `imagen_producto` WHERE `idImagenProducto`='$i'";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        return elimarArchivo("../recursos/productos/$p/", $this->obtenerImagenesEspecificasProducto($i, "nombre"));
    }

    //Obtener listado caracteristicas de producto
    public function obtenerListadoCaracteristicasProducto($idProducto) {
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `caracteristica` WHERE `idProducto`='$idProducto' ORDER BY idCaracteristica ASC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);
        if ($numFilas > 0) {
            do {
                echo"<fieldset>
                        <legend>Caracteristica 1</legend>
                        <label>Nombre</label>
                        <input type='text' name='nombreCaracteristica[]' class='other' value='" . $fila['nombre'] . "'/>
                        <label>Descripcion</label>
                        <textarea name='descripcionCaracteristica[]' cols='100' rows='10' class='other' style='width: 550px; height: 100px; max-width: 550px; max-height: 100px; min-width: 550px; min-height: 100px;'>" . $fila['descripcion'] . "</textarea>
                            <a href='javascript:void(0);' onclick='eliminarCaracteristica(" . $idProducto . "," . $fila['idCaracteristica'] . ");'>Eliminar</a>
                     </fieldset><br/>";
                //TODO metodos caracteristicas
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Eliminar una caracteristica del producto
    public function eliminarCaracteristica($p, $c) {
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "DELETE FROM `caracteristica` WHERE `idCaracteristica`='$c'";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
    }
    
    //Obtener listado especificaciones de producto
    public function obtenerListadoEspecificacionesProducto($idProducto) {
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `especificacion` WHERE `idProducto`='$idProducto' ORDER BY idEspecificacion ASC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);
        if ($numFilas > 0) {
            do {
                echo"<fieldset>
                        <legend>Especificacion 1</legend>
                        <label>Titulo</label>
                        <input type='text' name='tituloEspecificacion[]' class='other' value='" . $fila['titulo'] . "'/>
                        <label>Descripcion</label>
                        <textarea name='descripcionEspecificacion[]' cols='100' rows='10' style='width: 550px; height: 100px; max-width: 550px; max-height: 100px; min-width: 550px; min-height: 100px;'>" . $fila['descripcion'] . "</textarea>
                            <a href='javascript:void(0);' onclick='eliminarEspecificacion(" . $idProducto . "," . $fila['idEspecificacion'] . ");'>Eliminar</a>
                     </fieldset><br/>";
                //TODO metodos especificaciones
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Eliminar una especificacion del producto
    public function eliminarEspecificacion($p, $e) {
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "DELETE FROM `especificacion` WHERE `idEspecificacion`='$e'";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
    }

    //Agrega y retorna los usos de un producto
    public function agregarUso($uso) {
        return "listo";
    }

    //Especifica imagen producto
    public function obtenerEspecificoImagenProducto() {
        
    }

    //Especifico consecutivo producto
    public function obtenerConsecutivoProducto() {
        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `producto` ORDER BY idProducto DESC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultado);
        return $fila[idProducto] + 1;
    }

    //Especifico consecutivo imagenProducto
    public function obtenerConsecutivoImagenProducto() {
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `imagen_producto` ORDER BY idImagenProducto DESC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultado);
        return $fila[idImagenProducto] + 1;
    }

    //Especifico consecutivo uso_producto
    public function obtenerConsecutivoUsoProducto() {
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `uso_producto` ORDER BY idUsoProducto DESC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultado);
        return $fila[idUsoProducto] + 1;
    }

    //Especifico consecutivo especificacion
    public function obtenerConsecutivoEspecificacion() {
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `especificacion` ORDER BY idEspecificacion DESC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultado);
        return $fila[idEspecificacion] + 1;
    }

    //Especifico consecutivo caracteristica
    public function obtenerConsecutivoCaracteristica() {
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `caracteristica` ORDER BY idCaracteristica DESC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultado);
        return $fila[idCaracteristica] + 1;
    }

    //Obtener listado Eayudas
    public function obtenerListadoEayudas($idEcurso) {

        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `eayudascurso` WHERE `idecurso`=$idEcurso ORDER BY id ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);


        if ($numFilas > 0) {
            do {
                echo ' <div>
				<a>' . $fila[adjunto] . '</a>
				<a class="btnEliminarElementoLista" title="' . $fila[id] . '"  href="eayudascurso">Eliminar</a><input type="hidden" id="carpetaImagenBorrar" value="ecurso/' . $idEcurso . '/ayudas/" /><input type="hidden" id="idEcursoItemLista" value="' . $idEcurso . '" /></div>';
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Agregar ayuda ecursos
    public function agregarAyudaEcurso() {

        $imagen = comprobarArchivo("imagen");
        $idEcurso = $_POST['idEcurso'];

        $id = $this->obtenerConsecutivoAyudaEcurso();

        $this->conectar();



        if ($imagen != "") {

            mkdir("../recursos/ecurso/$idEcurso/ayudas/$id");
            $imagen = guardarImagen("../recursos/ecurso/$idEcurso/ayudas/$id/", "imagen");

            mysql_select_db($this->database_conn, $this->conn);
            $sentencia = "INSERT INTO `eayudascurso` (`id`, `adjunto`, `idecurso`) VALUES ('$id', '$imagen', '$idEcurso');";

            $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        }




        header('Location: ../cursosAyudas.php?id=' . $idEcurso);
    }

    //Especifico consecutivo ayudaecurso
    public function obtenerConsecutivoAyudaEcurso() {
        $this->conectar();


        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `eayudascurso` ORDER BY id DESC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultado);
        return $fila[id] + 1;
    }

    //Agregar pregunta ecursos
    public function agregarPreguntaEcurso() {

        $pregunta = htmlspecialchars($_POST['pregunta'], ENT_QUOTES);
        $imagen = comprobarArchivo("imagen");
        $titulo = htmlspecialchars($_POST['titulo'], ENT_QUOTES);
        $descripcionCompleta = htmlspecialchars($_POST['descripcionCompleta'], ENT_QUOTES);
        $idEcurso = $_POST['idEcurso'];

        $id = $this->obtenerConsecutivoPreguntasEcurso();

        $this->conectar();


        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "INSERT INTO `epregunta` (`id`, `pregunta`, `titulo`, `descripcion`, `idecurso`) VALUES ('$id', '$pregunta', '$titulo', '$descripcionCompleta', '$idEcurso');";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());

        //Crear directorio
        mkdir("../recursos/ecurso/$idEcurso/pregunta/$id");
        mkdir("../recursos/ecurso/$idEcurso/pregunta/$id/adjunto");
        mkdir("../recursos/ecurso/$idEcurso/pregunta/$id/slides");
        mkdir("../recursos/ecurso/$idEcurso/pregunta/$id/slides/ayudas");

        if ($imagen != "") {


            $imagen = guardarImagen("../recursos/ecurso/$idEcurso/pregunta/$id/", "imagen");

            mysql_select_db($this->database_conn, $this->conn);
            $sentencia = "UPDATE `epregunta` SET  `imagen` =  '$imagen' WHERE  `epregunta`.`id` =$id;";

            $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        }




        header('Location: ../cursosPreguntas.php?id=' . $idEcurso);
    }

    //Modificar pregunta ecursos
    public function modificarPreguntaEcurso() {

        $pregunta = htmlspecialchars($_POST['pregunta'], ENT_QUOTES);
        $imagen = comprobarArchivo("imagen");
        $imagen2 = comprobarArchivo("imagen2");
        $titulo = htmlspecialchars($_POST['titulo'], ENT_QUOTES);
        $descripcionCompleta = htmlspecialchars($_POST['descripcionCompleta'], ENT_QUOTES);
        $idEcurso = $_POST['idEcurso'];

        $id = $_POST['id'];

        $this->conectar();


        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "UPDATE  `epregunta` SET  `pregunta` =  '$pregunta',
					`titulo` =  '$titulo',
					`descripcion` =  '$descripcionCompleta' WHERE  `epregunta`.`id` =$id;";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());

        if ($imagen != "") {

            elimarArchivo("../recursos/ecurso/$idEcurso/pregunta/$id/", $this->obtenerEspecificoPreguntaEcurso($id, "imagen"));
            $imagen = guardarImagen("../recursos/ecurso/$idEcurso/pregunta/$id/", "imagen");

            mysql_select_db($this->database_conn, $this->conn);
            $sentencia = "UPDATE `epregunta` SET  `imagen` =  '$imagen' WHERE  `epregunta`.`id` =$id;";

            $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        }

        if ($imagen2 != "") {
            $idAyudaPregunta = $this->obtenerConsecutivoAyudaPreguntaEcurso();

            mkdir("../recursos/ecurso/$idEcurso/pregunta/$id/adjunto/$idAyudaPregunta");

            $imagen2 = guardarImagen("../recursos/ecurso/$idEcurso/pregunta/$id/adjunto/$idAyudaPregunta/", "imagen2");

            mysql_select_db($this->database_conn, $this->conn);
            $sentencia = "INSERT INTO `eayudapregunta` (`id`, `adjunto`, `idpregunta`) VALUES ('$idAyudaPregunta', '$imagen2', '$id');";

            $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        }




        header('Location: ../cursosPreguntasModificar.php?id=' . $id);
    }

    //Obtener listado Eayudas pregunta
    public function obtenerListadoEayudasPregunta($idPregunta) {

        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `eayudapregunta` WHERE `idpregunta`=$idPregunta ORDER BY id ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);


        if ($numFilas > 0) {
            do {
                //$idEpregunta = $this->obtenerEspecificoSlidesEpregunta($fila[ideslidepregunta],"idepregunta");
                $idEcurso = $this->obtenerEspecificoPreguntaEcurso($idPregunta, "idecurso");

                echo ' <div>
				<a>' . $fila[adjunto] . '</a>
				<a class="btnEliminarElementoLista" title="' . $fila[id] . '"  href="eayudapregunta">Eliminar</a><input type="hidden" id="carpetaImagenBorrar" value="ecurso/' . $idEcurso . '/pregunta/' . $idPregunta . '/adjunto/" /><input type="hidden" id="idPreguntatemLista" value="' . $idPregunta . '" /></div>';
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Obtener listado ayudas epregunta web
    public function obtenerListadoAyudasEpreguntasWeb($idPregunta) {

        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `eayudapregunta` WHERE idpregunta=$idPregunta ORDER BY id ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);

        $objConexion = new CConexion();


        if ($numFilas > 0) {


            do {
                $idEcurso = $this->obtenerEspecificoPreguntaEcurso($fila[idpregunta], "idecurso");

                echo '<div class="ItemContenedorDescargas">
                    <table width="0" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><img src="' . $objConexion->obteneUrlBaseSitio() . 'imagenes/icono_clip.jpg" width="29" height="25" /></td>
    <td class="etiqueta"><a href="' . $objConexion->obteneUrlBaseSitio() . '/91f5167c34c400758115c2a6826ec2e3/recursos/ecurso/' . $idEcurso . '/pregunta/' . $fila[idpregunta] . '/adjunto/' . $fila[id] . '/' . $fila[adjunto] . '" target="_blank">Descargar Anexos</a></td>
  </tr>
</table>

                    </div>';
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Obtener consecutivo ayudaecurso
    public function obtenerConsecutivoPreguntasEcurso() {
        $this->conectar();


        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `epregunta` ORDER BY id DESC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultado);
        return $fila[id] + 1;
    }

    //Obtener consecutivo ayudaepregunta
    public function obtenerConsecutivoAyudaPreguntaEcurso() {
        $this->conectar();


        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `eayudapregunta` ORDER BY id DESC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultado);
        return $fila[id] + 1;
    }

    //Obtener listado epreguntas
    public function obtenerListadoEpreguntas() {

        $this->conectar();

        $idEcurso = (int) $_GET['id'];

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `epregunta` WHERE idecurso=$idEcurso ORDER BY id ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);


        if ($numFilas > 0) {
            do {
                echo '<tr>
<td>' . $fila[id] . '</td><td>' . $fila[titulo] . '</td><td><a href="' . $fila[id] . '" class="BtnModificarPregunta">Modificar</a> | <a href="cursosSlides.php?id=' . $fila[id] . '" target="_blank">Slides</a> | <a href="cursosRespuestas.php?id=' . $fila[id] . '" target="_blank">Respuestas</a> | <a class="btnEliminarElementoLista" title="' . $fila[id] . '"  href="epregunta">Eliminar</a><input type="hidden" id="carpetaImagenBorrar" value="ecurso/' . $idEcurso . '/pregunta/" /><input type="hidden" id="idEcursoItemLista" value="' . $idEcurso . '" /></td></tr>';
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Obtener especifico preguntaecurso
    public function obtenerEspecificoPreguntaEcurso($id, $campo) {

        $this->conectar();

        $id = (int) $id;

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `epregunta` WHERE id = $id";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);

        if ($numFilas > 0) {
            return $fila[$campo];
        }
    }

    //Obtener listado erespuestas
    public function obtenerListadoErespuestas() {

        $this->conectar();

        $idepregunta = (int) $_GET['id'];

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `erespuesta` WHERE idepregunta=$idepregunta ORDER BY id ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);


        if ($numFilas > 0) {
            do {
                echo '<tr>
<td>' . $fila[respuesta] . '</td><td>' . $fila[tipo] . '</td><td><a href="' . $fila[id] . '" class="BtnModificarRespuesta">Modificar</a> | <a class="btnEliminarElementoLista" title="' . $fila[id] . '"  href="erespuesta">Eliminar</a><input type="hidden" id="carpetaImagenBorrar" value="" /><input type="hidden" id="idEpreguntaItemLista" value="' . $idepregunta . '" /></td></tr>';
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Agregar pregunta erespuesta
    public function agregarErespuesta() {

        $respuesta = htmlspecialchars($_POST['respuesta'], ENT_QUOTES);
        $tipo = htmlspecialchars($_POST['tipo'], ENT_QUOTES);
        $idEpregunta = $_POST['idEpregunta'];

        $id = $this->obtenerConsecutivoPreguntasErespuesta();

        $this->conectar();


        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "INSERT INTO `erespuesta` (`id`, `respuesta`, `tipo`, `idepregunta`) VALUES ('$id', '$respuesta', '$tipo', '$idEpregunta');";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());



        header('Location: ../cursosRespuestas.php?id=' . $idEpregunta);
    }

    //modificar pregunta erespuesta
    public function modificarErespuesta() {

        $respuesta = htmlspecialchars($_POST['respuesta'], ENT_QUOTES);
        $tipo = htmlspecialchars($_POST['tipo'], ENT_QUOTES);
        $idEpregunta = $_POST['idEpregunta'];

        $id = $_POST['id'];

        $this->conectar();


        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "UPDATE `erespuesta` SET  `respuesta` =  '$respuesta',
`tipo` =  '$tipo' WHERE  `erespuesta`.`id` =$id;";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());



        header('Location: ../cursosRespuestasModificar.php?id=' . $id);
    }

    //Obtener consecutivo erespuesta
    public function obtenerConsecutivoPreguntasErespuesta() {
        $this->conectar();


        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `erespuesta` ORDER BY id DESC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultado);
        return $fila[id] + 1;
    }

    //Obtener especifico erespuestas
    public function obtenerEspecificoErepuestas($id, $campo) {

        $this->conectar();

        $id = (int) $id;

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `erespuesta` WHERE id = $id";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);

        if ($numFilas > 0) {
            return $fila[$campo];
        }
    }

    //Obtener listado slidesepreguntas
    public function obtenerListadoSlidesEpreguntas() {

        $this->conectar();

        $idepregunta = (int) $_GET['id'];
        $idEcurso = $this->obtenerEspecificoPreguntaEcurso($idepregunta, "idecurso");

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `eslidepregunta` WHERE idepregunta=$idepregunta ORDER BY id ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);


        if ($numFilas > 0) {
            do {
                echo '<tr>
<td>' . $fila[id] . '</td><td>' . $fila[titulo] . '</td><td><a href="' . $fila[id] . '" class="BtnModificarSlide">Modificar</a> | <a class="btnEliminarElementoLista" title="' . $fila[id] . '"  href="eslidepregunta">Eliminar</a><input type="hidden" id="carpetaImagenBorrar" value="ecurso/' . $idEcurso . '/pregunta/' . $idepregunta . '/slides/" /><input type="hidden" id="idEpreguntaItemLista" value="' . $idepregunta . '" /></td></tr>';
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Agregar pregunta slideepregunta
    public function agregarSlideEpregunta() {

        $imagen = comprobarArchivo("imagen");
        $titulo = htmlspecialchars($_POST['titulo'], ENT_QUOTES);
        $descripcionCompleta = htmlspecialchars($_POST['descripcionCompleta'], ENT_QUOTES);
        $titulo2 = htmlspecialchars($_POST['titulo2'], ENT_QUOTES);
        $descripcionCompleta2 = htmlspecialchars($_POST['descripcionCompleta2'], ENT_QUOTES);
        $idEpregunta = $_POST['idEpregunta'];
        $idEcurso = $this->obtenerEspecificoPreguntaEcurso($idEpregunta, "idecurso");

        $id = $this->obtenerConsecutivoSlideEpregunta();




        $this->conectar();


        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "INSERT INTO `eslidepregunta` (`id`, `titulo`, `descripcion`, `idepregunta`, `titulo2`, `descripcion2`) VALUES ('$id', '$titulo', '$descripcionCompleta', '$idEpregunta', '$titulo2', '$descripcionCompleta2');";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());

        //Crear directorio
        mkdir("../recursos/ecurso/$idEcurso/pregunta/$idEpregunta/slides/$id");
        mkdir("../recursos/ecurso/$idEcurso/pregunta/$idEpregunta/slides/ayudas/$id");

        if ($imagen != "") {


            $imagen = guardarImagen("../recursos/ecurso/$idEcurso/pregunta/$idEpregunta/slides/$id/", "imagen");

            mysql_select_db($this->database_conn, $this->conn);
            $sentencia = "UPDATE `eslidepregunta` SET  `imagen` =  '$imagen' WHERE  `eslidepregunta`.`id` =$id;";

            $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        }




        header('Location: ../cursosSlides.php?id=' . $idEpregunta);
    }

    //Modificar pregunta slideepregunta
    public function modificarSlideEpregunta() {

        $imagen = comprobarArchivo("imagen");
        $imagen2 = comprobarArchivo("imagen2");
        $titulo = htmlspecialchars($_POST['titulo'], ENT_QUOTES);
        $descripcionCompleta = htmlspecialchars($_POST['descripcionCompleta'], ENT_QUOTES);
        $titulo2 = htmlspecialchars($_POST['titulo2'], ENT_QUOTES);
        $descripcionCompleta2 = htmlspecialchars($_POST['descripcionCompleta2'], ENT_QUOTES);
        $idEpregunta = $_POST['idEpregunta'];
        $idEcurso = $this->obtenerEspecificoPreguntaEcurso($idEpregunta, "idecurso");

        $id = $_POST['id'];




        $this->conectar();


        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "UPDATE `eslidepregunta` SET
					`titulo` =  '$titulo',
					`descripcion` =  '$descripcionCompleta',
					`titulo2` =  '$titulo2',
					`descripcion2` =  '$descripcionCompleta2'
					 WHERE  `eslidepregunta`.`id` =$id;";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());


        if ($imagen != "") {

            elimarArchivo("../recursos/ecurso/$idEcurso/pregunta/$idEpregunta/slides/$id/", $this->obtenerEspecificoSlidesEpregunta($id, "imagen"));
            $imagen = guardarImagen("../recursos/ecurso/$idEcurso/pregunta/$idEpregunta/slides/$id/", "imagen");

            mysql_select_db($this->database_conn, $this->conn);
            $sentencia = "UPDATE `eslidepregunta` SET  `imagen` =  '$imagen' WHERE  `eslidepregunta`.`id` =$id;";

            $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        }

        //adjunto
        if ($imagen2 != "") {
            $idCarpetaAdjunto = $this->obtenerConsecutivoAyudaSlideEpregunta();
            mkdir("../recursos/ecurso/$idEcurso/pregunta/$idEpregunta/slides/ayudas/$id/$idCarpetaAdjunto");

            $imagen2 = guardarImagen("../recursos/ecurso/$idEcurso/pregunta/$idEpregunta/slides/ayudas/$id/$idCarpetaAdjunto/", "imagen2");

            mysql_select_db($this->database_conn, $this->conn);
            $sentencia = "INSERT INTO `eayudasslide` (`id`, `adjunto`, `ideslidepregunta`) VALUES ('$idCarpetaAdjunto', '$imagen2', '$id');";

            $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        }




        header('Location: ../cursosSlidesModificar.php?id=' . $id);
    }

    //Obtener consecutivo ayuda slideepregunta
    public function obtenerConsecutivoAyudaSlideEpregunta() {
        $this->conectar();


        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `eayudasslide` ORDER BY id DESC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultado);
        return $fila[id] + 1;
    }

    //Obtener listado Eayudas slide
    public function obtenerListadoEayudasSlide($idSlide) {

        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `eayudasslide` WHERE `ideslidepregunta`=$idSlide ORDER BY id ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);


        if ($numFilas > 0) {
            do {
                $idEpregunta = $this->obtenerEspecificoSlidesEpregunta($fila[ideslidepregunta], "idepregunta");
                $idEcurso = $this->obtenerEspecificoPreguntaEcurso($idEpregunta, "idecurso");

                echo ' <div>
				<a>' . $fila[adjunto] . '</a>
				<a class="btnEliminarElementoLista" title="' . $fila[id] . '"  href="eayudasslide">Eliminar</a><input type="hidden" id="carpetaImagenBorrar" value="ecurso/' . $idEcurso . '/pregunta/' . $idEpregunta . '/slides/ayudas/' . $fila[ideslidepregunta] . '/" /><input type="hidden" id="idSlideItemLista" value="' . $fila[ideslidepregunta] . '" /></div>';
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Obtener consecutivo slideepregunta
    public function obtenerConsecutivoSlideEpregunta() {
        $this->conectar();


        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `eslidepregunta` ORDER BY id DESC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultado);
        return $fila[id] + 1;
    }

    //Obtener especifico erespuestas
    public function obtenerEspecificoSlidesEpregunta($id, $campo) {

        $this->conectar();

        $id = (int) $id;

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `eslidepregunta` WHERE id = $id";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);

        if ($numFilas > 0) {
            return $fila[$campo];
        }
    }

    //Obtener listado ecursos web
    public function obtenerListadoEcursosWeb() {

        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `ecurso` ORDER BY id ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);

        $objConexion = new CConexion();


        if ($numFilas > 0) {
            $contador = 1;
            $contadorCursos = 1;

            do {
                if ($contador == 3) {

                    echo '<div class="ItemLitadosCursos" style="margin:0px 0px 0px 0px;" onclick="document.location.href=\'' . $objConexion->obteneUrlBaseSitio() . 'descripcioncurso/' . $fila[id] . '\'">
					
					
            	<img src="' . $objConexion->obteneUrlBaseSitio() . '91f5167c34c400758115c2a6826ec2e3/recursos/ecurso/getimage.php?carpeta=' . $fila[id] . '&img=' . $fila[imagen] . '&w=280&h=200&exact" width="280" height="200" />
                
                <div class="relativo">
                <div style="position:absolute; left: 203px; top: -65px;">
                	<div class="ConsecutivoItemLitadosCursos">' . $fila[id] . '</div>
                </div>
              </div>
              
              
              <div class="TituloItemLitadosCursos">
              ' . $fila[titulo] . '
              </div>
              
              <div class="DescripcionItemLitadosCursos">
              ' . $fila[descripcioncorta] . '
              </div>
                
          </div>';

                    $contador = 1;
                } else {

                    echo '<div class="ItemLitadosCursos" onclick="document.location.href=\'' . $objConexion->obteneUrlBaseSitio() . 'descripcioncurso/' . $fila[id] . '\'">
            	<img src="' . $objConexion->obteneUrlBaseSitio() . '91f5167c34c400758115c2a6826ec2e3/recursos/ecurso/getimage.php?carpeta=' . $fila[id] . '&img=' . $fila[imagen] . '&w=280&h=200&exact" width="280" height="200" />
                
                <div class="relativo">
                <div style="position:absolute; left: 203px; top: -65px;">
                	<div class="ConsecutivoItemLitadosCursos">' . $fila[id] . '</div>
                </div>
              </div>
              
              
              <div class="TituloItemLitadosCursos">
              ' . $fila[titulo] . '
              </div>
              
              <div class="DescripcionItemLitadosCursos">
              ' . $fila[descripcioncorta] . '
              </div>
                
          </div>';

                    $contador += 1;
                }


                $contadorCursos += 1;
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Obtener numero de preguntas ecurso
    public function obtenerNumeroPreguntasEcurso($idCurso) {

        $this->conectar();

        $id = (int) $id;

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `epregunta` WHERE `idecurso` = $idCurso";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);


        return $numFilas;
    }

    //Comprobar usuario registrado
    public function comprobarUsuarioRegistrado() {


        $this->conectar();

        $idUsuario = (int) $_POST['idUsuario'];

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `usuariosconcurso` WHERE id=$idUsuario";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);



        if ($numFilas > 0) {
            $this->iniciarSessionUsuarioDaimlerel($idUsuario);
            echo $msg = "si";
        } else {
            echo $msg = "no";
        }
    }

    //Iniciar session usuario registrado
    public function iniciarSessionUsuarioDaimlerel($idUsuario) {
        setcookie("UsuarioDaimlerel", $idUsuario, time() + 36000, "/", "");
    }

    //Obtener session usuario registrado
    public function obtenerSessionUsuarioDaimlerel() {
        return $_COOKIE["UsuarioDaimlerel"];
    }

    //Cerrar session usuario registrado
    public function cerrarSessionUsuarioDaimlerel() {
        setcookie("UsuarioDaimlerel", $idUsuario, time() - 36000, "/", "");
    }

    //Obtener especifico usuarios registrados
    public function obtenerEspecificoUsuariosRegistrados($id, $campo) {

        $this->conectar();

        $id = (int) $id;

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `usuariosconcurso` WHERE id=$id";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);

        if ($numFilas > 0) {
            return $fila[$campo];
        }
    }

    //Obtener curso anterior y siguiente paginadores
    public function obtenerCursoAnteriorYsiguientePaginadores($idCurso) {

        $this->conectar();

        $id = (int) $idCurso;

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `ecurso` ORDER BY id ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);

        if ($numFilas > 0) {

            $arrayIdsCursos = array();
            $contador = 0;
            $posicionCursoActual;
            do {

                $arrayIdsCursos[$contador] = $fila[id];

                if ($fila[id] == $idCurso) {
                    $posicionCursoActual = $contador;
                }

                $contador += 1;
            } while ($fila = mysql_fetch_assoc($resultado));

            //print_r($arrayIdsCursos); echo "<br>";

            $posicionCursoActual = (int) $posicionCursoActual;
            $posicionCursoAnterior = $posicionCursoActual - 1;
            $posicionCursoSiguiente = $posicionCursoActual + 1;

            //echo "posicion curso actual: ".$posicionCursoActual;echo "<br>";
            //echo "posicion curso anterior: ".$posicionCursoAnterior;echo "<br>";
            //echo "posicion curso siguiente: ".$posicionCursoSiguiente;echo "<br>";


            $objConexion = new CConexion();


            echo '
			<div id="ContenedorPaginadoresCursoInterna">
            	
                <div id="ContenedorPaginadoresCursoInternaFlechaIzquierda"> ';

            if ($arrayIdsCursos[$posicionCursoAnterior] != "") {
                echo '<img src="' . $objConexion->obteneUrlBaseSitio() . 'imagenes/flecha_izquierda.png" width="12" height="22" onclick="document.location.href=\'' . $objConexion->obteneUrlBaseSitio() . 'descripcioncurso/' . $arrayIdsCursos[$posicionCursoAnterior] . '\'" />';
            }

            echo '</div>
                
                
                <div id="ContenedorPaginadoresCursoInternaCursoAnterior" onclick="document.location.href=\'' . $objConexion->obteneUrlBaseSitio() . 'descripcioncurso/' . $arrayIdsCursos[$posicionCursoAnterior] . '\'" style="cursor:pointer;">
                
                <div class="ContenedorPaginadoresCursoInternaTitulo">';

            if ($arrayIdsCursos[$posicionCursoAnterior] != "") {
                echo 'Anterior Curso';
            }


            echo '</div>
                
                <div class="ContenedorPaginadoresCursoInternaDescripcion" >' . $this->obtenerEspecificoEcurso($arrayIdsCursos[$posicionCursoAnterior], "titulo") . '</div>
                
                </div>
                
                
                <div id="ContenedorPaginadoresCursoInternaSeparador"></div>
                
                
                
                <div id="ContenedorPaginadoresCursoInternaCursoSiguiente" onclick="document.location.href=\'' . $objConexion->obteneUrlBaseSitio() . 'descripcioncurso/' . $arrayIdsCursos[$posicionCursoSiguiente] . '\'" style="cursor:pointer;">
                
                 <div class="ContenedorPaginadoresCursoInternaTitulo">';


            if ($arrayIdsCursos[$posicionCursoSiguiente] != "") {
                echo 'Siguiente Curso';
            }


            echo '</div>
                
                <div class="ContenedorPaginadoresCursoInternaDescripcion" >' . $this->obtenerEspecificoEcurso($arrayIdsCursos[$posicionCursoSiguiente], "titulo") . '</div>
                
                </div>
                
                
                <div id="ContenedorPaginadoresCursoInternaFlechaDerecha">';


            if ($arrayIdsCursos[$posicionCursoSiguiente] != "") {
                echo '<img src="' . $objConexion->obteneUrlBaseSitio() . 'imagenes/flecha_derecha.png" width="12" height="22" onclick="document.location.href=\'' . $objConexion->obteneUrlBaseSitio() . 'descripcioncurso/' . $arrayIdsCursos[$posicionCursoSiguiente] . '\'" />';
            }



            echo '</div>
                
                
                <div class="clear"></div>
                
                
            </div>';
        }
    }

    //Comprobar existencia ecurso
    public function comprobarExistenciaEcurso($id) {


        $this->conectar();

        $id = (int) $id;

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `ecurso` WHERE id=$id";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);



        if ($numFilas > 0) {
            return "si";
        } else {
            return "no";
        }
    }

    //Obtener mensajes globo
    public function obtenerMensajesGlobo() {


        if ($this->obtenerSessionUsuarioDaimlerel() != "") {
            if ($this->comprobarExistenciaEcurso($_GET['descripcioncurso']) == "si") {

                $objConexion = new CConexion();

                if ($this->obtenerEspecificoEcursosUsuario($_GET['descripcioncurso'], $this->obtenerSessionUsuarioDaimlerel(), "estado") != "finalizado") {

                    if ($_GET['gpc'] == "1") {
                        echo '                      <!--CURSO GUARDADO-->
                    <div class="GloboCursoTitulo" style="margin:75px 0px 0px 35px; color:#BDCE0F; text-align:center;">CURSO GUARDADO
</div>
                    
                    <div class="GloboCursoDescripcion">Usted podrá retomar el curso actual en otro momento. Ingrese y continúe en el camino a ser un buen Ciudadano Daimler.</div>
                    <!--FIN CURSO GUARDADO-->';
                    } else {
                        //Iniciar curso
                        $idPreguntasContestadas = $this->obtenerEspecificoEcursosUsuario($_GET['descripcioncurso'], $this->obtenerSessionUsuarioDaimlerel(), "idspreguntascontestadas");

                        $totalPreguntasContestadas = explode("-", $idPreguntasContestadas);
                        $totalPreguntasContestadas = count($totalPreguntasContestadas);
                        $totalPreguntasContestadas = (int) $totalPreguntasContestadas;
                        if ($totalPreguntasContestadas > 0) {
                            $totalPreguntasContestadas -= 1;
                        }


                        //Saber si se retoma o se inicia el curso
                        if ($this->obtenerEspecificoEcursosUsuario($_GET['descripcioncurso'], $this->obtenerSessionUsuarioDaimlerel(), "idspreguntascontestadas") == "") {

                            echo '<!--INICIAR CURSO-->
					<div class="GloboCursoTitulo" style="margin:105px 0px 10px 35px; font-size:20px; cursor:pointer;" onclick="document.location.href=\'' . $objConexion->obteneUrlBaseSitio() . 'cursoiniciopg/' . $_GET['descripcioncurso'] . '\'">INICIAR EL CURSO!</div>
					
					<div class="GloboCursoDescripcion" style="color:#D2D6D9; text-align:center; font-weight:bold;">Páginas</div>
					
					 <div class="GloboCursoDescripcion" style="color:#D2D6D9; text-align:center; font-weight:bold; font-size:16px;"><span style="color:#13AAD3;">' . $totalPreguntasContestadas . '</span> <span style="color:#153758;">de</span> <span style="color:#13AAD3;">' . $this->obtenerNumeroPreguntasEcurso($_GET['descripcioncurso']) . '</span></div>
					<!--FIN INICIAR CURSO-->';
                        } else {
                            echo '<!--RETOMAR CURSO-->
					<div class="GloboCursoTitulo" style="margin:105px 0px 10px 35px; font-size:20px; cursor:pointer;" onclick="document.location.href=\'' . $objConexion->obteneUrlBaseSitio() . 'cursoiniciopg/' . $_GET['descripcioncurso'] . '\'">RETOMAR CURSO!</div>
					
					<div class="GloboCursoDescripcion" style="color:#D2D6D9; text-align:center; font-weight:bold;">Páginas</div>
					
					 <div class="GloboCursoDescripcion" style="color:#D2D6D9; text-align:center; font-weight:bold; font-size:16px;"><span style="color:#13AAD3;">' . $totalPreguntasContestadas . '</span> <span style="color:#153758;">de</span> <span style="color:#13AAD3;">' . $this->obtenerNumeroPreguntasEcurso($_GET['descripcioncurso']) . '</span></div>
					<!--FIN RETOMAR CURSO-->';
                        }
                    }
                } else {
                    echo '                      <!--USTED YA HA REALIZADO ESTE CURSO-->
                    <div class="GloboCursoTitulo" style="margin:75px 0px 0px 35px; color:#BDCE0F;">USTED YA HA REALIZADO ESTE CURSO</div>
                    
                    <div class="GloboCursoDescripcion">Si usted quiere descargar o validar la aprobación del curso, haga clic en <strong>ver perfil</strong> o <a href="' . $objConexion->obteneUrlBaseSitio() . 'perfilUsuario.php" style="color:#585859;">clic aquí</a> para ver sus certificados</div>
                    <!--FIN USTED YA HA REALIZADO ESTE CURSO-->';
                }
            }
        } else {
            //Debe estar registrado
            echo ' <!--DEBE ESTAR REGISTRADO-->
		<div class="GloboCursoTitulo">DEBE ESTAR REGISTRADO</div>
		
		<div class="GloboCursoDescripcion">Ingrese su número de cédula para validarse como usuario y solucione el curso de su interés</div>
		<!--FIN DEBE ESTAR REGISTRADO-->';
        }
    }

    //Crear registro cursos usuario
    public function crearRegistroCursosUsuario($idCurso) {


        $this->conectar();

        //Comprobar existencia curso
        if ($this->comprobarExistenciaEcurso($idCurso) == "si") {
            $id = $this->obtenerConsecutivoCursosUsuario();
            $idUsuario = $this->obtenerSessionUsuarioDaimlerel();

            //Comprobar que no exista el usuaio ni el curso dentro de la tabla ecursosusuario
            if ($this->comprobarExistenciaEcursoYusuario($idCurso, $idUsuario) == "no existe") {
                mysql_select_db($this->database_conn, $this->conn);
                $sentencia = "INSERT INTO `ecursosusuarios` (`id`, `idecurso`, `idusuario`, `estado`, `fechafinalizacion`, `idspreguntascontestadas`) VALUES ('$id', '$idCurso', '$idUsuario', 'guardado', NULL, NULL);";

                $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
            }
        }
    }

    //Obtener consecutivo cursos usuario
    public function obtenerConsecutivoCursosUsuario() {
        $this->conectar();


        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `ecursosusuarios` ORDER BY id DESC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultado);
        return $fila[id] + 1;
    }

    //Comprobar existencia ecurso y usuario
    public function comprobarExistenciaEcursoYusuario($idEcurso, $idUsuario) {


        $this->conectar();

        $idEcurso = (int) $idEcurso;
        $idUsuario = (int) $idUsuario;

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `ecursosusuarios` WHERE `idecurso` = $idEcurso AND `idusuario`=$idUsuario";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);

        if ($numFilas > 0) {
            return "existe";
        } else {
            return "no existe";
        }
    }

    //Obtener pregunta curso
    public function obtenerPreguntaCurso($idEcurso) {
        //Obtener preguntas no respuestas por usuario
        $arrayIdsPreguntasNoRespuestasPorUsuario = $this->obtenerPreguntasNoRespuestasPorUsuario($idEcurso);

        $contador = 1;
        $idPreguntaFinal;
        foreach ($arrayIdsPreguntasNoRespuestasPorUsuario as $indice => $idPreguntaConcurso) {
            if ($contador == 1) {
                $idPreguntaFinal = $idPreguntaConcurso;
            }
            $contador += 1;
            //echo "indice: " .$indice. " valor: ".$idPreguntaConcurso;echo "<br>";
        }

        return $idPreguntaFinal;
    }

    //Obtener preguntas no respuestas por usuario
    public function obtenerPreguntasNoRespuestasPorUsuario($idEcurso) {


        $this->conectar();

        $idEcurso = (int) $idEcurso;
        $idUsuario = (int) $this->obtenerSessionUsuarioDaimlerel();


        //Array de ids de preguntas desordenado
        $idsPreguntasConcursoDesordenadas = $this->obtenerIdsPreguntasConcursoDesordenadas($idEcurso);
        $numeroTotalPreguntasDesordenadas = count($idsPreguntasConcursoDesordenadas);

        //Array de ids de preguntas contestadas por usuario
        $preguntasContestadas = explode("-", $this->obtenerEspecificoEcursosUsuario($idEcurso, $idUsuario, "idspreguntascontestadas"));
        $numeroTotalPreguntasContestadas = count($preguntasContestadas) - 1;

        //Obtener las ids que se deben mostrar para el usuario
        $arrayIdsFinalesParaResponder = array();
        $contadorIdsFinalesParaResponder = 0;

        if ($numeroTotalPreguntasContestadas > 0) {

            $result = array_diff($idsPreguntasConcursoDesordenadas, $preguntasContestadas);

            return $result;
        } else {

            return $idsPreguntasConcursoDesordenadas;
        }
    }

    //Obtener ids preguntas curso desordenadas
    public function obtenerIdsPreguntasConcursoDesordenadas($idEcurso) {
        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `epregunta` WHERE idecurso = $idEcurso";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);



        if ($numFilas > 0) {
            $arrayIdsPreguntas = array();
            $contador = 0;

            do {

                $arrayIdsPreguntas[$contador] = $fila[id];

                $contador += 1;
            } while ($fila = mysql_fetch_assoc($resultado));


            shuffle($arrayIdsPreguntas);
            return $arrayIdsPreguntas;
        }
    }

    //Obtener especifico ecursosusuario
    public function obtenerEspecificoEcursosUsuario($idEcurso, $idUsuario, $campo) {

        $this->conectar();

        $id = (int) $id;

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `ecursosusuarios` WHERE `idecurso` = $idEcurso AND `idusuario`=$idUsuario";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);

        if ($numFilas > 0) {
            return $fila[$campo];
        }
    }

    //Obtener listado erespuestas segun pregunta
    public function obtenerListadoErespuestasSegunPregunta($idepregunta) {

        $this->conectar();

        $idepregunta = (int) $idepregunta;

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `erespuesta` WHERE idepregunta=$idepregunta";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);

        $objConexion = new CConexion();


        if ($numFilas > 0) {
            $contadorNumeroRespuestas = 0;
            do {


                $contadorNumeroRespuestas += 1;

                echo '<table width="422" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="26">
	<img src="' . $objConexion->obteneUrlBaseSitio() . 'imagenes/checkbox_vacio.png" alt="' . $contadorNumeroRespuestas . '" class="checkRespuestas" style="cursor:pointer;" />
	
	<input type="hidden" id="r-' . $contadorNumeroRespuestas . '" value="' . $fila[tipo] . '" />
	<input type="hidden" id="ru-' . $contadorNumeroRespuestas . '" value="falsa" />
	</td>
    <td width="396" rowspan="2">' . $fila[respuesta] . ' </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    </tr>
</table>';
            } while ($fila = mysql_fetch_assoc($resultado));

            echo '<input type="hidden" id="numeroTotalRespuestas" value="' . $contadorNumeroRespuestas . '" />';
        }
    }

    //Guardar preguntas respuestas usuario
    public function guardarPreguntasRespuestasUsuario() {

        $this->conectar();

        $idPregunta = $_POST['idPregunta'] . "-";
        $idCurso = (int) $_POST['idCurso'];
        $idUsuario = (int) $_POST['idUsuario'];

        $idPreguntasContestadas = $this->obtenerEspecificoEcursosUsuario($idCurso, $idUsuario, "idspreguntascontestadas");

        if ($this->verificarDobleRespuesta($idPreguntasContestadas, $_POST['idPregunta']) == 0) {
            $idPreguntasContestadas .= $idPregunta;


            mysql_select_db($this->database_conn, $this->conn);
            $sentencia = "UPDATE `ecursosusuarios` SET  `idspreguntascontestadas` =  '$idPreguntasContestadas' WHERE  `idecurso`=$idCurso AND `idusuario`=$idUsuario;";

            $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());

            //Tope preguntas contestadas
            $totalPreguntasContestadas = explode("-", $idPreguntasContestadas);
            $totalPreguntasContestadas = count($totalPreguntasContestadas);
            $totalPreguntasContestadas = (int) $totalPreguntasContestadas;
            $totalPreguntasContestadas -= 1;
            $totalPreguntasCurso = $this->obtenerNumeroPreguntasEcurso($idCurso);

            if ($totalPreguntasContestadas == $totalPreguntasCurso) {
                //Guardar datos fin curso
                $fechaFinalizacion = date("Y-m-d");

                mysql_select_db($this->database_conn, $this->conn);
                $sentencia = "UPDATE `ecursosusuarios` SET  `fechafinalizacion` =  '$fechaFinalizacion',`estado` =  'finalizado' WHERE  `idecurso`=$idCurso AND `idusuario`=$idUsuario;";

                $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());

                //Aumentar puntaje concurso daimler
                /* 				$puntajeEcurso = (int)$this->obtenerEspecificoEcurso($idCurso,"puntos");
                  $puntajeConcursoDaimler = (int)$this->obtenerEspecificoUsuariosRegistrados($idUsuario,"puntaje");

                  $masPuntaje = $puntajeConcursoDaimler + $puntajeEcurso;

                  mysql_select_db($this->database_conn, $this->conn);
                  $sentencia = "UPDATE `usuariosconcurso` SET  `puntaje` =  '$masPuntaje' WHERE  `usuariosconcurso`.`id` =$idUsuario;";

                  $resultado = mysql_query($sentencia,$this->conn)or die(mysql_error()); */


                echo $msg = "tope";
            } else {
                echo $msg = "no tope";
            }
        }
    }

    //Verificar doble respuesta
    public function verificarDobleRespuesta($arrayIdsPreguntas, $idPreguntaBuscar) {
        $this->conectar();

        $arrayIdsPreguntas = explode("-", $arrayIdsPreguntas);
        $numeroElementos = count($arrayIdsPreguntas);

        $respuesta = 0;

        for ($i = 0; $i < $numeroElementos; $i++) {
            if ($arrayIdsPreguntas[$i] == $idPreguntaBuscar) {
                $respuesta += 1;
            }
        }

        return $respuesta;
    }

    //Obtener listado ayudas ecursos web
    public function obtenerListadoAyudasEcursosWeb($idEcurso) {

        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `eayudascurso` WHERE idecurso=$idEcurso ORDER BY id ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);

        $objConexion = new CConexion();


        if ($numFilas > 0) {


            do {

                echo '<div class="ItemContenedorDescargas">
                    <table width="0" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><img src="' . $objConexion->obteneUrlBaseSitio() . 'imagenes/icono_clip.jpg" width="29" height="25" /></td>
    <td class="etiqueta"><a href="' . $objConexion->obteneUrlBaseSitio() . '/91f5167c34c400758115c2a6826ec2e3/recursos/ecurso/' . $idEcurso . '/ayudas/' . $fila[id] . '/' . $fila[adjunto] . '" target="_blank">Descargar Anexos</a></td>
  </tr>
</table>

                    </div>';
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //descifrar slidesepreguntas
    public function descrifrarSlidesEpreguntas($idepregunta) {

        $this->conectar();

        $idepregunta = (int) $idepregunta;
        $idEcurso = $this->obtenerEspecificoPreguntaEcurso($idepregunta, "idecurso");

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `eslidepregunta` WHERE idepregunta=$idepregunta ORDER BY id DESC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);


        $objConexion = new CConexion();

        if ($numFilas > 0) {
            header('Location: ' . $objConexion->obteneUrlBaseSitio() . 'cursoiniciosl/' . $idEcurso . '/psl/' . $idepregunta);
        }
    }

    //obtener slidesepreguntas
    public function obtenerSlidesEpreguntasWeb($idepregunta) {

        $this->conectar();

        $idepregunta = (int) $idepregunta;
        $idEcurso = $this->obtenerEspecificoPreguntaEcurso($idepregunta, "idecurso");

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `eslidepregunta` WHERE idepregunta=$idepregunta ORDER BY id ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);

        $objConexion = new CConexion();

        if ($numFilas > 0) {
            do {



                echo '<div class="scrollEl" style="overflow:hidden; background-image:url(' . $objConexion->obteneUrlBaseSitio() . '91f5167c34c400758115c2a6826ec2e3/recursos/ecurso/' . $idEcurso . '/pregunta/' . $idepregunta . '/slides/' . $fila[id] . '/' . $fila[imagen] . '); width:940px; height:370px;background-repeat:no-repeat;">
      
	  
      
      	<div class="ContenedorDescripcionSlide">
        	<div class="ContenedorDescripcionSlideTitulo">
            ' . $fila[titulo] . '
            </div>
            
            <div class="ContenedorDescripcionSlideDescripcion">
           ' . htmlspecialchars_decode($fila[descripcion], ENT_QUOTES) . '
            </div>
            
        </div>
      
      </div>';
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //obtener informacion secundaria slidesepreguntas
    public function obtenerInformacionSecundariaSlidesWeb($idepregunta) {

        $this->conectar();

        $idepregunta = (int) $idepregunta;


        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `eslidepregunta` WHERE idepregunta=$idepregunta ORDER BY id ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);



        if ($numFilas > 0) {
            $contadorSlides = 1;
            do {
                if ($contadorSlides == 1) {
                    echo '
					<div id="InformacionSecundariaSlide' . $contadorSlides . '" class="CotenedorInfomacionSecundariaSlide" style="margin:20px 0px 0px 0px;" >
				
						<div style="font-size:20px; font-weight:bold; color:#656565;">
						' . $fila[titulo2] . '
						</div>
						
						<div style="color:#656565; ">
					   ' . htmlspecialchars_decode($fila[descripcion2], ENT_QUOTES) . '
						</div>
			
					
					</div>';
                } else {
                    echo '
					<div id="InformacionSecundariaSlide' . $contadorSlides . '" class="CotenedorInfomacionSecundariaSlide" style="display:none; margin:20px 0px 0px 0px;" >
				
						<div style="font-size:20px; font-weight:bold; color:#656565;">
						' . $fila[titulo2] . '
						</div>
						
						<div style="color:#656565; ">
					   ' . htmlspecialchars_decode($fila[descripcion2], ENT_QUOTES) . '
						</div>
			
					
					</div>';
                }




                $contadorSlides += 1;
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Obtener listado ayudas eslides pregunta web
    public function obtenerListadoAyudasESlidePreguntaWeb($idepregunta, $idEcurso) {


        $this->conectar();

        $idepregunta = (int) $idepregunta;
        $idEcurso = (int) $idEcurso;

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `eslidepregunta` WHERE idepregunta=$idepregunta ORDER BY id ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);

        $objConexion = new CConexion();

        if ($numFilas > 0) {
            $contadorSlides = 1;
            do {
                if ($contadorSlides == 1) {
                    echo '<div id="contenedorSlide' . $contadorSlides . '" class="contenedoresSlides">';

                    $this->obtenerAyudasESlidePreguntaWeb($fila[id], $idEcurso, $fila[idepregunta]);

                    echo '</div>';
                } else {
                    echo '<div id="contenedorSlide' . $contadorSlides . '" style="display:none;" class="contenedoresSlides">';

                    $this->obtenerAyudasESlidePreguntaWeb($fila[id], $idEcurso, $fila[idepregunta]);

                    echo '</div>';
                }


                $contadorSlides += 1;
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Obtener listado ayudas eslides pregunta web
    public function obtenerAyudasESlidePreguntaWeb($ideslidepregunta, $idEcurso, $idepregunta) {
        $ideslidepregunta = (int) $ideslidepregunta;

        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia2 = "SELECT * FROM `eayudasslide` WHERE ideslidepregunta=$ideslidepregunta ORDER BY id ASC";

        $resultado2 = mysql_query($sentencia2, $this->conn) or die(mysql_error());
        $numFilas2 = mysql_num_rows($resultado2);
        $fila2 = mysql_fetch_assoc($resultado2);

        $objConexion = new CConexion();

        if ($numFilas2 > 0) {


            do {



                echo '<div class="ItemContenedorDescargas">
					<table width="0" border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td><img src="' . $objConexion->obteneUrlBaseSitio() . 'imagenes/icono_clip.jpg" width="29" height="25" /></td>
	<td class="etiqueta"><a href="' . $objConexion->obteneUrlBaseSitio() . '/91f5167c34c400758115c2a6826ec2e3/recursos/ecurso/' . $idEcurso . '/pregunta/' . $idepregunta . '/slides/ayudas/' . $fila2[ideslidepregunta] . '/' . $fila2[id] . '/' . $fila2[adjunto] . '" target="_blank">Descargar Anexos</a></td>
  </tr>
</table>

					</div>';

                //echo $fila2[adjunto]."<br>";
            } while ($fila2 = mysql_fetch_assoc($resultado2));
        }
    }

    //obtener numero slidesepreguntas
    public function obtenerNumeroSlidesEpregunta($idepregunta) {

        $this->conectar();

        $idepregunta = (int) $idepregunta;
        $idEcurso = $this->obtenerEspecificoPreguntaEcurso($idepregunta, "idecurso");

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `eslidepregunta` WHERE idepregunta=$idepregunta ORDER BY id DESC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        return $numFilas = mysql_num_rows($resultado);
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

    //obtener cursos guardados por usuario
    public function obtenerCursosGuardadosPorUsuario($idUsuario) {

        $this->conectar();


        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `ecursosusuarios` WHERE `idusuario`=$idUsuario AND `estado`='guardado'";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);

        $objConexion = new CConexion();

        if ($numFilas > 0) {
            do {

                echo '<tr>
    <td width="15"><img src="' . $objConexion->obteneUrlBaseSitio() . 'imagenes/bullet_flecha_verde.jpg" width="5" height="9" /></td>
    <td width="285"><a href="' . $objConexion->obteneUrlBaseSitio() . 'descripcioncurso/' . $fila[idecurso] . '" style="text-decoration:none; color:#13AAD4;">' . $this->obtenerEspecificoEcurso($fila[idecurso], "titulo") . '</a></td>
  </tr>';
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //obtener numero cursos aprobados por usuario
    public function obtenerNumeroCursosAprobadosPorUsuario($idUsuario) {

        $this->conectar();


        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `ecursosusuarios` WHERE `idusuario`=$idUsuario AND `estado`='finalizado'";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        return $numFilas = mysql_num_rows($resultado);
    }

    //obtener listado cursos aprobados por usuario perfil
    public function listadoCursosAprobadosPorUsuarioPerfil($idUsuario) {

        $this->conectar();


        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `ecursosusuarios` WHERE `idusuario`=$idUsuario AND `estado`='finalizado'";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);

        $objConexion = new CConexion();

        if ($numFilas > 0) {
            $contadorTablas = 1;
            do {


                $fechaFinalizacion = explode("-", $this->obtenerEspecificoEcursosUsuario($fila[idecurso], $this->obtenerSessionUsuarioDaimlerel(), "fechafinalizacion"));

                $mesLetra = descifrarMes($fechaFinalizacion[1]);

                echo '<table width="943" border="0" cellspacing="0" cellpadding="0" id="t-' . $contadorTablas . '">
				   <tr>
					<td width="325">
						<div class="TituloCursosAprobadosPerfil">' . $this->obtenerEspecificoEcurso($fila[idecurso], "titulo") . '</div>
						<div class="SubTituloCursosAprobadosPerfil">Curso Aprobado el ' . $fechaFinalizacion[2] . ' de ' . $mesLetra . ' de ' . $fechaFinalizacion[0] . '</div>
					</td>
					<td width="296">
					<a href="' . $objConexion->obteneUrlBaseSitio() . '91f5167c34c400758115c2a6826ec2e3/recursos/certificados/' . md5($this->obtenerEspecificoEcursosUsuario($fila[idecurso], $this->obtenerSessionUsuarioDaimlerel(), "id")) . '.pdf"   target="_blank"  style="text-decoration:none; cursor:pointer;"><div class="BtnIzquierdoFinCurso">
					
					</div>
					
					<div class="BtnCentroFinCurso" style="padding:5px 65px 0px 65px;">
					Descargar Certificado
					</div>
					
					<div class="BtnDerechoFinCurso">
					
					</div>
					
					<div class="clear"></div></a>
					</td>
					<td width="9">&nbsp;</td>
					<td width="313">
					 <a class="" onclick="AbrirDialogoEnviarMailCertificado(\'' . $contadorTablas . '\',\'' . $fila[idecurso] . '\')" style="text-decoration:none; cursor:pointer;">
					  <div class="BtnIzquierdoFinCurso">
					
					</div>
					
					<div class="BtnCentroFinCurso">
					Enviar a Correo Electrónico
					</div>
					
					<div class="BtnDerechoFinCurso">
					
					</div>
					
					<div class="clear"></div>
					</a>
					</td>
				  </tr>
				</table>';


                $contadorTablas += 1;
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Comparar pregunta respuesta por usuario
    public function compararPreguntaRespuestaPorUsuario($idCurso, $idUsuario, $idPregunta) {

        $this->conectar();

        $idCurso = (int) $idCurso;
        $idUsuario = (int) $idUsuario;

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `ecursosusuarios` WHERE `idusuario`=$idUsuario AND `idecurso`='$idCurso'";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);

        if ($numFilas > 0) {
            $preguntasContestadas = explode("-", $fila[idspreguntascontestadas]);
            $numeroPreguntas = count($preguntasContestadas);

            $repuesta = "no contestada";

            for ($i = 0; $i <= $numeroPreguntas; $i++) {
                if ($preguntasContestadas[$i] == $idPregunta) {
                    $repuesta = "contestada";
                }
            }

            return $repuesta;
        }
    }

    //obtener listado usuarios cursos aprobnados y no
    public function obtenerListadoUsuariosCursosAprobadosYno() {

        $this->conectar();


        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `usuariosconcurso`";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);

        $objConexion = new CConexion();

        if ($numFilas > 0) {
            /* $array1 = array("uno", "dos", "tres", "cuatro");
              $array2 = array("uno", "cinco", "tres", "seis");
              $result_array = array_intersect($array1, $array2);
              print_r($result_array); */


            do {

                echo '<div class="CajaUsuario" style="margin:0px 0px 20px 0px; overflow:hidden;">
	<div class="CajaUsuarioNombre" style="font-size:20px; color:#0CF;">' . utf8_encode($fila[nombre]) . '</div>
    
    <div class="CajaUsuarioCursosAprobados" style="width:315px; float:left;">
    	<div class="CajaUsuarioCursosAprobadosTItulo"><strong>CURSOS APROBADOS</strong></div>
    
    	<ul>';
                echo $this->obtenerCursosAprobadosPorUsuario($fila[id]);
                echo ' </ul>
    </div>
    
    <div class="CajaUsuarioCursosNoAprobados" style="width:315px;  float:left; margin:0px 0px 0px 10px;">
    	<div class="CajaUsuarioCursosNoAprobadosTItulo"><strong>CURSOS NO APROBADOS</strong></div>
    	<ul>';

                echo $this->obtenerCursosNoAprobadosPorUsuario($fila[id]);

                echo '</ul>
    </div>
    
    
    <div class="clear"></div>
    
</div>';
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Obtener preguntas no aprobados por usuario
    public function obtenerCursosNoAprobadosPorUsuario($idUsuario) {


        $this->conectar();



        //Array ids ecursos
        $idsPreguntasConcursoDesordenadas = $this->obtenerArrayIdsEcursos();
        $numeroTotalPreguntasDesordenadas = count($idsPreguntasConcursoDesordenadas);



        //Array de ids de ecursos aprobados por usuario
        $preguntasContestadas = $this->obtenerArrayIdsEcursosFinalizadosUsuario($idUsuario);
        $numeroTotalPreguntasContestadas = count($preguntasContestadas);

        //Obtener las ids que se deben mostrar para el usuario
        $arrayIdsFinalesParaResponder = array();
        $contadorIdsFinalesParaResponder = 0;

        if ($numeroTotalPreguntasContestadas > 0) {

            $result = array_diff($idsPreguntasConcursoDesordenadas, $preguntasContestadas);


            $valores;
            foreach ($result as $key => $valor) {
                $valores .= '<li>' . $this->obtenerEspecificoEcurso($valor, "titulo") . '</li>';
            }

            return $valores;
        } else {

            $valores;
            foreach ($idsPreguntasConcursoDesordenadas as $key => $valor) {
                $valores .= '<li>' . $this->obtenerEspecificoEcurso($valor, "titulo") . '</li>';
            }

            return $valores;
        }
    }

    //Obtener cursos aprobados por usuario
    public function obtenerCursosAprobadosPorUsuario($idUsuario) {


        $this->conectar();


        //Array de ids de ecursos aprobados por usuario
        $preguntasContestadas = $this->obtenerArrayIdsEcursosFinalizadosUsuario($idUsuario);
        $numeroTotalPreguntasContestadas = count($preguntasContestadas);


        if ($numeroTotalPreguntasContestadas > 0) {


            $valores;
            foreach ($preguntasContestadas as $key => $valor) {
                $valores .= '<li>' . $this->obtenerEspecificoEcurso($valor, "titulo") . '</li>';
            }

            return $valores;
        }
    }

    //Obtener array ids ecursos
    public function obtenerArrayIdsEcursos() {
        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `ecurso` ORDER BY id ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);



        if ($numFilas > 0) {
            $arrayIdsPreguntas = array();
            $contador = 0;

            do {

                $arrayIdsPreguntas[$contador] = $fila[id];

                $contador += 1;
            } while ($fila = mysql_fetch_assoc($resultado));


            return $arrayIdsPreguntas;
        }
    }

    //Obtener array ids ecursos finalizados usuario
    public function obtenerArrayIdsEcursosFinalizadosUsuario($idUsuario) {
        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM  `ecursosusuarios` WHERE  `idusuario` =$idUsuario AND  `estado` =  'finalizado'";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);



        if ($numFilas > 0) {
            $arrayIdsPreguntas = array();
            $contador = 0;

            do {

                $arrayIdsPreguntas[$contador] = $fila[idecurso];

                $contador += 1;
            } while ($fila = mysql_fetch_assoc($resultado));


            return $arrayIdsPreguntas;
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

    //Obtener listado ecursos combobox
    public function obtenerListadoEcursosComboBox() {

        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `ecurso` ORDER BY id ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);


        if ($numFilas > 0) {
            do {
                echo '<option value="' . $fila[id] . '">' . $fila[titulo] . '</option>';
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //obtener listado usuarios cursos aprobnados y no buscador
    public function obtenerListadoUsuariosCursosAprobadosYnoBuscador() {
        $idCurso = $_POST["idCurso"];

        $msg .= '<div class="CajaUsuario" style="margin:0px 0px 20px 0px; overflow:hidden;">
	<div class="CajaUsuarioNombre" style="font-size:20px; color:#0CF;">' . $this->obtenerEspecificoEcurso($idCurso, "titulo") . '</div>
    
    <div class="CajaUsuarioCursosAprobados" style="width:315px; float:left;">
    	<div class="CajaUsuarioCursosAprobadosTItulo"><strong>USUARIOS QUE HICIERON EL CURSO</strong></div>
    
    	<ul>';
        $msg .= $this->obtenerUsuariosQueAprobaronElCurso($idCurso);
        $msg .= ' </ul>
    </div>
    
    <div class="CajaUsuarioCursosNoAprobados" style="width:315px;  float:left; margin:0px 0px 0px 10px;">
    	<div class="CajaUsuarioCursosNoAprobadosTItulo"><strong>USUARIOS QUE NO HAN HECHO EL CURSO</strong></div>
    	<ul>';

        $msg .= $this->obtenerUsuariosQueNoAprobaronElCurso($idCurso);

        $msg .= '</ul>
    </div>
    
    
    <div class="clear"></div>
    
</div>';


        echo $msg;
    }

    //Obtener usuarios que no aprobaron el curso
    public function obtenerUsuariosQueNoAprobaronElCurso($idEcurso) {


        $this->conectar();



        //Array ids usuarios concurso
        $idsPreguntasConcursoDesordenadas = $this->obtenerArrayIdsUsuariosConcurso();
        $numeroTotalPreguntasDesordenadas = count($idsPreguntasConcursoDesordenadas);



        //Array de ids de ecursos aprobados por usuario
        $preguntasContestadas = $this->obtenerArrayIdsUsuariosQueAprobaronElCurso($idEcurso);
        $numeroTotalPreguntasContestadas = count($preguntasContestadas);

        //Obtener las ids que se deben mostrar para el usuario
        $arrayIdsFinalesParaResponder = array();
        $contadorIdsFinalesParaResponder = 0;

        if ($numeroTotalPreguntasContestadas > 0) {

            $result = array_diff($idsPreguntasConcursoDesordenadas, $preguntasContestadas);


            $valores;
            foreach ($result as $key => $valor) {
                $valores .= '<li>' . utf8_encode($this->obtenerEspecificoUsuariosRegistrados($valor, "nombre")) . '</li>';
            }

            return $valores;
        } else {

            $valores;
            foreach ($idsPreguntasConcursoDesordenadas as $key => $valor) {
                $valores .= '<li>' . utf8_encode($this->obtenerEspecificoUsuariosRegistrados($valor, "nombre")) . '</li>';
            }

            return $valores;
        }
    }

    //Obtener usuarios que aprobaron el curso
    public function obtenerUsuariosQueAprobaronElCurso($idEcurso) {

        $this->conectar();


        //Array de ids de ecursos aprobados por usuario
        $preguntasContestadas = $this->obtenerArrayIdsUsuariosQueAprobaronElCurso($idEcurso);
        $numeroTotalPreguntasContestadas = count($preguntasContestadas);


        if ($numeroTotalPreguntasContestadas > 0) {


            $valores;
            foreach ($preguntasContestadas as $key => $valor) {
                $valores .= '<li>' . utf8_encode($this->obtenerEspecificoUsuariosRegistrados($valor, "nombre")) . '</li>';
            }

            return $valores;
        }
    }

    //Obtener array ids usuarios concurso
    public function obtenerArrayIdsUsuariosConcurso() {
        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `usuariosconcurso` ORDER BY id ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);



        if ($numFilas > 0) {
            $arrayIdsPreguntas = array();
            $contador = 0;

            do {

                $arrayIdsPreguntas[$contador] = $fila[id];

                $contador += 1;
            } while ($fila = mysql_fetch_assoc($resultado));


            return $arrayIdsPreguntas;
        }
    }

    //Obtener array ids usuarios concurso
    public function obtenerArrayIdsUsuariosQueAprobaronElCurso($idEcurso) {
        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM  `ecursosusuarios` WHERE  `idecurso` =$idEcurso AND  `estado` =  'finalizado' ORDER BY id ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);



        if ($numFilas > 0) {
            $arrayIdsPreguntas = array();
            $contador = 0;

            do {

                $arrayIdsPreguntas[$contador] = $fila[idusuario];

                $contador += 1;
            } while ($fila = mysql_fetch_assoc($resultado));


            return $arrayIdsPreguntas;
        }
    }

}

?>