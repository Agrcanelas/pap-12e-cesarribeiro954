<?php
session_start();
require_once "../db.php"; // aqui ligas à tua base de dados

// Criação de conta
if (isset($_POST['register'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (nome, email, senha) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome, $email, $senha);
    $stmt->execute();
    $_SESSION['user_nome'] = $nome;
    header("Location: ../index.php");
    exit;
}

// Login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 1) {
        $user = $res->fetch_assoc();
        if (password_verify($senha, $user['senha'])) {
            $_SESSION['user_nome'] = $user['nome'];
            header("Location: ../index.php");
            exit;
        } else {
            $erro = "Senha incorreta!";
        }
    } else {
        $erro = "Utilizador não encontrado!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Login - Ecopeças</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<h2>Login</h2>
<?php if(isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>
<form method="post">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <button type="submit" name="login">Login</button>
</form>

<h2>Criar Conta</h2>
<form method="post">
    <input type="text" name="nome" placeholder="Nome completo" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <button type="submit" name="register">Criar Conta</button>
</form>
</body>
</html>
