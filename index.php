<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require 'conexion.php';

$app = AppFactory::create();

$app->setBasePath("/APIOT20006");

$app->get('/doctores', function (Request $request, Response $response) {
    $db = getConexion();
    $sql = "SELECT * FROM Doctores";
    $stmt = $db->query($sql);
    $doctores = $stmt->fetchAll(PDO::FETCH_OBJ);
    $response->getBody()->write(json_encode($doctores));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/doctores/nuevo', function (Request $request, Response $response) {
    $data = json_decode($request->getBody());
    $db = getConexion();
    $sql = "INSERT INTO Doctores (IdDoctor, NombresDoctor, ApellidosDoctor, Especialidad, TurnoAtencion, PacientesMinDiarios, Sueldo, IdHospital) VALUES (:id, :nom, :ape, :esp, :tur, :pac, :sue, :idh)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ":id" => $data->IdDoctor,
        ":nom" => $data->NombresDoctor,
        ":ape" => $data->ApellidosDoctor,
        ":esp" => $data->Especialidad,
        ":tur" => $data->TurnoAtencion,
        ":pac" => $data->PacientesMinDiarios,
        ":sue" => $data->Sueldo,
        ":idh" => $data->IdHospital
    ]);
    $response->getBody()->write(json_encode(["mensaje" => "Doctor guardado"]));
    return $response;
});


$app->get('/hospitales', function (Request $request, Response $response) {
    $db = getConexion();
    $sql = "SELECT * FROM Hospitales";
    $stmt = $db->query($sql);
    $hospitales = $stmt->fetchAll(PDO::FETCH_OBJ);
    $response->getBody()->write(json_encode($hospitales));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/hospitales/nuevo', function (Request $request, Response $response) {
    $data = json_decode($request->getBody());
    $db = getConexion();
    
    $sql = "INSERT INTO hospitales (IdHospital, NomHospital, CapacidadAtencion, Especialidades) 
            VALUES (:id, :nom, :cap, :esp)";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ":id"  => $data->IdHospital,
        ":nom" => $data->NomHospital,
        ":cap" => $data->CapacidadAtencion,
        ":esp" => $data->Especialidades
    ]);
    
    $response->getBody()->write(json_encode(["mensaje" => "Hospital guardado"]));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/hospitales/{id}', function (Request $request, Response $response, array $args) {
    $db = getConexion();
    $sql = "SELECT * FROM hospitales WHERE IdHospital = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $args['id']]);
    $hospital = $stmt->fetch(PDO::FETCH_OBJ);

    $response->getBody()->write(json_encode($hospital));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
