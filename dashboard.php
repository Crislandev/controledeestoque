<?php
require 'db.php';
if (!isset($_SESSION['user'])) {
  header("Location: index.html");
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Painel - Controle de Estoque</title>
  <link rel="stylesheet" href="assets/style2.css">
</head>
<body>

<header class="main-header">
  <div class="logo">
    <h1>📦 Controle de Estoque</h1>
  </div>
  <div class="user-info">
    <span>👋 Olá, <strong><?= $_SESSION['user']['nome'] ?></strong></span>
    <a href="auth.php?action=logout" class="btn-logout">Sair</a>
  </div>
</header>

<main class="dashboard">

  <!-- FORMULÁRIO -->
  <section id="form-section" class="card form-card">
    <h2 id="form-title">Novo Produto</h2>
    <input type="hidden" id="produto-id">

    <div class="form-grid">
      <input type="text" id="nome" placeholder="Nome do produto">
      <input type="text" id="sku" placeholder="Marca">
      <input type="number" id="quantidade" placeholder="Quantidade">
      <input type="number" step="0.01" id="preco" placeholder="Preço">
    </div>
    <textarea id="descricao" placeholder="Descrição do produto"></textarea>

    <div class="btn-group">
      <button class="btn-primary" onclick="salvarProduto()">💾 Salvar</button>
      <button class="btn-secondary" onclick="limparFormulario()">🧹 Limpar</button>
    </div>
  </section>

  <!-- PRODUTOS -->
  <section class="card table-card">
  <h2>📋 Produtos Cadastrados</h2>

  <div class="search-box">
    <input type="text" id="buscar" placeholder="🔍 Buscar produto..." onkeyup="buscarProduto()">
  </div>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Marca</th>
        <th>Qtd</th>
        <th>Preço</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody id="tabela"></tbody>
  </table>
</section>

  <!-- MOVIMENTAÇÕES -->
  <section class="card table-card">
    <h2>📈 Histórico de Movimentações</h2>
    <table>
      <thead>
        <tr>
          <th>Produto</th>
          <th>Tipo</th>
          <th>Qtd</th>
          <th>Usuário</th>
          <th>Data</th>
        </tr>
      </thead>
      <tbody id="movimentos"></tbody>
    </table>
  </section>

</main>

<script src="assets/script.js"></script>
</body>
</html>

