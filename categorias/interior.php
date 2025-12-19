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
// Produtos de exemplo da categoria Interior
$interior = [
    ["img" => "https://via.placeholder.com/300x200.png?text=Banco+Couro", "name" => "Banco de Couro", "price" => "€220,00", "desc" => "Banco confortável em couro premium para maior luxo e estilo."],
    ["img" => "https://via.placeholder.com/300x200.png?text=Tapetes+Automotivos", "name" => "Tapetes Automotivos", "price" => "€45,00", "desc" => "Tapetes resistentes e fáceis de limpar, perfeitos para qualquer veículo."],
    ["img" => "https://via.placeholder.com/300x200.png?text=Volante+Esportivo", "name" => "Volante Esportivo", "price" => "€85,00", "desc" => "Volante ergonômico que oferece melhor controle e conforto."],
    ["img" => "https://via.placeholder.com/300x200.png?text=Capa+de+Banco", "name" => "Capa de Banco", "price" => "€30,00", "desc" => "Capa protetora que mantém seus bancos limpos e bem conservados."]
];
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Interior - Ecopeças</title>
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

.product-card .desc {
    font-size: 14px;
    color: #555;
    margin-bottom: 10px;
}

/* Botão "Adicionar ao carrinho" estilizado */
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
    text-decoration: none;
    transition: transform 0.3s, box-shadow 0.3s, background 0.3s;
    display: inline-block;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.product-card .btn:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    background: linear-gradient(45deg, #66d78b, #4caf70);
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

<h1 style="text-align:center; margin-top:30px;">Produtos - Interior</h1>

<div class="products-container">
<?php foreach($interior as $product): ?>
    <div class="product-card">
        <img src="<?= $product['img'] ?>" alt="<?= $product['name'] ?>">
        <h3><?= $product['name'] ?></h3>
        <div class="desc"><?= $product['desc'] ?></div>
        <div class="price"><?= $product['price'] ?></div>
        <button class="btn">Adicionar ao carrinho</button>
    </div>
<?php endforeach; ?>
</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>
