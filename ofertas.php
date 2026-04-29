<?php
session_start();
require_once 'auth/config.php';
require_once 'includes/header.php';

$lang = $_SESSION['lang'] ?? 'pt';

// 1. Buscar os produtos em oferta incluindo o campo brand_logo
$sql_ofertas = "SELECT * FROM products WHERE em_oferta = 1 AND status = 'ativo' LIMIT 6";
$result_ofertas = $conn->query($sql_ofertas);

// IMAGEM CONDIZENTE COM O TEMA
$banner_ofertas = "https://www.choosethemoon.com/images/sliders/ferrari%20458%20italia.jpg";
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ($lang == 'pt') ? 'Ofertas Relâmpago' : 'Flash Deals' ?> - Ecopeças</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --p-green: #2e7d32;
            --p-red: #d32f2f;
            --p-dark: #121212;
            --bg-body: <?= ($_SESSION['theme'] ?? 'light') === 'dark' ? '#121212' : '#f3f4f6' ?>;
            --card-bg: <?= ($_SESSION['theme'] ?? 'light') === 'dark' ? '#1e1e1e' : '#fff' ?>;
            --text-color: <?= ($_SESSION['theme'] ?? 'light') === 'dark' ? '#fff' : '#121212' ?>;
        }

        body { 
            background-color: var(--bg-body);
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            color: var(--text-color);
        }

        .page-header { 
            text-align: center; 
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('<?= $banner_ofertas ?>');
            background-size: cover;
            background-position: center;
            padding: 100px 20px;
            color: white;
            width: 100%;
            margin-bottom: 50px;
        }
        .page-header h1 { font-size: 48px; font-weight: 800; color: #fff; margin: 0; text-transform: uppercase; }
        .page-header p { font-size: 1.2rem; opacity: 0.9; margin-top: 10px; }

        .main-wrapper { 
            padding: 0 5% 80px; 
            max-width: 1300px; 
            margin: 0 auto;
        }

        .offers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
        }

        .offer-card {
            background: var(--card-bg);
            border-radius: 30px;
            padding: 25px;
            position: relative;
            transition: all 0.4s ease;
            border: 1px solid rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
        }

        .offer-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.2); }

        .img-container {
            position: relative;
            width: 100%;
            margin-bottom: 20px;
        }

        .prod-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 20px;
        }

        .brand-badge-flash {
            position: absolute;
            bottom: -10px;
            right: 10px;
            width: 75px;
            height: 55px;
            background: #fff;
            border-radius: 12px;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #eee;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            z-index: 5;
        }

        .brand-badge-flash img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transform: scale(1.1);
        }

        .badge-discount {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--p-red);
            color: #fff;
            padding: 6px 15px;
            border-radius: 50px;
            font-weight: 800;
            z-index: 10;
            animation: pulseRed 2s infinite;
        }

        @keyframes pulseRed {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .prod-info h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 10px; height: 50px; overflow: hidden; }

        .price-container { display: flex; align-items: baseline; gap: 10px; margin-bottom: 20px; }
        .old-price { text-decoration: line-through; color: #aaa; font-size: 1.1rem; }
        .current-price { color: var(--p-green); font-size: 1.8rem; font-weight: 900; }

        .btn-buy {
            background: var(--p-green);
            color: #fff;
            text-align: center;
            padding: 15px;
            border-radius: 50px;
            font-weight: 700;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

<div class="page-header">
    <h1>Ofertas Relâmpago ⚡</h1>
    <p><?= ($lang == 'pt') ? 'Peças premium com descontos imbatíveis.' : 'Premium parts with unbeatable discounts.' ?></p>
</div>

<div class="main-wrapper">
    <div class="offers-grid">
        <?php if ($result_ofertas && $result_ofertas->num_rows > 0): ?>
            <?php while($item = $result_ofertas->fetch_assoc()): 
                $preco_atual = $item['price'];
                $preco_antigo = $item['preco_antigo'];
                $percentagem = ($preco_antigo > 0) ? round((($preco_antigo - $preco_atual) / $preco_antigo) * 100) : 0;
                $logo_path = !empty($item['brand_logo']) ? "logotipos/" . $item['brand_logo'] : "logotipos/default.jpg";
            ?>
                <a href="produto.php?id=<?= $item['id'] ?>" class="offer-card">
                    <div class="img-container">
                        <?php if($percentagem > 0): ?>
                            <div class="badge-discount">-<?= $percentagem ?>%</div>
                        <?php endif; ?>

                        <img src="uploads/perfil/produtos/<?= htmlspecialchars($item['image_url']) ?>" 
                             class="prod-img" 
                             onerror="this.src='https://via.placeholder.com/400x300?text=Sem+Foto'">

                        <div class="brand-badge-flash">
                            <img src="<?= $logo_path ?>" alt="Marca" onerror="this.src='logotipos/default.jpg'">
                        </div>
                    </div>

                    <div class="prod-info">
                        <h3>#<?= $item['id'] ?> - <?= htmlspecialchars($item['name']) ?></h3>
                        
                        <div class="price-container">
                            <?php if($preco_antigo > 0): ?>
                                <span class="old-price"><?= number_format($preco_antigo, 2, ',', '.') ?>€</span>
                            <?php endif; ?>
                            <span class="current-price"><?= number_format($preco_atual, 2, ',', '.') ?>€</span>
                        </div>
                    </div>

                    <div class="btn-buy">
                        <?= ($lang == 'pt') ? 'Aproveitar Agora!' : 'Get it Now' ?>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="text-align:center; width:100%; grid-column: 1/-1; padding: 100px 0;">
                <h2>Nenhuma oferta ativa de momento.</h2>
                <a href="index.php" style="color: var(--p-green);">Voltar à Loja</a>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>