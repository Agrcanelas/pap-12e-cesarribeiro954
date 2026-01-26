<?php
session_start();

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
<title>Ofertas - Ecopeças</title>

<link rel="icon" type="image/png" href="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg?w=360">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

<style>
* { box-sizing:border-box; margin:0; padding:0; font-family:'Roboto', sans-serif; }

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
    gap:45px;
    flex-wrap:wrap;
    margin-left:auto;
}

nav.menu {
    display:flex;
    gap:45px;
    font-weight:bold;
}

nav.menu a {
    color:#fff;
    text-decoration:none;
    display:flex;
    align-items:center;
    gap:6px;
}

/* PESQUISA */
.search-box {
    position: relative;
    display:flex;
    align-items:center;
}

.search-box input {
    padding:10px 50px 10px 20px;
    border-radius:30px;
    border:none;
    outline:none;
    width:260px;
    font-size:15px;
    box-shadow:0 3px 10px rgba(0,0,0,0.2);
}

.search-box button {
    position:absolute;
    right:0px;
    top:50%;
    transform:translateY(-50%);
    border:none;
    background:#66d78b;
    border-radius:50%;
    width:36px;
    height:36px;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
}

/* BANDEIRAS */
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
    min-height:100vh;
    background: <?= $theme=='dark' ? '#121212' : '#f0f0f0' ?>;
    color: <?= $theme=='dark' ? '#f0f0f0' : '#000' ?>;
    padding:160px 20px 40px; /* espaço para header fixo */
}

/* ======= OFERTAS ======= */
.offers-container {
    max-width:1200px;
    margin:0 auto;
    display:grid;
    grid-template-columns: repeat(auto-fill,minmax(250px,1fr));
    gap:20px;
}

.offer-card {
    background: <?= $theme=='dark' ? 'rgba(30,30,30,0.8)' : '#fff' ?>;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
    overflow:hidden;
    transition:transform 0.3s, box-shadow 0.3s;
}

.offer-card:hover {
    transform:translateY(-5px);
    box-shadow:0 15px 35px rgba(0,0,0,0.3);
}

.offer-card img {
    width:100%;
    height:180px;
    object-fit:cover;
}

.offer-card-content {
    padding:15px 20px;
}

.offer-card-content h3 {
    font-size:20px;
    color: <?= $theme=='dark' ? '#66d78b' : '#2e7d32' ?>;
    margin-bottom:8px;
}

.offer-card-content p {
    font-size:16px;
    margin-bottom:12px;
    color: <?= $theme=='dark' ? '#f0f0f0' : '#333' ?>;
}

.offer-card-content .price {
    font-weight:bold;
    font-size:18px;
    color:#ff3d3d;
}

@media(max-width:600px){
    .offers-container { grid-template-columns:1fr; }
}
</style>
</head>

<body>

<!-- ================= HEADER ================= -->
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
        <div style="position:relative; display:flex; align-items:center;">
            <input type="text" id="searchBox" placeholder="Pesquisar produtos..."
                   style="padding:10px 20px; border-radius:30px; border:none; outline:none; width:260px; font-size:15px; box-shadow:0 3px 10px rgba(0,0,0,0.2); transition:0.3s;">
            <button type="button"
                    style="position:absolute; right:0; border:none; background:#66d78b; border-radius:50%; width:36px; height:36px; cursor:pointer; color:#fff; display:flex; justify-content:center; align-items:center;">
                <i class="fa fa-search"></i>
            </button>
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

<h1 style="text-align:center; margin-bottom:40px;">Ofertas Especiais</h1>

<div class="offers-container">
    <div class="offer-card">
        <img src="https://via.placeholder.com/400x200.png?text=Filtro+de+Óleo" alt="Filtro de Óleo">
        <div class="offer-card-content">
            <h3>Filtro de Óleo</h3>
            <p>Desconto especial para você!</p>
            <div class="price">€35,00 <span style="text-decoration:line-through; color:gray;">€50,00</span></div>
        </div>
    </div>

    <div class="offer-card">
        <img src="https://via.placeholder.com/400x200.png?text=Pastilhas+de+Freio" alt="Pastilhas de Freio">
        <div class="offer-card-content">
            <h3>Pastilhas de Freio</h3>
            <p>Oferta por tempo limitado!</p>
            <div class="price">€90,00 <span style="text-decoration:line-through; color:gray;">€120,00</span></div>
        </div>
    </div>

    <div class="offer-card">
        <img src="https://via.placeholder.com/400x200.png?text=Bateria+de+Carro" alt="Bateria de Carro">
        <div class="offer-card-content">
            <h3>Bateria de Carro</h3>
            <p>Economize agora!</p>
            <div class="price">€80,00 <span style="text-decoration:line-through; color:gray;">€110,00</span></div>
        </div>
    </div>
</div>

</body>
</html>
