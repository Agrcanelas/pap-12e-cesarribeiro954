<?php
// register.php
session_start();
require_once 'config.php'; // Ajuste o caminho conforme sua pasta

$message = '';
$messageColor = '';

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Verificar campos
    if (!$name || !$email || !$password || !$confirmPassword) {
        $message = "Preencha todos os campos!";
        $messageColor = "red";
    } elseif ($password !== $confirmPassword) {
        $message = "As senhas não coincidem!";
        $messageColor = "red";
    } else {
        // Verificar se o email já existe
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows > 0){
            $message = "Email já registrado!";
            $messageColor = "red";
        } else {
            // Inserir no banco
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (nome, email, senha) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashedPassword);

            if($stmt->execute()){
                $message = "Conta criada com sucesso! Pode fazer login.";
                $messageColor = "green";
            } else {
                $message = "Erro ao criar conta. Tente novamente.";
                $messageColor = "red";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Criar Conta - Ecopeças</title>

<!-- Favicon -->
<link rel="icon" type="image/png" href="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg?w=360">

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:"Roboto", sans-serif; }
body {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(rgba(0,128,0,0.3), rgba(0,128,0,0.3)),
                url("https://img.freepik.com/fotos-premium/simbolo-de-carro-de-brilho-ecologico-feito-de-folhas-conceito-ecologico-isolado-em-fundo-preto_955712-32071.jpg?semt=ais_hybrid&w=740&q=80") no-repeat center center/cover;
    background-size: cover;
}
.register-container {
    width: 100%;
    max-width: 420px;
    background: rgba(255, 255, 255, 0.95);
    padding: 40px;
    border-radius: 25px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    text-align: center;
    animation: fadeIn 0.6s ease;
}
@keyframes fadeIn { from { opacity:0; transform: translateY(25px); } to { opacity:1; transform: translateY(0); } }
.register-header { display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 30px; }
.register-header .logo { width: 60px; height: 60px; border-radius: 12px; object-fit: contain; filter: drop-shadow(0 3px 8px rgba(0,0,0,0.25)); }
.register-header h1 { font-size: 32px; font-weight: bold; color: #2e7d32; }
h2 { margin-bottom: 25px; font-size: 22px; color: #2e7d32; }
.input-container { text-align:left; margin-bottom: 20px; position: relative; }
.input-container label { font-weight: bold; color:#2e7d32; font-size: 15px; }
.input-container input { width: 100%; padding: 12px 40px 12px 12px; margin-top: 6px; border-radius: 12px; border: 1px solid #cccccc; transition: 0.3s; font-size: 15px; }
.input-container input:focus { border-color: #66d78b; box-shadow: 0 0 8px rgba(102, 215, 139, 0.5); outline: none; }
.toggle-password { position: absolute; right: 12px; top: 42px; cursor: pointer; color: #888; font-size: 16px; transition: color 0.3s; }
.toggle-password:hover { color: #2e7d32; }
.register-btn { width: 100%; padding: 14px; background: linear-gradient(135deg, #66d78b, #4caf70); border: none; border-radius: 50px; color: white; font-size: 18px; font-weight: bold; cursor: pointer; box-shadow: 0 8px 22px rgba(76,175,112,0.35); transition: 0.3s; margin-top: 15px; }
.register-btn:hover { transform: scale(1.03); box-shadow: 0 12px 26px rgba(76,175,112,0.45); }
.login-link { margin-top: 20px; font-size: 14px; }
.login-link a { color: #2e7d32; font-weight: bold; text-decoration: none; }
.login-link a:hover { text-decoration: underline; }
.message { margin-bottom: 15px; font-weight: bold; }
</style>
</head>
<body>

<div class="register-container">
    <div class="register-header">
        <img src="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg" alt="Logo Ecopeças" class="logo">
        <h1>Ecopeças</h1>
    </div>

    <h2>Criar Conta</h2>

    <?php if($message): ?>
        <p class="message" style="color: <?= $messageColor ?>"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="input-container">
            <label for="name">Nome Completo</label>
            <input type="text" id="name" name="name" placeholder="Digite seu nome" required>
        </div>

        <div class="input-container">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Digite seu email" required>
        </div>

        <div class="input-container">
            <label for="password">Palavra-passe</label>
            <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
            <i class="fa fa-eye toggle-password" id="togglePassword"></i>
        </div>

        <div class="input-container">
            <label for="confirmPassword">Confirmar Palavra-passe</label>
            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirme sua senha" required>
            <i class="fa fa-eye toggle-password" id="toggleConfirmPassword"></i>
        </div>

        <button type="submit" class="register-btn">Criar Conta</button>
    </form>

    <p class="login-link">Já tem conta? <a href="login.php">Login</a></p>
</div>

<script>
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');
togglePassword.addEventListener('click', () => {
    const type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;
    togglePassword.classList.toggle('fa-eye-slash');
});

const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
const confirmInput = document.getElementById('confirmPassword');
toggleConfirmPassword.addEventListener('click', () => {
    const type = confirmInput.type === 'password' ? 'text' : 'password';
    confirmInput.type = type;
    toggleConfirmPassword.classList.toggle('fa-eye-slash');
});
</script>

</body>
</html>
