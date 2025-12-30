<?php
// Ativa exibição de erros para debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ajusta caminhos conforme tua estrutura
require_once '../auth/config.php';
require_once '../includes/header.php';

// Produtos de exemplo da categoria Airbags
$airbags = [
    [
        "img" => "https://s3.eu-central-1.wasabisys.com/atena-cloud/149/parts/213963/197b9f1584a330.webp",
        "name" => "Airbag cortina",
        "price" => "€120,00",
        "desc" => "Proteção frontal para condutor e passageiro.",
        "condition" => "Novo"
    ],
    [
        "img" => "https://ireland.apollo.olxcdn.com/v1/files/46n9h4lbxox81-PT/image;s=1000x700",
        "name" => "Airbag Lateral",
        "price" => "€115,00",
        "desc" => "Airbag lateral frente esquerdo BMW 7 730 D",
        "condition" => "Bom"
    ],
    [
        "img" => "https://ireland.apollo.olxcdn.com/v1/files/eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmbiI6InIyNmFoeG8yc2pxcy1TVERWVExQVCIsInciOlt7ImZuIjoiNm1nandscDdrZ2RiMi1TVERWVExQVCIsInMiOiIxNiIsImEiOiIwIiwicCI6IjEwLC0xMCJ9XX0.Hj3zWQOYCPQWlXTbYIBuUr_Y6iw_Drbp-afpCrGNnZE/image;s=1024x0;q=80",
        "name" => "Airbag do Condutor",
        "price" => "€130,00",
        "desc" => "Airbag De Volante Volkswagen Polo (6R1, 6C1)",
        "condition" => "Excelente"
    ],
    [
        "img" => "https://ireland.apollo.olxcdn.com/v1/files/eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmbiI6Ims5Nmd3MXc0YnJ5bDMtU1REVlRMUFQiLCJ3IjpbeyJmbiI6IjZtZ2p3bHA3a2dkYjItU1REVlRMUFQiLCJzIjoiMTYiLCJhIjoiMCIsInAiOiIxMCwtMTAifV19.l-OBLkNgzznXAO98HxiHW2iemuINkzBdrYdRRohQtvc/image;s=1024x0;q=80",
        "name" => "Airbag do Passageiro",
        "price" => "€73,00",
        "desc" => "CHEVROLET MATIZ 800 2008 0.8I 52CV 5P BRANCO",
        "condition" => "Razoavel"
    ],
];
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Airbags - Ecopeças</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- FAVICON -->
<link rel="icon" href="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg?w=360" type="image/png">

<style>
.products-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin: 50px 20px;
}

.product-card {
    width: 250px;
    margin: 15px;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    text-align: center;
    padding: 15px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.product-card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 10px;
}

.product-card h3 {
    font-size: 18px;
    margin: 10px 0;
    color: #2e7d32;
}

.product-card .desc {
    font-size: 14px;
    color: #555;
    margin-bottom: 10px;
}

.product-card .condition {
    font-size: 13px;
    color: #2e7d32;
    font-weight: bold;
    margin-bottom: 10px;
}

.product-card .price {
    font-weight: bold;
    color: #ff3d3d;
    font-size: 16px;
    margin-bottom: 10px;
}

/* Botão "Adicionar ao carrinho" */
.product-card .btn {
    margin-top: 10px;
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

.product-card .btn:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    background: linear-gradient(45deg, #66d78b, #4caf70);
}

/* Botão "Ver detalhes" discreto */
.btn-details {
    display: block;
    margin: 5px auto 0 auto;
    padding: 5px 12px;
    background: #ccc;
    color: #333;
    border-radius: 20px;
    font-size: 13px;
    text-decoration: none;
    text-align: center;
    transition: 0.3s;
}

.btn-details:hover {
    background: #bbb;
}
</style>
</head>

<body>

<h1 style="text-align:center; margin-top:30px;">Produtos - Airbags</h1>

<div class="products-container">
<?php foreach($airbags as $product): ?>
    <div class="product-card">
        <img src="<?= $product['img'] ?>" alt="<?= $product['name'] ?>">
        <h3><?= $product['name'] ?></h3>
        <div class="desc"><?= $product['desc'] ?></div>
        <div class="condition">Estado: <?= $product['condition'] ?></div>
        <div class="price"><?= $product['price'] ?></div>
        <button class="btn">Adicionar ao carrinho</button>
        <a href="../produto/produto.php?id=<?= urlencode($product['name']) ?>" class="btn-details">Ver detalhes</a>
    </div>
<?php endforeach; ?>
</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>
