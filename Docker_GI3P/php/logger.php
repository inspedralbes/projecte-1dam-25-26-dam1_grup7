<?php
// logger.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
use MongoDB\Client;

function registrarLog() {
    $inici = microtime(true);
    $usuari_id = $_SESSION['user_id'] ?? 'Anònim';

    try {
        $client = new Client("mongodb+srv://admin:example@gi3p.rjbxiyc.mongodb.net/?appName=GI3P");
        $collection = $client->Logs->registres_connexio;
        
        $temps_ms = round((microtime(true) - $inici) * 1000);
        
        $LOG = [
            'url'               => $_SERVER['REQUEST_URI'],
            'metode'            => $_SERVER['REQUEST_METHOD'],
            'usuari_id'         => $usuari_id,
            'timestamp'         => new MongoDB\BSON\UTCDateTime(),
            'navegador'         => $_SERVER['HTTP_USER_AGENT'],
            'ip'                => $_SERVER['REMOTE_ADDR'],
            'temps_resposta_ms' => $temps_ms
        ];

        $collection->insertOne($LOG);
    } catch (Exception $e) {
        error_log("Error guardant a MongoDB: " . $e->getMessage());
    }
}
