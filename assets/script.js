async function carregarProdutos() {
  const res = await fetch('api.php?action=listar');
  const produtos = await res.json();
  const tbody = document.getElementById('tabela');
  tbody.innerHTML = produtos.map(p => `
    <tr>
      <td>${p.id}</td>
      <td>${p.nome}</td>
      <td>${p.sku || '-'}</td>
      <td>${p.quantidade}</td>
      <td>R$ ${p.preco}</td>
      <td>
        <button onclick='editar(${JSON.stringify(p)})'>Editar</button>
        <button onclick='remover(${p.id})'>Excluir</button>
        <button onclick='movimentar(${p.id},"entrada")'>+ Entrada</button>
        <button onclick='movimentar(${p.id},"saida")'>- Saída</button>
      </td>
    </tr>`).join('');
}

async function carregarMovimentos() {
  const res = await fetch('api.php?action=movimentos');
  const lista = await res.json();
  const tb = document.getElementById('movimentos');
  tb.innerHTML = lista.map(m => `
    <tr>
      <td>${m.produto}</td>
      <td>${m.tipo}</td>
      <td>${m.quantidade}</td>
      <td>${m.usuario}</td>
      <td>${m.criado_em}</td>
    </tr>`).join('');
}

async function salvarProduto() {
  const produto = {
    id: document.getElementById('produto-id').value,
    nome: document.getElementById('nome').value,
    sku: document.getElementById('sku').value,
    quantidade: document.getElementById('quantidade').value,
    preco: document.getElementById('preco').value,
    descricao: document.getElementById('descricao').value
  };
  await fetch('api.php?action=salvar', {
    method: 'POST',
    body: JSON.stringify(produto)
  });
  limparFormulario();
  carregarProdutos();
}

function editar(p) {
  document.getElementById('produto-id').value = p.id;
  document.getElementById('nome').value = p.nome;
  document.getElementById('sku').value = p.sku;
  document.getElementById('quantidade').value = p.quantidade;
  document.getElementById('preco').value = p.preco;
  document.getElementById('descricao').value = p.descricao;
}

async function remover(id) {
  if (confirm("Excluir produto?")) {
    await fetch(`api.php?action=excluir&id=${id}`);
    carregarProdutos();
  }
}

async function movimentar(id, tipo) {
  const qtd = prompt(`Quantidade para ${tipo}:`);
  if (!qtd || isNaN(qtd) || qtd <= 0) return;
  await fetch('api.php?action=movimentar', {
    method: 'POST',
    body: JSON.stringify({ produto_id: id, tipo, quantidade: qtd })
  });
  carregarProdutos();
  carregarMovimentos();
}

function limparFormulario() {
  document.getElementById('produto-id').value = '';
  document.getElementById('nome').value = '';
  document.getElementById('sku').value = '';
  document.getElementById('quantidade').value = '';
  document.getElementById('preco').value = '';
  document.getElementById('descricao').value = '';
}

carregarProdutos();
carregarMovimentos();
function buscarProduto() {
  const termo = document.getElementById('buscar').value.toLowerCase();
  const linhas = document.querySelectorAll('#tabela tr');

  linhas.forEach(linha => {
    const nome = linha.cells[1]?.textContent.toLowerCase() || "";
    const sku = linha.cells[2]?.textContent.toLowerCase() || "";
    if (nome.includes(termo) || sku.includes(termo)) {
      linha.style.display = '';
    } else {
      linha.style.display = 'none';
    }
  });
}

	function buscarProduto() {
  const termo = document.getElementById('buscar').value.toLowerCase();
  const linhas = document.querySelectorAll('#tabela tr');

  linhas.forEach(linha => {
    const nome = linha.cells[1]?.textContent.toLowerCase() || "";
    const sku = linha.cells[2]?.textContent.toLowerCase() || "";
    if (nome.includes(termo) || sku.includes(termo)) {
      linha.style.display = '';
    } else {
      linha.style.display = 'none';
    }
  });
}
