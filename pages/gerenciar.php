<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ./pages/usuario.php');
    exit();
}

// Usar caminho absoluto para evitar erros
$base_dir = dirname(__DIR__); 
require_once $base_dir . '/config/database.php';

$database = new Database();
$db = $database->pdo;

// Buscar dados do usuário
$query = "SELECT * FROM usuarios WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $_SESSION['usuario_id']);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Painel do Usuário - ZenWatt</title>
  <link rel="stylesheet" href="../assets/css/usuario.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="profile">
      <img src="../assets/images/fav-zen.png" alt="Foto do Usuário">
      <h3><?php echo htmlspecialchars($usuario['nome']); ?></h3>
      <p><?php echo htmlspecialchars($usuario['email']); ?></p>
    </div>
    <ul class="menu">
      <li class="active"><i class="fas fa-home"></i> <a href="../pages/usuario.php"> <span>Dashboard</span></a></li>
      <li><i class="fas fa-user"></i> <a href="../pages/conta.php">Conta</a></li>
      <li><i class="fas fa-map-marker-alt"></i> <span>Localização</span></li>
      <li><i class="fas fa-comment"></i> <span>Chat</span></li>
      <li><i class="fas fa-star"></i> <span>Favoritos</span></li>
      <li><i class="fas fa-cog"></i> <span>Configurações</span></li>
      <li><i class="fas fa-lock"></i> <span>Privacidade</span></li>
      <li class="logout">
        <a href="logout.php" style="color: inherit; text-decoration: none;">
          <i class="fas fa-sign-out-alt"></i> <span>Sair</span>
        </a>
      </li>
    </ul>
  </aside>

  <!-- Main -->
  <main class="main-content">
    <!-- Topbar -->
    <header class="topbar">
      <div class="search-box">
        <input type="text" id="searchInput" placeholder="Pesquisar...">
        <i class="fas fa-search"></i>
      </div>
      <div class="top-icons">
        <i class="fas fa-bell"></i>
        <i class="fas fa-user"></i>
        <i class="fas fa-ellipsis-h"></i>
      </div>
    </header>

    <!-- Dashboard -->
    <section class="dashboard">

      <!-- Seus KPIs e Gráficos permanecem iguais -->

      <!-- ========================== -->
      <!-- SEÇÃO DE IMÓVEIS -->
      <!-- ========================== -->
      <div class="card wide">
        <div class="card-header">
          <h3>Gerenciar Imóveis</h3>
        </div>

        <!-- Formulário -->
        <form id="formImovel" class="aparelho-form">
          <input type="hidden" id="imovel_id" name="id">
          <div class="form-row">
            <div class="field">
              <label>Nome do Imóvel</label>
              <input type="text" id="nome_imovel" name="nome" placeholder="Ex: Casa Principal" required />
            </div>
            <div class="field">
              <label>Endereço</label>
              <input type="text" id="endereco_imovel" name="endereco" placeholder="Rua, nº, bairro" />
            </div>
            <div class="field submit-field">
              <button type="submit" class="btn"><i class="fa-solid fa-plus"></i> Cadastrar</button>
              <button type="button" id="cancelarEdicaoImovel" class="btn danger" style="display:none; margin-left: 10px;">Cancelar</button>
            </div>
          </div>
        </form>

        <!-- Tabela -->
        <div class="table-wrap">
          <table class="table" id="tabelaImoveis">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Endereço</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              <!-- Preenchido via JS -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- ========================== -->
      <!-- SEÇÃO DE ÁREAS -->
      <!-- ========================== -->
      <div class="card wide">
        <div class="card-header">
          <h3>Gerenciar Áreas</h3>
        </div>

        <!-- Formulário -->
        <form id="formArea" class="aparelho-form">
          <input type="hidden" id="area_id" name="id">
          <div class="form-row">
            <div class="field">
              <label>Imóvel</label>
              <select id="imovel_area" name="imovel_id" class="form-control" required>
                <option value="">Selecione um imóvel</option>
              </select>
            </div>
            <div class="field">
              <label>Nome da Área</label>
              <input type="text" id="nome_area" name="nome" placeholder="Ex: Sala de Estar" required />
            </div>
            <div class="field submit-field">
              <button type="submit" class="btn"><i class="fa-solid fa-plus"></i> Cadastrar</button>
              <button type="button" id="cancelarEdicaoArea" class="btn danger" style="display:none; margin-left: 10px;">Cancelar</button>
            </div>
          </div>
        </form>

        <!-- Tabela -->
        <div class="table-wrap">
          <table class="table" id="tabelaAreas">
            <thead>
              <tr>
                <th>Área</th>
                <th>Imóvel</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              <!-- Preenchido via JS -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- ========================== -->
      <!-- SEÇÃO DE EQUIPAMENTOS -->
      <!-- ========================== -->
      <div class="card wide">
        <div class="card-header">
          <h3>Gerenciar Equipamentos</h3>
        </div>

        <!-- Formulário -->
        <form id="formEquipamento" class="aparelho-form">
          <input type="hidden" id="equip_id" name="id">
          <div class="form-row">
            <div class="field">
              <label>Área</label>
              <select id="area_equip" name="area_id" class="form-control" required>
                <option value="">Selecione uma área</option>
              </select>
            </div>
            <div class="field">
              <label>Nome do Equipamento</label>
              <input type="text" id="nome_equip" name="nome" placeholder="Ex: Ar Condicionado" required />
            </div>
            <div class="field">
              <label>Modelo</label>
              <input type="text" id="modelo_equip" name="modelo" placeholder="Ex: Split 12.000 BTUs" />
            </div>
          </div>
          <div class="form-row">
            <div class="field">
              <label>Potência (W)</label>
              <input type="number" step="0.01" id="potencia_equip" name="potencia" placeholder="Ex: 1200" required />
            </div>
            <div class="field">
              <label>Horas por Dia</label>
              <input type="number" step="0.1" id="horas_equip" name="horas_por_dia" placeholder="Ex: 4.5" required />
            </div>
            <div class="field submit-field">
              <button type="submit" class="btn"><i class="fa-solid fa-plus"></i> Cadastrar</button>
              <button type="button" id="cancelarEdicaoEquip" class="btn danger" style="display:none; margin-left: 10px;">Cancelar</button>
            </div>
          </div>
        </form>

        <!-- Tabela -->
        <div class="table-wrap">
          <table class="table" id="tabelaEquip">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Modelo</th>
                <th>Potência (W)</th>
                <th>Horas/dia</th>
                <th>Área</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              <!-- Preenchido via JS -->
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
  // ==============================
  // IMÓVEIS - CRUD VIA FETCH
  // ==============================

  async function carregarImoveis() {
    try {
      const res = await fetch("listar_imoveis.php");
      const imoveis = await res.json();

      const tbody = document.querySelector("#tabelaImoveis tbody");
      const selectArea = document.querySelector("#imovel_area");
      
      tbody.innerHTML = "";
      // Manter a primeira opção e limpar as demais
      selectArea.innerHTML = '<option value="">Selecione um imóvel</option>';

      imoveis.forEach(imovel => {
        // Preencher tabela
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${imovel.nome}</td>
          <td>${imovel.endereco || ''}</td>
          <td>
            <button onclick="editarImovel(${imovel.id}, '${imovel.nome}', '${imovel.endereco || ''}')" class="btn">Editar</button>
            <button onclick="excluirImovel(${imovel.id})" class="btn danger">Excluir</button>
          </td>
        `;
        tbody.appendChild(tr);

        // Preencher select de áreas
        const option = document.createElement("option");
        option.value = imovel.id;
        option.textContent = imovel.nome;
        selectArea.appendChild(option);
      });
    } catch (error) {
      console.error("Erro ao carregar imóveis:", error);
    }
  }

  // Salvar/Editar Imóvel
  document.getElementById("formImovel").addEventListener("submit", async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const id = document.getElementById("imovel_id").value;
    const url = id ? "atualizar_imovel.php" : "salvar_imovel.php";

    try {
      const res = await fetch(url, { method: "POST", body: formData });
      const data = await res.json();

      alert(data.mensagem);
      if (data.sucesso) {
        this.reset();
        document.getElementById("cancelarEdicaoImovel").style.display = "none";
        document.getElementById("imovel_id").value = "";
        carregarImoveis();
      }
    } catch (error) {
      console.error("Erro ao salvar imóvel:", error);
      alert("Erro ao processar a solicitação.");
    }
  });

  // Editar Imóvel
  function editarImovel(id, nome, endereco) {
    document.getElementById("imovel_id").value = id;
    document.getElementById("nome_imovel").value = nome;
    document.getElementById("endereco_imovel").value = endereco;
    document.getElementById("cancelarEdicaoImovel").style.display = "inline-block";
  }

  // Cancelar Edição Imóvel
  document.getElementById("cancelarEdicaoImovel").addEventListener("click", function() {
    document.getElementById("formImovel").reset();
    document.getElementById("imovel_id").value = "";
    this.style.display = "none";
  });

  // Excluir Imóvel
  async function excluirImovel(id) {
    if (!confirm("Deseja realmente excluir este imóvel?")) return;

    const formData = new FormData();
    formData.append("id", id);

    try {
      const res = await fetch("excluir_imovel.php", { method: "POST", body: formData });
      const data = await res.json();

      alert(data.mensagem);
      if (data.sucesso) carregarImoveis();
    } catch (error) {
      console.error("Erro ao excluir imóvel:", error);
      alert("Erro ao processar a solicitação.");
    }
  }

  // ==============================
  // ÁREAS - CRUD VIA FETCH
  // ==============================

  async function carregarAreas() {
    try {
      const res = await fetch("listar_areas.php");
      const areas = await res.json();

      const tbody = document.querySelector("#tabelaAreas tbody");
      const selectEquip = document.querySelector("#area_equip");
      
      tbody.innerHTML = "";
      // Manter a primeira opção e limpar as demais
      selectEquip.innerHTML = '<option value="">Selecione uma área</option>';

      areas.forEach(area => {
        // Preencher tabela
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${area.nome}</td>
          <td>${area.imovel_nome}</td>
          <td>
            <button onclick="editarArea(${area.id}, '${area.nome}', ${area.imovel_id})" class="btn">Editar</button>
            <button onclick="excluirArea(${area.id})" class="btn danger">Excluir</button>
          </td>
        `;
        tbody.appendChild(tr);

        // Preencher select de equipamentos
        const option = document.createElement("option");
        option.value = area.id;
        option.textContent = `${area.nome} (${area.imovel_nome})`;
        selectEquip.appendChild(option);
      });
    } catch (error) {
      console.error("Erro ao carregar áreas:", error);
    }
  }

  // Salvar/Editar Área
  document.getElementById("formArea").addEventListener("submit", async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const id = document.getElementById("area_id").value;
    const url = id ? "atualizar_area.php" : "salvar_area.php";

    try {
      const res = await fetch(url, { method: "POST", body: formData });
      const data = await res.json();

      alert(data.mensagem);
      if (data.sucesso) {
        this.reset();
        document.getElementById("cancelarEdicaoArea").style.display = "none";
        document.getElementById("area_id").value = "";
        carregarAreas();
      }
    } catch (error) {
      console.error("Erro ao salvar área:", error);
      alert("Erro ao processar a solicitação.");
    }
  });

  // Editar Área
  function editarArea(id, nome, imovelId) {
    document.getElementById("area_id").value = id;
    document.getElementById("nome_area").value = nome;
    document.getElementById("imovel_area").value = imovelId;
    document.getElementById("cancelarEdicaoArea").style.display = "inline-block";
  }

  // Cancelar Edição Área
  document.getElementById("cancelarEdicaoArea").addEventListener("click", function() {
    document.getElementById("formArea").reset();
    document.getElementById("area_id").value = "";
    this.style.display = "none";
  });

  // Excluir Área
  async function excluirArea(id) {
    if (!confirm("Deseja realmente excluir esta área?")) return;

    const formData = new FormData();
    formData.append("id", id);

    try {
      const res = await fetch("excluir_area.php", { method: "POST", body: formData });
      const data = await res.json();

      alert(data.mensagem);
      if (data.sucesso) carregarAreas();
    } catch (error) {
      console.error("Erro ao excluir área:", error);
      alert("Erro ao processar a solicitação.");
    }
  }

  // ==============================
  // EQUIPAMENTOS - CRUD VIA FETCH
  // ==============================

  async function carregarEquipamentos() {
    try {
      const res = await fetch("listar_equipamentos.php");
      const equipamentos = await res.json();

      const tbody = document.querySelector("#tabelaEquip tbody");
      tbody.innerHTML = "";

      equipamentos.forEach(equip => {
        // Preencher tabela
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${equip.nome}</td>
          <td>${equip.modelo || ''}</td>
          <td>${equip.potencia}</td>
          <td>${equip.horas_por_dia}</td>
          <td>${equip.area_nome}</td>
          <td>
            <button onclick="editarEquipamento(${equip.id}, '${equip.nome}', '${equip.modelo || ''}', ${equip.potencia}, ${equip.horas_por_dia}, ${equip.area_id})" class="btn">Editar</button>
            <button onclick="excluirEquipamento(${equip.id})" class="btn danger">Excluir</button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    } catch (error) {
      console.error("Erro ao carregar equipamentos:", error);
    }
  }

  // Salvar/Editar Equipamento
  document.getElementById("formEquipamento").addEventListener("submit", async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const id = document.getElementById("equip_id").value;
    const url = id ? "atualizar_equipamento.php" : "salvar_equipamento.php";

    try {
      const res = await fetch(url, { method: "POST", body: formData });
      const data = await res.json();

      alert(data.mensagem);
      if (data.sucesso) {
        this.reset();
        document.getElementById("cancelarEdicaoEquip").style.display = "none";
        document.getElementById("equip_id").value = "";
        carregarEquipamentos();
      }
    } catch (error) {
      console.error("Erro ao salvar equipamento:", error);
      alert("Erro ao processar a solicitação.");
    }
  });

  // Editar Equipamento
  function editarEquipamento(id, nome, modelo, potencia, horas, areaId) {
    document.getElementById("equip_id").value = id;
    document.getElementById("nome_equip").value = nome;
    document.getElementById("modelo_equip").value = modelo;
    document.getElementById("potencia_equip").value = potencia;
    document.getElementById("horas_equip").value = horas;
    document.getElementById("area_equip").value = areaId;
    document.getElementById("cancelarEdicaoEquip").style.display = "inline-block";
  }

  // Cancelar Edição Equipamento
  document.getElementById("cancelarEdicaoEquip").addEventListener("click", function() {
    document.getElementById("formEquipamento").reset();
    document.getElementById("equip_id").value = "";
    this.style.display = "none";
  });

  // Excluir Equipamento
  async function excluirEquipamento(id) {
    if (!confirm("Deseja realmente excluir este equipamento?")) return;

    const formData = new FormData();
    formData.append("id", id);

    try {
      const res = await fetch("excluir_equipamento.php", { method: "POST", body: formData });
      const data = await res.json();

      alert(data.mensagem);
      if (data.sucesso) carregarEquipamentos();
    } catch (error) {
      console.error("Erro ao excluir equipamento:", error);
      alert("Erro ao processar a solicitação.");
    }
  }

  // Carregar dados na entrada
  document.addEventListener("DOMContentLoaded", function() {
    carregarImoveis();
    carregarAreas();
    carregarEquipamentos();
  });
  </script>

  <style>
  /* Estilos adicionais para os botões e formulários */
  .btn.danger {
    background: linear-gradient(135deg, #ff4d4d 0%, #cc0000 100%);
    box-shadow: 0 6px 18px rgba(255, 77, 77, 0.25);
  }
  
  .form-control {
    border: 1px solid #e3e9e9;
    border-radius: 12px;
    padding: 10px 12px;
    outline: none;
    background: #fbfdfd;
    width: 100%;
  }
  
  .form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
    margin-bottom: 15px;
  }
  
  .table-wrap {
    margin-top: 20px;
    max-height: 300px;
    overflow-y: auto;
  }
  
  .table th {
    position: sticky;
    top: 0;
    background: #f7faf9;
    z-index: 10;
  }
  </style>
</body>
</html>
