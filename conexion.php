<?php
function getConexion() {
    $host = "1p1ljt.h.filess.io";
    $db = "control_doc_hosp_scenedugso";
    $user = "control_doc_hosp_scenedugso";
    $pass = "f5b6f02f16d969e3d0beeb8e28e8662b0eb96e71";

    try {
        $pdo = new PDO("mysql:host=$host;port=3306;dbname=$db;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}