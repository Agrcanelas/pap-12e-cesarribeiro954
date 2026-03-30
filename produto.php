<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'auth/config.php'; 

$id = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$id) { header("Location: index.php"); exit; }

// 1. Vai buscar os dados principais do produto
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id); 
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<div style='text-align:center; padding: 150px; font-family: sans-serif;'><h2>Produto não encontrado.</h2><a href='index.php'>Voltar à loja</a></div>";
    exit;
}

// --- ADICIONADO: Lógica do Logo da Base de Dados ---
$nome_logo = !empty($product['brand_logo']) ? $product['brand_logo'] : 'default.jpg';
$caminho_logo = "logotipos/" . $nome_logo;

// 2. BUSCA AS IMAGENS NA GALERIA (Tua Lógica de Gênio)
$sql_fotos = "SELECT image_url FROM product_images WHERE product_id = ?";
$stmt_fotos = $conn->prepare($sql_fotos);
$stmt_fotos->bind_param("i", $id);
$stmt_fotos->execute();
$res_fotos = $stmt_fotos->get_result();

$todas_as_fotos = [];
while($f = $res_fotos->fetch_assoc()) {
    $todas_as_fotos[] = $f['image_url'];
}

if (empty($todas_as_fotos) && !empty($product['image_url'])) {
    $todas_as_fotos[] = $product['image_url'];
}

if (!empty($product['image_url']) && !in_array($product['image_url'], $todas_as_fotos)) {
    array_unshift($todas_as_fotos, $product['image_url']);
}

// Lógica de Oferta
$em_oferta = (isset($product['em_oferta']) && $product['em_oferta'] == 1);
$preco_antigo = $product['preco_antigo'] ?? 0;
$preco_atual = $product['price'] ?? 0;

// Lógica de Distintivos (Badges)
$estado = mb_strtolower($product['condition_state'] ?? '');
$badge_html = '';

if (strpos($estado, 'excelente') !== false) {
    $badge_html = '<span class="badge excelent"><i class="fa fa-diamond"></i> Excelente</span>';
} elseif (strpos($estado, 'bom') !== false) {
    $badge_html = '<span class="badge good"><i class="fa fa-check-circle"></i> Bom</span>';
} elseif (strpos($estado, 'razoável') !== false || strpos($estado, 'razoavel') !== false) {
    $badge_html = '<span class="badge fair"><i class="fa fa-wrench"></i> Razoável</span>';
} elseif (!empty($product['condition_state'])) {
    $badge_html = '<span class="badge neutral"><i class="fa fa-info-circle"></i> ' . htmlspecialchars($product['condition_state']) . '</span>';
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>#<?= $id ?> - <?= htmlspecialchars($product['name']) ?> | Ecopeças Premium</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2e7d32;
            --primary-light: #66d78b;
            --offer-red: #d32f2f;
        }

        body { font-family: 'Inter', sans-serif; background-color: #fcfcfc; color: #333; margin: 0; }
        body.dark { background-color: #121212; color: #eee; }

        .page-wrapper { padding: 120px 20px 150px; } 
        
        .product-container-premium {
            max-width: 1200px; margin: auto;
            display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 60px;
            align-items: start;
        }

        /* Estilos do Slider */
        .image-showcase {
            position: sticky; top: 120px;
            background: #fff; border-radius: 30px; overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.04);
            border: 1px solid #f0f0f0;
            display: flex; flex-direction: column;
        }
        body.dark .image-showcase { background: #1e1e1e; border-color: #333; box-shadow: 0 20px 40px rgba(0,0,0,0.4); }
        
        .slider-wrapper { position: relative; width: 100%; height: 550px; overflow: hidden; }
        .slide { width: 100%; height: 100%; object-fit: cover; display: none; }
        .slide.active { display: block; }

        .nav-btn {
            position: absolute; top: 50%; transform: translateY(-50%);
            background: rgba(255,255,255,0.8); color: #333;
            border: none; width: 45px; height: 45px; border-radius: 50%;
            cursor: pointer; font-size: 18px; transition: 0.3s;
            display: flex; align-items: center; justify-content: center;
            z-index: 10; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .nav-btn:hover { background: var(--primary); color: white; }
        .prev-btn { left: 15px; }
        .next-btn { right: 15px; }

        .slider-dots {
            position: absolute; bottom: 20px; width: 100%;
            display: flex; justify-content: center; gap: 8px; z-index: 10;
        }
        .dot {
            width: 10px; height: 10px; border-radius: 50%;
            background: rgba(0,0,0,0.2); cursor: pointer; transition: 0.3s;
        }
        .dot.active { background: var(--primary); width: 25px; border-radius: 10px; }
        body.dark .dot { background: rgba(255,255,255,0.2); }
        body.dark .dot.active { background: var(--primary-light); }

        /* ADICIONADO: Estilo do Logo ao lado do Título */
        .header-flex-container { display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; margin-bottom: 15px; }
        .brand-logo-detail { width: 75px; height: 75px; background: #fff; padding: 10px; border-radius: 15px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 25px rgba(0,0,0,0.06); border: 1px solid #eee; flex-shrink: 0; }
        .brand-logo-detail img { max-width: 100%; max-height: 100%; object-fit: contain; }

        .badge { display: inline-flex; align-items: center; gap: 8px; padding: 8px 18px; border-radius: 50px; font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 20px; margin-right: 10px; }
        .excelent { background: rgba(27, 94, 32, 0.1); color: #1b5e20; border: 1px solid rgba(27, 94, 32, 0.2); }
        .good { background: rgba(251, 192, 45, 0.1); color: #f57f17; border: 1px solid rgba(251, 192, 45, 0.2); }
        .fair { background: rgba(117, 117, 117, 0.1); color: #616161; border: 1px solid rgba(117, 117, 117, 0.2); }
        .neutral { background: rgba(0, 0, 0, 0.05); color: #555; border: 1px solid rgba(0, 0, 0, 0.1); }
        .badge-promo { background: var(--offer-red); color: #fff; animation: pulseRed 2s infinite; }
        @keyframes pulseRed { 0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(211, 47, 47, 0.4); } 70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(211, 47, 47, 0); } 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(211, 47, 47, 0); } }
        .product-title { font-size: 42px; font-weight: 800; line-height: 1.1; margin: 0; color: #111; }
        body.dark .product-title { color: #fff; }
        .price-container { margin-bottom: 35px; }
        .old-price { font-size: 20px; text-decoration: line-through; color: #999; margin-bottom: 5px; display: block; }
        .price-tag { font-size: 40px; font-weight: 300; color: var(--primary); }
        .price-tag span { font-weight: 800; }
        .price-tag.promo { color: var(--offer-red); }
        body.dark .price-tag { color: var(--primary-light); }
        body.dark .price-tag.promo { color: #ff5252; }
        .description-box h3 { font-size: 13px; text-transform: uppercase; letter-spacing: 2px; opacity: 0.4; margin-bottom: 12px; }
        .description-box p { font-size: 17px; line-height: 1.8; opacity: 0.8; margin-bottom: 40px; }
        .specs-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 40px; padding: 25px 0; border-top: 1px solid rgba(0,0,0,0.06); }
        .spec-item { display: flex; align-items: center; gap: 12px; font-size: 14px; font-weight: 500; }
        .spec-item i { color: var(--primary); font-size: 18px; }
        .btn-premium-cart { display: flex; align-items: center; justify-content: center; gap: 12px; padding: 22px; background: linear-gradient(135deg, #2e7d32, #1b5e20); color: #fff; border-radius: 20px; font-size: 17px; font-weight: 700; text-decoration: none; transition: all 0.4s; box-shadow: 0 10px 25px rgba(46, 125, 50, 0.2); }
        .btn-premium-cart:hover { transform: translateY(-4px); box-shadow: 0 15px 35px rgba(46, 125, 50, 0.4); filter: brightness(1.1); }

        @media (max-width: 992px) {
            .product-container-premium { grid-template-columns: 1fr; }
            .image-showcase { position: relative; top: 0; }
            .slider-wrapper { height: 380px; }
        }
    </style>
</head>
<body class="<?= ($_SESSION['theme'] ?? 'light') === 'dark' ? 'dark' : '' ?>">

<?php require_once 'includes/header.php'; ?>

<div class="page-wrapper">
    <div class="product-container-premium">
        
        <div class="image-showcase">
            <div class="slider-wrapper">
                <?php if(!empty($todas_as_fotos)): ?>
                    <?php foreach($todas_as_fotos as $index => $foto): ?>
                        <img src="uploads/perfil/produtos/<?= $foto ?>" 
                             class="slide <?= $index === 0 ? 'active' : '' ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             onerror="this.src='https://via.placeholder.com/800x800?text=Imagem+Indisponivel'">
                    <?php endforeach; ?>
                <?php else: ?>
                    <img src="https://via.placeholder.com/800x800?text=Sem+Imagem" class="slide active">
                <?php endif; ?>

                <?php if(count($todas_as_fotos) > 1): ?>
                    <button class="nav-btn prev-btn" onclick="moveSlide(-1)"><i class="fa fa-chevron-left"></i></button>
                    <button class="nav-btn next-btn" onclick="moveSlide(1)"><i class="fa fa-chevron-right"></i></button>
                    
                    <div class="slider-dots">
                        <?php foreach($todas_as_fotos as $index => $foto): ?>
                            <div class="dot <?= $index === 0 ? 'active' : '' ?>" onclick="goToSlide(<?= $index ?>)"></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="product-details-content">
            <div style="display: flex; flex-wrap: wrap;">
                <?= $badge_html ?>
                <?php if($em_oferta): ?>
                    <span class="badge badge-promo"><i class="fa fa-bolt"></i> Oferta Ativa</span>
                <?php endif; ?>
            </div>
            
            <div class="header-flex-container">
                <h1 class="product-title">#<?= $id ?> - <?= htmlspecialchars($product['name']) ?></h1>
                
                <div class="brand-logo-detail">
                    <img src="<?= $caminho_logo ?>" 
                         alt="Logo Marca" 
                         onerror="this.src='https://via.placeholder.com/100?text=Eco';">
                </div>
            </div>
            
            <div class="price-container">
                <?php if($em_oferta && $preco_antigo > 0): ?>
                    <span class="old-price">€ <?= number_format($preco_antigo, 2, ',', '.') ?></span>
                    <div class="price-tag promo">
                        € <span><?= number_format($preco_atual, 2, ',', '.') ?></span>
                    </div>
                <?php else: ?>
                    <div class="price-tag">
                        € <span><?= number_format($preco_atual, 2, ',', '.') ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="description-box">
                <h3>Informações Técnicas</h3>
                <p><?= nl2br(htmlspecialchars($product['description'] ?? 'Nenhuma descrição disponível.')) ?></p>
            </div>

            <div class="specs-grid">
                <div class="spec-item"><i class="fa fa-shield"></i> 12 Meses Garantia</div>
                <div class="spec-item"><i class="fa fa-truck"></i> Envio em 24h</div>
                <div class="spec-item"><i class="fa fa-check-square-o"></i> 100% Original</div>
                <div class="spec-item"><i class="fa fa-leaf"></i> Eco-Responsável</div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 15px;">
                <a href="add_to_cart.php?id=<?= $id ?>" class="btn-premium-cart">
                    <i class="fa fa-shopping-bag"></i> Adicionar ao Carrinho
                </a>
                <a href="index.php" style="text-align: center; text-decoration: none; color: inherit; font-size: 14px; opacity: 0.5;">
                    <i class="fa fa-long-arrow-left"></i> Voltar à Loja
                </a>
            </div>
        </div>
    </div>
</div>

<script>
let currentIdx = 0;
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.dot');

function showSlide(index) {
    if (slides.length === 0) return;
    slides.forEach(s => s.classList.remove('active'));
    dots.forEach(d => d.classList.remove('active'));
    if (index >= slides.length) currentIdx = 0;
    else if (index < 0) currentIdx = slides.length - 1;
    else currentIdx = index;
    slides[currentIdx].classList.add('active');
    if (dots[currentIdx]) dots[currentIdx].classList.add('active');
}
function moveSlide(step) { showSlide(currentIdx + step); }
function goToSlide(index) { showSlide(index); }
</script>

</body>
</html>