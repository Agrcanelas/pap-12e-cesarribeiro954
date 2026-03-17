<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ========= LÓGICA DE SESSÃO ========= */
if (isset($_GET['theme'])) { $_SESSION['theme'] = $_GET['theme']; }
$theme = $_SESSION['theme'] ?? 'light';

/* ========= RAIZ DO SITE (Caminho Dinâmico) ========= */
$base = dirname($_SERVER['SCRIPT_NAME']);
$base = explode('/categorias', $base)[0]; 
$base = explode('/auth', $base)[0]; // Garante que não duplica se estiver na pasta auth
$base = rtrim($base, '/');

$lang = $_SESSION['lang'] ?? 'pt';
$translations = [
    'pt' => ['home' => 'Início', 'cart' => 'Carrinho', 'login' => 'Entrar', 'offers' => 'Ofertas', 'logout' => 'Sair', 'search' => 'Pesquisar produtos...'],
    'en' => ['home' => 'Home', 'cart' => 'Cart', 'login' => 'Login', 'offers' => 'Offers', 'logout' => 'Logout', 'search' => 'Search products...']
];

$user_logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';
$is_admin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

/* ========= CARRINHO E FOTO ========= */
$foto_header = "";
$contagem_header = 0;
if ($user_logged_in) {
    $conn_h = new mysqli("localhost", "root", "", "ecopecas");
    if (!$conn_h->connect_error) {
        $res_h = $conn_h->query("SELECT foto_perfil FROM users WHERE id = '".$_SESSION['user_id']."'");
        if ($res_h && $row_h = $res_h->fetch_assoc()) { $foto_header = $row_h['foto_perfil']; }
        $res_c = $conn_h->query("SELECT COUNT(*) as total FROM cart WHERE user_id = '".$_SESSION['user_id']."'");
        if ($res_c) { $contagem_header = $res_c->fetch_assoc()['total']; }
        $conn_h->close();
    }
}
?>

<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'pt', 
    includedLanguages: 'pt,en,de,es', 
    layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
    autoDisplay: false
  }, 'google_translate_element');
}
</script>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<link rel="icon" type="image/png" href="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg">
<link rel="stylesheet" href="<?= $base ?>/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* 1. ELIMINAR A BARRA BRANCA DEFINITIVAMENTE */
    .goog-te-banner-frame.skiptranslate, .goog-te-banner-frame { display: none !important; visibility: hidden !important; }
    html { top: 0px !important; }
    body { top: 0px !important; position: static !important; }
    .skiptranslate > iframe { height: 0 !important; border: 0 !important; display: none !important; }

    /* 2. ESTILO DO TRADUTOR */
    #google_translate_element {
        display: inline-block;
        vertical-align: middle;
    }
    .goog-te-gadget-simple {
        background-color: #fff !important;
        border: none !important;
        padding: 5px 12px !important;
        border-radius: 20px !important;
        cursor: pointer !important;
        display: flex !important;
        align-items: center !important;
        height: 35px;
        box-sizing: border-box;
    }
    .goog-te-gadget-icon { display: none !important; }
    .goog-te-gadget-simple span { color: #2e7d32 !important; font-weight: bold !important; font-size: 13px !important; }

    /* 3. ESTRUTURA DO HEADER */
    #mainHeader { 
        background: var(--header-bg, #2e7d32) !important; 
        color: #fff; padding: 15px 30px; 
        display: flex; align-items: center; justify-content: space-between; 
        box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-radius: 0 0 10px 10px; 
        width: 100%; box-sizing: border-box; 
        position: relative; z-index: 1000; transition: background 0.3s ease;
        flex-wrap: nowrap;
        gap: 15px;
    }

    #searchBox { 
        background: #fff !important; color: #333 !important; 
        padding: 10px 45px 10px 15px; border-radius: 30px; 
        border: none; outline: none; width: 240px; font-size: 15px; 
    }
    
    .search-btn {
        position: absolute; right: 0px; top: 50%; transform: translateY(-50%); 
        border: none; background: #66d78b; border-radius: 50%; 
        width: 37px; height: 37px; cursor: pointer; color: #fff;
        display: flex; align-items: center; justify-content: center;
    }

    .nav-link { color: #fff; text-decoration: none; font-weight: bold; display: flex; align-items: center; gap: 8px; transition: 0.3s; }
    .nav-link:hover { opacity: 0.8; }

    .cart-btn { position: relative; }
    .cart-badge {
        position: absolute; top: -10px; left: 10px; background: #ff4444; color: white; 
        font-size: 11px; font-weight: bold; min-width: 18px; height: 18px; 
        border-radius: 50%; display: flex; align-items: center; justify-content: center; 
        border: 2px solid #2e7d32;
    }
</style>

<header id="mainHeader">
    <div style="display:flex; align-items:center; gap:10px; flex-shrink: 0;">
        <img src="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg" style="height:50px; border-radius: 8px;">
        <div style="font-size:26px; font-weight:bold;">Ecopeças</div>
    </div>

    <nav style="display:flex; gap:20px; align-items:center;">
        <a href="<?= $base ?>/index.php" class="nav-link"><i class="fa fa-home"></i> <?= $translations[$lang]['home'] ?></a>
        <a href="<?= $base ?>/cart.php" class="nav-link cart-btn">
            <i class="fa fa-shopping-cart"></i>
            <?php if($contagem_header > 0): ?><span class="cart-badge"><?= $contagem_header ?></span><?php endif; ?>
            <?= $translations[$lang]['cart'] ?>
        </a>
        <a href="<?= $base ?>/ofertas.php" class="nav-link"><i class="fa fa-tags"></i> <?= $translations[$lang]['offers'] ?></a>
    </nav>

    <form action="<?= $base ?>/search.php" method="GET" style="position:relative; margin:0; flex-shrink: 0;">
        <input type="text" name="q" id="searchBox" placeholder="<?= $translations[$lang]['search'] ?>" required>
        <button type="submit" class="search-btn">
            <i class="fa fa-search"></i>
        </button>
    </form>

    <div style="display:flex; align-items:center; gap:15px; flex-shrink: 0;">
        <button id="theme-toggle" style="background:#fff; border:none; border-radius:20px; padding:7px 15px; cursor:pointer; font-weight:bold; color:#2e7d32; display:flex; align-items:center; gap:5px;">
            <span id="theme-icon"><?= $theme == 'light' ? '🌞' : '🌜' ?></span>
        </button>

        <div id="google_translate_element"></div>
    </div>

    <div style="display:flex; align-items:center; gap:15px; flex-shrink: 0;">
        <?php if($user_logged_in): ?>
            <?php if($is_admin): ?>
                <a href="<?= $base ?>/index.php?view=admin" style="color:#fff; font-size:18px;"><i class="fa fa-tools"></i></a>
            <?php endif; ?>

            <a href="<?= $base ?>/perfil.php" class="nav-link">
                <?php if(!empty($foto_header)): ?>
                    <img src="<?= $base ?>/uploads/perfil/<?= $foto_header ?>" style="width:32px; height:32px; border-radius:50%; object-fit:cover; border:2px solid #fff;">
                <?php else: ?>
                    <i class="fa fa-user-circle" style="font-size:24px;"></i>
                <?php endif; ?>
                <?= htmlspecialchars(explode(' ', $user_name)[0]) ?>
            </a>
            <a href="<?= $base ?>/auth/logout.php" style="color:#fff;"><i class="fa fa-sign-out-alt"></i></a>
        <?php else: ?>
            <a href="<?= $base ?>/auth/login.php" class="nav-link"><i class="fa fa-user"></i> <?= $translations[$lang]['login'] ?></a>
        <?php endif; ?>
    </div>
</header>

<script>
document.getElementById('theme-toggle').addEventListener('click', function() {
    const isDark = document.body.classList.toggle('dark');
    document.getElementById('theme-icon').innerHTML = isDark ? '🌜' : '🌞';
    fetch('<?= $base ?>/auth/toggle_theme.php?theme=' + (isDark ? 'dark' : 'light'));
});
</script>