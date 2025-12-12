<?php
session_start();

// Detecta idioma selecionado ou usa PT por padrão
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
$lang = $_SESSION['lang'] ?? 'pt';

// Detecta modo claro/escuro selecionado ou usa claro por padrão
if (isset($_GET['theme'])) {
    $_SESSION['theme'] = $_GET['theme'];
}
$theme = $_SESSION['theme'] ?? 'light';
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ofertas - Ecopeças</title>

<!-- Favicon para aparecer na aba do navegador -->
<link rel="icon" type="image/png" href="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg?w=360">

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

<style>
    * { box-sizing:border-box; margin:0; padding:0; font-family:'Roboto', sans-serif; }
    body {
        min-height:100vh;
        background: <?= $theme=='dark' ? '#121212' : '#f0f0f0' ?>;
        color: <?= $theme=='dark' ? '#f0f0f0' : '#000' ?>;
        padding:50px 20px;
    }

    .offers-container {
        max-width:1200px;
        margin:0 auto;
        display:grid;
        grid-template-columns: repeat(auto-fill,minmax(250px,1fr));
        gap:20px;
    }

    .offer-card {
        background: <?= $theme=='dark' ? 'rgba(30,30,30,0.8)' : '#fff' ?>;
        border-radius:20px;
        box-shadow:0 10px 25px rgba(0,0,0,0.15);
        overflow:hidden;
        transition:transform 0.3s, box-shadow 0.3s;
    }

    .offer-card:hover {
        transform:translateY(-5px);
        box-shadow:0 15px 35px rgba(0,0,0,0.3);
    }

    .offer-card img {
        width:100%;
        height:180px;
        object-fit:cover;
    }

    .offer-card-content {
        padding:15px 20px;
    }

    .offer-card-content h3 {
        font-size:20px;
        color: <?= $theme=='dark' ? '#66d78b' : '#2e7d32' ?>;
        margin-bottom:8px;
    }

    .offer-card-content p {
        font-size:16px;
        margin-bottom:12px;
        color: <?= $theme=='dark' ? '#f0f0f0' : '#333' ?>;
    }

    .offer-card-content .price {
        font-weight:bold;
        font-size:18px;
        color:#ff3d3d;
    }

    @media(max-width:600px){
        .offers-container { grid-template-columns: 1fr; }
    }
</style>
</head>
<body>

<h1 style="text-align:center; margin-bottom:40px;">Ofertas Especiais</h1>

<div class="offers-container">
    <div class="offer-card">
        <img src="https://via.placeholder.com/400x200.png?text=Filtro+de+Óleo" alt="Filtro de Óleo">
        <div class="offer-card-content">
            <h3>Filtro de Óleo</h3>
            <p>Desconto especial para você!</p>
            <div class="price">€35,00 <span style="text-decoration:line-through; color:gray;">€50,00</span></div>
        </div>
    </div>

    <div class="offer-card">
        <img src="https://via.placeholder.com/400x200.png?text=Pastilhas+de+Freio" alt="Pastilhas de Freio">
        <div class="offer-card-content">
            <h3>Pastilhas de Freio</h3>
            <p>Oferta por tempo limitado!</p>
            <div class="price">€90,00 <span style="text-decoration:line-through; color:gray;">€120,00</span></div>
        </div>
    </div>

    <div class="offer-card">
        <img src="https://via.placeholder.com/400x200.png?text=Bateria+de+Carro" alt="Bateria de Carro">
        <div class="offer-card-content">
            <h3>Bateria de Carro</h3>
            <p>Economize agora!</p>
            <div class="price">€80,00 <span style="text-decoration:line-through; color:gray;">€110,00</span></div>
        </div>
    </div>
</div>

</body>
</html>
