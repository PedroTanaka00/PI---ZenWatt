<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
  header('Location: login.php');
  exit();
}

// Usar caminho absoluto para evitar erros
$base_dir = dirname(__DIR__);
require_once $base_dir . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

// Buscar dados do usuário
$query = "SELECT * FROM usuarios WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $_SESSION['usuario_id']);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
  echo "Usuário não encontrado!";
  exit();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidade</title>
    <link rel="stylesheet" href="../assets/css/privacidade.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Ícones da sidebar (opcional) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css">
</head>

<body>

    <div class="layout">

<aside class="sidebar">
    <div class="profile">
      <img src="../assets/images/fav-zen.png" alt="Foto do Usuário">
      <h3><?php echo htmlspecialchars($usuario['nome']); ?></h3>
      <p><?php echo htmlspecialchars($usuario['email']); ?></p>
    </div>
    <ul class="menu">
      <li class="active"><a href="dashboard.php"><i class="fas fa-home"></i> <span
            style="color: #fff !important;">Dashboard</span></li></a>
      <li><i class="fas fa-user"></i> <a href="../pages/gerenciar.php" style="color: #fff !important;">Gerenciar</a>
      </li>
      <li><i class="fa-solid fa-chart-line"></i> <a href="monitoramento.php"><span
            style="color: #fff !important;">Monitoramento</span></a></li>
      <li><i class="fas fa-star"></i> <span style="color: #fff !important;">Favoritos</span></li>
      <li><i class="fas fa-cog"></i> <a href="../pages/conta.php"><span
            style="color: #fff !important;">Configurações</span></li></a>
      <li><i class="fas fa-lock"><a href="privacidade.php"></i> <span style="color: #fff !important;">Privacidade</span></li></a>
      <li class="logout">
        <a href="../pages/logout.php" style="color: inherit; text-decoration: none;">
          <i class="fas fa-sign-out-alt"></i> <span style="color: #fff !important;">Sair</span>
        </a>
      </li>
    </ul>
  </aside>

        <main class="content-area">

            <div class="container">
                <h1>Política de Privacidade</h1>
                <p class="update">Última atualização: 18/11/2025</p>

                <section>
                    <h2>1. Informações Gerais</h2>
                    <p>
                        Esta Política de Privacidade explica como coletamos, utilizamos, armazenamos e protegemos os
                        dados
                        dos usuários da plataforma destinada ao monitoramento de consumo de energia, análise de gastos e
                        fornecimento de recomendações sustentáveis.
                    </p>
                </section>

                <section>
                    <h2>2. Dados Que Coletamos</h2>

                    <h3>2.1. Dados fornecidos pelo usuário</h3>
                    <ul>
                        <li>Nome</li>
                        <li>E-mail</li>
                        <li>Senha (criptografada)</li>
                        <li>Telefone</li>
                        <li>Localização (cidade/estado) — usada para consultar a bandeira tarifária</li>
                        <li>Tensão residencial</li>
                    </ul>

                    <h3>2.2. Dados coletados automaticamente</h3>
                    <ul>
                        <li>Endereço IP</li>
                        <li>Data e hora de acesso</li>
                        <li>Dispositivo e navegador</li>
                        <li>Páginas visitadas</li>
                    </ul>

                    <h3>2.3. Dados técnicos</h3>
                    <ul>
                        <li>kWh consumido por cada equipamento</li>
                        <li>Dados separados por imóvel</li>
                        <li>Estimativas baseadas em bandeiras tarifárias</li>
                    </ul>
                </section>

                <section>
                    <h2>3. Como Utilizamos os Dados</h2>
                    <ul>
                        <li>Gerar análises e relatórios de consumo</li>
                        <li>Calcular gastos diários/mensais</li>
                        <li>Enviar alertas e recomendações de economia</li>
                        <li>Determinar a bandeira tarifária regional</li>
                        <li>Melhorar a experiência e segurança da plataforma</li>
                    </ul>
                </section>

                <section>
                    <h2>4. Armazenamento e Segurança</h2>
                    <p>
                        Utilizamos criptografia, protocolos HTTPS e controles de acesso.
                        Embora adotemos boas práticas, nenhum sistema digital é 100% seguro.
                    </p>
                </section>

                <section>
                    <h2>5. Compartilhamento de Dados</h2>
                    <p>Compartilhamos somente quando necessário:</p>
                    <ul>
                        <li>Cumprimento legal</li>
                        <li>Serviços essenciais (como hospedagem, banco de dados)</li>
                    </ul>
                </section>

                <section>
                    <h2>6. Direitos do Usuário</h2>
                    <ul>
                        <li>Acesso aos próprios dados</li>
                        <li>Correção ou atualização</li>
                        <li>Exclusão de conta</li>
                        <li>Revogação de consentimento</li>
                    </ul>
                </section>

                <section>
                    <h2>7. Cookies</h2>
                    <ul>
                        <li>Manter usuário logado</li>
                        <li>Preferências de navegação</li>
                        <li>Melhorar desempenho</li>
                    </ul>
                </section>

                <section>
                    <h2>8. Alterações</h2>
                    <p>
                        Esta política pode ser atualizada periodicamente. A nova versão entra em vigor após publicação.
                    </p>
                </section>

                <section>
                    <h2>9. Contato</h2>
                    <p>
                        Em caso de dúvidas, entre em contato:
                        <strong>suporte@seudominio.com</strong>
                    </p>
                </section>
            </div>

        </main>

    </div>

</body>

</html>