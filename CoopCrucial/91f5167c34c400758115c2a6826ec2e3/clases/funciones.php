<?php

require_once('CAdministradores.php');
require_once('CCategorias.php');
require_once('CProductos.php');
require_once('CImagenHome.php');
require_once('COfertaEspecial.php');
require_once('CImagenDestacado.php');
require_once('CBarraHorizontal.php');
require_once('CRegistros.php');
require_once('CEliminacionRegistros.php');
require_once('colaborativas.php');

class funciones {
    /*     * ******************************************************************************************** */
    /*     * ******************************** VERIFICACION ACCESO USUARIOS ****************************** */
    /*     * ******************************************************************************************** */

    //Verificar nombre de usuario y contraseña
    public function verificarUsuario() {
        $objeto = new CAdministradores();
        $objeto->verificarUsuario();
    }

    //Iniciar session de usuario admin
    public function iniciarSessionUsuario() {
        $objeto = new CAdministradores();
        $objeto->iniciarSessionUsuario();
    }

    //Obtener session usuario
    public function obtenerSessionUsuario() {
        $objeto = new CAdministradores();
        $objeto->obtenerSessionUsuario();
    }

    //Verificar si el usuario administrador esta autentificado
    public function autentificarSessionUsuario() {
        $objeto = new CAdministradores();
        $objeto->autentificarSessionUsuario();
    }

    //Cerrar session de usuario admin
    public function cerrarSessionUsuario() {
        $objeto = new CAdministradores();
        $objeto->cerrarSessionUsuario();
    }

    public function especificoDatosUsuario($usuario, $campo) {
        $objeto = new CAdministradores();
        $objeto->especificoDatosUsuario($usuario, $campo);
    }

    public function modificarDatosUsuario() {
        $objeto = new CAdministradores();
        $objeto->modificarDatosUsuario();
    }

    /*     * ******************************************************************************************** */
    /*     * ******************************** MODULO CATEGORIAS ****************************** */
    /*     * ******************************************************************************************** */

    //Obtener listado categorias
    public function obtenerListadoCategorias() {
        $objeto = new CCategorias();
        $objeto->obtenerListadoCategorias();
    }

    //Obtener listado usos
    public function obtenerListadoUsos() {
        $objeto = new CCategorias();
        $objeto->obtenerListadoUsos();
    }

    //Crear categoria
    public function crearCategoria() {
        $objeto = new CCategorias();
        $objeto->crearCategoria();
    }

    //Modificar categoria
    public function modificarCategoria() {
        $objeto = new CCategorias();
        $objeto->modificarCategoria();
    }

    //Obtener categoria especifica
    public function obtenerEspecificoCategoria($id, $campo) {
        $objeto = new CCategorias();
        return $objeto->obtenerEspecificoCategoria($id, $campo);
    }

    //Obtener categoria uso especifica
    public function obtenerEspecificoUso($id, $campo) {
        $objeto = new CCategorias();
        return $objeto->obtenerEspecificoUso($id, $campo);
    }

    /*     * ******************************************************************************************** */
    /*     * ******************************** MODULO PRODUCTOS ****************************** */
    /*     * ******************************************************************************************** */

    //Obtener listado productos
    public function obtenerListadoProductos() {
        $objeto = new CProductos();
        $objeto->obtenerListadoProductos();
    }

    //Crear producto
    public function crearProducto() {
        $objeto = new CProductos();
        $objeto->crearProducto();
    }

    //Modificar producto
    public function modificarProducto() {
        $objeto = new CProductos();
        $objeto->modificarProducto();
    }

    //Obtener categoria especifica
    public function obtenerEspecificoProducto($id, $campo) {
        $objeto = new CProductos();
        return $objeto->obtenerEspecificoProducto($id, $campo);
    }

    //Obtener tipo de Tallas
    public function obtenerListadoTipoTallas($idTalla) {
        $tiposTallas = array(1=>"No tiene",2=>"Letras",3=>"Numeros",4=>"Unica");
        $select = "";
        $selected = "";
        for ($i = 1; $i <= count($tiposTallas); $i++) {
            if($idTalla == $i)
                $selected = "selected";
            $select .= "<option value='$i' $selected>" . $tiposTallas[$i] . "</option>";
        }
        echo $select;
    }

    //Obtener listado categorias-productos
    public function obtenerListadoCategoriasProducto($id) {
        $objeto = new CProductos();
        $objeto->obtenerListadoCategoriasProducto($id);
    }

    //Obtener listado categorias usos-productos
    public function obtenerListadoUsosProducto($id) {
        $objeto = new CProductos();
        $objeto->obtenerListadoUsosProducto($id);
    }

    //Obtener listado imagenes-productos
    public function obtenerListadoImagenesProducto($idProducto) {
        $objeto = new CProductos();
        $objeto->obtenerListadoImagenesProducto($idProducto);
    }

    //Obtener listado caracteristicas-productos
    public function obtenerListadoCaracteristicasProducto($idProducto) {
        $objeto = new CProductos();
        $objeto->obtenerListadoCaracteristicasProducto($idProducto);
    }

    //Obtener listado especificaciones-productos
    public function obtenerListadoEspecificacionesProducto($idProducto) {
        $objeto = new CProductos();
        $objeto->obtenerListadoEspecificacionesProducto($idProducto);
    }

    //Elimina la imagen de un producto
    public function eliminarImagenProducto($p,$i){
        $objeto = new CProductos();
        return $objeto->eliminarImagenProducto($p, $i);
    }

    //Elimina la caracteristica de un producto
    public function eliminarCaracteristica($p,$c){
        $objeto = new CProductos();
        $objeto->eliminarCaracteristica($p, $c);
    }

    //Elimina la especificacion de un producto
    public function eliminarEspecificacion($p,$e){
        $objeto = new CProductos();
        $objeto->eliminarEspecificacion($p, $e);
    }

    /*     * ******************************************************************************************** */
    /*     * ************************************** MODULO IMAGENES HOME ********************************* */
    /*     * ******************************************************************************************** */

    //Obtener listado iamgenes home
    public function obtenerListadoImagenesHome() {
        $objeto = new CImagenHome();
        $objeto->obtenerListadoImagenesHome();
    }

    //Crear imagen home
    public function crearImagenHome() {
        $objeto = new CImagenHome();
        $objeto->crearImagenHome();
    }

    /*     * ******************************************************************************************** */
    /*     * ******************************** MODULO OFERTAS ESPECIALES ****************************** */
    /*     * ******************************************************************************************** */

    //Obtener listado ofertas especiales
    public function obtenerListadoOfertasEspeciales() {
        $objeto = new COfertaEspecial();
        $objeto->obtenerListadoOfertasEspeciales();
    }

    //Crear oferta especial
    public function crearOfertaEspecial() {
        $objeto = new COfertaEspecial();
        $objeto->crearOfertaEspecial();
    }

    /*     * ******************************************************************************************** */
    /*     * ************************************** MODULO IMAGEN DESTACADO ********************************* */
    /*     * ******************************************************************************************** */

    //Obtener listado imagen destacado
    public function obtenerListadoImagenDestacado() {
        $objeto = new CImagenDestacado();
        $objeto->obtenerListadoImagenDestacado();
    }

    //Crear imagen destacado
    public function crearImagenDestacado() {
        $objeto = new CImagenDestacado();
        $objeto->crearImagenDestacado();
    }

        /*     * ******************************************************************************************** */
    /*     * ******************************** MODULO BARRA HORIZONTAL ****************************** */
    /*     * ******************************************************************************************** */

    //Obtener listado barra horizontal
    public function obtenerListadoBarraHorizontal() {
        $objeto = new CBarraHorizontal();
        $objeto->obtenerListadoBarraHorizontal();
    }

    //Crear barra horizontal
    public function crearBarraHorizontal() {
        $objeto = new CBarraHorizontal();
        $objeto->crearBarraHorizontal();
    }

    //Modificar barra horizontal
    public function modificarBarraHorizontal() {
        $objeto = new CBarraHorizontal();
        $objeto->modificarBarraHorizontal();
    }

    //Obtener especifico barra horizontal
    public function obtenerEspecificoBarraHorizontal($id, $campo) {
        $objeto = new CBarraHorizontal();
        return $objeto->obtenerEspecificoBarraHorizontal($id, $campo);
    }

    /*     * ******************************************************************************************** */
    /*     * ******************************** MODULO REGISTROS ****************************** */
    /*     * ******************************************************************************************** */

    //Obtener listado registros
    public function obtenerListadoRegistros() {
        $objeto = new CRegistros();
        $objeto->obtenerListadoRegistros();
    }

    //Modificar categoria
    public function modificarRegistro() {
        $objeto = new CRegistros();
        $objeto->modificarRegistro();
    }

    //Obtener registro especifico
    public function obtenerEspecificoRegistro($id, $campo) {
        $objeto = new CRegistros();
        return $objeto->obtenerEspecificoRegistro($id, $campo);
    }

    /*     * ******************************************************************************************** */
    /*     * ******************************** ELIMINACIONES TABLAS Y CARPETAS ****************************** */
    /*     * ******************************************************************************************** */

    //Eliminar registro tabla
    public function eliminarRegistroTabla() {
        $objeto = new CEliminacionRegistros();
        $objeto->eliminarRegistroTabla();
    }

    /*     * ******************************************************************************************** */
    /*     * ******************************** URL BASE SITIO ****************************** */
    /*     * ******************************************************************************************** */

    public function obteneUrlBaseSitio() {
        $objeto = new CConexion();
        return $objeto->obteneUrlBaseSitio();
    }

}

//Fin Clase

$dato = new funciones();
$opcion = $_POST['opcion'];

switch ($opcion) {
    case 1:
        $dato->verificarUsuario();
        break;
    case 2:
        $dato->iniciarSessionUsuario();
        break;
    case 3:
        $dato->cerrarSessionUsuario();
        break;
    case 4:
        $dato->modificarDatosUsuario();
        break;
    case 5:
        $dato->eliminarRegistroTabla();
        break;
    case 6:
        $dato->crearCategoria();
        break;
    case 7:
        $dato->modificarCategoria();
        break;
    case 8:
        $dato->crearProducto();
        break;
    case 9:
        $dato->modificarProducto();
        break;
    case 10:
        $dato->crearImagenHome();
        break;
    case 11:
        $dato->modificarImagenHome();
        break;
    case 12:
        $dato->crearRegistro();
        break;
    case 13:
        $dato->modificarRegistro();
        break;
    case 14:
        $dato->crearOfertaEspecial();
        break;
    case 15:
        $dato->modificarOfertaEspecial();
        break;
    case 16:
        //$dato->agregarUso($_POST['idUso']);
        break;
    case 17:
        $dato->eliminarImagenProducto($_POST['p'],$_POST['i']);
        break;
    case 18:
        $dato->crearBarraHorizontal();
        break;
    case 19:
        $dato->modificarBarraHorizontal();
        break;
    case 20:
        $dato->crearImagenDestacado();
        break;
    case 21:
        $dato->eliminarCaracteristica($_POST['p'],$_POST['c']);
        break;
    case 22:
        $dato->eliminarEspecificacion($_POST['p'],$_POST['e']);
        break;
}
?>