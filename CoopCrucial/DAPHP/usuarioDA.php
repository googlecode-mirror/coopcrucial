<?php

session_start();

require_once 'conexionBD.php';

function strRandom($n) {
    $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    $cad = "";
    for ($i = 0; $i < $n; $i++) {
        $cad .= substr($str, mt_rand(0, strlen($str) - 1), 1);
    }
    return $cad;
}

function registrarUsuario($nombre, $apellidos, $tipoDocumento, $numeroDocumento, $fechaNacimiento, $correo, $celular, $telefono, $direccion, $pais, $ciudad, $usuario, $pwd, $actualizaciones, $promociones) {
    $mdb2 = conectar();
    $nuevoUsuario = new Usuario($mdb2['dsn']);
    $nuevoUsuario->useResult('object');
    $nUsuario = $nuevoUsuario->newEntity();
    $nUsuario->nombre = $nombre;
    $nUsuario->apellidos = $apellidos;
    $nUsuario->tipoDocumento = $tipoDocumento;
    $nUsuario->numeroDocumento = (int) $numeroDocumento;
    $nUsuario->fechaNacimiento = $fechaNacimiento;
    $nUsuario->correoElectronico = $correo;
    $nUsuario->celular = $celular;
    $nUsuario->actualizacion = $actualizaciones;
    $nUsuario->promocion = $promociones;
    $codigo = "COOP" . strtoupper(strRandom(4));
    $nUsuario->codigo = $codigo;
    $nUsuario->usuario = $usuario;
    $nUsuario->contrasenia = sha1($pwd);
    $idUsuario = $nUsuario->save();
    if (is_numeric($idUsuario)) {
        $nuevaDireccion = new Direccion($mdb2['dsn']);
        $nuevaDireccion->useResult('object');
        $nDireccion = $nuevaDireccion->newEntity();
        $nDireccion->direccion = $direccion;
        $nDireccion->telefono = $telefono;
        $nDireccion->predeterminada = 1;
        $nDireccion->idUsuario = $idUsuario;
        $nDireccion->idCiudad = $ciudad;
        $idDireccion = $nDireccion->save();
        if (is_numeric($idDireccion)) {
            $_SESSION['usuario']['idUsuario'] = $idUsuario;
            $mensaje = "<div style='text-align: center; float: left;'>
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
            <label><strong style='text-decoration: underline; font-size: 30px;'>$codigo</strong></label>
            <br/>
            <label>Puede copiar este código</label>
            <p/>
            <label>Contrase&ntilde;a: <strong style='text-decoration: underline; font-size: 20px;'>$pwd</strong></label>
            <p/>
        </div>";
            $cabecera = "Content-type: text/html\r\n";
            $resp = mail($correo, "Registro Exitoso", $mensaje, $cabecera);
            return array("bool" => true, "codigo" => $codigo);
        }
        return array("bool" => false);
    }
    return array("bool" => false);
}

function validarUsuario($nombreUsuario, $contrasenia) {
    $mdb2 = conectar();
    $usuario = new Usuario($mdb2['dsn']);
    $usuario->setSelect("idUsuario");
    $usuario->addSelect("nombre");
    $usuario->addSelect("apellidos");
    $usuario->addSelect("usuario");
    $usuario->setWhere("usuario = '$nombreUsuario'");
    $usuario->addWhere("contrasenia = '" . sha1($contrasenia) . "'");
    return $usuario->getAll();
}

function cargarDirecciones() {
    $mdb2 = conectar();
    $direcciones = new Usuario($mdb2['dsn']);
    $direcciones->setSelect("correoElectronico");
    $direcciones->addSelect("celular");
    $direcciones->addSelect(TABLA_DIRECCION . ".idDireccion AS idDireccion");
    $direcciones->addSelect(TABLA_DIRECCION . ".telefono AS telefono");
    $direcciones->addSelect(TABLA_DIRECCION . ".direccion AS direccion");
    $direcciones->addSelect(TABLA_DIRECCION . ".predeterminada AS predeterminada");
    $direcciones->addSelect(TABLA_CIUDAD . ".nombre AS ciudad");
    $direcciones->addSelect(TABLA_PAIS . ".nombre AS pais");
    $direcciones->setWhere("idUsuario = " . $_SESSION['usuario']['idUsuario']);
    $direcciones->setJoin(TABLA_DIRECCION, "usuario.idUsuario = " . TABLA_DIRECCION . ".idUsuario", inner);
    $direcciones->addJoin(TABLA_CIUDAD, TABLA_DIRECCION . ".idCiudad = " . TABLA_CIUDAD . ".idCiudad", inner);
    $direcciones->addJoin(TABLA_PAIS, TABLA_CIUDAD . ".idPais = " . TABLA_PAIS . ".idPais", inner);
    $direcciones->setOrder("predeterminada DESC");
    $direcciones = $direcciones->getAll();
    for ($i = 0; $i < count($direcciones); $i++) {
        $tel = explode("-", $direcciones[$i]['telefono']);
        $direcciones[$i]['telefono'] = $tel[1] . " (" . $tel[0] . ")";
        $dir = explode("-", $direcciones[$i]['direccion']);
        $direcciones[$i]['direccion'] = $dir[1] . " (" . $dir[0] . ")";
    }
    $direccion = new Usuario($mdb2['dsn']);
    $direccion->setSelect("correoElectronico");
    $direccion->addSelect("celular");
    $direccion->addSelect(TABLA_DIRECCION . ".telefono AS telefono");
    $direccion->addSelect(TABLA_DIRECCION . ".direccion AS direccion");
    $direccion->addSelect(TABLA_CIUDAD . ".nombre AS ciudad");
    $direccion->addSelect(TABLA_PAIS . ".nombre AS pais");
    $direccion->setWhere("idUsuario = " . $_SESSION['usuario']['idUsuario']);
    $direccion->addWhere(TABLA_DIRECCION . ".predeterminada = 1");
    $direccion->setJoin(TABLA_DIRECCION, "usuario.idUsuario = " . TABLA_DIRECCION . ".idUsuario", inner);
    $direccion->addJoin(TABLA_CIUDAD, TABLA_DIRECCION . ".idCiudad = " . TABLA_CIUDAD . ".idCiudad", inner);
    $direccion->addJoin(TABLA_PAIS, TABLA_CIUDAD . ".idPais = " . TABLA_PAIS . ".idPais", inner);
    $direccion = $direccion->getAll();
    $direccion = $direccion[0];
    $tel = explode("-", $direccion['telefono']);
    $direccion['telefono'] = $tel[1] . " (" . $tel[0] . ")";
    $dir = explode("-", $direccion['direccion']);
    $direccion['direccion'] = $dir[1] . " (" . $dir[0] . ")";
    return array("direccion" => $direccion, "direcciones" => $direcciones);
}

function cargarDireccionPorId($idDireccion) {
    $mdb2 = conectar();
    $direccion = new Direccion($mdb2['dsn']);
    $direccion->setSelect("telefono");
    $direccion->addSelect("direccion");
    $direccion->addSelect("idDireccion");
    $direccion->addSelect(TABLA_USUARIO . ".correoElectronico AS correoElectronico");
    $direccion->addSelect(TABLA_USUARIO . ".celular AS celular");
    $direccion->addSelect(TABLA_CIUDAD . ".nombre AS ciudad");
    $direccion->addSelect(TABLA_CIUDAD . ".idCiudad AS idCiudad");
    $direccion->addSelect(TABLA_PAIS . ".idPais AS idPais");
    $direccion->addSelect(TABLA_PAIS . ".nombre AS pais");
    $direccion->setWhere(TABLA_DIRECCION . ".idUsuario = " . $_SESSION['usuario']['idUsuario']);
    $direccion->addWhere(TABLA_DIRECCION . ".idDireccion = $idDireccion");
    $direccion->setJoin(TABLA_USUARIO, "direccion.idUsuario = " . TABLA_USUARIO . ".idUsuario", inner);
    $direccion->addJoin(TABLA_CIUDAD, "direccion.idCiudad = " . TABLA_CIUDAD . ".idCiudad", inner);
    $direccion->addJoin(TABLA_PAIS, TABLA_CIUDAD . ".idPais = " . TABLA_PAIS . ".idPais", inner);
    $direccion = $direccion->getAll();
    $direccion = $direccion[0];
    $tel = explode("-", $direccion['telefono']);
    $direccion['telefono'] = $tel[1];
    $direccion['lugarTel'] = $tel[0];
    $dir = explode("-", $direccion['direccion']);
    $direccion['direccion'] = $dir[1];
    $direccion['lugarDir'] = $dir[0];
    return $direccion;
}

function guardarDireccion($direccion, $telefono, $predeterminada, $idUsuario, $ciudad) {
    $mdb2 = conectar();
    if ($predeterminada == 1) {
        $direcciones = new Direccion($mdb2['dsn']);
        $direcciones->setSelect('idDireccion');
        $direcciones->setWhere("predeterminada = 1");
        $direcciones->setWhere("idUsuario = $idUsuario");
        $direcciones = $direcciones->getAll();
        if (count($direcciones) > 0) {
            foreach ($direcciones as $d) {
                $dataArray = array("idDireccion" => $d['idDireccion'], "predeterminada" => 0);
                $dir = new Direccion($mdb2['dsn']);
                $dir->save($dataArray);
            }
        }
    }
    $nuevaDireccion = new Direccion($mdb2['dsn']);
    $nuevaDireccion->useResult('object');
    $nDireccion = $nuevaDireccion->newEntity();
    $nDireccion->direccion = $direccion;
    $nDireccion->telefono = $telefono;
    $nDireccion->predeterminada = $predeterminada;
    $nDireccion->idUsuario = $idUsuario;
    $nDireccion->idCiudad = $ciudad;
    $idDireccion = $nDireccion->save();
    return is_numeric($idDireccion);
}

function editarDireccionCompleta($idDireccion, $direccion, $telefono, $idUsuario, $ciudad) {
    $mdb2 = conectar();
    if ($predeterminada == 1) {
        $direcciones = new Direccion($mdb2['dsn']);
        $direcciones->setSelect('idDireccion');
        $direcciones->setWhere("predeterminada = 1");
        $direcciones->setWhere("idUsuario = $idUsuario");
        $direcciones = $direcciones->getAll();
        if (count($direcciones) > 0) {
            foreach ($direcciones as $d) {
                $dataArray = array("idDireccion" => $d['idDireccion'], "predeterminada" => 0);
                $dir = new Direccion($mdb2['dsn']);
                $dir->save($dataArray);
            }
        }
    }
    $dataArray = array("idDireccion" => $idDireccion, "direccion" => $direccion, "telefono" => $telefono, "idCiudad" => $ciudad);
    $editada = new Direccion($mdb2['dsn']);
    return $editada->save($dataArray);
}

function agregarDireccion($idDireccion) {
    $mdb2 = conectar();
    $ventas = new Venta($mdb2['dsn']);
    $ventas->setSelect('idVenta');
    $ventas->setWhere("idUsuario = " . $_SESSION['usuario']['idUsuario']);
    $ventas = $ventas->getAll();
    for ($i = 0; $i < sizeof($ventas); $i++) {
        $arrayData = array("idVenta" => $ventas[$i]['idVenta'], "idDireccion" => $idDireccion);
        $venta = new Venta($mdb2['dsn']);
        $venta->save($arrayData);
    }
}

function validarDocumento($doc) {
    $mdb2 = conectar();
    $usuario = new Usuario($mdb2['dsn']);
    $usuario->setSelect("numeroDocumento");
    $usuario->setWhere("numeroDocumento = '" . $doc . "'");
    $usuario = $usuario->getAll();
    if (sizeof($usuario) > 0)
        return 0;
    return 1;
}

function validarUsuarioCampo($nombreUsuario) {
    $mdb2 = conectar();
    $usuario = new Usuario($mdb2['dsn']);
    $usuario->setSelect("usuario");
    $usuario->setWhere("usuario = '$nombreUsuario'");
    $usuario = $usuario->getAll();
    if (sizeof($usuario) > 0)
        return 0;
    return 1;
}

function recordarContrasenia($correo) {
    $mdb2 = conectar();
    $usuario = new Usuario($mdb2['dsn']);
    $usuario->setSelect("idUsuario");
    $usuario->addSelect("nombre");
    $usuario->addSelect("apellidos");
    $usuario->addSelect("usuario");
    $usuario->addSelect("correoElectronico");
    $usuario->setWhere("correoElectronico = '$correo'");
    $usuario = $usuario->getAll();
    if (is_array($usuario) && !empty($usuario)) {
        $usuario = $usuario[0];
        $q = sha1($usuario['usuario']);
        $link = "www.nabica.com.co/clientes/crucial/e-commerce/service/usuarios.php?q=$q" . $usuario['idUsuario'];
        $mensaje = "<div style='text-align: center; float: left;'>
            <h1>" . $usuario['nombre'] . " " . $usuario['apellidos'] . ", gracias por utilizar nuestros servicios</h1>
            <div style='width: 60%; margin-left: 20%;'>
                Este servicio es para recordarle su usuario registrado y enviarle un link donde puede cambiar su contrase&ntilde;a.
            </div>
            <p/>
            <table>
                <tr>
                    <td align='right'>Usuario</td>
                    <td align='left'><strong style='font-size: 30px;'>" . $usuario['usuario'] . "</strong></td>
                </tr>
                <tr>
                    <td align='right'>Restablecer contrase&ntilde;a</td>
                    <td align='left'><a href='$link'>$link</a></td>
                </tr>
            </table>
            <p/>
            <div style='width: 60%; margin-left: 20%;'>
                Si tu no has olvidado tu contrase&ntilde;a o estás siendo víctima de algún tipo de ataque cibernético, no dudes en comunicarte con nosotros
                por medio de info@coopcrucial.com, nuestros asesores atenderán tu duda o requerimiento lo más pronto posible.
            </div>
        </div>";
        $cabecera = "Content-type: text/html\r\n";
        $resp = mail($correo, "Cambio Contraseña", $mensaje, $cabecera);
        return $resp;
    }
    else
        return false;
}

function consultarUsuarioEncriptado($usuarioSha1, $id) {
    $mdb2 = conectar();
    $usuario = new Usuario($mdb2['dsn']);
    $usuario = $usuario->get($id);
    if (is_array($usuario)) {
        $nombreUsuario = $usuario['usuario'];
        if (sha1($nombreUsuario) == $usuarioSha1)
            return array("bool" => true, "usuario" => $usuario);
    }
    return array("bool" => false);
}

function cargarPerfilUsuario($id) {
    $mdb2 = conectar();
    $usuario = new Usuario($mdb2['dsn']);
    $usuario->addSelect(TABLA_PAIS . ".nombre AS pais");
    $usuario->addSelect(TABLA_CIUDAD . ".nombre AS ciudad");
    $usuario->addSelect(TABLA_DIRECCION . ".direccion AS direccion");
    $usuario->addSelect(TABLA_DIRECCION . ".telefono AS telefono");
    $usuario->setWhere("idUsuario = $id");
    $usuario->addWhere(TABLA_DIRECCION . ".predeterminada = 1");
    $usuario->setJoin(TABLA_DIRECCION, "usuario.idUsuario = " . TABLA_DIRECCION . ".idUsuario", inner);
    $usuario->addJoin(TABLA_CIUDAD, TABLA_DIRECCION . ".idCiudad = " . TABLA_CIUDAD . ".idCiudad", inner);
    $usuario->addJoin(TABLA_PAIS, TABLA_CIUDAD . ".idPais = " . TABLA_PAIS . ".idPais", inner);
    return $usuario->getAll();
}

function editarDireccion($campo, $valor, $idUsuario) {
    $mdb2 = conectar();
    $idDireccion = new Direccion($mdb2['dsn']);
    $idDireccion->setSelect("idDireccion");
    $idDireccion->setWhere("predeterminada == 1");
    $idDireccion->addWhere("idUsuario = $idUsuario");
    $idDireccion = $idDireccion->getAll();
    if (is_array($idDireccion)) {
        if ($campo == "ciudad")
            $dataArray = array("idDireccion" => $idDireccion[0]["idDireccion"], "idCiudad" => $valor);
        elseif ($campo == "tel")
            $dataArray = array("idDireccion" => $idDireccion[0]["idDireccion"], "telefono" => $valor);
        $direccion = new Direccion($mdb2['dsn']);
        $direccion->save($dataArray);
    }
}

function editarUsuario($data) {
    $mdb2 = conectar();
    $usuario = new Usuario($mdb2['dsn']);
    $usuario->save($data);
}

function cargarVentas() {
    $mdb2 = conectar();
    $fechas = new Venta($mdb2['dsn']);
    $fechas->setSelect("fecha");
    $fechas->setWhere("idUsuario = " . $_SESSION['usuario']['idUsuario']);
    $fechas->setGroup("fecha");
    $fechas = $fechas->getAll();
    $reg = array();
    for ($i = 0; $i < count($fechas); $i++) {
        $ventas = new Venta($mdb2['dsn']);
        $ventas->setSelect("idVenta");
        $ventas->addSelect("cantidad");
        $ventas->addSelect("DATE(fecha) AS fecha");
        $ventas->addSelect("idProducto");
        $ventas->addSelect(TABLA_PRODUCTO . ".nombre AS nombre");
        $ventas->addSelect(TABLA_PRODUCTO . ".precioWeb AS precioWeb");
        $ventas->addSelect(TABLA_IMAGEN_PRODUCTO . ".nombre AS imagen");
        $ventas->setWhere("carrito = 0");
        $ventas->addWhere("idUsuario = " . $_SESSION['usuario']['idUsuario']);
        $ventas->addWhere("fecha = '" . $fechas[$i]["fecha"] . "'");
        $ventas->setJoin(TABLA_PRODUCTO, "venta.idProducto = " . TABLA_PRODUCTO . ".idProducto", inner);
        $ventas->addJoin(TABLA_IMAGEN_PRODUCTO, TABLA_PRODUCTO . ".idProducto = " . TABLA_IMAGEN_PRODUCTO . ".idProducto", inner);
        $ventas->setGroup("idVenta");
        array_push($reg, $ventas->getAll());
    }
    return $reg;
}

function cargarInfoDireccion($idDireccion) {
    $mdb2 = conectar();
    $direccion = new Direccion($mdb2['dsn']);
    $direccion->addSelect(TABLA_USUARIO . ".correoElectronico AS correoElectronico");
    $direccion->addSelect(TABLA_USUARIO . ".celular AS celular");
    $direccion->addSelect("idDireccion");
    $direccion->addSelect("telefono");
    $direccion->addSelect("direccion");
    $direccion->addSelect(TABLA_CIUDAD . ".nombre AS ciudad");
    $direccion->addSelect(TABLA_PAIS . ".nombre AS pais");
    $direccion->setWhere("idDireccion = " . $idDireccion);
    $direccion->addWhere("idUsuario = " . $_SESSION['usuario']['idUsuario']);
    $direccion->setJoin(TABLA_USUARIO, "direccion.idUsuario = " . TABLA_USUARIO . ".idUsuario", inner);
    $direccion->addJoin(TABLA_CIUDAD, TABLA_DIRECCION . ".idCiudad = " . TABLA_CIUDAD . ".idCiudad", inner);
    $direccion->addJoin(TABLA_PAIS, TABLA_CIUDAD . ".idPais = " . TABLA_PAIS . ".idPais", inner);
    $direccion = $direccion->getAll();
    $direccion = $direccion[0];
    $tel = explode("-", $direccion['telefono']);
    $direccion['telefono'] = $tel[1] . " (" . $tel[0] . ")";
    $dir = explode("-", $direccion['direccion']);
    $direccion['direccion'] = $dir[1] . " (" . $dir[0] . ")";
    return $direccion;
}

function eliminarDireccion($idDireccion) {
    $mdb2 = conectar();
    $direccion = new Direccion($mdb2['dsn']);
    $resp = $direccion->remove($idDireccion, "idDireccion");
    if ($resp)
        return "Direccion eliminada con exito.";
    return "No se pudo eliminar la direccion";
}

?>