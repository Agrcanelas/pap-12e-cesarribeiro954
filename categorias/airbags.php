<?php
// Ativa exibição de erros para debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Ligar à Base de Dados
require_once '../auth/config.php'; 

// 2. Procurar o ID da categoria "airbags"
$cat_slug = 'airbags';
$stmt_cat = $conn->prepare("SELECT id FROM categories WHERE slug = ?");
$stmt_cat->bind_param("s", $cat_slug);
$stmt_cat->execute();
$res_cat = $stmt_cat->get_result();
$cat_data = $res_cat->fetch_assoc();

if (!$cat_data) {
    die("Erro: Categoria 'airbags' não encontrada.");
}

$category_id = $cat_data['id'];

// 3. Procurar os produtos
$stmt_prod = $conn->prepare("SELECT * FROM products WHERE category_id = ?");
$stmt_prod->bind_param("i", $category_id);
$stmt_prod->execute();
$products_result = $stmt_prod->get_result();

// 4. Incluir o Header
require_once '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title>Airbags - Ecopeças</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .products-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            padding: 40px;
        }

        .product-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            width: 320px;
            text-align: center;
            padding: 25px;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            border: 1px solid #f0f0f0;
        }

        body.dark .product-card {
            background: #1e1e1e;
            color: #fff;
            border: 1px solid #333;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 15px;
        }

        .product-card h3 {
            font-size: 1.2rem;
            margin-bottom: 8px;
            height: 45px;
            overflow: hidden;
            font-weight: 700;
        }

        .product-card .price {
            font-size: 1.6rem;
            color: #2e7d32;
            font-weight: 800;
            margin: 10px 0;
        }

        body.dark .product-card .price {
            color: #66d78b;
        }

        /* --- BOTÕES MODERNOS PILL-STYLE --- */
        .card-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: white;
            padding: 12px 5px;
            text-decoration: none;
            border-radius: 50px; 
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-weight: bold;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        /* Gradiente Saber Mais */
        .btn-details {
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
        }

        /* Gradiente Adicionar ao Carrinho */
        .btn-cart {
            background: linear-gradient(135deg, #66d78b 0%, #43a047 100%);
        }

        .btn:hover {
            transform: scale(1.04);
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
            color: #fff;
        }

        /* Animação especial para o ícone do carrinho no hover */
        .btn-cart:hover i {
            transform: rotate(-15deg) scale(1.2);
            transition: 0.2s;
        }

        .btn:active {
            transform: scale(0.96);
        }

        .btn i {
            font-size: 0.9rem;
        }

    </style>
</head>
<body class="<?= ($_SESSION['theme'] ?? 'light') === 'dark' ? 'dark' : '' ?>">

<h1 style="text-align:center; margin-top:40px; font-weight: 800; color: <?= ($_SESSION['theme'] ?? 'light') === 'dark' ? '#fff' : '#2e7d32' ?>;">
    <?= ($lang == 'pt') ? 'Produtos: Airbags' : 'Products: Airbags' ?>
</h1>

<div class="products-container">
    <?php if ($products_result->num_rows > 0): ?>
        <?php while($product = $products_result->fetch_assoc()): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                
                <div class="price">€<?= number_format($product['price'], 2, ',', '.') ?></div>
                
                <p style="font-size: 0.9rem; opacity: 0.8; margin-bottom: 5px;">
                    <strong>Estado:</strong> <?= htmlspecialchars($product['condition_state']) ?>
                </p>
                
                <div class="card-buttons">
                    <a href="../produto.php?id=<?= $product['id'] ?>" class="btn btn-details">
                        <i class="fa fa-info-circle"></i> 
                        <?= ($lang == 'pt') ? 'Detalhes' : 'Details' ?>
                    </a>
                    
                    <a href="../add_to_cart.php?id=<?= $product['id'] ?>" class="btn btn-cart">
    <i class="fa fa-cart-plus"></i> 
    <?= ($lang == 'pt') ? 'Adicionar' : 'Add' ?>
</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center; width:100%; font-size: 1.2rem; margin-top: 50px;">
            <?= ($lang == 'pt') ? 'Nenhum produto encontrado.' : 'No products found.' ?>
        </p>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
</body>
</html>