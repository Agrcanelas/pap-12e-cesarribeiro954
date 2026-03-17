<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ========= LÓGICA DE SESSÃO (Tema e Língua) ========= */
if (isset($_GET['theme'])) { $_SESSION['theme'] = $_GET['theme']; }
if (isset($_GET['lang'])) { $_SESSION['lang'] = $_GET['lang']; }

$lang = $_SESSION['lang'] ?? 'pt';
$theme = $_SESSION['theme'] ?? 'light';

/* ========= RAIZ DO SITE ========= */
$base = dirname($_SERVER['SCRIPT_NAME']);
$base = explode('/categorias', $base)[0]; 
$base = rtrim($base, '/');

$translations = [
    'pt' => ['home' => 'Início', 'cart' => 'Carrinho', 'login' => 'Entrar', 'theme' => 'Modo', 'offers' => 'Ofertas', 'logout' => 'Sair', 'search' => 'Pesquisar produtos...', 'see_products' => 'Ver Produtos'],
    'en' => ['home' => 'Home', 'cart' => 'Cart', 'login' => 'Login', 'theme' => 'Mode', 'offers' => 'Offers', 'logout' => 'Logout', 'search' => 'Search products...', 'see_products' => 'View Products']
];

$user_logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';
$is_admin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

/* ========= BUSCAR DADOS (FOTO E CARRINHO) ========= */
$foto_header = "";
$contagem_header = 0;

if ($user_logged_in) {
    $conn_h = new mysqli("localhost", "root", "", "ecopecas");
    if (!$conn_h->connect_error) {
        $res_h = $conn_h->query("SELECT foto_perfil FROM users WHERE id = '".$_SESSION['user_id']."'");
        if ($res_h && $row_h = $res_h->fetch_assoc()) {
            $foto_header = $row_h['foto_perfil'];
        }
        $res_c = $conn_h->query("SELECT COUNT(*) as total FROM cart WHERE user_id = '".$_SESSION['user_id']."'");
        if ($res_c) {
            $contagem_header = $res_c->fetch_assoc()['total'];
        }
        $conn_h->close();
    }
}
?>
<link rel="icon" type="image/png" href="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg">
<link rel="stylesheet" href="<?= $base ?>/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    body { margin: 0; padding: 0; transition: background 0.3s ease; }
    #mainHeader { 
        background: var(--header-bg, #2e7d32) !important; 
        color: #fff; padding: 15px 30px; 
        display: flex; align-items: center; justify-content: space-between; 
        box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-radius: 0 0 10px 10px; 
        flex-wrap: wrap; gap: 10px; width: 100%; box-sizing: border-box; 
        position: relative; z-index: 1000; transition: background 0.3s ease;
    }

    /* BANDEIRAS: TAMANHO UNIFORME E BRILHO */
    .flag { 
        border-radius: 2px; 
        transition: 0.3s ease; 
        width: 28px; 
        height: 18px; 
        object-fit: cover;
        display: block;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2); 
    }
    .flag:hover { 
        transform: scale(1.1); 
        filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.9)); 
        cursor: pointer;
    }
    .lang-btn { background: none; border: none; cursor: pointer; padding: 0; display: flex; align-items: center; }

    #searchBox { background: var(--input-bg, #fff) !important; color: var(--texto, #333) !important; padding: 10px 45px 10px 15px; border-radius: 30px; border: none; outline: none; width: 260px; font-size: 15px; }
    
    .user-profile-link { color: #fff; text-decoration: none; font-weight: bold; padding: 5px 10px; border-radius: 20px; transition: 0.3s; display: flex; align-items: center; gap: 8px; }
    .user-profile-link:hover { background: rgba(255, 255, 255, 0.2); }

    .nav-link { color: #fff; text-decoration: none; display: flex; align-items: center; gap: 6px; transition: 0.3s; }
    .nav-link:hover { opacity: 0.8; }

    /* ÍCONE DE ADMIN (FERRAMENTA) CORRIGIDO */
    .admin-link { color: #fff; text-decoration: none; font-size: 18px; transition: 0.3s; display: flex; align-items: center; padding: 5px; }
    .admin-link:hover { color: #66d78b; transform: scale(1.2); }

    .cart-btn { position: relative; display: flex; align-items: center; color: #fff; text-decoration: none; gap: 5px; }
    .cart-badge {
        position: absolute;
        top: -8px;
        left: 8px;
        background: #ff4444;
        color: white;
        font-size: 10px;
        font-weight: bold;
        min-width: 16px;
        height: 16px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid #2e7d32;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        animation: pulseBadge 2s infinite;
    }

    @keyframes pulseBadge {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 68, 68, 0.7); }
        70% { transform: scale(1.1); box-shadow: 0 0 0 5px rgba(255, 68, 68, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 68, 68, 0); }
    }
</style>

<header id="mainHeader">
    <div class="logo-container" style="display:flex; align-items:center; gap:10px;">
        <img src="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg" style="height:50px; width:auto; object-fit:contain;">
        <div class="logo-text" style="font-size:28px; font-weight:bold; color:#fff;">Ecopeças</div>
    </div>

    <div style="display:flex; align-items:center; gap:20px; flex-wrap:wrap;">
        <nav class="menu" style="display:flex; gap:25px; font-weight:bold; align-items:center;">
            <a href="<?= $base ?>/index.php" class="nav-link"><i class="fa fa-home"></i> <?= $translations[$lang]['home'] ?></a>
            
            <a href="<?= $base ?>/cart.php" class="cart-btn nav-link">
                <i class="fa fa-shopping-cart"></i>
                <?php if($contagem_header > 0): ?>
                    <span class="cart-badge"><?= $contagem_header ?></span>
                <?php endif; ?>
                <span><?= $translations[$lang]['cart'] ?></span>
            </a>

            <a href="<?= $base ?>/ofertas.php" class="nav-link"><i class="fa fa-tags"></i> <?= $translations[$lang]['offers'] ?></a>
        </nav>

        <form action="<?= $base ?>/search.php" method="GET" style="position:relative; display:flex; align-items:center; margin:0;">
            <input type="text" name="q" id="searchBox" placeholder="<?= $translations[$lang]['search'] ?>" required>
            <button type="submit" style="position:absolute; right:0; border:none; background:#66d78b; border-radius:50%; width:38px; height:36px; cursor:pointer; color:#fff; display:flex; justify-content:center; align-items:center;">
                <i class="fa fa-search"></i>
            </button>
        </form>

        <button id="theme-toggle" style="background:#fff; border:none; border-radius:20px; padding:5px 12px; cursor:pointer; font-weight:bold; color:#2e7d32; display:flex; align-items:center; gap:5px;">
            <span id="theme-icon"><?= $theme == 'light' ? '🌞' : '🌜' ?></span>
        </button>

        <form method="get" style="margin:0; display:flex; align-items:center; gap:8px;">
            <button type="submit" name="lang" value="pt" class="lang-btn">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Flag_of_Portugal.svg" class="flag">
            </button>
            <button type="submit" name="lang" value="en" class="lang-btn">
                <img src="https://upload.wikimedia.org/wikipedia/en/a/ae/Flag_of_the_United_Kingdom.svg" class="flag">
            </button>
        </form>

        <div class="user-menu" style="display:flex; align-items:center; gap:12px;">
            <?php if($user_logged_in): ?>
                
                <?php if($is_admin): ?>
                    <a href="<?= $base ?>/index.php?view=admin" class="admin-link" title="Administração">
                        <i class="fa fa-tools"></i>
                    </a>
                <?php endif; ?>

                <a href="<?= $base ?>/perfil.php" class="user-profile-link">
                    <?php if(!empty($foto_header)): ?>
                        <img src="<?= $base ?>/uploads/perfil/<?= $foto_header ?>" style="width:30px; height:30px; border-radius:50%; object-fit:cover; border:2px solid #fff;">
                    <?php else: ?>
                        <i class="fa fa-user-circle"></i>
                    <?php endif; ?>
                    Olá, <?= htmlspecialchars(explode(' ', $user_name)[0]) ?>
                </a>
                
                <a href="<?= $base ?>/auth/logout.php" style="color:#fff; font-weight:bold; text-decoration:none;" title="<?= $translations[$lang]['logout'] ?>">
                    <i class="fa fa-sign-out-alt"></i>
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
    const isDark = document.body.classList.toggle('dark');
    const newTheme = isDark ? 'dark' : 'light';
    document.getElementById('theme-icon').innerHTML = isDark ? '🌜' : '🌞';
    fetch('<?= $base ?>/auth/toggle_theme.php?theme=' + newTheme);
});
</script>