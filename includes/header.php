<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ========= RAIZ DO SITE ========= */
$base = dirname($_SERVER['SCRIPT_NAME']);
$base = explode('/categorias', $base)[0]; 
$base = rtrim($base, '/');
/* ================================= */

$lang = $_SESSION['lang'] ?? 'pt';
$theme = $_SESSION['theme'] ?? 'light';

$translations = [
    'pt' => ['home' => 'Inicio', 'cart' => 'Carrinho', 'login' => 'Entrar', 'theme' => 'Modo', 'offers' => 'Ofertas', 'logout' => 'Sair'],
    'en' => ['home' => 'Home', 'cart' => 'Cart', 'login' => 'Login', 'theme' => 'Mode', 'offers' => 'Offers', 'logout' => 'Logout']
];

$user_logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';
?>

<link rel="icon" type="image/png" href="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg">

<link rel="stylesheet" href="<?= $base ?>/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* Reset de margens para o header colar no topo */
    body { margin: 0; padding: 0; }
    #mainHeader { margin-top: 0 !important; }
    
    .flag { border-radius: 2px; transition: 0.3s ease; }
    .flag:hover { box-shadow: 0 0 12px 4px rgba(255,255,255,0.6); transform: scale(1.1); }
</style>

<header id="mainHeader" style="background:#2e7d32; padding:15px 30px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 4px 10px rgba(0,0,0,0.1); border-radius:0 0 10px 10px; flex-wrap:wrap; gap:10px; width:100%; box-sizing:border-box; margin-top: 0; position: relative; z-index: 1000;">

    <div class="logo-container" style="display:flex; align-items:center; gap:10px;">
        <img src="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg"
             alt="Logo Ecopeças"
             style="height:50px; width:auto; object-fit:contain;">
        <div class="logo-text" style="font-size:28px; font-weight:bold; color:#fff;">
            Ecopeças
        </div>
    </div>

    <div style="display:flex; align-items:center; gap:20px; flex-wrap:wrap;">

        <nav class="menu" style="display:flex; gap:25px; font-weight:bold; align-items:center; position:relative;">
            <a href="<?= $base ?>/index.php" style="color:#fff; text-decoration:none;">
                <i class="fa fa-home"></i> <?= $translations[$lang]['home'] ?>
            </a>
            <a href="<?= $base ?>/cart.php" style="color:#fff; text-decoration:none;">
                <i class="fa fa-shopping-cart"></i> <?= $translations[$lang]['cart'] ?>
            </a>
            <a href="<?= $base ?>/ofertas.php" style="color:#fff; text-decoration:none;">
                <i class="fa fa-tags"></i> <?= $translations[$lang]['offers'] ?>
            </a>
        </nav>

        <div style="position:relative; display:flex; align-items:center;">
            <input type="text" id="searchBox" placeholder="Pesquisar produtos..."
                   style="padding:10px 20px; border-radius:30px; border:none; outline:none; width:260px; font-size:15px; box-shadow:0 3px 10px rgba(0,0,0,0.2); transition:0.3s;">
            <button type="button"
                    style="position:absolute; right:0; border:none; background:#66d78b; border-radius:50%; width:36px; height:36px; cursor:pointer; color:#fff; display:flex; justify-content:center; align-items:center;">
                <i class="fa fa-search"></i>
            </button>
        </div>

        <form method="get" style="margin:0; display:flex; align-items:center; gap:8px;">
            <input type="hidden" name="lang" value="<?= $lang ?>">
            <button type="submit" name="theme" value="<?= $theme=='light'?'dark':'light' ?>"
                    style="background:#fff; border:none; border-radius:20px; padding:5px 12px; cursor:pointer; font-weight:bold; color:#2e7d32;">
                <?= $theme=='light'?'🌞':'🌜' ?>
            </button>
        </form>

        <form method="get" style="margin:0; display:flex; align-items:center; gap:8px;">
            <button type="submit" name="lang" value="pt" style="background:none; border:none; cursor:pointer;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Flag_of_Portugal.svg" alt="PT" style="width:28px; height:18px;" class="flag">
            </button>
            <button type="submit" name="lang" value="en" style="background:none; border:none; cursor:pointer;">
                <img src="https://upload.wikimedia.org/wikipedia/en/a/ae/Flag_of_the_United_Kingdom.svg" alt="EN" style="width:28px; height:18px;" class="flag">
            </button>
        </form>

        <div class="user-menu">
            <?php if($user_logged_in): ?>
                <span style="color:#fff; font-weight:bold; margin-right:10px;">
                    Olá, <?= htmlspecialchars($user_name) ?> 😄
                </span>
                <a href="<?= $base ?>/auth/logout.php" style="color:#fff; font-weight:bold; text-decoration:none;">
                    <i class="fa fa-sign-out-alt"></i> <?= $translations[$lang]['logout'] ?>
                </a>
            <?php else: ?>
                <a href="<?= $base ?>/auth/login.php" style="color:#fff; font-weight:bold; text-decoration:none;">
                    <i class="fa fa-user"></i> <?= $translations[$lang]['login'] ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>