<?php

session_start();

require_once '../util/smarty.php';
require_once '../DAPHP/usuarioDA.php';
require_once '../DAPHP/imagenDA.php';
require_once '../DAPHP/variosDA.php';

extract($_REQUEST);

switch ($accion) {
    case 0:// Valida el usuario para el inicio de Sesion
        $resp = validarUsuario($usuario, $contrasenia);
        if (is_array($resp) && count($resp) == 1) {
            $usuario = $resp[0];
            $_SESSION['usuario']['nombreUsuario'] = $usuario['usuario'];
            $_SESSION['usuario']['nombre'] = $usuario['nombre'] . " " . $usuario['apellidos'];
            $_SESSION['usuario']['idUsuario'] = $usuario['idUsuario'];
        }
        header("Location:../service");
        break;
    case 1://Cierra session
        unset($_SESSION['usuario']['nombreUsuario']);
        unset($_SESSION['usuario']['nombre']);
        unset($_SESSION['usuario']['idUsuario']);
        header("Location:../service");
        break;
    case 2://Carga la informacion para el registro
        $tiposDocumento = array("CC" => "Cedula de Ciudadania", "PS" => "Pasaporte");
        $smarty->assign("tiposDocumento", $tiposDocumento);
        $smarty->assign("paises", consultarPaises());
        $smarty->assign("valorBono", consultarValorBono());
        $smarty->assign("ofertaEspecial", consultarOfertaEspecial());
        $smarty->display("registro.html");
        break;
    case 3://Registro el usuario
        $resp = registrarUsuario($nombre, $apellidos, $tipoDocumento, $numeroDocumento, $anio . "/" . $mes . "/" . $dia, $correo, $cel, $lugarTelefono . "-" . $telefono, $lugarDireccion . "-" . $direccion, $pais, $ciudad, $usuario, $pwd, $actualizaciones, $promociones);
        if ($resp['bool']) {
            $_SESSION['usuario']['nombreUsuario'] = $usuario;
            $_SESSION['usuario']['nombre'] = $nombre . " " . $apellidos;
            echo "<div style='text-align: center; float: left;'>
            <h1>Bienvenido, gracias por registrarte</h1>
            <label>Nosotros somos Coopcrucial, la coperativa de la Cruz Roja, Colombiana.</label>
            <br/>
            <br/>
            <div style='width: 60%; margin-left: 20%; float: left;'>
                $nombre, para nosotros es un gran privilegio tenerlo en nuestra base de datos y poder atender a sus necesidades
                con productos de primera calidad. Como bienvenida, le regalamos un bono de descuento por $100.000 COL que podrá
                utilizar desde su primera compra.
            </div>
            <p/>
            <label><strong style='text-decoration: underline; font-size: 30px;'>" . $resp['codigo'] . "</strong></label>
            <br/>
            <label>Puede copiar este código</label>
            <p/>
            <p/>
            <input type='button' value='Ir al inicio' onclick=\"javascript: location.href = 'index.php';\"/>
        </div>";
        }
        else
            header("Location:../service");
        break;
    case 4://Consulta las ciudades del pais seleccionado
        if (isset($idPais) && $idPais != "") {
            echo consultarCiudades($idPais);
        }
        else
            echo "<select id='n_ciudad' name='ciudad' style='width: auto;'>
                    <option value=''>---</option>
                  </select>";
        break;
    case 5://Guarda una nueva direccion o una editada
        if (isset($idDireccion) && $idDireccion != "") {
            $resp = editarDireccionCompleta($idDireccion, $lugarDireccion . "-" . $direccion, $lugarTelefono . "-" . $telefono, $_SESSION['usuario']['idUsuario'], $ciudad);
            if ($resp)
                echo "Direccion editada.";
            else
                echo "No se pudo editar la direccion.";
        } else {
            $resp = guardarDireccion($lugarDireccion . "-" . $direccion, $lugarTelefono . "-" . $telefono, $direccionPredeterminada, $_SESSION['usuario']['idUsuario'], $ciudad);
            if ($resp)
                echo "Direccion agregada.";
            else
                echo "No se pudo agregar la direccion.";
        }
        break;
    case 6://Valida la unicidad del documento
        if (is_numeric($doc))
            echo validarDocumento($doc);
        else
            echo "2";
        break;
    case 7://Valida la unicidad del nombre del Usuario
        echo validarUsuarioCampo($usuario);
        break;
    case 8://Carga el Captcha de Google
        require_once '../util/recaptchalib.php';
        if (isset($recaptcha_challenge_field) && isset($recaptcha_response_field)) {
            $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $recaptcha_challenge_field, $recaptcha_response_field);
            if ($resp->is_valid) {
                $bool = recordarContrasenia($correo);
                if ($bool)
                    header("Location:../service");
                else {
                    $error_captcha = null;
                    $smarty->assign("correo", $correo);
                    $smarty->assign("error", "No se pudo enviar el correo.");
                    $smarty->assign("captcha_html", recaptcha_get_html($publickey, $error_captcha));
                    $smarty->display("recordar.html");
                }
            } else {
                $error_captcha = null;
                if (isset($correo) && $correo != "")
                    $smarty->assign("correo", $correo);
                $smarty->assign("error", $resp->error);
                $smarty->assign("captcha_html", recaptcha_get_html($publickey, $error_captcha));
                $smarty->display("recordar.html");
            }
        } else {
            $error_captcha = null;
            if (isset($correo) && $correo != "")
                $smarty->assign("correo", $correo);
            $smarty->assign("captcha_html", recaptcha_get_html($publickey, $error_captcha));
            $smarty->display("recordar.html");
        }
        break;
    case 9://Carga la informacion para el perfil del usuario
        if (isset($_SESSION['usuario']['idUsuario']) && $_SESSION['usuario']['idUsuario'] != "") {
            $resp = cargarPerfilUsuario($_SESSION['usuario']['idUsuario']);
            if (is_array($resp)) {
                $smarty->assign("paises", consultarPaises());
                $smarty->assign("usuario", $resp[0]);
                $dir = cargarDirecciones();
                $smarty->assign("direccion", $dir['direccion']);
                $smarty->assign("direcciones", $dir['direcciones']);
                $smarty->assign("ventas", cargarVentas());
                $smarty->display("perfil.html");
            }
            else
                header("Location: ../");
        }
        else
            header("Location: ../");
        break;
    case 10://Edita el usuario
        if (isset($campo) && $campo != "" && isset($id) && $campo != "" && is_numeric($id) && isset($valor) && $valor != "") {
            if ($campo == "ciudad" || $campo == "tel")
                editarDireccion($campo, $valor, $id);
            else {
                if ($campo == "contrasenia")
                    $valor = sha1($valor);
                $dataArray = array("idUsuario" => $id, $campo => $valor);
                editarUsuario($dataArray);
            }
            header("Location: usuarios.php?accion=9");
        }
        break;
    case 11://Carga la informacion de la direccion seleccionada
        if (isset($idDireccion) && $idDireccion != "") {
            $direccion = cargarInfoDireccion($idDireccion);
            if (is_array($direccion)) {
                echo "<div align='right' style='float: left; width:100%;'>
                        <a href='javascript:void(0);' onclick=\"editarDireccion('" . $direccion['idDireccion'] . "');\">Editar Direccion</a>
                        <a href='javascript:void(0);' onclick=\"eliminarDireccion('" . $direccion['idDireccion'] . "');\">Eliminar Direccion</a>
                        </div>
                    <label><strong>" . $_SESSION['usuario']['nombre'] . ", estos los productos serán entregados a:</strong></label><p/>
                        <table>
                            <tr>
                                <td align='right'><strong>Pa&iacute;s:</strong></td>
                                <td>" . $direccion['pais'] . "</td>
                            </tr>
                            <tr>
                                <td align='right'><strong>Ciudad:</strong></td>
                                <td>" . $direccion['ciudad'] . "</td>
                            </tr>
                            <tr>
                                <td align='right'><strong>Direcci&oacute;n:</strong></td>
                                <td>" . $direccion['direccion'] . "</td>
                            </tr>
                            <tr>
                                <td align='right'><strong>Tel&eacute;fono:</strong></td>
                                <td>" . $direccion['telefono'] . "</td>
                            </tr>
                            <tr>
                                <td align='right'><strong>Celular:</strong></td>
                                <td>" . $direccion['celular'] . "</td>
                            </tr>
                            <tr>
                                <td align='right'><strong>Correo Electr&oacute;nico:</strong></td>
                                <td>" . $direccion['correoElectronico'] . "</td>
                            </tr>
                        </table>";
            }
            else
                echo "No se pudo cargar la direccion";
        }
        else
            echo "No se pudo cargar la direccion.";
        break;
    case 12://Elimina una direccion dada
        if (isset($idDireccion) && $idDireccion != "")
            echo eliminarDireccion($idDireccion);
        else
            echo "No se pudo eliminar la direccion.";
        break;
    case 13://Carga la informacion de una direccion dada.
        if (isset($idDireccion) && $idDireccion != "") {
            $direccion = cargarDireccionPorId($idDireccion);
            $paises = consultarPaises();
            $stringOptionPais = "";
            foreach ($paises as $k => $p) {
                $stringOptionPais .= "<option value='$k'>$p</option>";
            }
            echo "<label><strong>Ingresar la nueva direcci&oacute;n que quieres en el sistema</strong></label><p/>
                        <form id='frm_direccion' action='' method='post'>
                            <table>
                                <tr>
                                    <td align='right'><strong>Tel&eacute;fono</strong></td>
                                    <td>
                                        <input type='text' id='telefono' name='telefono' value='" . $direccion['telefono'] . "'/><br/>
                                        <input type='radio' name='lugarTelefono' value='oficina' " . ($direccion['lugarTel'] == "oficina" ? "checked" : "") . "/>Oficina
                                        <input type='radio' name='lugarTelefono' value='casa' " . ($direccion['lugarTel'] == "casa" ? "checked" : "") . "/>Casa
                                    </td>
                                </tr>
                                <tr>
                                    <td align='right'><strong>Pais</strong></td>
                                    <td>
                                        <select id='n_pais' name='pais' onclick='consultarCiudades();'>
                                            <option value=''>Seleccione su pais de residencia</option>
                                            $stringOptionPais;
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align='right'><strong>Ciudad</strong></td>
                                    <td>
                                        <div id='divCiudad'>
                                            <select id='ciudad' name='ciudad'>
                                                <option value=''>Seleccione la ciudad</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align='right'><strong>Direcci&oacute;n</strong></td>
                                    <td>
                                        <input type='text' id='direccion' name='direccion' value='" . $direccion['direccion'] . "'/><br/>
                                        <input type='radio' name='lugarDireccion' value='oficina' " . ($direccion['lugarDir'] == "oficina" ? "checked" : "") . "/>Oficina
                                        <input type='radio' name='lugarDireccion' value='casa' " . ($direccion['lugarDir'] == "casa" ? "checked" : "") . "/>Casa
                                        <input type='hidden' name='idDireccion' value='" . $direccion['idDireccion'] . "'/>
                                    </td>
                                </tr>
                            </table>
                        </form>
                        <div align='center' style='width: 100%; float: left;'>
                            <input type='button' onclick='guardarDireccion();' value='GUARDAR NUEVA DIRECCION'/>
                        </div>";
        }
        else
            echo "No se pudo cargar la informacion de la direccion.";
        break;
    default:
        header("Location: ../");
        break;
}
if (isset($q) && $q != "") {
    $resp = consultarUsuarioEncriptado(substr($q, 0, 40), substr($q, 40));
    if ($resp['bool']) {
        $smarty->assign("usuario", $resp['usuario']);
        $smarty->assign("reset", 1);
    }
    $smarty->display("perfil.html");
}
?>
