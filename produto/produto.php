<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../auth/config.php';
require_once '../includes/header.php';

$airbags = [
    "Airbag cortina" => [
        "name" => "Airbag cortina",
        "price" => "€120,00",
        "desc" => "Proteção frontal para condutor e passageiro.",
        "condition" => "Novo",
        "year" => "2023",
        "images" => [
            "https://s3.eu-central-1.wasabisys.com/atena-cloud/149/parts/213963/197b9f1584a330.webp",
            "https://s3.eu-central-1.wasabisys.com/atena-cloud/149/parts/213963/197b9f15c1b336.webp"
        ]
    ],
    "Airbag Lateral" => [
        "name" => "Airbag Lateral",
        "price" => "€115,00",
        "desc" => "Airbag lateral frente esquerdo BMW 7 730 D",
        "condition" => "Bom",
        "year" => "2020",
        "images" => [
            "https://ireland.apollo.olxcdn.com/v1/files/ypt90zps5mz51-PT/image;s=1000x700",
            "https://ireland.apollo.olxcdn.com/v1/files/sq3up22boc28-PT/image;s=1000x700"
        ]
    ]
];

$id = $_GET['id'] ?? null;

if (!$id || !isset($airbags[$id])) {
    echo "<h2 style='text-align:center; margin-top:120px;'>Produto não encontrado</h2>";
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
<title><?= $product['name'] ?></title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>
.page-wrapper {
    padding: 130px 20px 80px;
}

.product-page {
    max-width: 1100px;
    margin: auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
}

.slider-box {
    background: #fff;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    position: relative;
}

.slider-box img {
    width: 100%;
    border-radius: 15px;
    display: none;
}

.slider-box img.active {
    display: block;
}

/* Setas */
.slider-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0,0,0,0.6);
    color: #fff;
    border: none;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 18px;
}

.slider-btn.left { left: 15px; }
.slider-btn.right { right: 15px; }

.slider-btn:hover {
    background: #4caf70;
}

/* BOLINHAS INDICADORAS */
.slider-indicators {
    text-align: center;
    margin-top: 10px;
}

.slider-indicators span {
    display: inline-block;
    width: 12px;
    height: 12px;
    margin: 0 6px;
    background: #ccc;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s;
}

.slider-indicators span.active {
    background: #4caf70;
}

/* INFO */
.product-info {
    background: #fff;
    border-radius: 20px;
    padding: 35px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    display: flex;
    flex-direction: column;
}

.product-info h2 {
    color: #2e7d32;
    margin-bottom: 15px;
}

.product-info .desc {
    color: #555;
    line-height: 1.6;
    margin-bottom: 20px;
}

.product-info .spec {
    font-weight: bold;
    margin-bottom: 8px;
}

.product-info .price {
    font-size: 22px;
    font-weight: bold;
    color: #ff3d3d;
    margin: 25px 0;
}

.product-info .btn {
    margin-top: auto;
    padding: 14px;
    border: none;
    background: linear-gradient(45deg, #4caf70, #66d78b);
    color: #fff;
    border-radius: 30px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 6px 18px rgba(0,0,0,0.25);
}

.product-info .btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.35);
}

@media (max-width: 900px) {
    .product-page {
        grid-template-columns: 1fr;
    }
}
</style>
</head>
<body>

<div class="page-wrapper">
    <div class="product-page">

        <!-- SLIDER -->
        <div class="slider-box">
            <?php foreach($product['images'] as $index => $img): ?>
                <img src="<?= $img ?>" class="<?= $index === 0 ? 'active' : '' ?>">
            <?php endforeach; ?>

            <button class="slider-btn left" onclick="prevImg()">❮</button>
            <button class="slider-btn right" onclick="nextImg()">❯</button>

            <div class="slider-indicators">
                <?php foreach($product['images'] as $index => $img): ?>
                    <span class="<?= $index === 0 ? 'active' : '' ?>" onclick="goToImg(<?= $index ?>)"></span>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- INFO -->
        <div class="product-info">
            <h2><?= $product['name'] ?></h2>
            <div class="desc"><?= $product['desc'] ?></div>
            <div class="spec">Estado: <?= $product['condition'] ?></div>
            <div class="spec">Ano: <?= $product['year'] ?></div>
            <div class="price"><?= $product['price'] ?></div>
            <button class="btn">Adicionar ao carrinho</button>
        </div>

    </div>
</div>

<script>
let index = 0;
const images = document.querySelectorAll('.slider-box img');
const indicators = document.querySelectorAll('.slider-indicators span');

function showImg(i) {
    images.forEach(img => img.classList.remove('active'));
    indicators.forEach(ind => ind.classList.remove('active'));
    images[i].classList.add('active');
    indicators[i].classList.add('active');
    index = i;
}

function nextImg() {
    index = (index + 1) % images.length;
    showImg(index);
}

function prevImg() {
    index = (index - 1 + images.length) % images.length;
    showImg(index);
}

function goToImg(i) {
    showImg(i);
}
</script>

<?php require_once '../includes/footer.php'; ?>
</body>
</html>
