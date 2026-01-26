<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Idioma
if (isset($_GET['lang'])) { $_SESSION['lang'] = $_GET['lang']; }
$lang = $_SESSION['lang'] ?? 'pt';

// Tema
if (isset($_GET['theme'])) { $_SESSION['theme'] = $_GET['theme']; }
$theme = $_SESSION['theme'] ?? 'light';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ecopeças</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="icon" href="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg?w=360" type="image/png">

<style>
body {
    background: url('https://wallpapers.com/images/hd/cool-green-background-yldkpcmn6kp9767o.jpg') no-repeat center center/cover;
    margin:0;
    font-family: Arial, sans-serif;
}

/* SLIDER PRINCIPAL */
.slider-container {
    position: relative;
    width: 100%;
    max-width: 1200px;
    height: 250px;
    margin: 20px auto;
    overflow: hidden;
    border-radius: 15px;
    box-shadow: 0 0 20px 5px rgba(102, 215, 139, 0.6), 0 4px 15px rgba(0,0,0,0.2);
}
.slides-wrapper { display: flex; width: 500%; transition: transform 1s ease-in-out; height: 100%; }
.slider-slide { width: 20%; height: 100%; flex-shrink: 0; position: relative; }
.slider-slide img { width: 100%; height: 100%; object-fit: cover; border-radius: 15px; }
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
}

/* CARDS DE CATEGORIAS */
.card { width: 250px; padding: 15px; margin: 15px; display: inline-block; vertical-align: top; background: rgba(249, 255, 249, 0.8); border-radius: 15px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); text-align: center; transition: transform 0.3s; }
.card:hover { transform: translateY(-5px); }
.card img { width: 100%; height: 150px; object-fit: cover; border-radius: 10px; }
.card h3 { font-size: 18px; margin: 10px 0; }
.card .btn { margin-top: 10px; padding: 8px 12px; border: none; background: #4caf70; color: #fff; border-radius: 25px; cursor: pointer; font-weight: bold; font-size: 14px; text-decoration: none; display: inline-block; transition: transform 0.3s, box-shadow 0.3s, background 0.3s; }
.card .btn:hover { transform: scale(1.1); box-shadow: 0 4px 12px rgba(0,0,0,0.2); background: #66d78b; }
.cards-container { display: flex; flex-wrap: wrap; justify-content: center; }

/* SEÇÃO DE DESCRIÇÃO COM MINI SLIDER FADE */
.site-description {
    max-width: 1200px;
    margin: 50px auto 30px auto;
    background: rgba(255,255,255,0.85);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 30px;
    gap: 20px;
}
.site-description .desc-text { flex:1; font-size: 18px; color: #2e7d32; }
.site-description .desc-img { flex:1; position: relative; }
.desc-slider-wrapper { overflow: hidden; border-radius: 12px; position: relative; height: 250px; }
.desc-slide { position: absolute; top:0; left:0; width:100%; height:100%; opacity:0; transition: opacity 1s ease-in-out; }
.desc-slide img { width: 100%; height: 100%; object-fit: cover; border-radius: 12px; }
.desc-slide.active { opacity:1; }

@media (max-width:768px) {
    .slider-container { height: 180px; }
    .slide-text { font-size: 16px; padding: 10px 15px; max-width: 80%; }
    .site-description { flex-direction: column; gap: 15px; }
    .desc-slider-wrapper { height: 200px; }
}
</style>
</head>
<body>

<?php require_once __DIR__ . '/includes/header.php'; ?>

<!-- SLIDER PRINCIPAL -->
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
  ["id"=>1,"img"=>"https://netun.com/cdn/shop/articles/01-Airbag_civicsi.jpg?v=1716802572","name"=>"Airbags","file"=>"airbags.php"],
  ["id"=>2,"img"=>"https://blog.mixauto.com.br/wp-content/uploads/2018/05/caixa-de-cambio.jpg","name"=>"Motor e Transmição","file"=>"MotorTransmição.php"],
  ["id"=>3,"img"=>"https://s7d9.scene7.com/is/image/dow/AdobeStock_385390317?qlt=82&ts=1692809401103&dpr=off","name"=>"Iluminação","file"=>"iluminação.php"],
  ["id"=>4,"img"=>"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRJ5NwDgRfalMhSg_JrDaskCoPjKOi3HHhxMA&s","name"=>"Suspensão","file"=>"suspencao.php"],
  ["id"=>5,"img"=>"https://reparadorsa.com.br/wp-content/uploads/2022/12/RSA_MATERIAS-2_05-12_HEADER.png","name"=>"Elétrica","file"=>"eletrica.php"],
  ["id"=>6,"img"=>"https://global-img.bitauto.com/usercenter/yhzx/20250815/793/w1200_yichecar_522658379372509.jpg.webp","name"=>"Interior","file"=>"interior.php"]
];
foreach($categories as $c){
    echo "<div class='card'><img src='{$c['img']}' alt='{$c['name']}'><h3>{$c['name']}</h3><a href='categorias/{$c['file']}' class='btn'>Ver Produtos</a></div>";
}
?>
</div>

<!-- DESCRIÇÃO COM MINI SLIDER FADE -->
<div class="site-description">
    <div class="desc-text">
        <h2>Sobre o Ecopeças</h2>
        <p>O Ecopeças é a sua plataforma de confiança para adquirir peças de automóveis ecológicas e sustentáveis. 
        Trabalhamos para oferecer produtos de alta qualidade, promoções especiais e uma experiência de compra segura e confiável.</p>
    </div>
    <div class="desc-img">
        <div class="desc-slider-wrapper">
            <div class="desc-slide active"><img src="https://images.unsplash.com/photo-1605902711622-cfb43c4437a5?auto=format&fit=crop&w=600&q=80" alt=""></div>
            <div class="desc-slide"><img src="https://images.unsplash.com/photo-1581091215363-6d2d94db1e92?auto=format&fit=crop&w=600&q=80" alt=""></div>
            <div class="desc-slide"><img src="https://images.unsplash.com/photo-1581090700227-5b8910c6f44e?auto=format&fit=crop&w=600&q=80" alt=""></div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
// SLIDER PRINCIPAL
let currentIndex = 0;
const slidesWrapper = document.querySelector('.slides-wrapper');
const totalSlides = document.querySelectorAll('.slider-slide').length;
function slide() { currentIndex = (currentIndex+1)%totalSlides; slidesWrapper.style.transform = `translateX(-${currentIndex*20}%)`; }
setInterval(slide, 5000);

// MINI SLIDER FADE
const descSlides = document.querySelectorAll('.desc-slide');
let descIndex = 0;
function descSlide() {
    descSlides.forEach((s,i)=>s.classList.remove('active'));
    descIndex = (descIndex+1) % descSlides.length;
    descSlides[descIndex].classList.add('active');
}
setInterval(descSlide, 5000);
</script>

</body>
</html>
