<?php
// Ativa exibição de erros para debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../auth/config.php';
require_once '../includes/header.php';

// Produtos de exemplo (mesmos do airbags.php) com imagens adicionais e ano
$airbags = [
    "Airbag Frontal" => [
        "name" => "Airbag Frontal",
        "price" => "€120,00",
        "desc" => "Proteção frontal para condutor e passageiro.",
        "condition" => "Novo",
        "year" => "2023",
        "images" => [
            "https://via.placeholder.com/500x300.png?text=Airbag+Frontal+1",
            "https://via.placeholder.com/500x300.png?text=Airbag+Frontal+2"
        ]
    ],
    "Airbag Lateral" => [
        "name" => "Airbag Lateral",
        "price" => "€115,00",
        "desc" => "Airbag lateral frente esquerdo BMW 7 730 D",
        "condition" => "Bom",
        "year" => "2020",
        "images" => [
            "https://prod-images.custojusto.pt/play/1618649487-airbag-lateral-frente-esquerdo-bmw-7-730-d.jpg",
            "https://via.placeholder.com/500x300.png?text=Airbag+Lateral+2"
        ]
    ],
    "Airbag do Condutor" => [
        "name" => "Airbag do Condutor",
        "price" => "€130,00",
        "desc" => "Airbag De Volante Volkswagen Polo (6R1, 6C1)",
        "condition" => "Excelente",
        "year" => "2021",
        "images" => [
            "https://ireland.apollo.olxcdn.com/v1/files/eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmbiI6InIyNmFoeG8yc2pxcy1TVERWVExQVCIsInciOlt7ImZuIjoiNm1nandscDdrZ2RiMi1TVERWVExQVCIsInMiOiIxNiIsImEiOiIwIiwicCI6IjEwLC0xMCJ9XX0.Hj3zWQOYCPQWlXTbYIBuUr_Y6iw_Drbp-afpCrGNnZE/image;s=1024x0;q=80",
            "https://via.placeholder.com/500x300.png?text=Airbag+Condutor+2"
        ]
    ],
    "Airbag do Passageiro" => [
        "name" => "Airbag do Passageiro",
        "price" => "€73,00",
        "desc" => "CHEVROLET MATIZ 800 2008 0.8I 52CV 5P BRANCO",
        "condition" => "Razoavel",
        "year" => "2008",
        "images" => [
            "https://ireland.apollo.olxcdn.com/v1/files/eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmbiI6Ims5Nmd3MXc0YnJ5bDMtU1REVlRMUFQiLCJ3IjpbeyJmbiI6IjZtZ2p3bHA3a2dkYjItU1REVlRMUFQiLCJzIjoiMTYiLCJhIjoiMCIsInAiOiIxMCwtMTAifV19.l-OBLkNgzznXAO98HxiHW2iemuINkzBdrYdRRohQtvc/image;s=1024x0;q=80",
            "https://via.placeholder.com/500x300.png?text=Airbag+Passageiro+2"
        ]
    ]
];

// Pega o id do produto da URL
$id = $_GET['id'] ?? null;

if (!$id || !isset($airbags[$id])) {
    echo "<h2 style='text-align:center; margin-top:50px;'>Produto não encontrado!</h2>";
    require_once '../includes/footer.php';
    exit;
}

$product = $airbags[$id];
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $product['name'] ?> - Detalhes</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
.product-detail-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin: 50px 20px;
}

.images-gallery {
    flex: 1;
    min-width: 300px;
    max-width: 500px;
    margin-right: 30px;
}

.images-gallery img {
    width: 100%;
    border-radius: 10px;
    margin-bottom: 10px;
}

.product-info {
    flex: 1;
    min-width: 250px;
    max-width: 400px;
}

.product-info h2 {
    color: #2e7d32;
    margin-bottom: 10px;
}

.product-info .desc {
    font-size: 15px;
    margin-bottom: 10px;
}

.product-info .condition,
.product-info .year,
.product-info .price {
    font-weight: bold;
    margin-bottom: 8px;
    font-size: 14px;
}

.product-info .btn {
    padding: 10px 18px;
    border: none;
    background: linear-gradient(45deg, #4caf70, #66d78b);
    color: #fff;
    border-radius: 25px;
    cursor: pointer;
    font-weight: bold;
    font-size: 15px;
    transition: transform 0.3s, box-shadow 0.3s, background 0.3s;
    display: inline-block;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.product-info .btn:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    background: linear-gradient(45deg, #66d78b, #4caf70);
}
</style>
</head>
<body>

<div class="product-detail-container">
    <div class="images-gallery">
        <?php foreach($product['images'] as $img): ?>
            <img src="<?= $img ?>" alt="<?= $product['name'] ?>">
        <?php endforeach; ?>
    </div>
    <div class="product-info">
        <h2><?= $product['name'] ?></h2>
        <div class="desc"><?= $product['desc'] ?></div>
        <div class="condition">Estado: <?= $product['condition'] ?></div>
        <div class="year">Ano: <?= $product['year'] ?></div>
        <div class="price">Preço: <?= $product['price'] ?></div>
        <button class="btn">Adicionar ao carrinho</button>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
</body>
</html>
