<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) { session_start(); }

/* ========= 1. LIGAÇÃO À BASE DE DADOS ========= */
$host = "localhost";
$user = "root";
$pass = ""; 
$dbname = "ecopecas"; 

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) { die("Erro de ligação"); }

/* ========= 2. LÓGICA DE UPLOAD ========= */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto_perfil']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $target_dir = "uploads/perfil/";
    if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }

    $ext = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
    $nome_foto = "user_" . $user_id . "_" . time() . "." . $ext;
    
    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $target_dir . $nome_foto)) {
        $conn->query("UPDATE users SET foto_perfil = '$nome_foto' WHERE id = '$user_id'");
        header("Location: perfil.php");
        exit();
    }
}

/* ========= 3. BUSCAR DADOS EXATOS DO UTILIZADOR ========= */
if (!isset($_SESSION['user_id'])) { header("Location: auth/login.php"); exit(); }
$user_id = $_SESSION['user_id'];

$res_user = $conn->query("SELECT * FROM users WHERE id = '$user_id'");
$user_db = $res_user->fetch_assoc();

$user_name = $user_db['nome'] ?? $_SESSION['user_name'] ?? 'Utilizador';
$foto_perfil = $user_db['foto_perfil'] ?? '';
$total_compras = $user_db['total_compras'] ?? 0;

$data_bruta = $user_db['data_registo'] ?? $user_db['criado_em'] ?? '';
if ($data_bruta) {
    $data_exata = date('d/m/Y', strtotime($data_bruta));
} else {
    $data_exata = date('d/m/Y');
}

// Contar carrinho
$res_cart = $conn->query("SELECT COUNT(*) as total FROM cart WHERE user_id = '$user_id'");
$cart_count = ($res_cart) ? $res_cart->fetch_assoc()['total'] : 0;

// Verificar se é admin para o selo
$is_admin = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin');
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil - Ecopeças</title>
    <link rel="icon" type="image/png" href="img/logo.png"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --verde: #2e7d32; }
        body { background: #f0f2f5; font-family: 'Segoe UI', sans-serif; margin: 0; }
        
        .perfil-card {
            max-width: 800px; margin: 50px auto; background: white; padding: 40px;
            border-radius: 25px; border: 5px solid var(--verde); text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); animation: fadeIn 0.6s ease;
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .avatar-container { position: relative; width: 150px; height: 150px; margin: 0 auto 25px; }
        .avatar-box {
            width: 150px; height: 150px; background: var(--verde); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 70px; border: 4px solid white; overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1); transition: 0.3s;
        }
        .avatar-box img { width: 100%; height: 100%; object-fit: cover; }

        .btn-camera {
            position: absolute; bottom: 8px; right: 8px; 
            background: white; color: var(--verde);
            width: 42px; height: 42px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; border: 2px solid var(--verde);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2); transition: 0.3s;
        }
        .btn-camera:hover { background: var(--verde); color: white; transform: rotate(-15deg); }

        /* SELO ADMIN */
        .badge-admin {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, #1b5e20, #4caf50);
            color: white; padding: 6px 18px; border-radius: 50px;
            font-size: 13px; font-weight: bold; text-transform: uppercase;
            margin-top: 12px; box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
        }

        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin: 30px 0; }
        .stat-item { padding: 15px 5px; border: 1px solid #eee; border-radius: 15px; transition: 0.3s; cursor: pointer; }
        .stat-item:hover { transform: translateY(-5px); border-color: var(--verde); background: #f8fdf9; }
        .stat-item i { font-size: 22px; color: var(--verde); }
        
        .btn-home { display: inline-block; padding: 12px 30px; background: var(--verde); color: white; text-decoration: none; border-radius: 25px; font-weight: bold; transition: 0.3s; }
        .btn-home:hover { opacity: 0.9; transform: translateY(-2px); }

        @media (max-width: 600px) { .stats { grid-template-columns: 1fr 1fr; } }
    </style>
</head>
<body>

<?php 
if (file_exists('includes/header.php')) { include 'includes/header.php'; }
elseif (file_exists('header.php')) { include 'header.php'; }
?>

<div class="perfil-card">
    <div class="avatar-container">
        <div class="avatar-box">
            <?php if($foto_perfil && file_exists("uploads/perfil/$foto_perfil")): ?>
                <img src="uploads/perfil/<?= $foto_perfil ?>">
            <?php else: ?>
                <?= strtoupper(substr($user_name, 0, 1)) ?>
            <?php endif; ?>
        </div>
        
        <form action="" method="POST" enctype="multipart/form-data" id="form-foto">
            <label for="input-foto" class="btn-camera"><i class="fa fa-camera"></i></label>
            <input type="file" name="foto_perfil" id="input-foto" style="display:none" onchange="this.form.submit()">
        </form>
    </div>

    <h1 style="margin: 0; color: #222;"><?= htmlspecialchars($user_name) ?></h1>

    <?php if ($is_admin): ?>
        <div class="badge-admin">
            <i class="fas fa-user-shield"></i> Conta Administrador
        </div>
    <?php endif; ?>

    <div class="stats">
        <div class="stat-item" onclick="window.location.href='cart.php'">
            <i class="fa fa-shopping-cart"></i>
            <div style="font-size: 18px; margin-top: 5px;"><b><?= $cart_count ?></b></div>
            <label style="font-size:10px; color:#999; text-transform: uppercase;">No Carrinho</label>
        </div>

        <div class="stat-item">
            <i class="fa fa-bag-shopping"></i>
            <div style="font-size: 18px; margin-top: 5px;"><b><?= $total_compras ?></b></div>
            <label style="font-size:10px; color:#999; text-transform: uppercase;">Compras</label>
        </div>

        <div class="stat-item">
            <i class="fa fa-id-card"></i>
            <div style="font-size: 18px; margin-top: 5px;"><b>#<?= $user_id ?></b></div>
            <label style="font-size:10px; color:#999; text-transform: uppercase;">ID</label>
        </div>

        <div class="stat-item">
            <i class="fa fa-calendar-alt"></i>
            <div style="font-size: 18px; margin-top: 5px;"><b><?= $data_exata ?></b></div>
            <label style="font-size:10px; color:#999; text-transform: uppercase;">Membro</label>
        </div>
    </div>

    <a href="index.php" class="btn-home">Ir para a Loja</a>
</div>

</body>
</html>
<?php ob_end_flush(); ?>