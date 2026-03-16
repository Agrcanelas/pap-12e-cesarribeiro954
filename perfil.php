<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Bloquear acesso se não estiver logado
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// CORREÇÃO DO CAMINHO DO HEADER:
// Tentamos encontrar o header.php na mesma pasta ou na pasta includes
$header_path = __DIR__ . '/header.php';
if (!file_exists($header_path)) {
    $header_path = __DIR__ . '/includes/header.php';
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Ecopeças</title>
    <style>
        .perfil-wrapper {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
        }
        .perfil-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px;
            text-align: center;
        }
        /* Garantir que o avatar herda o verde do teu tema */
        .avatar-grande {
            width: 120px; height: 120px;
            background: #2e7d32; color: white;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 50px; font-weight: bold;
            margin-bottom: 20px;
            border: 5px solid #66d78b;
        }
        .perfil-nome { font-size: 32px; color: #333; margin: 10px 0; }
        .grid-info {
            display: grid; grid-template-columns: 1fr 1fr; gap: 20px;
            width: 100%; text-align: left;
            margin-top: 20px; border-top: 1px solid #eee; padding-top: 30px;
        }
        .info-item { background: #f9f9f9; padding: 15px; border-radius: 10px; }
        .info-item label { display: block; font-size: 11px; color: #2e7d32; font-weight: bold; }
        .btn-voltar {
            margin-top: 30px; background: #2e7d32; color: white;
            text-decoration: none; padding: 12px 30px; border-radius: 30px;
            font-weight: bold; transition: 0.3s;
        }
        .btn-voltar:hover { background: #4caf50; transform: translateY(-3px); }
    </style>
</head>
<body style="background: #f0f2f5; font-family: sans-serif; margin:0;">

    <?php 
    // Aqui ele vai carregar o header usando o caminho que definimos acima
    if (file_exists($header_path)) {
        include $header_path;
    } else {
        echo "<div style='background:red; color:white; padding:10px;'>Erro: header.php não encontrado!</div>";
    }
    ?>

    <div class="perfil-wrapper">
        <div class="perfil-card">
            <div class="avatar-grande">
                <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
            </div>
            <h1 class="perfil-nome">Olá, <?= htmlspecialchars($user_name ?? $_SESSION['user_name']) ?>!</h1>
            
            <div class="grid-info">
                <div class="info-item">
                    <label>Nome de Utilizador</label>
                    <span><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                </div>
                <div class="info-item">
                    <label>ID da Conta</label>
                    <span>#<?= $_SESSION['user_id'] ?></span>
                </div>
                <div class="info-item">
                    <label>Estado</label>
                    <span style="color: #2e7d32; font-weight:bold;">Online</span>
                </div>
                <div class="info-item">
                    <label>Ano Atual</label>
                    <span>2026</span>
                </div>
            </div>

            <a href="index.php" class="btn-voltar">Voltar para a Loja</a>
        </div>
    </div>

</body>
</html>