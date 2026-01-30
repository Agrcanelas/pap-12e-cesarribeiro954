<?php
// login.php
session_start();
require_once 'config.php'; // Ajuste o caminho conforme sua pasta

/* ---------- IDIOMA ---------- */
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
$lang = $_SESSION['lang'] ?? 'pt';

/* ---------- TRADUÇÕES ---------- */
$t = [
    'pt' => [
        'title' => 'Login - Ecopeças',
        'login_header' => 'Entrar',
        'name' => 'Nome',
        'name_placeholder' => 'Digite seu nome',
        'email' => 'Email',
        'email_placeholder' => 'Digite seu email',
        'password' => 'Palavra-passe',
        'password_placeholder' => 'Digite sua senha',
        'submit' => 'Entrar',
        'no_account' => 'Ainda não tem conta?',
        'create_account' => 'Criar conta',
        'fill_fields' => 'Preencha todos os campos!',
        'name_mismatch' => 'Nome não confere com o registrado!',
        'wrong_password' => 'Senha incorreta!',
        'email_not_found' => 'Email não encontrado!'
    ],
    'en' => [
        'title' => 'Login - Ecopeças',
        'login_header' => 'Login',
        'name' => 'Name',
        'name_placeholder' => 'Enter your name',
        'email' => 'Email',
        'email_placeholder' => 'Enter your email',
        'password' => 'Password',
        'password_placeholder' => 'Enter your password',
        'submit' => 'Login',
        'no_account' => 'Don’t have an account?',
        'create_account' => 'Create account',
        'fill_fields' => 'Please fill in all fields!',
        'name_mismatch' => 'Name does not match registered!',
        'wrong_password' => 'Incorrect password!',
        'email_not_found' => 'Email not found!'
    ]
];

$message = '';
$messageColor = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!$name || !$email || !$password) {
        $message = $t[$lang]['fill_fields'];
        $messageColor = "red";
    } else {
        $stmt = $conn->prepare("SELECT id, nome, senha FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows === 1){
            $stmt->bind_result($id, $nomeRegistrado, $hashedPassword);
            $stmt->fetch();

            if($name !== $nomeRegistrado){
                $message = $t[$lang]['name_mismatch'];
                $messageColor = "red";
            }
            elseif(password_verify($password, $hashedPassword)){
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $nomeRegistrado;
                header("Location: ../index.php");
                exit();
            } else {
                $message = $t[$lang]['wrong_password'];
                $messageColor = "red";
            }
        } else {
            $message = $t[$lang]['email_not_found'];
            $messageColor = "red";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $t[$lang]['title'] ?></title>
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
.login-container {
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
.login-header { display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 30px; }
.login-header .logo { width: 60px; height: 60px; border-radius: 12px; object-fit: contain; filter: drop-shadow(0 3px 8px rgba(0,0,0,0.25)); }
.login-header h1 { font-size: 32px; font-weight: bold; color: #2e7d32; }
h2 { margin-bottom: 25px; font-size: 22px; color: #2e7d32; }
.input-container { text-align:left; margin-bottom: 20px; position: relative; }
.input-container label { font-weight: bold; color:#2e7d32; font-size: 15px; }
.input-container input { width: 100%; padding: 12px 40px 12px 12px; margin-top: 6px; border-radius: 12px; border: 1px solid #cccccc; transition: 0.3s; font-size: 15px; }
.input-container input:focus { border-color: #66d78b; box-shadow: 0 0 8px rgba(102, 215, 139, 0.5); outline: none; }
.toggle-password { position: absolute; right: 12px; top: 42px; cursor: pointer; color: #888; font-size: 16px; transition: color 0.3s; }
.toggle-password:hover { color: #2e7d32; }
.login-btn { width: 100%; padding: 14px; background: linear-gradient(135deg, #66d78b, #4caf70); border: none; border-radius: 50px; color: white; font-size: 18px; font-weight: bold; cursor: pointer; box-shadow: 0 8px 22px rgba(76,175,112,0.35); transition: 0.3s; margin-top: 15px; }
.login-btn:hover { transform: scale(1.03); box-shadow: 0 12px 26px rgba(76,175,112,0.45); }
.register-link { margin-top: 20px; font-size: 14px; }
.register-link a { color: #2e7d32; font-weight: bold; text-decoration: none; }
.register-link a:hover { text-decoration: underline; }
.message { margin-bottom: 15px; font-weight: bold; }
</style>
</head>
<body>

<div class="login-container">
    <div class="login-header">
        <img src="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg" alt="Logo Ecopeças" class="logo">
        <h1>Ecopeças</h1>
    </div>

    <h2><?= $t[$lang]['login_header'] ?></h2>

    <?php if($message): ?>
        <p class="message" style="color: <?= $messageColor ?>"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="input-container">
            <label for="name"><?= $t[$lang]['name'] ?></label>
            <input type="text" id="name" name="name" placeholder="<?= $t[$lang]['name_placeholder'] ?>" required>
        </div>

        <div class="input-container">
            <label for="email"><?= $t[$lang]['email'] ?></label>
            <input type="email" id="email" name="email" placeholder="<?= $t[$lang]['email_placeholder'] ?>" required>
        </div>

        <div class="input-container">
            <label for="password"><?= $t[$lang]['password'] ?></label>
            <input type="password" id="password" name="password" placeholder="<?= $t[$lang]['password_placeholder'] ?>" required>
            <i class="fa fa-eye toggle-password" id="togglePassword"></i>
        </div>

        <button type="submit" class="login-btn"><?= $t[$lang]['submit'] ?></button>
    </form>

    <p class="register-link"><?= $t[$lang]['no_account'] ?> <a href="register.php"><?= $t[$lang]['create_account'] ?></a></p>
</div>

<script>
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');

togglePassword.addEventListener('click', () => {
    const type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;
    togglePassword.classList.toggle('fa-eye-slash');
});
</script>

</body>
</html>
