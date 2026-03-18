<?php
session_start();
require_once 'auth/config.php';
require_once 'includes/header.php';

// 1. Buscar apenas os produtos marcados como 'em_oferta' na DB
$sql_ofertas = "SELECT * FROM products WHERE em_oferta = 1";
$result_ofertas = $conn->query($sql_ofertas);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ofertas Relâmpago | Ecopeças Premium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --p-green: #2e7d32;
            --p-red: #d32f2f;
            --p-dark: #121212;
            --bg-body: #f3f4f6;
        }

        body { 
            background-color: var(--bg-body);
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            color: var(--p-dark);
        }

        .main-wrapper { 
            padding: 160px 5% 80px; 
            max-width: 1300px; 
            margin: 0 auto;
        }

        .page-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .page-header h1 {
            font-size: 42px;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 10px;
        }

        .page-header p {
            color: #666;
            font-size: 18px;
        }

        .offers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }

        .offer-card {
            background: #fff;
            border-radius: 30px;
            padding: 20px;
            position: relative;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
        }

        .offer-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }

        .badge-discount {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--p-red);
            color: #fff;
            padding: 6px 15px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 14px;
            z-index: 10;
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);
            animation: pulseRed 2s infinite;
        }

        @keyframes pulseRed {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .prod-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 20px;
            background: #f9f9f9;
            margin-bottom: 20px;
        }

        .prod-info h3 {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 10px;
            color: var(--p-dark);
        }

        .price-container {
            display: flex;
            align-items: baseline;
            gap: 10px;
            margin-bottom: 20px;
        }

        .old-price {
            text-decoration: line-through;
            color: #aaa;
            font-size: 16px;
            font-weight: 600;
        }

        .current-price {
            color: var(--p-green);
            font-size: 28px;
            font-weight: 900;
        }

        .btn-buy {
            background: var(--p-dark);
            color: #fff;
            text-align: center;
            padding: 15px;
            border-radius: 15px;
            text-decoration: none;
            font-weight: 700;
            transition: 0.3s;
            margin-top: auto;
        }

        .offer-card:hover .btn-buy {
            background: var(--p-green);
        }

        .empty-offers {
            text-align: center;
            padding: 100px 0;
            grid-column: 1 / -1;
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <div class="page-header">
        <h1>Ofertas Relâmpago ⚡</h1>
        <p>Peças premium com descontos imbatíveis por tempo limitado.</p>
    </div>

    <div class="offers-grid">
        <?php if ($result_ofertas && $result_ofertas->num_rows > 0): ?>
            <?php while($item = $result_ofertas->fetch_assoc()): 
                $preco_atual = $item['price'] ?? $item['preco'] ?? 0;
                $preco_antigo = $item['preco_antigo'] ?? 0;
                
                $percentagem = ($preco_antigo > 0) ? round((($preco_antigo - $preco_atual) / $preco_antigo) * 100) : 0;
            ?>
                <a href="produto.php?id=<?= $item['id'] ?>" class="offer-card">
                    <?php if($percentagem > 0): ?>
                        <div class="badge-discount">-<?= $percentagem ?>%</div>
                    <?php endif; ?>

                    <img src="<?= htmlspecialchars($item['image'] ?? $item['imagem'] ?? 'https://via.placeholder.com/400x300') ?>" 
                         class="prod-img" 
                         onerror="this.src='https://via.placeholder.com/400x300'">

                    <div class="prod-info">
                        <h3><?= htmlspecialchars($item['name'] ?? $item['nome'] ?? 'Peça de Performance') ?></h3>
                        
                        <div class="price-container">
                            <?php if($preco_antigo > 0): ?>
                                <span class="old-price"><?= number_format($preco_antigo, 2, ',', '.') ?>€</span>
                            <?php endif; ?>
                            <span class="current-price"><?= number_format($preco_atual, 2, ',', '.') ?>€</span>
                        </div>
                    </div>

                    <div class="btn-buy">
                        APROVEITAR AGORA
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-offers">
                <i class="fa-solid fa-tags" style="font-size: 50px; color: #ccc; margin-bottom: 20px;"></i>
                <h2>Não há ofertas ativas no momento.</h2>
                <p>Fique atento, voltaremos em breve com novidades!</p>
                <a href="index.php" style="color: var(--p-green); font-weight: 700; text-decoration: none; display: block; margin-top: 20px;">
                    Voltar para a loja
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>