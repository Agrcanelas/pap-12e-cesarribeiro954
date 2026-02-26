<?php
require_once 'auth/config.php'; // Ajusta o caminho se necessário
require_once 'includes/header.php';

// Pega o termo de pesquisa e protege contra SQL Injection
$query_term = isset($_GET['q']) ? trim($_GET['q']) : '';

if (!empty($query_term)) {
    // Procura no nome ou na descrição do produto
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
    <style>
        .search-title { text-align: center; margin-top: 40px; font-weight: 800; color: #2e7d32; }
        body.dark .search-title { color: #fff; }

        .products-container { display: flex; flex-wrap: wrap; justify-content: center; gap: 30px; padding: 40px; }
        
        .product-card {
            background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            width: 320px; text-align: center; padding: 25px; transition: all 0.3s ease;
            display: flex; flex-direction: column; border: 1px solid #f0f0f0;
        }
        body.dark .product-card { background: #1e1e1e; color: #fff; border: 1px solid #333; }
        .product-card:hover { transform: translateY(-8px); box-shadow: 0 15px 35px rgba(0,0,0,0.15); }
        .product-card img { width: 100%; height: 200px; object-fit: cover; border-radius: 15px; margin-bottom: 15px; }
        .product-card h3 { font-size: 1.2rem; height: 45px; overflow: hidden; font-weight: 700; }
        .product-card .price { font-size: 1.6rem; color: #2e7d32; font-weight: 800; margin: 10px 0; }
        body.dark .product-card .price { color: #66d78b; }

        /* Botões Modernos */
        .card-buttons { display: flex; gap: 10px; margin-top: 20px; }
        .btn {
            flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
            color: white; padding: 12px 5px; text-decoration: none; border-radius: 50px;
            font-weight: bold; font-size: 0.8rem; text-transform: uppercase; transition: 0.3s;
        }
        .btn-details { background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); }
        .btn-cart { background: linear-gradient(135deg, #66d78b 0%, #43a047 100%); }
        .btn:hover { transform: scale(1.04); }
    </style>
</head>
<body class="<?= ($_SESSION['theme'] ?? 'light') === 'dark' ? 'dark' : '' ?>">

    <h1 class="search-title">
        <?= ($lang == 'pt') ? 'Resultados para: ' : 'Results for: ' ?> "<?= htmlspecialchars($query_term) ?>"
    </h1>

    <div class="products-container">
        <?php if (isset($results) && $results->num_rows > 0): ?>
            <?php while($product = $results->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="<?= $base ?>/images/<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <div class="price">€<?= number_format($product['price'], 2, ',', '.') ?></div>
                    <p><strong>Estado:</strong> <?= htmlspecialchars($product['condition_state']) ?></p>
                    
                    <div class="card-buttons">
                        <a href="<?= $base ?>/produto.php?id=<?= $product['id'] ?>" class="btn btn-details">
                            <i class="fa fa-info-circle"></i> Detalhes
                        </a>
                       <a href="<?= $base ?>/add_to_cart.php?id=<?= $product['id'] ?>" class="btn btn-cart">
                        <i class="fa fa-cart-plus"></i> 
                        <?= ($lang == 'pt') ? 'Adicionar' : 'Add' ?>
                    </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="text-align: center; margin-top: 50px;">
                <i class="fa fa-search-minus" style="font-size: 50px; opacity: 0.3;"></i>
                <p style="font-size: 1.2rem; margin-top: 20px;">
                    <?= ($lang == 'pt') ? 'Não encontramos nada para essa pesquisa.' : 'We found nothing for that search.' ?>
                </p>
                <a href="<?= $base ?>/index.php" style="color: #2e7d32; font-weight: bold;">Voltar à Loja</a>
            </div>
        <?php endif; ?>
    </div>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>