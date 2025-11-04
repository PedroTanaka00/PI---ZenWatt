<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$csvFile = 'data.csv';
$data = [];
$lastHours = 24; // Últimas 24 horas

if (file_exists($csvFile)) {
    $lines = file($csvFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    // Pular cabeçalho
    array_shift($lines);
    
    // Pegar apenas os últimos X registros ou por período
    $recentData = array_slice($lines, -100); // Últimos 100 registros
    
    foreach ($recentData as $line) {
        $values = str_getcsv($line);
        if (count($values) >= 3) {
            $data[] = [
                'timestamp' => $values[0],
                'corrente' => floatval($values[1]),
                'potencia' => floatval($values[2])
            ];
        }
    }
}

echo json_encode([
    'success' => true,
    'data' => $data,
    'total' => count($data)
]);
?>