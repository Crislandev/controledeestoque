<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
  http_response_code(403);
  echo "Acesso negado";
  exit;
}

$termo = isset($_GET['q']) ? trim($_GET['q']) : '';

$sql = "SELECT * FROM produtos";
$params = [];

if ($termo !== '') {
  $sql .= " WHERE nome LIKE ? OR sku LIKE ?";
  $params = ["%$termo%", "%$termo%"];
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($produtos);
