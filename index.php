<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require 'conexion.php';

error_reporting(0); 

$app = AppFactory::create();

function jsonResponse($response, $data, $status = 200) {
    $response->getBody()->write(json_encode($data));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus($status);
}

$app->get('/doctores', function (Request $request, Response $response) {
    $db = getConexion();
    $stmt = $db->query("SELECT * FROM doctores");
    return jsonResponse($response, $stmt->fetchAll(PDO::FETCH_OBJ));
});

$app->post('/doctores/nuevo', function (Request $request, Response $response) {
    $data = json_decode($request->getBody()->getContents());
    
    if (!$data) return jsonResponse($response, ["error" => "JSON inválido"], 400);

    $db = getConexion();
    try {
        $sql = "INSERT INTO doctores (IdDoctor, NombresDoctor, ApellidosDoctor, Especialidad, TurnoAtencion, PacientesMinDiarios, Sueldo, IdHospital) 
                VALUES (:id, :nom, :ape, :esp, :tur, :pac, :sue, :idh)";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ":id"  => $data->IdDoctor ?? '',
            ":nom" => $data->NombresDoctor ?? '',
            ":ape" => $data->ApellidosDoctor ?? '',
            ":esp" => $data->Especialidad ?? '',
            ":tur" => $data->TurnoAtencion ?? '',
            ":pac" => $data->PacientesMinDiarios ?? 0,
            ":sue" => $data->Sueldo ?? 0,
            ":idh" => $data->IdHospital ?? ''
        ]);
        
        return jsonResponse($response, ["mensaje" => "Doctor guardado"]);
        
    } catch (PDOException $e) {
        // Si el IdHospital no existe, aquí recibirás: "SQLSTATE[23000]: Integrity constraint violation..."
        return jsonResponse($response, ["error" => $e->getMessage()], 500);
    }
});


$app->get('/hospitales', function (Request $request, Response $response) {
    $db = getConexion();
    $stmt = $db->query("SELECT * FROM hospitales");
    return jsonResponse($response, $stmt->fetchAll(PDO::FETCH_OBJ));
});

$app->post('/hospitales/nuevo', function (Request $request, Response $response) {
    $data = json_decode($request->getBody()->getContents());
    
    if (!$data) return jsonResponse($response, ["error" => "Datos incompletos"], 400);

    $db = getConexion();
    $sql = "INSERT INTO hospitales (IdHospital, NomHospital, CapacidadAtencion, Especialidades) 
            VALUES (:id, :nom, :cap, :esp)";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ":id"  => $data->IdHospital ?? '',
        ":nom" => $data->NomHospital ?? '',
        ":cap" => $data->CapacidadAtencion ?? 0,
        ":esp" => $data->Especialidades ?? ''
    ]);
    
    return jsonResponse($response, ["mensaje" => "Hospital guardado"]);
});

$app->run();