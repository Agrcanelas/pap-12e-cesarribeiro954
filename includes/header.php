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
$base = explode('/auth', $base)[0]; 
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

<link rel="icon" type="image/png" href="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg">

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

<link rel="stylesheet" href="<?= $base ?>/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* 1. ELIMINAR A BARRA BRANCA DEFINITIVAMENTE */
    .goog-te-banner-frame.skiptranslate, .goog-te-banner-frame { display: none !important; visibility: hidden !important; }
    html { top: 0px !important; }
    body { top: 0px !important; position: static !important; }
    .skiptranslate > iframe { height: 0 !important; border: 0 !important; display: none !important; }

    /* 2. ESTILO DO TRADUTOR */
    #google_translate_element { display: inline-block; vertical-align: middle; }
    .goog-te-gadget-simple {
        background-color: #fff !important; border: none !important; padding: 5px 12px !important;
        border-radius: 20px !important; cursor: pointer !important; display: flex !important;
        align-items: center !important; height: 35px; box-sizing: border-box;
    }
    .goog-te-gadget-icon { display: none !important; }
    .goog-te-gadget-simple span { color: #2e7d32 !important; font-weight: bold !important; font-size: 13px !important; }

    /* 3. ESTRUTURA DO HEADER */
    #mainHeader { 
        background: var(--header-bg, #2e7d32) !important; 
        color: #fff; 
        box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
        border-radius: 0 0 10px 10px; 
        width: 100%; 
        position: relative; 
        z-index: 1000; 
        transition: background 0.3s ease;
    }

    .header-container {
        max-width: 1560px; 
        margin: 0 auto;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .header-left { display: flex; align-items: center; gap: 30px; }
    .header-center { flex: 1; display: flex; justify-content: center; align-items: center; }
    .header-right { display: flex; align-items: center; gap: 15px; }

    .search-wrapper { position: relative; width: 100%; max-width: 400px; margin: 0; display: flex; align-items: center; }
    #searchBox { 
        background: #fff !important; color: #333 !important; 
        padding: 10px 45px 10px 20px; border-radius: 30px; 
        border: none; outline: none; width: 100%; font-size: 15px; 
        box-sizing: border-box; height: 40px; 
    }
    .search-btn {
        position: absolute; right: 0px; top: 50%; transform: translateY(-50%); 
        border: none; background: #66d78b; border-radius: 50%; 
        width: 36px; height: 37px; cursor: pointer; color: #fff;
        display: flex; align-items: center; justify-content: center;
    }

    .nav-link { 
        color: #fff; text-decoration: none; font-weight: bold; 
        display: flex; align-items: center; gap: 8px; transition: 0.3s; height: 40px;
    }
    .nav-link:hover { opacity: 0.8; }
    .cart-btn { position: relative; }
    .cart-badge {
        position: absolute; top: -5px; left: 10px; background: #ff4444; color: white; 
        font-size: 11px; font-weight: bold; min-width: 18px; height: 18px; 
        border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #2e7d32;
    }
    .theme-btn {
        background:#fff; border:none; border-radius:20px; padding:7px 15px; 
        cursor:pointer; font-weight:bold; color:#2e7d32; display:flex; align-items:center; gap:5px; height: 35px;
    }

    /* MODO DARK */
    body.dark { background-color: #121212 !important; color: #ffffff !important; }
    body.dark #mainHeader { background-color: #1a1a1a !important; }
    body.dark #searchBox, body.dark .goog-te-gadget-simple, body.dark .theme-btn {
        background-color: #252525 !important; color: #ffffff !important; border: 1px solid #444 !important;
    }
    body.dark .goog-te-gadget-simple span { color: #66d78b !important; }

    body.dark .box, body.dark .card, body.dark .categoria, body.dark .produto-box,
    body.dark section, body.dark table, body.dark .admin-container,
    body.dark div[id*="sobre"], body.dark section[class*="sobre"],
    body.dark div[class*="admin"], body.dark form,
    body.dark div[style*="background-color: white"],
    body.dark div[style*="background: #fff"],
    body.dark div[style*="background-color: #ffffff"],
    body.dark #cookie-banner {
        background-color: #1e1e1e !important;
        color: #ffffff !important;
        border-color: #333333 !important;
    }

    /* ESTILO DO FUNDO BORRADO (OVERLAY) */
    #cookie-overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 9998;
        display: none;
    }

    /* ESTILO DO BANNER DE COOKIES (CENTRADO) */
    #cookie-banner {
        position: fixed;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 90%; max-width: 420px;
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 15px 50px rgba(0,0,0,0.3);
        z-index: 9999;
        display: none;
        border: 1px solid #eee;
        font-family: sans-serif;
    }
    #cookie-banner h4 { margin-top: 0; color: #2e7d32; display: flex; align-items: center; gap: 10px; font-size: 18px; }
    body.dark #cookie-banner h4 { color: #66d78b; }
    #cookie-banner p { font-size: 14px; line-height: 1.6; color: #555; margin-bottom: 25px; }
    body.dark #cookie-banner p { color: #ccc; }

    .cookie-btns { display: flex; flex-direction: column; gap: 12px; }
    .btn-c { padding: 12px; border-radius: 10px; border: none; font-weight: bold; cursor: pointer; transition: 0.3s; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; }
    .btn-c-accept { background: #2e7d32; color: white; }
    .btn-c-accept:hover { background: #1b5e20; transform: scale(1.02); }
    .btn-c-reject { background: #f44336; color: white; }
    .btn-c-reject:hover { background: #d32f2f; transform: scale(1.02); }
    .btn-c-details { background: #e0e0e0; color: #333; }
    .btn-c-details:hover { background: #d0d0d0; }
    body.dark .btn-c-details { background: #333; color: #eee; }
    body.dark .btn-c-details:hover { background: #444; }

    #cookie-privacy-text { 
        display: none; 
        margin-top: 20px; 
        font-size: 12px; 
        color: #777; 
        padding-top: 15px; 
        border-top: 1px solid #eee; 
        max-height: 180px; 
        overflow-y: auto; 
        text-align: left;
    }
    body.dark #cookie-privacy-text { border-top-color: #444; color: #aaa; }
</style>

<header id="mainHeader">
    <div class="header-container">
        <div class="header-left">
            <div style="display:flex; align-items:center; gap:10px;">
                <img src="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg" style="height:45px; border-radius: 8px;">
                <div style="font-size:24px; font-weight:bold; letter-spacing: 0.5px;">Ecopeças</div>
            </div>
            <nav style="display:flex; gap:20px;">
                <a href="<?= $base ?>/index.php" class="nav-link"><i class="fa fa-home"></i> <?= $translations[$lang]['home'] ?></a>
                <a href="<?= $base ?>/cart.php" class="nav-link cart-btn">
                    <i class="fa fa-shopping-cart"></i>
                    <?php if($contagem_header > 0): ?><span class="cart-badge"><?= $contagem_header ?></span><?php endif; ?>
                    <?= $translations[$lang]['cart'] ?>
                </a>
                <a href="<?= $base ?>/ofertas.php" class="nav-link"><i class="fa fa-tags"></i> <?= $translations[$lang]['offers'] ?></a>
            </nav>
        </div>

        <div class="header-center">
            <form action="<?= $base ?>/search.php" method="GET" class="search-wrapper">
                <input type="text" name="q" id="searchBox" placeholder="<?= $translations[$lang]['search'] ?>" required>
                <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
            </form>
        </div>

        <div class="header-right">
            <button id="theme-toggle" class="theme-btn">
                <span id="theme-icon"><?= $theme == 'light' ? '🌞' : '🌜' ?></span>
            </button>
            <div id="google_translate_element"></div>
            <div style="display:flex; align-items:center; gap:15px; border-left: 1px solid rgba(255,255,255,0.2); padding-left: 15px;">
                <?php if($user_logged_in): ?>
                    <?php if($is_admin): ?>
                        <a href="<?= $base ?>/index.php?view=admin" style="color:#fff; font-size:18px;" title="Painel Admin"><i class="fa fa-tools"></i></a>
                    <?php endif; ?>
                    <a href="<?= $base ?>/perfil.php" class="nav-link">
                        <?php if(!empty($foto_header)): ?>
                            <img src="<?= $base ?>/uploads/perfil/<?= $foto_header ?>" style="width:32px; height:32px; border-radius:50%; object-fit:cover; border:2px solid #fff;">
                        <?php else: ?>
                            <i class="fa fa-user-circle" style="font-size:24px;"></i>
                        <?php endif; ?>
                        <?= htmlspecialchars(explode(' ', $user_name)[0]) ?>
                    </a>
                    <a href="<?= $base ?>/auth/logout.php" style="color:#fff;" title="Sair"><i class="fa fa-sign-out-alt"></i></a>
                <?php else: ?>
                    <a href="<?= $base ?>/auth/login.php" class="nav-link"><i class="fa fa-user"></i> <?= $translations[$lang]['login'] ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<?php if (!$user_logged_in): ?>
<div id="cookie-overlay"></div>
<div id="cookie-banner">
    <h4><i class="fa fa-cookie-bite"></i> Cookies & Privacidade</h4>
    <p>Utilizamos cookies para melhorar a sua experiência e garantir o funcionamento seguro do site. Aceita a nossa política de privacidade?</p>
    <div class="cookie-btns">
        <button onclick="handleCookie('accept')" class="btn-c btn-c-accept">Sim, eu aceito</button>
        <button onclick="handleCookie('reject')" class="btn-c btn-c-reject">Não aceito</button>
        <button onclick="toggleCookieDetails()" class="btn-c btn-c-details">Ver mais detalhes</button>
    </div>
    <div id="cookie-privacy-text">
        <strong>Termos e Condições:</strong><br>
        A Ecopeças respeita a sua privacidade. Utilizamos cookies técnicos para manter a sua sessão iniciada e cookies de preferência para o modo dark e idioma. Não vendemos os seus dados a terceiros.
    </div>
</div>
<?php endif; ?>

<script>
// Favicon dinâmico
(function() {
    var link = document.querySelector("link[rel*='icon']") || document.createElement('link');
    link.type = 'image/png'; link.rel = 'shortcut icon';
    link.href = 'https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg';
    document.getElementsByTagName('head')[0].appendChild(link);
})();

// Aplicação do Tema
if ('<?= $theme ?>' === 'dark') { document.body.classList.add('dark'); }

document.getElementById('theme-toggle').addEventListener('click', function() {
    const isDark = document.body.classList.toggle('dark');
    document.getElementById('theme-icon').innerHTML = isDark ? '🌜' : '🌞';
    fetch('<?= $base ?>/auth/toggle_theme.php?theme=' + (isDark ? 'dark' : 'light'));
});

/* LÓGICA DE COOKIES INTELIGENTE */
window.onload = function() {
    // Só processa cookies se o utilizador NÃO estiver logado
    <?php if (!$user_logged_in): ?>
        localStorage.removeItem('ecopecas_cookies');
        if (!sessionStorage.getItem('ecopecas_cookies')) {
            setTimeout(() => {
                document.getElementById('cookie-overlay').style.display = 'block';
                document.getElementById('cookie-banner').style.display = 'block';
                document.body.style.overflow = 'hidden';
            }, 1000);
        }
    <?php endif; ?>
}

function handleCookie(action) {
    sessionStorage.setItem('ecopecas_cookies', action);
    document.getElementById('cookie-overlay').style.display = 'none';
    document.getElementById('cookie-banner').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function toggleCookieDetails() {
    const text = document.getElementById('cookie-privacy-text');
    text.style.display = (text.style.display === 'block') ? 'none' : 'block';
}
</script>