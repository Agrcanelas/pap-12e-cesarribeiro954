<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Ecopeças</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">

<style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:"Roboto", sans-serif; }

    body {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;

        /* FUNDO DO LOGIN */
        /* Substitui "SEU_LINK_DE_IMAGEM_AQUI" pelo link da tua imagem */
        background: linear-gradient(rgba(0,128,0,0.3), rgba(0,128,0,0.3)),
                    url("https://img.freepik.com/fotos-premium/simbolo-de-carro-de-brilho-ecologico-feito-de-folhas-conceito-ecologico-isolado-em-fundo-preto_955712-32071.jpg?semt=ais_hybrid&w=740&q=80") no-repeat center center/cover;
    }

    .login-container {
        width: 100%;
        max-width: 420px;
        background: rgba(255, 255, 255, 0.95);
        padding: 40px;
        border-radius: 25px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        animation: fadeIn 0.6s ease;
        text-align: center;
    }

    @keyframes fadeIn {
        from { opacity:0; transform: translateY(25px); }
        to { opacity:1; transform: translateY(0); }
    }

    /* HEADER COM LOGO AO LADO DO TÍTULO */
    .login-header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        margin-bottom: 30px;
    }

    .login-header .logo {
        width: 60px; /* tamanho do ícone */
        height: 60px;
        border-radius: 12px;
        object-fit: contain;
        filter: drop-shadow(0 3px 8px rgba(0,0,0,0.25));
    }

    .login-header h1 {
        font-size: 32px;
        font-weight: bold;
        color: #2e7d32;
        margin: 0;
    }

    h2 {
        margin-bottom: 25px;
        font-size: 22px;
        color: #2e7d32;
    }

    .input-container {
        text-align:left;
        margin-bottom: 20px;
    }

    .input-container label {
        font-weight: bold;
        color:#2e7d32;
        font-size: 15px;
    }

    .input-container input {
        width: 100%;
        padding: 12px;
        margin-top: 6px;
        border-radius: 12px;
        border: 1px solid #cccccc;
        transition: 0.3s;
        font-size: 15px;
    }

    .input-container input:focus {
        border-color: #66d78b;
        box-shadow: 0 0 8px rgba(102, 215, 139, 0.5);
        outline: none;
    }

    .login-btn {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #66d78b, #4caf70);
        border: none;
        border-radius: 50px;
        color: white;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 8px 22px rgba(76,175,112,0.35);
        transition: 0.3s;
        margin-top: 5px;
    }

    .login-btn:hover {
        transform: scale(1.03);
        box-shadow: 0 12px 26px rgba(76,175,112,0.45);
    }

    .register-link {
        margin-top: 20px;
        font-size: 14px;
    }

    .register-link a {
        color: #2e7d32;
        font-weight: bold;
        text-decoration: none;
    }

    .register-link a:hover {
        text-decoration: underline;
    }

</style>
</head>

<body>

<div class="login-container">

    <!-- HEADER: LOGO AO LADO DO TÍTULO -->
    <div class="login-header">
        <img src="https://img.freepik.com/vetores-premium/ilustracao-vetorial-de-icones-da-interface-de-usuario_1253044-4512.jpg?semt=ais_hybrid&w=740&q=80" alt="Logo Ecopeças" class="logo">
        <h1>Ecopeças</h1>
    </div>

    <h2>Login</h2>

    <div class="input-container">
        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Digite o seu email">
    </div>

    <div class="input-container">
        <label for="password">Palavra-passe</label>
        <input type="password" id="password" placeholder="Digite a sua palavra-passe">
    </div>

    <button class="login-btn">Entrar</button>

    <p class="register-link">Ainda não tem conta? <a href="#">Criar conta</a></p>

</div>

</body>
</html>

