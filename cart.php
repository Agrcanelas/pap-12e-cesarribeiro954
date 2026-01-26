<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ========= RAIZ DO SITE ========= */
$base = dirname($_SERVER['SCRIPT_NAME']);
$base = explode('/categorias', $base)[0];
/* ================================= */

// Idioma
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
$lang = $_SESSION['lang'] ?? 'pt';

// Tema
if (isset($_GET['theme'])) {
    $_SESSION['theme'] = $_GET['theme'];
}
$theme = $_SESSION['theme'] ?? 'light';

// Traduções
$translations = [
    'pt' => [
        'home' => 'Inicio',
        'cart' => 'Carrinho',
        'login' => 'Entrar',
        'offers' => 'Ofertas',
        'logout' => 'Sair'
    ],
    'en' => [
        'home' => 'Home',
        'cart' => 'Cart',
        'login' => 'Login',
        'offers' => 'Offers',
        'logout' => 'Logout'
    ]
];

$user_logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Carrinho - Ecopeças</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
}

/* ======= HEADER ======= */
header#mainHeader {
    background:#2e7d32;
    padding:18px 50px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
    position:fixed;
    top:0;
    left:0;
    right:0;
    z-index:1000;
}

.logo-container {
    display:flex;
    align-items:center;
    gap:12px;
}

.logo-container img {
    height:50px;
}

.logo-text {
    font-size:28px;
    font-weight:bold;
    color:#fff;
}

.header-right {
    display:flex;
    align-items:center;
    gap:45px; /* espaçamento maior entre itens */
    flex-wrap:wrap;
    margin-left:auto; /* força itens para a direita */
}

nav.menu {
    display:flex;
    gap:45px; /* espaçamento maior entre links */
    font-weight:bold;
}

nav.menu a {
    color:#fff;
    text-decoration:none;
    display:flex;
    align-items:center;
    gap:6px;
}

/* ===== PESQUISA ===== */
.search-box {
    position: relative;
    display:flex;
    align-items:center;
}

.search-box input {
    padding:10px 50px 10px 20px; /* espaço para o botão */
    border-radius:30px;
    border:none;
    outline:none;
    width:260px;
    font-size:15px;
    box-shadow:0 3px 10px rgba(0,0,0,0.2);
}

.search-box button {
    position:absolute;
    right:0px; /* mantém o botão no canto direito */
    top:50%;
    transform:translateY(-50%);
    border:none;
    background:#66d78b;
    border-radius:50%;
    width:36px; /* um pouquinho maior para ficar bonito */
    height:36px;
    cursor:pointer;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
}

/* ===== BANDEIRAS ===== */
.flag {
    border-radius:2px;
    transition:0.3s ease;
}

.flag:hover {
    box-shadow:0 0 12px 4px rgba(255,255,255,0.6);
    transform:scale(1.1);
}

/* ======= BODY ======= */
body {
    min-height: 100vh;
    background: url('https://st2.depositphotos.com/1001335/10397/i/950/depositphotos_103971628-stock-photo-concept-of-auto-parts-shopping.jpg') no-repeat center/cover;
    background-size: cover;
    padding: 160px 20px 40px; /* espaço para header fixo */
}

/* ======= CARRINHO ======= */
.cart-container {
    max-width: 700px;
    margin: auto;
    background: rgba(255,255,255,0.45);
    border-radius: 25px;
    padding: 40px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.25);
}

.cart-title {
    display:flex;
    justify-content:center;
    align-items:center;
    gap:15px;
    margin-bottom:40px;
}

.cart-title img {
    width:60px;
    border-radius:50%;
}

.cart-title span {
    font-size:34px;
    font-weight:bold;
    color:#2e7d32;
}

.cart-item {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:20px;
    margin-bottom:20px;
    border-radius:15px;
    background:rgba(255,255,255,0.6);
}

.cart-item input {
    width:60px;
    padding:8px;
    border-radius:10px;
    border:1px solid #ccc;
}

.remove-btn {
    background:#ff3d3d;
    color:#fff;
    border:none;
    padding:10px 18px;
    border-radius:30px;
    cursor:pointer;
}

.cart-total {
    text-align:right;
    font-size:24px;
    font-weight:bold;
    margin-top:25px;
    color:#2e7d32;
}

.checkout-btn {
    margin-top:25px;
    width:100%;
    padding:16px;
    background:#4caf70;
    color:#fff;
    border:none;
    border-radius:40px;
    font-size:18px;
    font-weight:bold;
    cursor:pointer;
}
</style>
</head>

<body>

<header id="mainHeader">

    <div class="logo-container">
        <img src="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg">
        <div class="logo-text">Ecopeças</div>
    </div>

    <div class="header-right">

        <nav class="menu">
            <a href="<?= $base ?>/index.php"><i class="fa fa-home"></i> <?= $translations[$lang]['home'] ?></a>
            <a href="<?= $base ?>/cart.php"><i class="fa fa-shopping-cart"></i> <?= $translations[$lang]['cart'] ?></a>
            <a href="<?= $base ?>/ofertas.php"><i class="fa fa-tags"></i> <?= $translations[$lang]['offers'] ?></a>
        </nav>

        <!-- PESQUISA -->
        <div class="search-box">
            <input type="text" placeholder="Pesquisar produtos...">
            <button><i class="fa fa-search"></i></button>
        </div>

        <!-- MODO -->
        <form method="get">
            <input type="hidden" name="lang" value="<?= $lang ?>">
            <button name="theme" value="<?= $theme=='light'?'dark':'light' ?>" style="background:#fff;border:none;border-radius:20px;padding:6px 14px;font-weight:bold;color:#2e7d32;">
                <?= $theme=='light'?'🌞':'🌜' ?>
            </button>
        </form>

        <!-- BANDEIRAS -->
        <form method="get" style="display:flex; gap:10px;">
            <button name="lang" value="pt" style="border:none;background:none;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Flag_of_Portugal.svg" class="flag" width="28">
            </button>
            <button name="lang" value="en" style="border:none;background:none;">
                <img src="https://upload.wikimedia.org/wikipedia/en/a/ae/Flag_of_the_United_Kingdom.svg" class="flag" width="28">
            </button>
        </form>

        <!-- LOGIN -->
        <div>
            <?php if($user_logged_in): ?>
                <span style="color:#fff;font-weight:bold;margin-right:12px;">
                    Olá, <?= htmlspecialchars($user_name) ?> 😄
                </span>
                <a href="<?= $base ?>/auth/logout.php" style="color:#fff;font-weight:bold;text-decoration:none;">
                    <i class="fa fa-sign-out-alt"></i> <?= $translations[$lang]['logout'] ?>
                </a>
            <?php else: ?>
                <a href="<?= $base ?>/auth/login.php" style="color:#fff;font-weight:bold;text-decoration:none;">
                    <i class="fa fa-user"></i> <?= $translations[$lang]['login'] ?>
                </a>
            <?php endif; ?>
        </div>

    </div>
</header>

<div class="cart-container">
    <div class="cart-title">
        <img src="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg">
        <span>Carrinho</span>
    </div>

    <div class="cart-item">
        <div>
            <h3>Filtro de Óleo</h3>
            <p>Preço: €50</p>
        </div>
        <input type="number" value="1">
        <button class="remove-btn">Remover</button>
    </div>

    <div class="cart-item">
        <div>
            <h3>Pastilhas de Freio</h3>
            <p>Preço: €120</p>
        </div>
        <input type="number" value="2">
        <button class="remove-btn">Remover</button>
    </div>

    <div class="cart-total">Total: €290</div>
    <button class="checkout-btn">Finalizar Compra</button>
</div>

</body>
</html>
