<?php
    if (!class_exists('Conexion')) {
        
    class Conexion {
        public function conectar() {
            $servidor = "localhost";
            $usuario = "jmora";
            $password = "Moaj890119*";
            $db = "helpdesk";
            $conexion = mysqli_connect($servidor, $usuario, $password, $db);
            return $conexion;
        }
    }
}