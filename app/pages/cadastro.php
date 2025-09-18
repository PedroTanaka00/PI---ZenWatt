<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cadastro & Login</title>
  <style>
    /* Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    :root {
  /* Light Theme Colors */
  --primary-color: #04b600;
  --secondary-color: #014700;
  --accent-color: #8eff8d;
  --text-primary: #014700;
  --text-secondary: #7f8c8d;
  --bg-primary: #dfdfdf;
  --bg-secondary: #f8fafcc9;
  --bg-card: #ffffff;
  --border-color: #e2e8f0;
  --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  --gradient-primary: linear-gradient(135deg, #04b600 0%, #014700 100%);
  --gradient-secondary: linear-gradient(135deg, #8eff8d 0%, #04b600 100%);
}

[data-theme="dark"] {
  /* Dark Theme Colors */
  --text-primary: #ffffff;
  --text-secondary: #cbd5e0;
  --bg-primary: #0f130f;
  --bg-secondary: #111111a2;
  --bg-card: #111111;
  --border-color: #2e682d;
  --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
  --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
}


    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: url(../assets/images/favicon/banner-login.png) no-repeat center center;
      background-size: cover;
      height: 100vh;
      overflow: hidden;
    }

    .container {
      position: relative;
      width: 900px;
      max-width: 95%;
      height: 600px;
      border-radius: 20px;
      overflow: hidden;
      display: flex;
      box-shadow: 0 10px 25px rgba(0,0,0,0.3);
      backdrop-filter: blur(12px);
    }

    /* Painel que se move */
    .overlay-container {
      position: absolute;
      top: 0;
      left: 50%;
      width: 50%;
      height: 100%;
      background: linear-gradient(135deg, rgba(0,200,81,0.9), rgba(0,148,50,0.9));
      color: #fff;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 40px;
      transition: transform 0.8s ease-in-out;
      z-index: 1000;
    }

    .overlay-container h2 {
      font-size: 28px;
      margin-bottom: 15px;
    }

    .overlay-container p {
      font-size: 16px;
      margin-bottom: 25px;
    }

    .overlay-container button {
      background: #fff;
      color: #009432;
      border: none;
      padding: 12px 25px;
      border-radius: 25px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .overlay-container button:hover {
      background: #f0f0f0;
      transform: scale(1.05);
    }

    /* Containers de forms */
    .form-container {
      position: absolute;
      top: 0;
      height: 100%;
      width: 50%;
      padding: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
      transition: all 0.8s ease-in-out;
      background: rgba(255,255,255,0.15);
      backdrop-filter: blur(18px);
    }

    .form {
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 15px;
      color: #fff;
    }

    .form h2 {
      text-align: center;
      color: #fff;
      margin-bottom: 10px;
    }

    .input-group {
      display: flex;
      align-items: center;
      border: 1px solid rgba(255,255,255,0.3);
      border-radius: 10px;
      padding: 12px;
      transition: 0.3s;
      background: rgba(255,255,255,0.2);
    }

    .input-group:focus-within {
      border-color: #00c851;
      box-shadow: 0 0 8px rgba(0,200,81,0.3);
    }

    .input-group i {
      color: #fff;
      margin-right: 10px;
      font-size: 18px;
    }

    .input-group input {
      border: none;
      outline: none;
      flex: 1;
      font-size: 15px;
      background: transparent;
      color: #fff;
    }

    .input-group input::placeholder {
      color: rgba(255,255,255,0.7);
    }

    .submit-btn {
      background: linear-gradient(135deg, #00c851, #009432);
      border: none;
      padding: 14px;
      border-radius: 10px;
      color: #fff;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .submit-btn:hover {
      transform: scale(1.05);
      background: linear-gradient(135deg, #009432, #007a2f);
    }

    /* Botões de tensão residencial */
    .tensao-options {
      display: flex;
      gap: 15px;
      justify-content: center;
    }

    .tensao-btn {
      flex: 1;
      padding: 12px;
      border: 2px solid #fff;
      border-radius: 10px;
      background: transparent;
      cursor: pointer;
      font-weight: 600;
      transition: 0.3s;
      color: #fff;
    }

    .tensao-btn:hover {
      background: rgba(255,255,255,0.2);
    }

    .tensao-btn.active {
      background: #009432;
      border-color: #009432;
    }

    /* Posição inicial */
    .login-container {
      left: 0;
      z-index: 2;
    }

    .register-container {
      left: 0;
      opacity: 0;
      z-index: 1;
    }

    /* Ativando cadastro */
    .container.active .login-container {
      transform: translateX(100%);
      opacity: 0;
      z-index: 1;
    }

    .container.active .register-container {
      transform: translateX(100%);
      opacity: 1;
      z-index: 2;
    }

    .container.active .overlay-container {
      transform: translateX(-100%);
    }

    /* Responsivo */
    @media (max-width: 768px) {
      .container {
        width: 100%;
        height: auto;
        flex-direction: column;
      }

      .form-container {
        position: relative;
        width: 100%;
        height: auto;
        padding: 30px 20px;
      }

      .overlay-container {
        position: relative;
        width: 100%;
        height: auto;
        padding: 30px 20px;
        transform: translateX(0) !important;
      }

      .container.active .login-container,
      .container.active .register-container {
        transform: translateX(0);
      }

      .overlay-container button {
        margin-top: 15px;
      }
    }
  </style>
  <script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>
</head>
<body>

  <div class="container" id="container" >
    <!-- Form de Login -->
    <div class="form-container login-container">
      <form class="form">
        <h2>Login</h2>
        <div class="input-group">
          <i class="fas fa-envelope"></i>
          <input type="email" placeholder="E-mail">
        </div>
        <div class="input-group">
          <i class="fas fa-lock"></i>
          <input type="password" placeholder="Senha">
        </div>
        <button class="submit-btn">Entrar</button>
      </form>
    </div>

    <!-- Form de Cadastro -->
    <div class="form-container register-container">
      <form class="form">
        <h2>Cadastrar</h2>
        <div class="input-group">
          <i class="fas fa-user"></i>
          <input type="text" placeholder="Nome completo">
        </div>
        <div class="input-group">
          <i class="fas fa-envelope"></i>
          <input type="email" placeholder="E-mail">
        </div>
        <div class="input-group">
          <i class="fas fa-phone"></i>
          <input type="tel" placeholder="Telefone">
        </div>
        <div class="tensao-options">
          <button type="button" class="tensao-btn" onclick="selectTensao(this)">110V</button>
          <button type="button" class="tensao-btn" onclick="selectTensao(this)">220V</button>
        </div>
        <div class="input-group">
          <i class="fas fa-lock"></i>
          <input type="password" placeholder="Senha">
        </div>
        <div class="input-group">
          <i class="fas fa-lock"></i>
          <input type="password" placeholder="Confirmar Senha">
        </div>
        <button class="submit-btn">Cadastrar</button>
      </form>
    </div>

    <!-- Overlay -->
    <div class="overlay-container" id="overlay">
      <h2 id="overlayTitle">Bem-vindo de volta!</h2>
      <p id="overlayText">Já possui conta? Entre agora mesmo para continuar economizando.</p>
      <button id="toggleBtn">Ir para Cadastro</button>
    </div>
  </div>

  <script>
    const container = document.getElementById("container");
    const toggleBtn = document.getElementById("toggleBtn");
    const overlayTitle = document.getElementById("overlayTitle");
    const overlayText = document.getElementById("overlayText");

    toggleBtn.addEventListener("click", () => {
      container.classList.toggle("active");
      if (container.classList.contains("active")) {
        overlayTitle.textContent = "Olá, amigo!";
        overlayText.textContent = "Ainda não tem conta? Cadastre-se e comece agora mesmo.";
        toggleBtn.textContent = "Ir para Login";
      } else {
        overlayTitle.textContent = "Bem-vindo de volta!";
        overlayText.textContent = "Já possui conta? Entre agora mesmo para continuar economizando.";
        toggleBtn.textContent = "Ir para Cadastro";
      }
    });

    function selectTensao(btn) {
      document.querySelectorAll(".tensao-btn").forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
    }
  </script>

</body>
</html>
