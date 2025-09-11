<?php
session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["sucesso" => false, "mensagem" => "Usuário não logado"]);
    exit;
}

$database = new Database();
$db = $database->pdo;

$imovel_id = $_POST['imovel_id'] ?? null;
$nome = trim($_POST['nome'] ?? '');

if (!$imovel_id || empty($nome)) {
    echo json_encode(["sucesso" => false, "mensagem" => "Dados inválidos"]);
    exit;
}

$stmt = $db->prepare("INSERT INTO areas (imovel_id, nome) VALUES (:imovel_id, :nome)");
$stmt->execute([':imovel_id' => $imovel_id, ':nome' => $nome]);

echo json_encode(["sucesso" => true, "mensagem" => "Área cadastrada com sucesso"]);
