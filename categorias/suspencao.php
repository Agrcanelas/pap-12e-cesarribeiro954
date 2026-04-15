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
$lang = $_SESSION['lang'] ?? 'pt';

// --- LÓGICA DE ORDENAÇÃO ---
$sort = $_GET['sort'] ?? '';
$order_query = "p.id DESC"; 

if ($sort == 'price_asc') {
    $order_query = "p.price ASC";
} elseif ($sort == 'price_desc') {
    $order_query = "p.price DESC";
}

// 2. Procurar o ID da categoria "suspensao"
$cat_slug = 'suspensao'; 
$stmt_cat = $conn->prepare("SELECT id, nome_pt, nome_en FROM categories WHERE slug = ?");
$stmt_cat->bind_param("s", $cat_slug);
$stmt_cat->execute();
$res_cat = $stmt_cat->get_result();
$cat_data = $res_cat->fetch_assoc();

if (!$cat_data) {
    die("Erro: Categoria 'Suspensão' não encontrada.");
}

$category_id = $cat_data['id'];
$titulo_exibicao = ($lang == 'pt') ? $cat_data['nome_pt'] : $cat_data['nome_en'];

// 3. Procurar os produtos com Ordenação e Imagem
$stmt_prod = $conn->prepare("
    SELECT p.*, 
    (SELECT pi.image_url FROM product_images pi WHERE pi.product_id = p.id LIMIT 1) AS main_image 
    FROM products p 
    WHERE p.category_id = ? AND p.status = 'ativo' 
    ORDER BY $order_query
");
$stmt_prod->bind_param("i", $category_id);
$stmt_prod->execute();
$products_result = $stmt_prod->get_result();
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo_exibicao ?> - Ecopeças</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* BANNER HERO - SUSPENSÃO */
        .category-hero {
            width: 100%;
            height: 350px;
            background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.7)), 
                              url('https://www.minutoseguros.com.br/blog/wp-content/uploads/2022/05/amortecedor-recondicionado.jpg'); 
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #ffffff !important;
            margin-bottom: 40px;
            text-shadow: 2px 2px 15px rgba(0,0,0,0.9);
        }

        .category-hero h1 {
            font-size: 3.5rem;
            font-weight: 900;
            margin: 0;
            text-transform: uppercase;
            color: #ffffff !important;
        }

        /* FILTROS E LAYOUT */
        .filter-wrapper {
            display: flex;
            justify-content: flex-end;
            max-width: 1200px;
            margin: 0 auto 20px auto;
            padding: 0 40px;
        }

        .sort-select {
            padding: 10px 15px;
            border-radius: 12px;
            border: 1px solid #ddd;
            background: <?= ($_SESSION['theme'] ?? 'light') === 'dark' ? '#2a2a2a' : '#fff' ?>;
            color: <?= ($_SESSION['theme'] ?? 'light') === 'dark' ? '#fff' : '#333' ?>;
            font-family: inherit;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .products-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            padding: 0 40px 100px 40px;
        }

        /* CARD DO PRODUTO */
        .product-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            width: 320px;
            padding: 20px;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            border: 1px solid #f0f0f0;
        }

        body.dark .product-card { background: #1e1e1e; color: #fff; border: 1px solid #333; }
        .product-card:hover { transform: translateY(-8px); }

        .product-card img.main-prod-img {
            width: 100%;
            height: 220px;
            object-fit: contain;
            background-color: #f8f9fa; 
            border-radius: 15px;
            margin-bottom: 15px;
            padding: 10px;
            box-sizing: border-box;
        }

        body.dark .product-card img.main-prod-img { background-color: #2a2a2a; }

        .card-brand-area { display: flex; justify-content: flex-end; margin-bottom: 10px; }
        .brand-badge-large {
            width: 75px; height: 55px; background: #fff; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid #eee; padding: 6px;
        }
        .brand-badge-large img { width: 100%; height: 100%; object-fit: contain; }

        .product-card h3 { font-size: 1.1rem; margin-bottom: 8px; height: 45px; overflow: hidden; font-weight: 700; text-align: center; }
        .product-card .price { font-size: 1.6rem; color: #2e7d32; font-weight: 800; margin: 5px 0 10px 0; text-align: center; }
        body.dark .product-card .price { color: #66d78b; }

        .card-buttons { display: flex; gap: 10px; margin-top: auto; }
        .btn {
            flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
            color: white !important; padding: 12px 5px; text-decoration: none;
            border-radius: 50px; font-weight: bold; font-size: 0.8rem; text-transform: uppercase;
        }
        .btn-details { background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); }
        .btn-cart { background: linear-gradient(135deg, #66d78b 0%, #43a047 100%); }
    </style>
</head>
<body class="<?= ($_SESSION['theme'] ?? 'light') === 'dark' ? 'dark' : '' ?>" style="margin:0; padding:0;">

<?php require_once '../includes/header.php'; ?>

<div class="category-hero">
    <h1><?= $titulo_exibicao ?></h1>
    <p><?= ($lang == 'pt') ? 'Segurança e conforto com amortecedores e molas de qualidade.' : 'Safety and comfort with quality shocks and springs.' ?></p>
</div>

<div class="filter-wrapper">
    <form method="GET">
        <select name="sort" class="sort-select" onchange="this.form.submit()">
            <option value="" <?= $sort == '' ? 'selected' : '' ?>><?= ($lang == 'pt') ? 'Mais Recentes' : 'Recent' ?></option>
            <option value="price_asc" <?= $sort == 'price_asc' ? 'selected' : '' ?>><?= ($lang == 'pt') ? 'Preço: Baixo' : 'Price: Low' ?></option>
            <option value="price_desc" <?= $sort == 'price_desc' ? 'selected' : '' ?>><?= ($lang == 'pt') ? 'Preço: Alto' : 'Price: High' ?></option>
        </select>
    </form>
</div>

<div class="products-container">
    <?php if ($products_result->num_rows > 0): ?>
        <?php while($product = $products_result->fetch_assoc()): 
            $logo_path = !empty($product['brand_logo']) ? "../logotipos/" . $product['brand_logo'] : "../logotipos/default.jpg";
        ?>
            <div class="product-card">
                <img src="../uploads/perfil/produtos/<?= htmlspecialchars($product['main_image'] ?? '') ?>" 
                     alt="<?= htmlspecialchars($product['name']) ?>" 
                     class="main-prod-img"
                     onerror="this.src='https://via.placeholder.com/320x220?text=Imagem+Indispon%C3%ADvel'">
                
                <div class="card-brand-area">
                    <div class="brand-badge-large">
                        <img src="<?= $logo_path ?>" alt="Marca" onerror="this.src='../logotipos/default.jpg'">
                    </div>
                </div>

                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <div class="price">€<?= number_format($product['price'], 2, ',', '.') ?></div>
                
                <p style="font-size: 0.85rem; opacity: 0.8; margin-bottom: 10px; text-align: center;">
                    <strong><?= ($lang == 'pt') ? 'Estado:' : 'Condition:' ?></strong> <?= htmlspecialchars($product['condition_state'] ?? 'Usado') ?>
                </p>
                
                <div class="card-buttons">
                    <a href="../produto.php?id=<?= $product['id'] ?>" class="btn btn-details">DETALHES</a>
                    <a href="../add_to_cart.php?id=<?= $product['id'] ?>" class="btn btn-cart">ADICIONAR</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center; width:100%; margin-top: 50px;">
            <?= ($lang == 'pt') ? 'Nenhum componente encontrado.' : 'No components found.' ?>
        </p>
    <?php endif; ?>
</div>

</body>
</html>