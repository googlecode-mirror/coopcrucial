<?php

ini_set('include_path', ini_get('include_path') . '.:/home/content/07/5840507/html/clientes/crucial/e-commerce/frameworks/PEAR');

require_once 'PEAR.php';
require_once 'MDB/QueryTool.php';
require_once 'MDB2.php';
require_once '../clases/tablas.clases.php';

function conectar() {
    if (!isset($mdb2)) {
        //Local
        $db_name = 'root';
        $db_password = '';
        $db_server = 'localhost';
        $db_database = 'coopcrucial';
        //Nabica
        /*$db_name = 'coopcrucial';
        $db_password = 'Nabica2012';
        $db_server = 'coopcrucial.db.5840507.hostedresource.com';
        $db_database = 'coopcrucial';*/
        $dsn = 'mysql://' . $db_name . ':' . $db_password . '@' . $db_server . '/' . $db_database;
        try {
            $mdb2 = & MDB2::factory($dsn, true);
            if (MDB2::isError($mdb2)) {
                die($mdb2->getmessage() . ' - ' . $mdb2->getUserInfo());
            } else {
                $mdb2->setFetchMode(MDB2_FETCHMODE_ASSOC);
            }
            $mdb2 = array('mdb2' => $mdb2, 'dsn' => $dsn);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    return $mdb2;
}

?>
