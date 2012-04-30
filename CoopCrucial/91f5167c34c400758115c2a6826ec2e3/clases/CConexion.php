<?php

class CConexion {

    //Local
    public $hostname_conn = 'localhost';
    public $database_conn = 'coopcrucial';
    public $username_conn = 'root';
    public $password_conn = '';
    public $urlBaseSitio = "coopCrucial/";
    
    //Nabica
    /*public $hostname_conn = 'coopcrucial.db.5840507.hostedresource.com';
    public $database_conn = 'coopcrucial';
    public $username_conn = 'coopcrucial';
    public $password_conn = 'Nabica2012';
    public $urlBaseSitio = "coopCrucial/";*/

    public function obteneHostName() {
        return $this->hostname_conn;
    }

    public function obteneDataBase() {
        return $this->database_conn;
    }

    public function obteneUserName() {
        return $this->username_conn;
    }

    public function obtenePassword() {
        return $this->password_conn;
    }

    public function obteneUrlBaseSitio() {
        $url = "http://" . $_SERVER['HTTP_HOST'] . "/";
        return $url . $this->urlBaseSitio;
    }
}
?>