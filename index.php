<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ecopeças</title>

<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="icon" href="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg?w=360">

<style>
body {
    background: url('https://wallpapers.com/images/hd/cool-green-background-yldkpcmn6kp9767o.jpg') no-repeat center center/cover;
}

/* ---------- SLIDER ---------- */
.slider-container {
    max-width:1200px;
    height:250px;
    margin:20px auto;
    overflow:hidden;
    border-radius:15px;
    box-shadow:0 0 20px rgba(102,215,139,0.6);
}
.slides-wrapper {
    display:flex;
    width:500%;
    transition:transform 1s ease-in-out;
}
.slider-slide {
    width:20%;
    position:relative;
}
.slider-slide img {
    width:100%;
    height:250px;
    object-fit:cover;
}
.slide-text {
    position:absolute;
    bottom:20px;
    left:20px;
    background:rgba(0,0,0,0.6);
    color:#fff;
    padding:12px 18px;
    border-radius:12px;
    font-weight:bold;
}

/* ---------- CATEGORIAS ---------- */
.cards-container {
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
}
.card {
    width:250px;
    margin:15px;
    padding:15px;
    background:rgba(255,255,255,0.85);
    border-radius:15px;
    text-align:center;
    transition:transform .3s;
}
.card:hover { transform:translateY(-5px); }
.card img {
    width:100%;
    height:150px;
    object-fit:cover;
    border-radius:10px;
}
.card .btn {
    display:inline-block;
    margin-top:10px;
    padding:8px 14px;
    background:#4caf70;
    color:#fff;
    border-radius:25px;
    text-decoration:none;
    font-weight:bold;
}

/* ---------- RETÂNGULO DESCRIÇÃO FINAL ---------- */
.about-box {
    max-width:1200px;
    margin:70px auto 40px;
    padding:25px 40px;
    display:flex;
    align-items:center;
    justify-content:space-between;

    background:rgba(255,255,255,0.75);
    border-radius:18px;

    box-shadow:
        0 0 15px rgba(255,255,255,0.4),
        0 10px 25px rgba(0,0,0,0.15);

    transition:transform .4s ease, box-shadow .4s ease;
}

.about-box:hover {
    transform:translateY(-6px);
    box-shadow:
        0 0 25px rgba(255,255,255,0.9),
        0 15px 35px rgba(0,0,0,0.25);
}

.about-text {
    max-width:65%;
}

.about-text h2 {
    color:#2e7d32;
    margin-bottom:10px;
}

.about-text p {
    font-size:16px;
    line-height:1.6;
    color:#333;
}

.about-logo img {
    max-width:140px;
    filter:drop-shadow(0 5px 10px rgba(0,0,0,0.25));
}

/* Responsivo */
@media(max-width:768px){
    .about-box {
        flex-direction:column;
        text-align:center;
        gap:20px;
    }
    .about-text { max-width:100%; }
}
</style>
</head>

<body>

<?php require_once __DIR__ . '/includes/header.php'; ?>

<!-- SLIDER -->
<div class="slider-container">
    <div class="slides-wrapper">
        <div class="slider-slide">
            <img src="https://blog.kroftools.com/wp-content/uploads/2023/11/pecas-de-automoveis.png">
            <div class="slide-text">Peças ecológicas de qualidade</div>
        </div>
        <div class="slider-slide">
            <img src="https://amr-auto.pt/wp-content/uploads/2024/01/reparacao_motor.jpg">
            <div class="slide-text">Promoções especiais para si</div>
        </div>
        <div class="slider-slide">
            <img src="https://inovegas.com.br/wp-content/uploads/2018/08/carro-miniatura-papel-na-grama.jpg">
            <div class="slide-text">Sustentabilidade e confiança</div>
        </div>
        <div class="slider-slide">
            <img src="https://clickpetroleoegas.com.br/wp-content/uploads/2025/07/raw-8.jpg">
            <div class="slide-text">Melhor seleção de interiores!</div>
        </div>
        <div class="slider-slide">
            <img src="https://img.freepik.com/fotos-gratis/acordo-de-negocios-aperto-de-mao-gesto-de-mao_53876-130006.jpg?semt=ais_hybrid&w=740&q=80">
            <div class="slide-text">Fidelidade e confiança</div>
        </div>
    </div>
</div>

<!-- CATEGORIAS -->
<div class="cards-container">
<?php
$categories = [
 ["img"=>"https://netun.com/cdn/shop/articles/01-Airbag_civicsi.jpg","name"=>"Airbags","file"=>"airbags.php"],
 ["img"=>"https://blog.mixauto.com.br/wp-content/uploads/2018/05/caixa-de-cambio.jpg","name"=>"Motor e Transmissão","file"=>"MotorTransmição.php"],
 ["img"=>"https://s7d9.scene7.com/is/image/dow/AdobeStock_385390317","name"=>"Iluminação","file"=>"iluminação.php"],
 ["img"=>"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRJ5NwDgRfalMhSg_JrDaskCoPjKOi3HHhxMA","name"=>"Suspensão","file"=>"suspencao.php"],
 ["img"=>"https://reparadorsa.com.br/wp-content/uploads/2022/12/RSA_MATERIAS-2_05-12_HEADER.png","name"=>"Elétrica","file"=>"eletrica.php"],
 ["img"=>"https://global-img.bitauto.com/usercenter/yhzx/20250815/793/w1200_yichecar_522658379372509.jpg.webp","name"=>"Interior","file"=>"interior.php"]
];
foreach($categories as $c){
    echo "<div class='card'>
            <img src='{$c['img']}'>
            <h3>{$c['name']}</h3>
            <a class='btn' href='categorias/{$c['file']}'>Ver Produtos</a>
          </div>";
}
?>
</div>

<!-- DESCRIÇÃO FINAL -->
<div class="about-box">
    <div class="about-text">
        <h2>Sobre a Ecopeças</h2>
        <p>
            A Ecopeças é uma plataforma dedicada à venda de peças automóveis usadas e
            recondicionadas, promovendo sustentabilidade, economia e confiança.
            Todas as peças são verificadas para garantir qualidade e segurança.
        </p>
    </div>

    <div class="about-logo">
        <img src="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg?w=360">
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
let index = 0;
const wrapper = document.querySelector('.slides-wrapper');
setInterval(()=>{
    index = (index+1)%5;
    wrapper.style.transform = `translateX(-${index*20}%)`;
},5000);
</script>

</body>
</html>
