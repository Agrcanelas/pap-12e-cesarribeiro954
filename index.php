<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$lang = $_SESSION['lang'] ?? 'pt';

$t = [
    'pt' => [
        'slider1' => 'Peças ecológicas de qualidade', 'slider2' => 'Promoções especiais para si', 'slider3' => 'Sustentabilidade e confiança', 'slider4' => 'Melhor seleção de interiores!', 'slider5' => 'Fidelidade e confiança', 'see_products' => 'Ver Produtos', 'about_title' => 'Sobre a Ecopeças', 'about_text' => 'A Ecopeças é uma plataforma dedicada à venda de peças automóveis usadas e recondicionadas, focada na sustentabilidade e na economia circular. O nosso objetivo é oferecer aos nossos clientes peças de alta qualidade a preços acessíveis, contribuindo para a redução do desperdício e para a preservação do ambiente. Com uma vasta seleção de componentes, desde motores a interiores, garantimos que cada peça passa por um rigoroso controlo de qualidade antes de chegar até si.', 'about_slogan' => 'Ajude a salvar o planeta! ♻️', 'cat_motor' => 'Motor e Transmissão', 'cat_lighting' => 'Iluminação', 'cat_suspension' => 'Suspensão', 'cat_electric' => 'Elétrica', 'cat_interior' => 'Interior'
    ],
    'en' => [
        'slider1' => 'Quality eco-friendly parts', 'slider2' => 'Special offers for you', 'slider3' => 'Sustainability and trust', 'slider4' => 'Best interior selection!', 'slider5' => 'Loyalty and trust', 'see_products' => 'View Products', 'about_title' => 'About Ecopeças', 'about_text' => 'Ecopeças is a platform dedicated to selling used and reconditioned car parts, focused on sustainability and circular economy. Our goal is to offer high-quality parts at affordable prices, contributing to waste reduction and environmental preservation.', 'about_slogan' => 'Help save the planet! ♻️', 'cat_motor' => 'Engine & Transmission', 'cat_lighting' => 'Lighting', 'cat_suspension' => 'Suspension', 'cat_electric' => 'Electrical', 'cat_interior' => 'Interior'
    ]
];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title>Ecopeças</title>
    <link rel="icon" type="image/png" href="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        html { scroll-behavior: smooth; }
        body { background: url('https://wallpapers.com/images/hd/cool-green-background-yldkpcmn6kp9767o.jpg') fixed center/cover; }
        
        @keyframes subir { from { opacity: 0; transform: translateY(50px); } to { opacity: 1; transform: translateY(0); } }
        .animar-subida { animation: subir 1s ease-out forwards; scroll-margin-top: 100px; }
        
        .slider-container { max-width:1200px; height:250px; margin:20px auto; overflow:hidden; border-radius:15px; box-shadow:0 0 20px rgba(102,215,139,0.6); }
        .slides-wrapper { display:flex; width:500%; transition:transform 1s ease-in-out; }
        .slider-slide { width:20%; position:relative; }
        .slider-slide img { width:100%; height:250px; object-fit:cover; }
        .slide-text { position:absolute; bottom:20px; left:20px; background:rgba(0,0,0,0.6); color:#fff; padding:12px; border-radius:12px; }
        
        .cards-container { display:flex; flex-wrap:wrap; justify-content:center; padding: 20px 0; }
        .card { width:250px; margin:15px; padding:15px; background:rgba(255,255,255,0.85); border-radius:15px; text-align:center; transition:0.3s; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .card:hover { transform:translateY(-8px); }
        .card img { width:100%; height:150px; object-fit:cover; border-radius:10px; }
        
        .card h3 { color: #2e7d32 !important; font-weight: bold; margin: 15px 0; }
        .card .btn { display:inline-block; margin-top:10px; padding:10px 18px; background:#4caf70; color:#fff; border-radius:25px; text-decoration:none; font-weight:bold; }

        /* =========================================================
           === EFEITO ONDAS VERDES (REFEITO SEM DESFORMATAR) ===
           ========================================================= */
        .about-box { 
            max-width:1100px; 
            margin:80px auto; 
            padding:40px 60px; 
            display:flex; 
            align-items:center; 
            justify-content:space-between; 
            background: rgba(255, 255, 255, 0.95);
            border-radius:30px; 
            position: relative;
            
            /* Animação de Ondas de Cor */
            animation: ondasSuaves 4s infinite ease-in-out;
            border: 2px solid rgba(46, 125, 50, 0.2);
        }

        @keyframes ondasSuaves {
            0% { box-shadow: 0 0 0 0 rgba(46, 125, 50, 0.4), 0 0 0 5px rgba(165, 214, 167, 0.2); }
            50% { box-shadow: 0 0 20px 5px rgba(46, 125, 50, 0.2), 0 0 15px 10px rgba(165, 214, 167, 0.4); }
            100% { box-shadow: 0 0 0 0 rgba(46, 125, 50, 0.4), 0 0 0 5px rgba(165, 214, 167, 0.2); }
        }

        /* FOLHAS NOS CANTOS */
        .folha-canto {
            position: absolute;
            font-size: 60px;
            z-index: 10;
            pointer-events: none;
        }
        .folha-esq { top: -40px; left: -30px; transform: rotate(-20deg); }
        .folha-dir { bottom: -40px; right: -30px; transform: rotate(10deg); }

        .about-text { max-width:70%; }
        .about-text h2 { color:#2e7d32; font-size: 32px; margin-top: 0; }
        .about-text p { color: #333; line-height: 1.8; font-size: 17px; }
        .about-text h3 { color: #4caf50; font-size: 20px; }
        
        .about-logo img { max-width:160px; border-radius: 15px; }
    </style>
</head>
<body>
<?php require_once __DIR__ . '/includes/header.php'; ?>

<div class="slider-container">
    <div class="slides-wrapper">
        <div class="slider-slide"><img src="https://blog.kroftools.com/wp-content/uploads/2023/11/pecas-de-automoveis.png"><div class="slide-text"><?= $t[$lang]['slider1'] ?></div></div>
        <div class="slider-slide"><img src="https://amr-auto.pt/wp-content/uploads/2024/01/reparacao_motor.jpg"><div class="slide-text"><?= $t[$lang]['slider2'] ?></div></div>
        <div class="slider-slide"><img src="https://inovegas.com.br/wp-content/uploads/2018/08/carro-miniatura-papel-na-grama.jpg"><div class="slide-text"><?= $t[$lang]['slider3'] ?></div></div>
        <div class="slider-slide"><img src="https://clickpetroleoegas.com.br/wp-content/uploads/2025/07/raw-8.jpg"><div class="slide-text"><?= $t[$lang]['slider4'] ?></div></div>
        <div class="slider-slide"><img src="https://img.freepik.com/fotos-gratis/acordo-de-negocios-aperto-de-mao-gesto-de-mao_53876-130006.jpg"><div class="slide-text"><?= $t[$lang]['slider5'] ?></div></div>
    </div>
</div>

<div id="categorias" class="animar-subida">
    <div class="cards-container">
    <?php
    $categories = [
        ["img"=>"https://netun.com/cdn/shop/articles/01-Airbag_civicsi.jpg","name"=>"Airbags","file"=>"airbags.php"],
        ["img"=>"https://blog.mixauto.com.br/wp-content/uploads/2018/05/caixa-de-cambio.jpg","name"=>$t[$lang]['cat_motor'],"file"=>"MotorTransmição.php"],
        ["img"=>"https://s7d9.scene7.com/is/image/dow/AdobeStock_385390317","name"=>$t[$lang]['cat_lighting'],"file"=>"iluminação.php"],
        ["img"=>"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRJ5NwDgRfalMhSg_JrDaskCoPjKOi3HHhxMA","name"=>$t[$lang]['cat_suspension'],"file"=>"suspencao.php"],
        ["img"=>"https://reparadorsa.com.br/wp-content/uploads/2022/12/RSA_MATERIAS-2_05-12_HEADER.png","name"=>$t[$lang]['cat_electric'],"file"=>"eletrica.php"],
        ["img"=>"https://global-img.bitauto.com/usercenter/yhzx/20250815/793/w1200_yichecar_522658379372509.jpg.webp","name"=>$t[$lang]['cat_interior'],"file"=>"interior.php"]
    ];
    foreach($categories as $c){
        echo "<div class='card'><img src='{$c['img']}'><h3>{$c['name']}</h3><a class='btn' href='categorias/{$c['file']}'>{$t[$lang]['see_products']}</a></div>";
    }
    ?>
    </div>
</div>

<div class="about-box">
    <span class="folha-canto folha-esq">🌿</span>
    <span class="folha-canto folha-dir">🍃</span>
    
    <div class="about-text">
        <h2><?= $t[$lang]['about_title'] ?></h2>
        <p><?= $t[$lang]['about_text'] ?></p>
        <h3><?= $t[$lang]['about_slogan'] ?></h3>
    </div>
    <div class="about-logo">
        <img src="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg">
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
let idx = 0; const wrp = document.querySelector('.slides-wrapper');
setInterval(()=>{ idx = (idx+1)%5; wrp.style.transform = `translateX(-${idx*20}%)`; },5000);
</script>
</body>
</html>