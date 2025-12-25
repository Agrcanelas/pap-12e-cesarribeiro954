<?php
// Ativa exibição de erros para debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../auth/config.php';
require_once '../includes/header.php';

// Produtos de exemplo da categoria Motor e Transmissão
$motors = [
    [
        "name" => "Motor 1.6",
        "price" => "€850,00",
        "desc" => "Motor eficiente com desempenho otimizado para veículos compactos.",
        "condition" => "Novo",
        "year" => "2022",
        "images" => [
            "https://via.placeholder.com/500x300.png?text=Motor+1.6+1",
            "https://via.placeholder.com/500x300.png?text=Motor+1.6+2"
        ]
    ],
    [
        "name" => "Motor 2.0",
        "price" => "€1.200,00",
        "desc" => "Potente motor para condução suave em médias e grandes distâncias.",
        "condition" => "Bom",
        "year" => "2021",
        "images" => [
            "https://via.placeholder.com/500x300.png?text=Motor+2.0+1",
            "https://via.placeholder.com/500x300.png?text=Motor+2.0+2"
        ]
    ],
    [
        "name" => "Caixa de Velocidades Manual",
        "price" => "€450,00",
        "desc" => "Transmissão manual confiável e durável para todo tipo de condução.",
        "condition" => "Excelente",
        "year" => "2019",
        "images" => [
            "https://via.placeholder.com/500x300.png?text=Caixa+Manual+1",
            "https://via.placeholder.com/500x300.png?text=Caixa+Manual+2"
        ]
    ],
    [
        "name" => "Caixa de Velocidades Automática",
        "price" => "€600,00",
        "desc" => "Transmissão automática suave, ideal para conforto urbano e rodoviário.",
        "condition" => "Razoável",
        "year" => "2020",
        "images" => [
            "https://via.placeholder.com/500x300.png?text=Caixa+Automatica+1",
            "https://via.placeholder.com/500x300.png?text=Caixa+Automatica+2"
        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Motor e Transmissão - Ecopeças</title>
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
    margin-bottom: 5px;
}

.product-card .condition,
.product-card .year {
    font-size: 13px;
    color: #2e7d32;
    font-weight: bold;
    margin-bottom: 5px;
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

/* Botão "Ver detalhes" igual ao Airbags */
.product-card .details-btn {
    margin-top: 8px;
    padding: 8px 18px;
    font-size: 15px;
    border: none;
    background: #eee;
    color: #333;
    border-radius: 25px;
    cursor: pointer;
    font-weight: bold;
    display: inline-block;
    transition: background 0.3s, transform 0.3s;
}

.product-card .details-btn:hover {
    background: #ddd;
    transform: translateY(-2px);
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

<h1 style="text-align:center; margin-top:30px;">Produtos - Motor e Transmissão</h1>

<div class="products-container">
<?php foreach($motors as $product): ?>
    <div class="product-card">
        <img src="<?= $product['images'][0] ?>" alt="<?= $product['name'] ?>">
        <h3><?= $product['name'] ?></h3>
        <div class="desc"><?= $product['desc'] ?></div>
        <div class="condition">Estado: <?= $product['condition'] ?></div>
        <div class="year">Ano: <?= $product['year'] ?></div>
        <div class="price"><?= $product['price'] ?></div>
        <button class="btn">Adicionar ao carrinho</button>
        <a href="produto.php?id=<?= urlencode($product['name']) ?>"><button class="details-btn">Ver detalhes</button></a>
    </div>
<?php endforeach; ?>
</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>
