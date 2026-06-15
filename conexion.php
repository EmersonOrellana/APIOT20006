<?php
function getConexion() {
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "control_doc&hosp";
    $conexion = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conexion;
}
?>