<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ecopeças</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Favicon -->
<link rel="icon" href="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg?w=360" type="image/png">

<style>
/* IMAGEM DE FUNDO DO SITE */
body {
    background: url('https://wallpapers.com/images/hd/cool-green-background-yldkpcmn6kp9767o.jpg') no-repeat center center/cover;
}

/* SLIDER COM MOLDURA DE LUZ */
.slider-container {
    position: relative;
    width: 100%;
    max-width: 1200px;
    height: 250px;
    margin: 20px auto;
    overflow: hidden;
    border-radius: 15px;
    box-shadow: 0 0 20px 5px rgba(102, 215, 139, 0.6), 0 4px 15px rgba(0,0,0,0.2);
    transition: box-shadow 0.5s ease-in-out;
}
.slider-container:hover {
    box-shadow: 0 0 30px 10px rgba(102, 215, 139, 0.8), 0 6px 20px rgba(0,0,0,0.25);
}

.slides-wrapper {
    display: flex;
    width: 500%;
    transition: transform 1s ease-in-out;
    height: 100%;
}

.slider-slide {
    width: 20%;
    height: 100%;
    position: relative;
    flex-shrink: 0;
}

.slider-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    border-radius: 15px;
}

.slide-text {
    position: absolute;
    bottom: 20px;
    left: 20px;
    color: #fff;
    background: rgba(0,0,0,0.6);
    padding: 15px 20px;
    border-radius: 12px;
    font-size: 18px;
    font-weight: bold;
    max-width: 70%;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}

/* Cards de categorias */
.card {
    width: 250px;
    padding: 15px;
    margin: 15px;
    display: inline-block;
    vertical-align: top;
    background: rgba(249, 255, 249, 0.8);
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s;
}

.card:hover { transform: translateY(-5px); }

.card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 10px;
}

.card h3 { font-size: 18px; margin: 10px 0; }
.card .btn {
    margin-top: 10px;
    padding: 10px 15px;
    border: none;
    background: #4caf70;
    color: #fff;
    border-radius: 50px;
    cursor: pointer;
    font-weight: bold;
    transition: all 0.3s;
}
.card .btn:hover { background: #66d78b; }

/* Layout geral */
.cards-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

/* Responsivo */
@media (max-width:768px) {
    .slider-container { height: 180px; }
    .slide-text { font-size: 16px; padding: 10px 15px; max-width: 80%; }
}
</style>
</head>
<body>

<?php require_once __DIR__ . '/includes/header.php'; ?>

<!-- SLIDER -->
<div class="slider-container">
    <div class="slides-wrapper">
        <div class="slider-slide">
            <img src="https://blog.kroftools.com/wp-content/uploads/2023/11/pecas-de-automoveis.png" alt="Slide 1">
            <div class="slide-text">Descubra peças ecológicas de alta qualidade!</div>
        </div>
        <div class="slider-slide">
            <img src="https://amr-auto.pt/wp-content/uploads/2024/01/reparacao_motor.jpg" alt="Slide 2">
            <div class="slide-text">Promoções especiais em motores e suspensão!</div>
        </div>
        <div class="slider-slide">
            <img src="https://www.bigtires.com.br/media/blog/cache/1100x/magefan_blog/peca-falsa-de-carro-sinais.png" alt="Slide 3">
            <div class="slide-text">Peças usadas 100% funcionais!</div>
        </div>
        <div class="slider-slide">
            <img src="https://clickpetroleoegas.com.br/wp-content/uploads/2025/07/raw-8.jpg" alt="Slide 4">
            <div class="slide-text">A melhor seleção para o interior do seu carro!</div>
        </div>
        <div class="slider-slide">
            <img src="https://inovegas.com.br/wp-content/uploads/2018/08/carro-miniatura-papel-na-grama.jpg" alt="Slide 5">
            <div class="slide-text">Ecopeças: qualidade, confiança e sustentabilidade!</div>
        </div>
    </div>
</div>

<!-- CATEGORIAS -->
<div class="cards-container">
<?php
$categories = [
  ["id" => 1, "img" => "https://netun.com/cdn/shop/articles/01-Airbag_civicsi.jpg?v=1716802572", "name" => "Airbags"],
  ["id" => 2, "img" => "https://blog.mixauto.com.br/wp-content/uploads/2018/05/caixa-de-cambio.jpg", "name" => "Motor e Transmição"],
  ["id" => 3, "img" => "https://s7d9.scene7.com/is/image/dow/AdobeStock_385390317?qlt=82&ts=1692809401103&dpr=off", "name" => "Iluminação"],
  ["id" => 4, "img" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRJ5NwDgRfalMhSg_JrDaskCoPjKOi3HHhxMA&s", "name" => "Suspensão"],
  ["id" => 5, "img" => "https://reparadorsa.com.br/wp-content/uploads/2022/12/RSA_MATERIAS-2_05-12_HEADER.png", "name" => "Elétrica"],
  ["id" => 6, "img" => "https://global-img.bitauto.com/usercenter/yhzx/20250815/793/w1200_yichecar_522658379372509.jpg.webp", "name" => "Interior"]
];

foreach($categories as $c){
    echo "<div class='card'>";
    echo "<img src='{$c['img']}' alt='{$c['name']}'>";
    echo "<h3>{$c['name']}</h3>";
    echo "<a href='categoria.php?id={$c['id']}' class='btn'>Ver Produtos</a>";
    echo "</div>";
}
?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
// --- SLIDER JS COM EFEITO DESLIZANTE ---
let currentIndex = 0;
const slidesWrapper = document.querySelector('.slides-wrapper');
const totalSlides = document.querySelectorAll('.slider-slide').length;

function slide() {
    currentIndex = (currentIndex + 1) % totalSlides;
    slidesWrapper.style.transform = `translateX(-${currentIndex * 20}%)`;
}

setInterval(slide, 5000);
</script>

</body>
</html>
