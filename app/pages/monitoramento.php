<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
  header('Location: cadastro.php');
  exit();
}

require_once '../config/database.php';

$database = new Database();
$db = $database->pdo;

// Buscar dados do usuário
$query = "SELECT * FROM usuarios WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $_SESSION['usuario_id']);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
  session_destroy();
  header('Location: usuario.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoramento em Tempo Real - ZenWatt</title>
    <link rel="stylesheet" href="../assets/css/gerenciar.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Mesma estrutura de sidebar do gerenciar.php -->
  <aside class="sidebar">
    <div class="profile">
      <img src="../assets/images/fav-zen.png" alt="Foto do Usuário">
      <h3><?php echo htmlspecialchars($usuario['nome']); ?></h3>
      <p><?php echo htmlspecialchars($usuario['email']); ?></p>
    </div>
    <ul class="menu">
      <li class="active"><a href="usuario.php"><i class="fas fa-home"></i> <span
            style="color: #fff !important;">Dashboard</span></li></a>
      <li><i class="fas fa-user"></i> <a href="../pages/gerenciar.php" style="color: #fff !important;">Gerenciar</a>
      </li>
      <li><i class="fa-solid fa-chart-line"></i> <a href="monitoramento.php"><span style="color: #fff !important;">Monitoramento</span></a></li>
      <li><i class="fas fa-star"></i> <span style="color: #fff !important;">Favoritos</span></li>
      <li><i class="fas fa-cog"></i> <a href="../pages/conta.php"><span
            style="color: #fff !important;">Configurações</span></li></a>
      <li><i class="fas fa-lock"></i> <span style="color: #fff !important;">Privacidade</span></li>
      <li class="logout">
        <a href="../pages/logout.php" style="color: inherit; text-decoration: none;">
          <i class="fas fa-sign-out-alt"></i> <span style="color: #fff !important;">Sair</span>
        </a>
      </li>
    </ul>
  </aside>

    <main class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <h1><i class="fas fa-bolt"></i> Monitoramento em Tempo Real</h1>
                <p>Dados de corrente e potência da residência</p>
            </div>
        </header>

        <section class="dashboard">
            <!-- Cards de Status em Tempo Real -->
            <div class="summary-cards">
                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <div class="summary-info">
                        <h3 id="currentPower">0 W</h3>
                        <p>Potência Atual</p>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div class="summary-info">
                        <h3 id="currentCurrent">0 A</h3>
                        <p>Corrente Atual</p>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="summary-info">
                        <h3 id="dataCount">0</h3>
                        <p>Registros</p>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="tabs-container">
                <div class="tabs">
                    <button class="tab-button active" data-tab="tempo-real">
                        <i class="fas fa-clock"></i> Tempo Real
                    </button>
                    <button class="tab-button" data-tab="historico">
                        <i class="fas fa-history"></i> Histórico
                    </button>
                </div>

                <div class="tab-content">
                    <!-- Aba Tempo Real -->
                    <div id="tab-tempo-real" class="tab-pane active">
                        <div class="card">
                            <div class="chart-container">
                                <canvas id="realtimeChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Aba Histórico -->
                    <div id="tab-historico" class="tab-pane">
                        <div class="card">
                            <div class="chart-container">
                                <canvas id="historicalChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabela de Dados Recentes -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-table"></i> Dados Recentes</h3>
                    <button class="btn btn-primary" onclick="exportData()">
                        <i class="fas fa-download"></i> Exportar CSV
                    </button>
                </div>
                <div class="table-container">
                    <table class="table" id="tabelaDados">
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>Corrente (A)</th>
                                <th>Potência (W)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="corpoTabelaDados">
                            <!-- Dados carregados via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <script src="../assets/js/realtime.js"></script>
</body>

</html>