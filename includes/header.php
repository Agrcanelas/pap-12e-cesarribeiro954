<?php
session_start();

// Detecta idioma selecionado ou usa PT por padrão
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
$lang = $_SESSION['lang'] ?? 'pt';

// Detecta modo claro/escuro selecionado ou usa claro por padrão
if (isset($_GET['theme'])) {
    $_SESSION['theme'] = $_GET['theme'];
}
$theme = $_SESSION['theme'] ?? 'light';

// Traduções simples
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
            <a href="./index.php" style="color:#fff; text-decoration:none; <?= ($current_page=='index.php'?'text-decoration:underline;':'') ?>">
                <i class="fa fa-home"></i> <?= $translations[$lang]['home'] ?>
            </a>

            <a href="./cart.php" style="color:#fff; text-decoration:none; <?= ($current_page=='cart.php'?'text-decoration:underline;':'') ?>">
                <i class="fa fa-shopping-cart"></i> <?= $translations[$lang]['cart'] ?>
            </a>

            <a href="./ofertas.php" style="color:#fff; text-decoration:none; <?= ($current_page=='ofertas.php'?'text-decoration:underline;':'') ?>">
                <i class="fa fa-tags"></i> <?= $translations[$lang]['offers'] ?>
            </a>
        </nav>

        <!-- BARRA DE PESQUISA -->
        <div style="position:relative; display:flex; align-items:center;">
            <input type="text" id="searchBox" placeholder="Pesquisar produtos..."
                   style="padding:10px 20px 10px 20px; border-radius:30px; border:none; outline:none; width:260px; font-size:15px; box-shadow:0 3px 10px rgba(0,0,0,0.2); transition:0.3s;">
            <button type="submit" style="position:absolute; right:0; border:none; background:#66d78b; border-radius:50%; width:36px; height:36px; cursor:pointer; color:#fff; display:flex; justify-content:center; align-items:center; transition:0.3s;">
                <i class="fa fa-search"></i>
            </button>
        </div>

        <!-- TOGGLE MODO CLARO/ESCURO -->
        <form method="get" style="margin:0; display:flex; align-items:center; gap:8px;">
            <input type="hidden" name="lang" value="<?= $lang ?>">
            <button type="submit" name="theme" value="<?= $theme=='light'?'dark':'light' ?>" 
                    style="background:#fff; border:none; border-radius:20px; padding:5px 12px; cursor:pointer; font-weight:bold; color:#2e7d32; box-shadow:0 2px 8px rgba(0,0,0,0.2); transition:0.3s;">
                <?= $theme=='light'?'🌞':'🌜' ?>
            </button>
        </form>

        <!-- BANDEIRAS -->
        <form method="get" style="margin:0; display:flex; align-items:center; gap:8px;">
            <button type="submit" name="lang" value="pt" style="background:none; border:none; cursor:pointer; padding:0;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Flag_of_Portugal.svg" alt="PT" style="width:28px; height:18px; transition:0.3s;">
            </button>
            <button type="submit" name="lang" value="en" style="background:none; border:none; cursor:pointer; padding:0;">
                <img src="https://upload.wikimedia.org/wikipedia/en/a/ae/Flag_of_the_United_Kingdom.svg" alt="EN" style="width:28px; height:18px; transition:0.3s;">
            </button>
        </form>

        <!-- LOGIN / USUÁRIO LOGADO -->
        <div class="user-menu">
            <?php if(isset($_SESSION['user_id'])): ?>
                <span style="color:#fff; font-weight:bold;">Olá, <?= htmlspecialchars($_SESSION['user_name']) ?>!</span>
                <a href="auth/logout.php" style="color:#fff; font-weight:bold; margin-left:10px; text-decoration:none;">
                    (<?= $translations[$lang]['logout'] ?>)
                </a>
            <?php else: ?>
                <a href="auth/login.php" style="color:#fff; font-weight:bold; text-decoration:none; <?= ($current_page=='login.php'?'text-decoration:underline;':'') ?>">
                    <i class="fa fa-user"></i> <?= $translations[$lang]['login'] ?>
                </a>
            <?php endif; ?>
        </div>

    </div>

</header>
