<?php
require 'db.php';
if (!isset($_SESSION['user'])) { http_response_code(401); exit; }

$action = $_GET['action'] ?? '';

switch ($action) {
  case 'listar':
    $stmt = $pdo->query("SELECT * FROM produtos ORDER BY id DESC");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    break;

  case 'salvar':
    $data = json_decode(file_get_contents("php://input"), true);
    if (empty($data['id'])) {
      $stmt = $pdo->prepare("INSERT INTO produtos (nome, sku, descricao, quantidade, preco) VALUES (?, ?, ?, ?, ?)");
      $stmt->execute([$data['nome'], $data['sku'], $data['descricao'], $data['quantidade'], $data['preco']]);
    } else {
      $stmt = $pdo->prepare("UPDATE produtos SET nome=?, sku=?, descricao=?, quantidade=?, preco=? WHERE id=?");
      $stmt->execute([$data['nome'], $data['sku'], $data['descricao'], $data['quantidade'], $data['preco'], $data['id']]);
    }
    echo json_encode(["success"=>true]);
    break;

  case 'excluir':
    $id = $_GET['id'] ?? 0;
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id=?");
    $stmt->execute([$id]);
    echo json_encode(["success"=>true]);
    break;

  case 'movimentar':
    $data = json_decode(file_get_contents("php://input"), true);
    $tipo = $data['tipo']; // entrada ou saida
    $quant = intval($data['quantidade']);
    $produto_id = intval($data['produto_id']);
    $user_id = $_SESSION['user']['id'];

    // atualiza quantidade
    $stmt = $pdo->prepare("SELECT quantidade FROM produtos WHERE id=?");
    $stmt->execute([$produto_id]);
    $atual = $stmt->fetchColumn();

    $novaQtd = ($tipo === 'entrada') ? $atual + $quant : $atual - $quant;
    if ($novaQtd < 0) $novaQtd = 0;

    $pdo->prepare("UPDATE produtos SET quantidade=? WHERE id=?")->execute([$novaQtd, $produto_id]);

    // registra movimentação
    $pdo->prepare("INSERT INTO movimentacoes (produto_id, tipo, quantidade, usuario_id) VALUES (?, ?, ?, ?)")
        ->execute([$produto_id, $tipo, $quant, $user_id]);

    echo json_encode(["success"=>true]);
    break;

  case 'movimentos':
    $sql = "SELECT m.*, p.nome AS produto, u.nome AS usuario
            FROM movimentacoes m
            JOIN produtos p ON m.produto_id = p.id
            JOIN usuarios u ON m.usuario_id = u.id
            ORDER BY m.id DESC";
    $stmt = $pdo->query($sql);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    break;

  default:
    echo json_encode(["error"=>"Ação inválida"]);
}
?>
