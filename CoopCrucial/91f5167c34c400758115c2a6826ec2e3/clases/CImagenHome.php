<?php

require_once('CConexion.php');
require_once('colaborativas.php');

class CImagenHome {

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

    //Obtener listado imagenes home
    public function obtenerListadoImagenesHome() {

        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `imagen_home` ORDER BY idImagenHome ASC";

        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $numFilas = mysql_num_rows($resultado);
        $fila = mysql_fetch_assoc($resultado);


        if ($numFilas > 0) {
            do {
                echo '<tr>
                    <td>' . $fila[idImagenHome] . '</td>
                    <td>' . $fila[imagen] . '</td>
                    <td>' . $fila[url] . '</td>
                    <td><a class="btnEliminarElementoLista" title="' . $fila[idImagenHome] . '" name="idImagenHome"  href="imagen_home">Eliminar</a>
                        <input type="hidden" id="carpetaImagenBorrar" value="imagen_home/" />
                    </td></tr>';
            } while ($fila = mysql_fetch_assoc($resultado));
        }
    }

    //Crear Imagen Home
    public function crearImagenHome() {
        $imagen = comprobarArchivo("imagen");
        $url = htmlspecialchars($_POST['url'], ENT_QUOTES);
        $id = $this->obtenerConsecutivoImagenHome();
        if ($imagen != "") {
            $this->conectar();
            $imagen = guardarImagen("../recursos/imagenHome/", "imagen");
            mysql_select_db($this->database_conn, $this->conn);
            $sentencia = "INSERT INTO `imagen_home` (`idImagenHome`, `imagen`, `url`) VALUES ('$id', '$imagen', '$url');";
            $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        }
        header('Location: ../imagenesHome.php');
    }

    //Modificar categoria
    public function modificarCategoria() {

        $url = htmlspecialchars($_POST['url'], ENT_QUOTES);
        $imagen = comprobarArchivo("imagen");
        if (isset($_POST['oferta']))
            $oferta = (int) $_POST['oferta'];
        else
            $oferta = "";
        $id = $this->obtenerConsecutivoCategoria();
        $id = $_POST['id'];
        $this->conectar();
        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "UPDATE `categoria` SET  `nombre` =  '$nombre', `oferta` = '$oferta' WHERE  `categoria`.`idCategoria` = $id;";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());


        if ($imagen != "") {

            elimarArchivo("../recursos/categoria/$id/", $this->obtenerEspecificoCategoria($id, "imagen"));
            $imagen = guardarImagen("../recursos/categoria/$id/", "imagen");

            mysql_select_db($this->database_conn, $this->conn);
            $sentencia = "UPDATE `categoria` SET  `imagen` =  '$imagen' WHERE  `categoria`.`idCategoria` =$id;";

            $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
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

    //Obtener especifico ecurso
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

    //Especifico consecutivo Imagen Home
    public function obtenerConsecutivoImagenHome() {
        $this->conectar();

        mysql_select_db($this->database_conn, $this->conn);
        $sentencia = "SELECT * FROM `imagen_home` ORDER BY idImagenHome DESC";
        $resultado = mysql_query($sentencia, $this->conn) or die(mysql_error());
        $fila = mysql_fetch_assoc($resultado);
        return $fila[idImagenHome] + 1;
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