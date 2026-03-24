<?php
// CORREÇÃO DO ERRO: Iniciar a sessão antes de qualquer output HTML
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'auth/config.php'; 

$query_term = isset($_GET['q']) ? trim($_GET['q']) : '';
$lang = $_SESSION['lang'] ?? 'pt'; 

if (!empty($query_term)) {
    $search_param = "%$query_term%";
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
    $stmt->bind_param("ss", $search_param, $search_param);
    $stmt->execute();
    $results = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title>Resultados para: <?= htmlspecialchars($query_term) ?> - Ecopeças</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> 
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        .search-title { 
            text-align: center; margin-top: 30px; margin-bottom: 10px;
            font-weight: 800; color: #2e7d32; 
        }
        body.dark .search-title { color: #fff; }

        .products-container { 
            display: flex; flex-wrap: wrap; justify-content: center; 
            gap: 30px; padding: 20px 40px 40px 40px; 
        }
        
        .product-card {
            background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            width: 320px; text-align: center; padding: 25px; transition: all 0.3s ease;
            display: flex; flex-direction: column; border: 1px solid #f0f0f0;
        }
        body.dark .product-card { background: #1e1e1e; color: #fff; border: 1px solid #333; }
        .product-card:hover { transform: translateY(-8px); }
        .product-card img { width: 100%; height: 200px; object-fit: cover; border-radius: 15px; margin-bottom: 15px; }
        
        .product-card .price { font-size: 1.6rem; color: #2e7d32; font-weight: 800; margin: 10px 0; }
        body.dark .product-card .price { color: #66d78b; }

        .card-buttons { display: flex; gap: 10px; margin-top: 20px; }
        .btn {
            flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
            color: white; padding: 12px 5px; text-decoration: none; border-radius: 50px;
            font-weight: bold; font-size: 0.8rem; text-transform: uppercase; transition: 0.3s;
        }
        .btn-details { background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); }
        .btn-cart { background: linear-gradient(135deg, #66d78b 0%, #43a047 100%); }

        .no-results-area {
            width: 100%; text-align: center; padding: 60px 20px;
            color: inherit; 
        }

        .floating-sad {
            font-size: 100px; margin-bottom: 25px; display: inline-block;
            opacity: 0.5; 
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }

        .back-link-modern {
            display: inline-block; margin-top: 25px; padding: 14px 35px;
            background: #2e7d32; color: #fff; text-decoration: none;
            border-radius: 50px; font-weight: 700; transition: 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .back-link-modern:hover { transform: translateY(-3px); opacity: 0.9; }
    </style>
</head>
<body class="<?= (isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark') ? 'dark' : '' ?>" style="margin:0; padding:0;">

<?php require_once 'includes/header.php'; ?>

    <h1 class="search-title">
        <?= ($lang == 'pt') ? 'Resultados para: ' : 'Results for: ' ?> "<?= htmlspecialchars($query_term) ?>"
    </h1>

    <div class="products-container">
        <?php if (isset($results) && $results->num_rows > 0): ?>
            <?php while($product = $results->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="uploads/produtos/<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" onerror="this.src='https://via.placeholder.com/200'">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <div class="price">€<?= number_format($product['price'], 2, ',', '.') ?></div>
                    <p style="font-size: 0.9rem; opacity: 0.8;">
                        <strong>Estado:</strong> <?= htmlspecialchars($product['condition_state'] ?? 'Usado') ?>
                    </p>
                    <div class="card-buttons">
                        <a href="produto.php?id=<?= $product['id'] ?>" class="btn btn-details">
                            <i class="fa fa-info-circle"></i> Detalhes
                        </a>
                        <a href="add_to_cart.php?id=<?= $product['id'] ?>" class="btn btn-cart">
                            <i class="fa fa-cart-plus"></i> Adicionar
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-results-area">
                <i class="fa fa-frown-o floating-sad"></i>
                <h2 style="font-weight: 800; margin-bottom: 10px;">
                    <?= ($lang == 'pt') ? 'Ups! Não encontramos nada...' : 'Oops! We found nothing...' ?>
                </h2>
                <p style="font-size: 1.1rem; opacity: 0.7; max-width: 500px; margin: 0 auto;">
                    <?= ($lang == 'pt') 
                        ? 'Neste momento o produto não se encontra disponível, mas quem sabe brevemente!' 
                        : 'At the moment this product is not available, but who knows, maybe soon!' ?>
                </p>
                <a href="index.php" class="back-link-modern">
                    <i class="fa fa-arrow-left"></i> <?= ($lang == 'pt') ? 'Voltar à Loja' : 'Back to Shop' ?>
                </a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>