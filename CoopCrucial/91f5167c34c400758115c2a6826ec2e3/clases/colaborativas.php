<?php

/* * ******************************************************************** */
/* * ********************* MANEJO ARCHIVOS ****************************** */
/* * ******************************************************************** */

//Guardar archivo
function guardarImagen($rutaArchivo, $archivo) {
    if (isset($_FILES[$archivo]) && $_FILES[$archivo]["size"] > 0) {
        $ruta = $rutaArchivo;
        $nombreTemp2 = $_FILES[$archivo]["tmp_name"];
        $nombreArchivo2 = $_FILES[$archivo]["name"];
        $tamanyoArchivo2 = $_FILES[$archivo]["size"];
        $nomSeparado2 = explode(".", $nombreArchivo2);
        if (move_uploaded_file($nombreTemp2, $ruta . $nombreArchivo2)) {
            return $nombreArchivo2;
        }
    }
}

//Guardar archivos
function guardarImagenes($rutaArchivo, $archivo) {
    if (isset($_FILES[$archivo])) {
        $ruta = $rutaArchivo;
        $imagenes = array();
        for ($i = 0; $i < count($_FILES[$archivo]["size"]); $i++) {
            $nombreTemp2 = $_FILES[$archivo]["tmp_name"][$i];
            $nombreArchivo2 = $_FILES[$archivo]["name"][$i];
            if ($_FILES[$archivo]["size"][$i] > 0 && move_uploaded_file($nombreTemp2, $ruta . $nombreArchivo2)) {
                array_push($imagenes, $nombreArchivo2);
            }
        }
        return $imagenes;
    }
}

//Guardar archivo
function guardarImagen2($rutaArchivo, $archivo, $nombreArchivo) {
    if (isset($_FILES[$archivo]) && $_FILES[$archivo]["size"] > 0) {
        $ruta = $rutaArchivo;
        $nombreTemp2 = $_FILES[$archivo]["tmp_name"];
        $nombreArchivo2 = $_FILES[$archivo]["name"];
        $tamanyoArchivo2 = $_FILES[$archivo]["size"];
        $tipo = $_FILES[$archivo]["type"];


        $nomSeparado2 = explode(".", $nombreArchivo2);


        if (move_uploaded_file($nombreTemp2, $ruta . "pequena." . $nomSeparado2[1])) {
            //return $nombreArchivo.".".$nomSeparado2[1];
        }

        if (move_uploaded_file($nombreTemp2, $ruta . "grande." . $nomSeparado2[1])) {
            //return $nombreArchivo.".".$nomSeparado2[1];
        }
    }
}

//comprobar archivo
function comprobarArchivo($archivo) {
    if (isset($_FILES[$archivo]) && $_FILES[$archivo]["size"] > 0) {
        $nombreArchivo2 = $_FILES[$archivo]["name"];
        return $nombreArchivo2;
    }
}

//comprobar archivo
function comprobarArchivos($archivo) {

    //if (isset($_FILES[$archivo]) && $_FILES[$archivo]["size"] > 0) {
    if (isset($_FILES[$archivo])) {
        for ($i = 0; $i < count($_FILES[$archivo]["name"]); $i++) {
            if($_FILES[$archivo]["size"][$i]<0)
                return false;
        }
        return true;
    }
}

//Eliminar archivo de una carpeta
function elimarArchivo($carpeta, $archivo) {
    if ($archivo != "" && $archivo != " ") {
        if (file_exists($carpeta . $archivo)) {
            unlink($carpeta . $archivo);
        }
    }
}

//Eliminar archivo de una carpeta
function elimarArchivo2($ruta) {

    if (file_exists($ruta)) {
        unlink($ruta);
    }
}

//Eliminar directorios recursivamente
function eliminarRecursivoContenidoDeDirectorio($carpeta) {
    if (is_dir($carpeta)) {
        $directorio = opendir($carpeta);

        while ($archivo = readdir($directorio)) {
            if ($archivo != '.' && $archivo != '..') {
                //comprobamos si es un directorio o un archivo
                if (is_dir($carpeta . $archivo)) {
                    //si es un directorio, volvemos a llamar a la función para que elimine el contenido del mismo
                    eliminarRecursivoContenidoDeDirectorio($carpeta . $archivo . '/');
                    rmdir($carpeta . $archivo); //borrar el directorio cuando esté vacío
                } else {
                    //si no es un directorio, lo borramos
                    unlink($carpeta . $archivo);
                }
            }
        }

        closedir($directorio);
        rmdir($carpeta);
    }
}

function redimensionarImagen($anchoMaximo, $altoMaximo, $ubicacionImagen, $ubicacionImagen2, $nombreNuevoArchivo) {
    // Configuracion de la altura y ancho maximo de la imagen
    $anchura = $anchoMaximo;
    $hmax = $altoMaximo;

    // Parametro a enviar - es decir origen de la imagen
    $nombre = $ubicacionImagen;


    // Tomando informacion de la imagene, esta funcion envia los datos a un array
    $datos = getimagesize($nombre);

    //echo "ancho o alto".$datos[3];


    if ($datos[2] == 1) {
        $img = @imagecreatefromgif($nombre);
    }  // si es un Gif

    if ($datos[2] == 2) {
        $img = @imagecreatefromjpeg($nombre);
    } // si es un jpg

    if ($datos[2] == 3) {
        $img = @imagecreatefrompng($nombre);
    } //  si es un png


    /*
      aqui empieza lo bueno
      como bien sabemos no podemos redimensionar una imagen vertical de la misma forma que una horizontal
      bueno esta parte del script no ayuda a poder hacerlo lo que realiza es dividir el ancho original entre
      el ancho maximo y luego divide la altura original entre el resultado anterior
      bueno esto es para el ancho
     */

    $ratio = ($datos[0] / $anchura);
    $altura = ($datos[1] / $ratio);



    /*
      Para el Alto
      sera esto primero pregunta si el alto es mayor que el alto maximo que hemos definido
      esto solo significa que es una imagen vertical claro si la condicion nos da true
      lo que aremos sera multiplicar la altura maxima por la anchura que hemos definido
      y la dividimos entre la altura esto nos dara una imagen vertical apropiada para
      nuestra muestra
     */


    if ($altura > $hmax) {

        $anchura2 = $hmax * $anchura / $altura;
        $altura = $hmax;
        $anchura = $anchura2;
    }



    $thumb = imagecreatetruecolor($anchura, $altura); // se crea una imagen en blanco
    // Procedemos a remplazar la imagen antigua por la nueva
    imagecopyresampled($thumb, $img, 0, 0, 0, 0, $anchura, $altura, $datos[0], $datos[1]);



    // $destino=ImageCreateTrueColor($w,$h);
    //  imagecopyresampled($destino,$imagen,0,0,0,0,$w,$h,$x,$y);


    if ($datos[2] == 3) {
        imagepng($thumb, $ubicacionImagen2 . ".png");
        return $nombreNuevoArchivo . ".png";
    }
    if ($datos[2] == 1) {
        imagegif($thumb, $ubicacionImagen2 . ".gif");
        return $nombreNuevoArchivo . ".gif";
    } else {
        imagejpeg($thumb, $ubicacionImagen2 . ".jpg");
        return $nombreNuevoArchivo . ".jpg";
    }

    //  imagedestroy($thumb);
    // Preguntamos el tipo de imagen para saber que cabezera enviar
    /* if($datos[2]==1){header("Content-type: image/gif"); imagegif($thumb);}
      if($datos[2]==2){header("Content-type: image/jpeg");imagejpeg($thumb);}
      if($datos[2]==3){header("Content-type: image/png");imagepng($thumb); } */


    // Destruimos la imagen temporal para no recargar el servidor
    imagedestroy($thumb);
    imagedestroy($img);
    // Listo ya tenemos nuestro redimensionador listo
}

/* * ******************************************************************** */
/* * ********************* MANEJO FECHAS ****************************** */
/* * ******************************************************************** */

//Descifrar nombre mes
function descifrarMes($mes) {
    $arrayMeses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    if ($mes < 10) {
        $mes = $mes * 1;
    }

    return $arrayMeses[$mes];
}

//Ordenar calendario fecha
function ordenarFechaCalendario($fecha) {
    $fecha = explode("/", $fecha);

    $dia = $fecha[1];
    $mes = $fecha[0];
    $anyo = $fecha[2];

    return $fecha = $anyo . "-" . $mes . "-" . $dia;
}

//Ordenar fecha calendario 2
function ordenarFechaCalendario2($fecha) {
    $fecha = explode("-", $fecha);

    $dia = $fecha[1];
    $mes = $fecha[0];
    $anyo = $fecha[2];

    return $fecha = $dia . "/" . $anyo . "/" . $mes;
}

/* * ******************************************************************** */
/* * ********************* YOUTUBE ****************************** */
/* * ******************************************************************** */

//Descifrar enlace youtube
function descifrarEnlaceYoutube($enlace) {
    $vista = explode("=", $enlace);
    //$vista2 = explode("&",$vista[1]);
    return substr($vista[1], 0, 11);
}

/* * ******************************************************************** */
/* * ********************* URLS AMIGABLES ****************************** */
/* * ******************************************************************** */

function getVariables($url) {
    //quitamos la barra del final
    $url = preg_replace('/\/$/', '', $url);

    //separamos las partes de la url y las contamos
    $partes = explode('/', $url);
    $cantPartes = count($partes);

    //si la cantidad de partes no es par retornamos false, al ser impar una variable se quedaria sin valor y esto no es posible
    if ($cantPartes % 2 != 0)
        return false;

    $variables = array();
    for ($c = 0; $c < $cantPartes; $c = $c + 2) {
        //Acumulamos los pares de valores(para nosotros variables) en el arreglo
        $nombre = limpiar($partes[$c]);
        $valor = limpiar($partes[$c + 1]);
        $variables[$nombre] = $valor;
    }

    return $variables;
}

function limpiar($valor) {
    //permitimos solo letras(a-Z), numeros y guiones
    return preg_replace('/[^a-zA-Z0-9-_]/', '', $valor);
}

function urls_amigables($url) {

    // Tranformamos todo a minusculas

    $url = strtolower($url);

    //Rememplazamos caracteres especiales latinos

    $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');

    $repl = array('a', 'e', 'i', 'o', 'u', 'n');

    $url = str_replace($find, $repl, $url);

    // Añaadimos los guiones

    $find = array(' ', '&', '\r\n', '\n', '+');
    $url = str_replace($find, '-', $url);

    // Eliminamos y Reemplazamos demás caracteres especiales

    $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');

    $repl = array('', '-', '');

    $url = preg_replace($find, $repl, $url);

    return $url;
}

?>