<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ========= RAIZ DO SITE ========= */
$base = dirname($_SERVER['SCRIPT_NAME']);
$base = explode('/categorias', $base)[0]; // garante que funciona dentro das categorias
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
        'login' => 'Login',
        'theme' => 'Modo',
        'offers' => 'Ofertas',
        'logout' => 'Sair'
    ],
    'en' => [
        'home' => 'Home',
        'cart' => 'Cart',
        'login' => 'Login',
        'theme' => 'Mode',
        'offers' => 'Offers',
        'logout' => 'Logout'
    ]
];

$current_page = basename($_SERVER['PHP_SELF']);
$user_logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';
?>

<header id="mainHeader" style="background:#2e7d32; padding:15px 30px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 4px 10px rgba(0,0,0,0.1); border-radius:10px; flex-wrap:wrap; gap:10px;">

    <!-- LOGO + NOME -->
    <div class="logo-container" style="display:flex; align-items:center; gap:10px;">
        <img src="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg"
             alt="Logo Ecopeças"
             style="height:50px; width:auto; object-fit:contain;">
        <div class="logo-text" style="font-size:28px; font-weight:bold; color:#fff;">
            Ecopeças
        </div>
    </div>

    <!-- MENU + PESQUISA + MODO + BANDEIRAS -->
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

        <!-- PESQUISA -->
        <div style="position:relative; display:flex; align-items:center;">
            <input type="text" id="searchBox" placeholder="Pesquisar produtos..."
                   style="padding:10px 20px; border-radius:30px; border:none; outline:none; width:260px; font-size:15px; box-shadow:0 3px 10px rgba(0,0,0,0.2); transition:0.3s;">
            <button type="button"
                    style="position:absolute; right:0; border:none; background:#66d78b; border-radius:50%; width:36px; height:36px; cursor:pointer; color:#fff; display:flex; justify-content:center; align-items:center;">
                <i class="fa fa-search"></i>
            </button>
        </div>

        <!-- TOGGLE MODO CLARO/ESCURO -->
        <form method="get" style="margin:0; display:flex; align-items:center; gap:8px;">
            <input type="hidden" name="lang" value="<?= $lang ?>">
            <button type="submit" name="theme" value="<?= $theme=='light'?'dark':'light' ?>"
                    style="background:#fff; border:none; border-radius:20px; padding:5px 12px; cursor:pointer; font-weight:bold; color:#2e7d32;">
                <?= $theme=='light'?'🌞':'🌜' ?>
            </button>
        </form>

        <!-- BANDEIRAS -->
        <form method="get" style="margin:0; display:flex; align-items:center; gap:8px;">
            <button type="submit" name="lang" value="pt" style="background:none; border:none; cursor:pointer;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Flag_of_Portugal.svg" alt="PT" style="width:28px; height:18px;" class="flag">
            </button>
            <button type="submit" name="lang" value="en" style="background:none; border:none; cursor:pointer;">
                <img src="https://upload.wikimedia.org/wikipedia/en/a/ae/Flag_of_the_United_Kingdom.svg" alt="EN" style="width:28px; height:18px;" class="flag">
            </button>
        </form>

        <!-- LOGIN / USUÁRIO -->
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

<style>
/* === HOVER NAS BANDEIRAS COM SOMBRA DE LUZ BRANCA === */
.flag {
    border-radius: 2px;
    transition: 0.3s ease;
}

.flag:hover {
    box-shadow: 0 0 12px 4px rgba(255,255,255,0.6);
    transform: scale(1.1);
}
</style>
