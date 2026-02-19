<?php
// Ativa exibição de erros para debugging durante o desenvolvimento
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicia a sessão para manter idioma e tema
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Ligar à Base de Dados
// Ajusta o caminho se o ficheiro config.php estiver noutra pasta
require_once '../auth/config.php'; 

// 2. Procurar o ID da categoria "airbags" na tabela
$cat_slug = 'motor';
$stmt_cat = $conn->prepare("SELECT id FROM categories WHERE slug = ?");
$stmt_cat->bind_param("s", $cat_slug);
$stmt_cat->execute();
$res_cat = $stmt_cat->get_result();
$cat_data = $res_cat->fetch_assoc();

if (!$cat_data) {
    die("Erro: Categoria 'airbags' não encontrada na base de dados. Verifica a tabela 'categories'.");
}

$category_id = $cat_data['id'];

// 3. Procurar os produtos pertencentes a esta categoria
$stmt_prod = $conn->prepare("SELECT * FROM products WHERE category_id = ?");
$stmt_prod->bind_param("i", $category_id);
$stmt_prod->execute();
$products_result = $stmt_prod->get_result();

// 4. Incluir o Header (que já tem o CSS global e menu)
require_once '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title>Airbags - Ecopeças</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        /* Estilos específicos para a grelha de produtos */
        .products-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
            padding: 40px;
        }

        .product-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            width: 300px;
            text-align: center;
            padding: 20px;
            transition: transform 0.3s ease;
        }

        /* Suporte ao Modo Escuro */
        body.dark .product-card {
            background: #1e1e1e;
            color: #fff;
            box-shadow: 0 10px 20px rgba(0,0,0,0.5);
        }

        .product-card:hover {
            transform: translateY(-10px);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .product-card h3 {
            font-size: 1.4rem;
            margin-bottom: 10px;
        }

        .product-card .price {
            font-size: 1.2rem;
            color: #2e7d32;
            font-weight: bold;
            margin: 10px 0;
        }

        body.dark .product-card .price {
            color: #4caf50;
        }

        .product-card .btn {
            display: inline-block;
            background: #2e7d32;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 25px;
            transition: 0.3s;
        }

        .product-card .btn:hover {
            background: #1b5e20;
        }
    </style>
</head>
<body class="<?= ($_SESSION['theme'] ?? 'light') === 'dark' ? 'dark' : '' ?>">

<h1 style="text-align:center; margin-top:30px;">
    <?= ($lang == 'pt') ? 'Produtos - Airbags' : 'Products - Airbags' ?>
</h1>

<div class="products-container">
    <?php if ($products_result->num_rows > 0): ?>
        <?php while($product = $products_result->fetch_assoc()): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                
                <div class="price">€<?= number_format($product['price'], 2, ',', '.') ?></div>
                
                <p><strong>Estado:</strong> <?= htmlspecialchars($product['condition_state']) ?></p>
                
                <a href="../produto.php?id=<?= $product['id'] ?>" class="btn">
                    <?= ($lang == 'pt') ? 'Ver Detalhes' : 'View Details' ?>
                </a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center; width:100%;">
            <?= ($lang == 'pt') ? 'Nenhum produto encontrado nesta categoria.' : 'No products found in this category.' ?>
        </p>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
</body>
</html>