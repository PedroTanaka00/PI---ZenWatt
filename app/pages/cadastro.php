<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cadastro & Login Responsivo</title>
  <script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../assets/css/cadastro.css" />

</head>
<body>

  <div class="container" id="container">
    <!-- Form de Login -->
    <div class="form-container login-container" id="loginContainer">
      <form class="form" id="loginForm" autocomplete="on">
        <h2>Login</h2>
        <div class="input-group">
          <i class="fas fa-envelope" aria-hidden="true"></i>
          <input type="email" placeholder="E-mail" name="email" required autocomplete="email">
        </div>
        <div class="input-group">
          <i class="fas fa-lock" aria-hidden="true"></i>
          <input type="password" placeholder="Senha" name="senha" required autocomplete="current-password">
        </div>
        <button type="submit" class="submit-btn">Entrar</button>
      </form>
    </div>

    <!-- Form de Cadastro -->
    <div class="form-container register-container active-mobile" id="registerContainer">
      <form class="form" id="registerForm" autocomplete="on">
        <h2>Cadastrar</h2>
        <div class="input-group">
          <i class="fas fa-user" aria-hidden="true"></i>
          <input type="text" placeholder="Nome completo" name="nome" required autocomplete="name">
        </div>
        <div class="input-group">
          <i class="fas fa-envelope" aria-hidden="true"></i>
          <input type="email" placeholder="E-mail" name="email_cadastro" required autocomplete="email">
        </div>
        <div class="input-group">
          <i class="fas fa-phone" aria-hidden="true"></i>
          <input type="tel" placeholder="Telefone" name="telefone" autocomplete="tel">
        </div>

        <div class="tensao-options" role="tablist" aria-label="Tensão Residencial">
          <button type="button" class="tensao-btn" onclick="selectTensao(this)" aria-pressed="false">127V</button>
          <button type="button" class="tensao-btn" onclick="selectTensao(this)" aria-pressed="false">220V</button>
        </div>

        <div class="input-group">
          <i class="fas fa-lock" aria-hidden="true"></i>
          <input type="password" placeholder="Senha" name="senha_cadastro" required autocomplete="new-password">
        </div>
        <div class="input-group">
          <i class="fas fa-lock" aria-hidden="true"></i>
          <input type="password" placeholder="Confirmar Senha" name="confirmar_senha" required autocomplete="new-password">
        </div>

        <button type="submit" class="submit-btn">Cadastrar</button>
      </form>
    </div>

    <!-- Overlay (painel de instrução/alternância) -->
    <div class="overlay-container" id="overlay">
      <div class="logo" >
        <i class="fas fa-leaf" style="font-size: 30px; color: #fff;"></i>
      </div>
      <img src="../assets/images/logo-branca.png" width="100" alt="Foto do Usuário">
      <h2 id="overlayTitle">Olá!</h2>
      <p id="overlayText">Ainda não tem conta? Cadastre-se e comece agora mesmo.</p>
      <button id="toggleBtn">Já Possuo Conta</button>
    </div>
  </div>

  <script>
    (function () {
      const container = document.getElementById("container");
      const toggleBtn = document.getElementById("toggleBtn");
      const overlayTitle = document.getElementById("overlayTitle");
      const overlayText = document.getElementById("overlayText");
      const loginContainer = document.getElementById("loginContainer");
      const registerContainer = document.getElementById("registerContainer");

      const MOBILE_BREAK = 768;
      let isMobile = window.innerWidth <= MOBILE_BREAK;
      let showingRegister = true; // Por padrão, mostra cadastro

      function updateUI() {
        if (showingRegister) {
          overlayTitle.textContent = "Olá!";
          overlayText.textContent = "Ainda não tem conta? Cadastre-se e comece agora mesmo.";
          toggleBtn.textContent = "Já Possuo Conta";
        } else {
          overlayTitle.textContent = "Bem-vindo de volta!";
          overlayText.textContent = "Já possui conta? Entre agora mesmo para continuar economizando.";
          toggleBtn.textContent = "Não Possuo Conta";
        }
      }

      function initState() {
        isMobile = window.innerWidth <= MOBILE_BREAK;
        showingRegister = true; // Sempre inicia mostrando cadastro
        
        if (isMobile) {
          // Mobile: usar classes específicas
          container.classList.remove('active', 'show-login');
          loginContainer.classList.remove('active-mobile');
          registerContainer.classList.add('active-mobile');
        } else {
          // Desktop: usar sistema de slides
          container.classList.add('active');
          container.classList.remove('show-login');
        }
        
        updateUI();
      }

      function togglePanels() {
        showingRegister = !showingRegister;
        
        if (isMobile) {
          // Mobile: alternar visibilidade
          if (showingRegister) {
            container.classList.remove('show-login');
            loginContainer.classList.remove('active-mobile');
            registerContainer.classList.add('active-mobile');
          } else {
            container.classList.add('show-login');
            loginContainer.classList.add('active-mobile');
            registerContainer.classList.remove('active-mobile');
          }
        } else {
          // Desktop: sistema de slides
          if (showingRegister) {
            container.classList.add('active');
          } else {
            container.classList.remove('active');
          }
        }
        
        updateUI();
      }

      // Seleção de tensão
      window.selectTensao = function(btn) {
        document.querySelectorAll(".tensao-btn").forEach(b => {
          b.classList.remove("active");
          b.setAttribute('aria-pressed','false');
        });
        btn.classList.add("active");
        btn.setAttribute('aria-pressed','true');
      };

      // Handle resize
      let resizeTimer;
      function handleResize() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
          const prevMobile = isMobile;
          isMobile = window.innerWidth <= MOBILE_BREAK;
          if (isMobile !== prevMobile) {
            initState();
          }
        }, 100);
      }

      // Eventos
      window.addEventListener('resize', handleResize);
      window.addEventListener('orientationchange', () => {
        setTimeout(initState, 100);
      });
      toggleBtn.addEventListener('click', togglePanels);

      // Inicializar
      document.addEventListener('DOMContentLoaded', initState);
      
      // Garantir inicialização mesmo se DOM já carregou
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initState);
      } else {
        initState();
      }
    })();
  </script>

</body>
</html>