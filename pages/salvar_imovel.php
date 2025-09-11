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

$nome = trim($_POST['nome'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');

if (empty($nome)) {
    echo json_encode(["sucesso" => false, "mensagem" => "O nome do imóvel é obrigatório"]);
    exit;
}

$stmt = $db->prepare("INSERT INTO imoveis (usuario_id, nome, endereco) VALUES (:usuario_id, :nome, :endereco)");
$stmt->execute([
    ':usuario_id' => $_SESSION['usuario_id'],
    ':nome' => $nome,
    ':endereco' => $endereco
]);

echo json_encode(["sucesso" => true, "mensagem" => "Imóvel cadastrado com sucesso"]);
