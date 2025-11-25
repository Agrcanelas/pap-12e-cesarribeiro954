<?php
session_start();

// Detecta idioma selecionado ou usa PT por padrão
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
$lang = $_SESSION['lang'] ?? 'pt';

// Traduções simples
$translations = [
    'pt' => [
        'home' => 'Inicio',
        'cart' => 'Carrinho',
        'login' => 'Login',
    ],
    'en' => [
        'home' => 'Home',
        'cart' => 'Cart',
        'login' => 'Login',
    ]
];

$current_page = basename($_SERVER['PHP_SELF']);
?>

<header style="background:#2e7d32; padding:15px 30px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 4px 10px rgba(0,0,0,0.1); border-radius:10px;">

    <!-- LOGO + NOME -->
    <div class="logo-container" style="display:flex; align-items:center; gap:10px;">
        <img src="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg"
             alt="Logo Ecopeças"
             style="height:50px; width:auto; object-fit:contain;">
        <div class="logo-text" style="font-size:28px; font-weight:bold; color:#fff;">
            Ecopeças
        </div>
    </div>

    <!-- MENU -->
    <nav class="menu" style="display:flex; gap:25px; font-weight:bold; align-items:center;">
        <a href="./index.php"
           style="color:#fff; text-decoration:none; <?= ($current_page=='index.php'?'text-decoration:underline;':'') ?>">
            <i class="fa fa-home"></i> <?= $translations[$lang]['home'] ?>
        </a>
        <a href="./cart.php"
           style="color:#fff; text-decoration:none; <?= ($current_page=='cart.php'?'text-decoration:underline;':'') ?>">
            <i class="fa fa-shopping-cart"></i> <?= $translations[$lang]['cart'] ?>
        </a>

        <!-- SELEÇÃO DE IDIOMA -->
        <form method="get" style="margin:0;">
            <select name="lang" onchange="this.form.submit()" style="padding:4px; border-radius:5px;">
                <option value="pt" <?= $lang=='pt'?'selected':'' ?>>PT</option>
                <option value="en" <?= $lang=='en'?'selected':'' ?>>EN</option>
            </select>
        </form>
    </nav>

    <!-- LOGIN -->
    <div class="user-menu">
        <a href="auth/login.php"
           style="color:#fff; font-weight:bold; text-decoration:none; <?= ($current_page=='login.php'?'text-decoration:underline;':'') ?>">
            <i class="fa fa-user"></i> <?= $translations[$lang]['login'] ?>
        </a>
    </div>

</header>
