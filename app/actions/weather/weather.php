<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../helpers/auth.php';
login();

require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../../vendor/autoload.php';
$con = conectar();


use GuzzleHttp\Client;
$apiKey = '7ec76b954d6fc578bceb6adc6b84c3fe';
$city = isset($_GET['city']) ? $_GET['city'] : 'Elche'; 
//Tiempo actual
$url = "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric&lang=es";

// Previsión 5 días
$urlForecast = "http://api.openweathermap.org/data/2.5/forecast?q={$city}&appid={$apiKey}&units=metric&lang=es&cnt=8";


// Cliente HTTP con Guzzle
$client = new Client();
try {
// Realizar la solicitud a la API
$response = $client->request('GET', $url);
$data = json_decode($response->getBody(), true);
$weather = [
    'city' => $data['name'],
    'temperature' => $data['main']['temp'],
    'description' => $data['weather'][0]['description'],
    'icon' => $data['weather'][0]['icon'],
];
// Previsión próximas 24h
    $responseForecast = $client->request('GET', $urlForecast);
    $dataForecast = json_decode($responseForecast->getBody(), true);
    
    $forecast = [];
    foreach ($dataForecast['list'] as $item) {
        $forecast[] = [
            'time' => date('H:i', strtotime($item['dt_txt'])),
            'date' => date('d/m', strtotime($item['dt_txt'])),
            'temperature' => round($item['main']['temp']),
            'description' => ucfirst($item['weather'][0]['description']),
            'icon' => $item['weather'][0]['icon'],
        ];
    }

} catch (Exception $e) {
    $weather = null;
    $forecast = [];
    error_log("Error al obtener el clima: " . $e->getMessage());
}