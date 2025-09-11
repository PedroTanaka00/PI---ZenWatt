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

$id = $_POST['id'] ?? null;
$nome = trim($_POST['nome'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');

if (!$id || empty($nome)) {
    echo json_encode(["sucesso" => false, "mensagem" => "Dados inválidos"]);
    exit;
}

$stmt = $db->prepare("UPDATE imoveis SET nome = :nome, endereco = :endereco WHERE id = :id AND usuario_id = :usuario_id");
$stmt->execute([
    ':nome' => $nome,
    ':endereco' => $endereco,
    ':id' => $id,
    ':usuario_id' => $_SESSION['usuario_id']
]);

echo json_encode(["sucesso" => true, "mensagem" => "Imóvel atualizado com sucesso"]);
