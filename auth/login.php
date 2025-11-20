<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ecopeças</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            /* Fundo com imagem + gradiente verde */
            background: linear-gradient(rgba(0, 128, 0, 0.3), rgba(0, 128, 0, 0.3)), 
                        url('https://cdn.shopify.com/s/files/1/0050/1068/6042/files/eco-car-icon.webp?v=1675186015') no-repeat center center/cover;
        }

        .login-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
            width: 350px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(60deg, #a0e8b0, #66d78b, #a0e8b0, #66d78b);
            animation: rotate 6s linear infinite;
            z-index: 0;
            opacity: 0.1;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .login-container h1 {
            font-size: 28px;
            color: #2e7d32;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .login-container h2 {
            margin-bottom: 25px;
            color: #333;
            font-weight: 400;
            position: relative;
            z-index: 1;
        }

        .input-group {
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 50px;
            border: 1px solid #ccc;
            outline: none;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            border-color: #66d78b;
            box-shadow: 0 0 5px rgba(102, 215, 139, 0.5);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #66d78b;
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .btn-login:hover {
            background: #4caf70;
        }

        .login-footer {
            margin-top: 15px;
            font-size: 14px;
            color: #555;
            position: relative;
            z-index: 1;
        }

        .login-footer a {
            color: #66d78b;
            text-decoration: none;
            font-weight: bold;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Ecopeças</h1>
        <h2>Login</h2>
        <form action="#" method="POST">
            <div class="input-group">
                <input type="text" name="username" placeholder="Usuário" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Senha" required>
            </div>
            <button type="submit" class="btn-login">Entrar</button>
        </form>
        <div class="login-footer">
            Não tem conta? <a href="#">Cadastre-se</a>
        </div>
    </div>
</body>
</html>
