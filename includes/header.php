<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ========= LÓGICA DE SESSÃO (Tema e Língua) ========= */
if (isset($_GET['theme'])) {
    $_SESSION['theme'] = $_GET['theme'];
}
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang = $_SESSION['lang'] ?? 'pt';
$theme = $_SESSION['theme'] ?? 'light';

/* ========= RAIZ DO SITE ========= */
$base = dirname($_SERVER['SCRIPT_NAME']);
$base = explode('/categorias', $base)[0]; 
$base = rtrim($base, '/');
/* ================================= */

$translations = [
    'pt' => ['home' => 'Início', 'cart' => 'Carrinho', 'login' => 'Entrar', 'theme' => 'Modo', 'offers' => 'Ofertas', 'logout' => 'Sair', 'search' => 'Pesquisar produtos...'],
    'en' => ['home' => 'Home', 'cart' => 'Cart', 'login' => 'Login', 'theme' => 'Mode', 'offers' => 'Offers', 'logout' => 'Logout', 'search' => 'Search products...']
];

$user_logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';
?>

<link rel="icon" type="image/png" href="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg">

<link rel="stylesheet" href="<?= $base ?>/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* Reset e ajustes globais */
    body { margin: 0; padding: 0; transition: background 0.3s ease; }
    
    /* Garantir que o Header usa as variáveis do CSS para mudar de cor */
    #mainHeader { 
        background: var(--header-bg, #2e7d32) !important; 
        color: #fff;
        padding: 15px 30px; 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
        border-radius: 0 0 10px 10px; 
        flex-wrap: wrap; 
        gap: 10px; 
        width: 100%; 
        box-sizing: border-box; 
        position: relative; 
        z-index: 1000;
        transition: background 0.3s ease;
    }

    .flag { border-radius: 2px; transition: 0.3s ease; width: 28px; height: 18px; }
    .flag:hover { box-shadow: 0 0 12px 4px rgba(255,255,255,0.6); transform: scale(1.1); }

    /* Estilo da barra de pesquisa integrado com o modo escuro */
    #searchBox {
        background: var(--input-bg, #fff) !important;
        color: var(--texto, #333) !important;
        padding: 10px 45px 10px 15px; 
        border-radius: 30px; 
        border: none; 
        outline: none; 
        width: 260px; 
        font-size: 15px; 
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    }
</style>

<header id="mainHeader">

    <div class="logo-container" style="display:flex; align-items:center; gap:10px;">
        <img src="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg"
             alt="Logo Ecopeças"
             style="height:50px; width:auto; object-fit:contain;">
        <div class="logo-text" style="font-size:28px; font-weight:bold; color:#fff;">
            Ecopeças
        </div>
    </div>

    <div style="display:flex; align-items:center; gap:20px; flex-wrap:wrap;">

        <nav class="menu" style="display:flex; gap:25px; font-weight:bold; align-items:center;">
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

        <form action="<?= $base ?>/search.php" method="GET" style="position:relative; display:flex; align-items:center; margin:0;">
            <input type="text" name="q" id="searchBox" placeholder="<?= $translations[$lang]['search'] ?>" required>
            <button type="submit"
                    style="position:absolute; right:0; border:none; background:#66d78b; border-radius:50%; width:38px; height:36px; cursor:pointer; color:#fff; display:flex; justify-content:center; align-items:center; transition: 0.3s;">
                <i class="fa fa-search"></i>
            </button>
        </form>

        <div style="margin:0; display:flex; align-items:center;">
            <button id="theme-toggle" 
                    style="background:#fff; border:none; border-radius:20px; padding:5px 12px; cursor:pointer; font-weight:bold; color:#2e7d32; display:flex; align-items:center; gap:5px;">
                <span id="theme-icon"><?= $theme == 'light' ? '🌞' : '🌜' ?></span>
            </button>
        </div>

        <form method="get" style="margin:0; display:flex; align-items:center; gap:8px;">
            <button type="submit" name="lang" value="pt" style="background:none; border:none; cursor:pointer;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Flag_of_Portugal.svg" alt="PT" class="flag">
            </button>
            <button type="submit" name="lang" value="en" style="background:none; border:none; cursor:pointer;">
                <img src="https://upload.wikimedia.org/wikipedia/en/a/ae/Flag_of_the_United_Kingdom.svg" alt="EN" class="flag">
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

<script>
document.getElementById('theme-toggle').addEventListener('click', function() {
    const body = document.body;
    const isDark = body.classList.toggle('dark');
    const newTheme = isDark ? 'dark' : 'light';
    const iconSpan = document.getElementById('theme-icon');

    // Atualiza o ícone visualmente
    iconSpan.innerHTML = isDark ? '🌜' : '🌞';

    // Avisa o servidor para guardar a sessão (usa o ficheiro que criámos antes)
    fetch('<?= $base ?>/auth/toggle_theme.php?theme=' + newTheme)
        .then(() => {
            // Opcional: recarregar para garantir que tudo aplica, 
            // mas com as variáveis CSS nem é preciso!
        });
});
</script>