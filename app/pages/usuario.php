<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ./pages/usuario.php');
    exit();
}

// Usar caminho absoluto para evitar erros
$base_dir = dirname(__DIR__); // Volta 1 nível para a pasta app
require_once $base_dir . '/config/database.php';

$database = new Database();
// Use $db = $database->pdo; (como no login) OU $db = $database->getConnection();
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

  <!-- Favicons -->
  <link rel="apple-touch-icon-precomposed" sizes="57x57" href="/favicon/apple-touch-icon-57x57.png" />
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/favicon/apple-touch-icon-114x114.png" />
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/favicon/apple-touch-icon-72x72.png" />
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/favicon/apple-touch-icon-144x144.png" />
  <link rel="apple-touch-icon-precomposed" sizes="60x60" href="/favicon/apple-touch-icon-60x60.png" />
  <link rel="apple-touch-icon-precomposed" sizes="120x120" href="/favicon/apple-touch-icon-120x120.png" />
  <link rel="apple-touch-icon-precomposed" sizes="76x76" href="/favicon/apple-touch-icon-76x76.png" />
  <link rel="apple-touch-icon-precomposed" sizes="152x152" href="/favicon/apple-touch-icon-152x152.png" />
  <link rel="icon" type="image/png" href="/favicon/favicon-196x196.png" sizes="196x196" />
  <link rel="icon" type="image/png" href="/favicon/favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/png" href="/favicon/favicon-32x32.png" sizes="32x32" />
  <link rel="icon" type="image/png" href="/favicon/favicon-16x16.png" sizes="16x16" />
  <link rel="icon" type="image/png" href="/favicon/favicon-128.png" sizes="128x128" />
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
      <li class="active"><i class="fas fa-home"></i> <span>Dashboard</span></li>
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
      <!-- KPIs -->
      <div class="card small kpi">
        <div class="kpi-icon"><i class="fa-solid fa-bolt"></i></div>
        <div>
          <h3>Consumo Hoje</h3>
          <p class="big-number" id="kpiHoje">12.4 kWh</p>
          <span class="kpi-sub">+8% vs ontem</span>
        </div>
      </div>

      <div class="card small kpi">
        <div class="kpi-icon"><i class="fa-solid fa-money-bill-wave"></i></div>
        <div>
          <h3>Custo Estimado (mês)</h3>
          <p class="big-number" id="kpiCusto">R$ 238,90</p>
          <span class="kpi-sub">Bandeira: Verde</span>
        </div>
      </div>

      <div class="card small kpi">
        <div class="kpi-icon"><i class="fa-solid fa-seedling"></i></div>
        <div>
          <h3>Economia</h3>
          <p class="big-number" id="kpiEconomia">18%</p>
          <span class="kpi-sub">vs média dos últimos 3 meses</span>
        </div>
      </div>

      <!-- Gráficos principais -->
      <div class="card chart">
        <div class="card-header">
          <h3>Consumo Diário (últimos 30 dias)</h3>
          <div class="actions">
            <button class="btn ghost" id="btnAtualizar1"><i class="fa-solid fa-rotate"></i> atualizar</button>
          </div>
        </div>
        <canvas id="lineChart"></canvas>
      </div>

      <div class="card chart">
        <div class="card-header">
          <h3>Comparativo Mensal (kWh)</h3>
          <div class="actions">
            <button class="btn ghost" id="btnAtualizar2"><i class="fa-solid fa-rotate"></i> atualizar</button>
          </div>
        </div>
        <canvas id="barChart"></canvas>
      </div>

      <div class="card chart">
        <div class="card-header">
          <h3>Consumo por Finalidade</h3>
        </div>
        <canvas id="doughnutChart"></canvas>
      </div>

      <div class="card chart">
        <div class="card-header">
          <h3>Picos por Faixa Horária</h3>
        </div>
        <canvas id="areaChart"></canvas>
      </div>

      <div class="card chart">
        <div class="card-header">
          <h3>Participação por Eletrodoméstico</h3>
        </div>
        <canvas id="aparelhosChart"></canvas>
      </div>

      <div class="card chart">
        <div class="card-header">
          <h3>Projeção de Consumo (próx. 6 meses)</h3>
        </div>
        <canvas id="projectionChart"></canvas>
      </div>

      <!-- Calendário (maior + CTA) -->
      <div class="card calendar" style="width: 200% !important">
        <div class="card-header calendar-header">
          <h3>Calendário</h3>
          <div class="calendar-cta">
            <span class="hint"><i class="fa-solid fa-clock-rotate-left"></i> Dica: acompanhe o <strong>Histórico de consumo</strong> por dia.</span>
            <a href="historico.php" class="btn">Ver Histórico</a>
          </div>
        </div>
        <div class="calendar-grid" id="calendarGrid">
          <div>D</div><div>S</div><div>T</div><div>Q</div><div>Q</div><div>S</div><div>S</div>
          <!-- exemplo mês com 31 dias e offset de 2 -->
          <div></div><div></div>
          <div class="day">1</div><div class="day">2</div><div class="day">3</div><div class="day">4</div><div class="day">5</div>
          <div class="day">6</div><div class="day">7</div><div class="day active">8</div><div class="day">9</div><div class="day">10</div><div class="day">11</div><div class="day">12</div>
          <div class="day">13</div><div class="day">14</div><div class="day">15</div><div class="day">16</div><div class="day">17</div><div class="day">18</div><div class="day">19</div>
          <div class="day">20</div><div class="day">21</div><div class="day">22</div><div class="day">23</div><div class="day active">24</div><div class="day">25</div><div class="day">26</div>
          <div class="day">27</div><div class="day">28</div><div class="day">29</div><div class="day">30</div><div class="day">31</div>
        </div>
      </div>

      <!-- Formulário de eletrodomésticos -->
      <div class="card wide">
        <div class="card-header">
          <h3>Adicionar Eletrodoméstico</h3>
        </div>
        <form id="formAparelho" class="aparelho-form">
          <div class="form-row">
            <div class="field">
              <label>Nome</label>
              <input type="text" id="aparelhoNome" placeholder="Ex: Geladeira" required />
            </div>
            <div class="field">
              <label>Potência (W)</label>
              <input type="number" id="aparelhoPotencia" min="1" placeholder="Ex: 150" required />
            </div>
            <div class="field">
              <label>Horas/dia</label>
              <input type="number" id="aparelhoHoras" step="0.1" min="0" placeholder="Ex: 8" required />
            </div>
            <div class="field">
              <label>Quantidade</label>
              <input type="number" id="aparelhoQtd" min="1" value="1" required />
            </div>
            <div class="field submit-field">
              <button type="submit" class="btn"><i class="fa-solid fa-plus"></i> Adicionar</button>
            </div>
          </div>
        </form>

        <div class="table-wrap">
          <table class="table" id="tabelaAparelhos">
            <thead>
              <tr>
                <th>Aparelho</th>
                <th>Potência (W)</th>
                <th>Horas/dia</th>
                <th>Qtd</th>
                <th>kWh/mês (estimado)</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <!-- linhas adicionadas via JS -->
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main>

  <!-- Modal do calendário -->
  <div class="modal" id="calendarModal">
    <div class="modal-content">
      <span class="close-btn" id="closeModal">&times;</span>
      <h2 id="modalTitle">Evento</h2>
      <p id="modalText">Consumo do dia selecionado e dicas aparecerão aqui.</p>
      <div class="modal-actions">
        <a class="btn" href="historico.php">Abrir histórico</a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="../assets/js/usuario.js"></script>
</body>
</html>
