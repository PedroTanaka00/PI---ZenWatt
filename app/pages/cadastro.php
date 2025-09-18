<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cadastro & Login</title>
  <link rel="stylesheet" href="../assets/css/cadastro.css">
  <script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>
</head>
<body>

  <div class="container" id="container">
    <!-- Form de Login -->
    <div class="form-container login-container" id="loginContainer" aria-hidden="true">
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
    <div class="form-container register-container" id="registerContainer" aria-hidden="false">
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
          <button type="button" class="tensao-btn" onclick="selectTensao(this)" aria-pressed="false">110V</button>
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
    <div class="overlay-container" id="overlay" role="region" aria-label="Painel de alternância">
      <img src="../assets/images/logo-branca.png" width="100" alt="Foto do Usuário">
      <h2 id="overlayTitle">Olá, amigo!</h2>
      <p id="overlayText">Ainda não tem conta? Cadastre-se e comece agora mesmo.</p>
      <button id="toggleBtn" aria-pressed="true" aria-controls="loginContainer registerContainer">Ir para Login</button>
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

      // breakpoint mobile
      const MOBILE_BREAK = 768;
      let isMobile = window.innerWidth <= MOBILE_BREAK;

      // Inicializar estado: por especificação, mostrar CADASTRO por padrão
      function initState() {
        isMobile = window.innerWidth <= MOBILE_BREAK;
        if (isMobile) {
          // mobile: mostra cadastro por padrão
          container.classList.remove('active');          // remove desktop slide
          container.classList.add('mobile-register');    // mobile flag para mostrar register
          overlayTitle.textContent = "Olá, amigo!";
          overlayText.textContent = "Ainda não tem conta? Cadastre-se e comece agora mesmo.";
          toggleBtn.textContent = "Ir para Login";
          toggleBtn.setAttribute('aria-pressed','true');
          loginContainer.setAttribute('aria-hidden','true');
          registerContainer.setAttribute('aria-hidden','false');
        } else {
          // desktop: adiciona classe active para mostrar register (por padrão)
          container.classList.add('active');
          container.classList.remove('mobile-register');
          overlayTitle.textContent = "Olá, amigo!";
          overlayText.textContent = "Ainda não tem conta? Cadastre-se e comece agora mesmo.";
          toggleBtn.textContent = "Ir para Login";
          toggleBtn.setAttribute('aria-pressed','true');
          loginContainer.setAttribute('aria-hidden','true');
          registerContainer.setAttribute('aria-hidden','false');
        }
      }

      // Alterna (funciona tanto em mobile quanto desktop)
      function togglePanels() {
        if (window.innerWidth <= MOBILE_BREAK) {
          // mobile behavior: toggle mobile-register to show one form at a time
          container.classList.toggle('mobile-register');
          const showingRegister = container.classList.contains('mobile-register');
          if (showingRegister) {
            overlayTitle.textContent = "Olá, amigo!";
            overlayText.textContent = "Ainda não tem conta? Cadastre-se e comece agora mesmo.";
            toggleBtn.textContent = "Ir para Login";
            toggleBtn.setAttribute('aria-pressed','true');
            loginContainer.setAttribute('aria-hidden','true');
            registerContainer.setAttribute('aria-hidden','false');
          } else {
            overlayTitle.textContent = "Bem-vindo de volta!";
            overlayText.textContent = "Já possui conta? Entre agora mesmo para continuar economizando.";
            toggleBtn.textContent = "Ir para Cadastro";
            toggleBtn.setAttribute('aria-pressed','false');
            loginContainer.setAttribute('aria-hidden','false');
            registerContainer.setAttribute('aria-hidden','true');
          }
        } else {
          // desktop behavior: slide (active class)
          container.classList.toggle('active');
          const showingRegister = container.classList.contains('active');
          if (showingRegister) {
            overlayTitle.textContent = "Olá, amigo!";
            overlayText.textContent = "Ainda não tem conta? Cadastre-se e comece agora mesmo.";
            toggleBtn.textContent = "Ir para Login";
            toggleBtn.setAttribute('aria-pressed','true');
            loginContainer.setAttribute('aria-hidden','true');
            registerContainer.setAttribute('aria-hidden','false');
          } else {
            overlayTitle.textContent = "Bem-vindo de volta!";
            overlayText.textContent = "Já possui conta? Entre agora mesmo para continuar economizando.";
            toggleBtn.textContent = "Ir para Cadastro";
            toggleBtn.setAttribute('aria-pressed','false');
            loginContainer.setAttribute('aria-hidden','false');
            registerContainer.setAttribute('aria-hidden','true');
          }
        }
      }

      // Seleção de tensão (UI)
      window.selectTensao = function(btn) {
        document.querySelectorAll(".tensao-btn").forEach(b => {
          b.classList.remove("active");
          b.setAttribute('aria-pressed','false');
        });
        btn.classList.add("active");
        btn.setAttribute('aria-pressed','true');
        // se quiser guardar o valor: use btn.textContent.trim()
      };

      // Handle resize -> manter coerência entre mobile/desktop
      let resizeTimer;
      function handleResize() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
          const prevMobile = isMobile;
          isMobile = window.innerWidth <= MOBILE_BREAK;
          if (isMobile !== prevMobile) {
            // crossing breakpoint -> re-initialize default (cadastro visible)
            initState();
          }
        }, 80);
      }

      // eventos
      window.addEventListener('resize', handleResize);
      window.addEventListener('orientationchange', initState);
      toggleBtn.addEventListener('click', togglePanels);

      // start
      document.addEventListener('DOMContentLoaded', initState);
    })();
  </script>

</body>
</html>
