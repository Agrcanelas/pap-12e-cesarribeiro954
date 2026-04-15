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

// Garante que a variável $lang existe
$lang = $_SESSION['lang'] ?? 'pt';

// --- LÓGICA DE ORDENAÇÃO ---
$sort = $_GET['sort'] ?? '';
$order_query = "id DESC"; 

if ($sort == 'price_asc') {
    $order_query = "price ASC";
} elseif ($sort == 'price_desc') {
    $order_query = "price DESC";
}

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

// 3. Procurar os produtos COM ORDENAÇÃO
$stmt_prod = $conn->prepare("SELECT * FROM products WHERE category_id = ? ORDER BY $order_query");
$stmt_prod->bind_param("i", $category_id);
$stmt_prod->execute();
$products_result = $stmt_prod->get_result();
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title>Airbags - Ecopeças</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ESTILO DO BANNER DA CATEGORIA */
        .category-hero {
            width: 100%;
            height: 300px;
            background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://cdn-s-www.leprogres.fr/images/14E98D71-DC3D-41BC-9F5D-9499A549A506/NW_raw/photo-adobe-stock-1705419200.jpg'); 
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: white;
            margin-bottom: 40px;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
        }

        .category-hero h1 {
            font-size: 3rem;
            font-weight: 800;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .category-hero p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-top: 10px;
        }

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
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            outline: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }

        .sort-select:hover { border-color: #2e7d32; }

        .products-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            padding: 20px 40px 100px 40px;
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
            position: relative;
        }

        body.dark .product-card {
            background: #1e1e1e;
            color: #fff;
            border: 1px solid #333;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        }

        .product-card:hover { transform: translateY(-8px); box-shadow: 0 15px 35px rgba(0,0,0,0.15); }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }

        .card-brand-area {
            display: flex;
            justify-content: flex-end;
            padding: 0 5px;
            margin-bottom: 10px;
        }

        .brand-badge-large {
            width: 75px;        
            height: 55px;       
            background: #fff;
            border-radius: 10px;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #eee;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .brand-badge-large img {
            width: 100% !important;
            height: 100% !important;
            margin-bottom: 0 !important;
            object-fit: contain !important;
            transform: scale(1.1);
        }

        .product-card h3 { font-size: 1.2rem; margin-bottom: 8px; height: 45px; overflow: hidden; font-weight: 700; }
        .product-card .price { font-size: 1.6rem; color: #2e7d32; font-weight: 800; margin: 5px 0 10px 0; }
        body.dark .product-card .price { color: #66d78b; }

        .card-buttons { display: flex; gap: 10px; margin-top: 20px; }
        
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
        }

        .btn-details { background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); }
        .btn-cart { background: linear-gradient(135deg, #66d78b 0%, #43a047 100%); }
        .btn:hover { transform: scale(1.04); box-shadow: 0 6px 15px rgba(0,0,0,0.2); color: #fff; }
    </style>
</head>
<body class="<?= ($_SESSION['theme'] ?? 'light') === 'dark' ? 'dark' : '' ?>" style="margin:0; padding:0;">

<?php require_once '../includes/header.php'; ?>

<div class="category-hero">
    <h1>
        <?= ($lang == 'pt') ? 'Airbags' : 'Airbags' ?>
    </h1>
    <p><?= ($lang == 'pt') ? 'Segurança máxima para o seu veículo com peças originais.' : 'Maximum safety for your vehicle with genuine parts.' ?></p>
</div>

<div class="filter-wrapper">
    <form method="GET" id="sortForm">
        <select name="sort" class="sort-select" onchange="this.form.submit()">
            <option value="" <?= $sort == '' ? 'selected' : '' ?>>
                <?= ($lang == 'pt') ? 'Ordenar por: Recentes' : 'Sort by: Recent' ?>
            </option>
            <option value="price_asc" <?= $sort == 'price_asc' ? 'selected' : '' ?>>
                <?= ($lang == 'pt') ? 'Preço: Mais Baixo' : 'Price: Lowest' ?>
            </option>
            <option value="price_desc" <?= $sort == 'price_desc' ? 'selected' : '' ?>>
                <?= ($lang == 'pt') ? 'Preço: Mais Alto' : 'Price: Highest' ?>
            </option>
        </select>
    </form>
</div>

<div class="products-container" style="padding-bottom: 100px;"> 
    <?php if ($products_result->num_rows > 0): ?>
        <?php while($product = $products_result->fetch_assoc()): 
            $logo_path = !empty($product['brand_logo']) ? "../logotipos/" . $product['brand_logo'] : "../logotipos/default.jpg";
        ?>
            <div class="product-card">
                <img src="../uploads/perfil/produtos/<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" onerror="this.src='https://via.placeholder.com/300x200?text=Sem+Foto'">
                
                <div class="card-brand-area">
                    <div class="brand-badge-large">
                        <img src="<?= $logo_path ?>" alt="Marca" onerror="this.src='../logotipos/default.jpg'">
                    </div>
                </div>

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

</body>
</html>