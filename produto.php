<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'auth/config.php'; 

$id = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$id) { header("Location: index.php"); exit; }

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

// Lógica de Oferta
$em_oferta = ($product['em_oferta'] == 1);
$preco_antigo = $product['preco_antigo'] ?? 0;
$preco_atual = $product['price'] ?? 0;

// Lógica de Distintivos (Badges) por Estado
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
    <title><?= htmlspecialchars($product['name']) ?> | Ecopeças Premium</title>
    
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

        .page-wrapper { padding: 120px 20px 80px; }
        
        .product-container-premium {
            max-width: 1200px; margin: auto;
            display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 60px;
            align-items: start;
        }

        .image-showcase {
            position: sticky; top: 120px;
            background: #fff; border-radius: 30px; overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.04);
            border: 1px solid #f0f0f0;
        }
        body.dark .image-showcase { background: #1e1e1e; border-color: #333; box-shadow: 0 20px 40px rgba(0,0,0,0.4); }
        .image-showcase img { width: 100%; height: 550px; object-fit: cover; display: block; }

        /* Badges */
        .badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 18px; border-radius: 50px; font-size: 13px;
            font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;
            margin-bottom: 20px; margin-right: 10px;
        }
        .excelent { background: rgba(27, 94, 32, 0.1); color: #1b5e20; border: 1px solid rgba(27, 94, 32, 0.2); }
        .good { background: rgba(251, 192, 45, 0.1); color: #f57f17; border: 1px solid rgba(251, 192, 45, 0.2); }
        .fair { background: rgba(117, 117, 117, 0.1); color: #616161; border: 1px solid rgba(117, 117, 117, 0.2); }
        .neutral { background: rgba(0, 0, 0, 0.05); color: #555; border: 1px solid rgba(0, 0, 0, 0.1); }
        
        /* Badge de Oferta */
        .badge-promo { 
            background: var(--offer-red); color: #fff; border: none;
            animation: pulseRed 2s infinite;
        }
        @keyframes pulseRed {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(211, 47, 47, 0.4); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(211, 47, 47, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(211, 47, 47, 0); }
        }

        .product-title { font-size: 42px; font-weight: 800; line-height: 1.1; margin: 0 0 15px; color: #111; }
        body.dark .product-title { color: #fff; }

        /* Estilo do Preço com Oferta */
        .price-container { margin-bottom: 35px; }
        .old-price { font-size: 20px; text-decoration: line-through; color: #999; margin-bottom: 5px; display: block; }
        .price-tag { font-size: 40px; font-weight: 300; color: var(--primary); }
        .price-tag span { font-weight: 800; }
        .price-tag.promo { color: var(--offer-red); }
        body.dark .price-tag { color: var(--primary-light); }
        body.dark .price-tag.promo { color: #ff5252; }

        .description-box h3 { font-size: 13px; text-transform: uppercase; letter-spacing: 2px; opacity: 0.4; margin-bottom: 12px; }
        .description-box p { font-size: 17px; line-height: 1.8; opacity: 0.8; margin-bottom: 40px; }

        .specs-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 20px;
            margin-bottom: 40px; padding: 25px 0; border-top: 1px solid rgba(0,0,0,0.06);
        }
        .spec-item { display: flex; align-items: center; gap: 12px; font-size: 14px; font-weight: 500; }
        .spec-item i { color: var(--primary); font-size: 18px; }

        .btn-premium-cart {
            display: flex; align-items: center; justify-content: center; gap: 12px;
            padding: 22px; 
            background: linear-gradient(135deg, #2e7d32, #1b5e20);
            color: #fff;
            border-radius: 20px; font-size: 17px; font-weight: 700;
            text-decoration: none; transition: all 0.4s;
            box-shadow: 0 10px 25px rgba(46, 125, 50, 0.2);
        }
        .btn-premium-cart:hover { 
            transform: translateY(-4px); 
            box-shadow: 0 15px 35px rgba(46, 125, 50, 0.4); 
            filter: brightness(1.1);
        }

        @media (max-width: 992px) {
            .product-container-premium { grid-template-columns: 1fr; }
            .image-showcase { position: relative; top: 0; }
            .image-showcase img { height: 380px; }
        }
    </style>
</head>
<body class="<?= ($_SESSION['theme'] ?? 'light') === 'dark' ? 'dark' : '' ?>">

<?php require_once 'includes/header.php'; ?>

<div class="page-wrapper">
    <div class="product-container-premium">
        
        <div class="image-showcase">
            <?php 
                $foto_nome = $product['image_url'] ?? $product['image'] ?? '';
                $caminho_foto = !empty($foto_nome) ? "images/" . $foto_nome : "https://via.placeholder.com/800x800?text=Sem+Imagem";
            ?>
            <img src="<?= $caminho_foto ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>

        <div class="product-details-content">
            
            <div style="display: flex; flex-wrap: wrap;">
                <?= $badge_html ?>
                <?php if($em_oferta): ?>
                    <span class="badge badge-promo"><i class="fa fa-bolt"></i> Oferta Ativa</span>
                <?php endif; ?>
            </div>
            
            <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>
            
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
                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
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

<?php require_once 'includes/footer.php'; ?>

</body>
</html>