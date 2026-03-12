<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Ligações obrigatórias
require_once 'auth/config.php'; 
require_once 'includes/header.php';

// 2. Capturar o ID da URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    header("Location: index.php");
    exit;
}

// 3. Consulta na tabela 'products'
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id); 
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<div style='text-align:center; padding: 150px; color: var(--texto);'>
            <h2>Produto não encontrado.</h2>
            <a href='index.php#categorias' style='color: var(--preco);'>Voltar à loja</a>
          </div>";
    require_once 'includes/footer.php';
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name'] ?? 'Produto') ?> | Ecopeças</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Smooth Scroll para o efeito ser suave */
        html { scroll-behavior: smooth; }

        .page-wrapper { padding: 130px 20px 80px; min-height: 80vh; }
        
        .product-page {
            max-width: 1100px; margin: auto;
            display: grid; grid-template-columns: 1fr 1fr; gap: 50px;
        }

        .box-design {
            background: var(--bg-card);
            color: var(--texto);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .img-container img {
            width: 100%; border-radius: 15px; 
            height: 400px; object-fit: cover;
            background: #f9f9f9;
        }

        .product-info h2 { color: var(--preco); margin-bottom: 20px; font-size: 26px; }
        .product-info .price { font-size: 32px; font-weight: bold; color: var(--preco); margin: 20px 0; }
        .product-info .desc { line-height: 1.7; margin-bottom: 20px; opacity: 0.9; }

        /* Botão Adicionar ao Carrinho - Tamanho Médio */
        .btn-buy {
            display: inline-block; 
            width: 100%; 
            max-width: 260px; /* Largura média */
            padding: 15px 20px; 
            background: linear-gradient(45deg, #2e7d32, #4caf70);
            color: #fff; 
            border-radius: 30px; 
            font-weight: bold; 
            text-decoration: none; 
            text-align: center;
            transition: 0.3s;
        }

        .btn-buy:hover { transform: translateY(-2px); filter: brightness(1.1); }

        /* Estilo do link Voltar */
        .btn-back {
            display: inline-block; 
            margin-top: 30px; 
            text-decoration: none; 
            color: var(--texto); 
            font-size: 14px; 
            opacity: 0.8;
            transition: 0.3s;
        }
        .btn-back:hover { opacity: 1; color: var(--preco); transform: translateX(-5px); }

        @media (max-width: 850px) { 
            .product-page { grid-template-columns: 1fr; } 
            .btn-buy { max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="page-wrapper">
    <div class="product-page">
        
        <div class="box-design img-container">
            <?php 
                $foto_db = $product['image'] ?? $product['imagem'] ?? '';
                $foto = !empty($foto_db) ? $foto_db : 'https://via.placeholder.com/500x400?text=Sem+Imagem';
            ?>
            <img src="<?= htmlspecialchars($foto) ?>" alt="Imagem Peça">
        </div>

        <div class="box-design product-info">
            <h2><?= htmlspecialchars($product['name'] ?? 'Peça de Substituição') ?></h2>
            <div class="desc"><?= nl2br(htmlspecialchars($product['description'] ?? 'Descrição não disponível.')) ?></div>
            
            <p><strong>Estado:</strong> <?= htmlspecialchars($product['condition'] ?? 'Usado Original') ?></p>
            
            <div class="price">
                <?= number_format($product['price'] ?? 0, 2, ',', '.') ?> €
            </div>
            
            <div>
                <a href="add_to_cart.php?id=<?= $id ?>" class="btn-buy">
                    <i class="fa fa-shopping-cart"></i> Adicionar ao carrinho
                </a>
            </div>
            
            <a href="index.php#categorias" class="btn-back">
                <i class="fa fa-arrow-left"></i> Voltar à Loja🛒
            </a>
        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>