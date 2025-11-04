<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Receber dados JSON do ESP32
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if ($data && isset($data['corrente'])) {
    $csvFile = 'data.csv';
    
    // Criar cabeçalho se arquivo não existir
    if (!file_exists($csvFile)) {
        $header = "timestamp,corrente,potencia\n";
        file_put_contents($csvFile, $header);
    }
    
    // Adicionar dados ao CSV
    $line = $data['timestamp'] . "," . 
            $data['corrente'] . "," . 
            $data['potencia'] . "\n";
    
    file_put_contents($csvFile, $line, FILE_APPEND | LOCK_EX);
    
    echo json_encode(['success' => true, 'message' => 'Dados salvos']);
} else {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
}
?>