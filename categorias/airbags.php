<?php
// Ativa exibição de erros para debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Ajusta caminhos conforme tua estrutura
require_once '../auth/config.php';
require_once '../includes/header.php';
?>

<?php
// Produtos de exemplo da categoria Airbags
$airbags = [
    ["img" => "https://via.placeholder.com/300x200.png?text=Airbag+Frontal", "name" => "Airbag Frontal", "price" => "€120,00"],
    ["img" => "https://via.placeholder.com/300x200.png?text=Airbag+Lateral", "name" => "Airbag Lateral", "price" => "€150,00"],
    ["img" => "https://via.placeholder.com/300x200.png?text=Airbag+do+Condutor", "name" => "Airbag do Condutor", "price" => "€130,00"],
    ["img" => "https://via.placeholder.com/300x200.png?text=Airbag+do+Passageiro", "name" => "Airbag do Passageiro", "price" => "€140,00"]
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

.product-card .price {
    font-weight: bold;
    color: #ff3d3d;
    font-size: 16px;
    margin-bottom: 10px;
}

.back-btn {
    display: inline-block;
    margin: 20px;
    padding: 10px 15px;
    background: #4caf70;
    color: #fff;
    border-radius: 50px;
    font-weight: bold;
    text-decoration: none;
    transition: all 0.3s;
}

.back-btn:hover { background: #66d78b; }
</style>
</head>
<body>

<h1 style="text-align:center; margin-top:30px;">Produtos - Airbags</h1>

<div class="products-container">
<?php foreach($airbags as $product): ?>
    <div class="product-card">
        <img src="<?= $product['img'] ?>" alt="<?= $product['name'] ?>">
        <h3><?= $product['name'] ?></h3>
        <div class="price"><?= $product['price'] ?></div>
        <button class="btn">Adicionar ao carrinho</button>
    </div>
<?php endforeach; ?>
</div>

<div style="text-align:center;">
    <a href="../index.php" class="back-btn"><i class="fa fa-arrow-left"></i> Voltar</a>
</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>
